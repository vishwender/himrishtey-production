document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.querySelector('#detail');
    if (!textarea || typeof ClassicEditor === 'undefined') return;

    ClassicEditor.create(textarea)
        .then(editor => {
            // Native validation cannot focus the textarea hidden by CKEditor.
            // The save handler still validates that the story is required.
            textarea.required = false;
            const sync = () => { textarea.value = editor.getData(); };
            editor.model.document.on('change:data', sync);
            textarea.form.addEventListener('submit', sync);
            sync();
        })
        .catch(error => {
            console.error('Success story editor could not be loaded:', error);
        });
});
