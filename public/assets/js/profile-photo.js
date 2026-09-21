const avatar = document.getElementById('profileAvatar');
const fileInput = document.getElementById('profilePhotoInput');

avatar.addEventListener('click', () => {
    fileInput.click();
});

document.querySelector('.sidebar-avatar-badge').addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', async function () {

    if (!this.files.length) {
        return;
    }

    const formData = new FormData();
    formData.append('photo', this.files[0]);
    console.log(formData.get('photo'));

    const response = await fetch(uploadProfilePhotosUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        },
        body: formData
    });

    const result = await response.json();
    console.log(result.photo_url);

    if (result.success) {
        avatar.src = result.photo_url + '?t=' + new Date().getTime();
        HimRishteyToast.success('Profile photo updated successfully.');
    } else {
        HimRishteyToast.error(result.message || 'Unable to update profile photo.');
    }
});
