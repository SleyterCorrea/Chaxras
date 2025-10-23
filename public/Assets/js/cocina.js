document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.querySelector('input[type="file"][name="imagen"]');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const preview = document.querySelector('.preview-img') || document.createElement('img');
            preview.classList.add('preview-img');

            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(this.files[0]);
            }

            this.parentElement.appendChild(preview);
        });
    }
});
