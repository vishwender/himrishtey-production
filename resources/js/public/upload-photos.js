const modal = document.getElementById("photoUploadModal");
    const openButton = document.getElementById("openPhotoUpload");
    const closeButton = document.getElementById("closePhotoModal");
    const uploadButton = document.getElementById("uploadPhotos");
    const input = document.getElementById("photoInput");
    const preview = document.getElementById("photoPreview");
    const uploadButtonLabel = uploadButton?.querySelector(".upload-photo-btn-label");

    function setUploading(isUploading) {
        if (!uploadButton) return;

        uploadButton.disabled = isUploading;
        uploadButton.setAttribute("aria-busy", isUploading ? "true" : "false");

        if (uploadButtonLabel) {
            uploadButtonLabel.textContent = isUploading ? "Uploading..." : "Upload Photos";
        }
    }

    function openPhotoModal() {
        modal.classList.add("show");
        modal.setAttribute("aria-hidden", "false");
    }

    function closePhotoModal() {
        modal.classList.remove("show");
        modal.setAttribute("aria-hidden", "true");
        input.value = "";
        preview.innerHTML = "";
    }

    openButton?.addEventListener("click", openPhotoModal);
    closeButton?.addEventListener("click", closePhotoModal);

    modal?.addEventListener("click", function(event) {
        if (event.target === modal) {
            closePhotoModal();
        }
    });

    input?.addEventListener("change", function() {
        preview.innerHTML = "";

        Array.from(this.files).forEach(file => {
            const reader = new FileReader();

            reader.onload = function(event) {
                preview.innerHTML += `<img src="${event.target.result}" alt="Selected preview">`;
            };

            reader.readAsDataURL(file);
        });
    });

    uploadButton?.addEventListener("click", function(event) {
        event.preventDefault();

        const files = input.files;

        if (files.length === 0) {
            HimRishteyToast.error("Please select photos.");
            return;
        }

        const formData = new FormData();

        Array.from(files).forEach(file => {
            formData.append("photos[]", file);
        });

        setUploading(true);

        fetch(uploadPhotosUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(async response => {
                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(payload.message || "Failed to upload photos.");
                }

                return payload;
            })
            .then(res => {
                HimRishteyToast.success(res.message || "Photos uploaded successfully.");
                closePhotoModal();
                location.reload();
            })
            .catch(error => {
                HimRishteyToast.error(error.message || "Unable to upload photos.");
                setUploading(false);
            });
    });
