document.addEventListener('DOMContentLoaded', function () {
    const colorThief = new ColorThief();

    document.querySelectorAll('.card.style-6 img').forEach(function (img) {
        img.setAttribute('crossorigin', 'anonymous');
        if (img.complete) {
            applyGlow(img);
        } else {
            img.addEventListener('load', function () {
                applyGlow(img);
            });
        }
    });

    function applyGlow(img) {
        const color = colorThief.getColor(img);
        const rgbColor = `rgb(${color[0]}, ${color[1]}, ${color[2]})`;
        img.closest('.card').style.setProperty('--glow-color', rgbColor);
        img.closest('.card').classList.add('glow');
        img.previousElementSibling.classList.add('d-none');
    }

    const skinPreviewModal = document.getElementById('skinPreviewModal');
    skinPreviewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const skinImage = button.getAttribute('data-skin-image');
        const skinName = button.getAttribute('data-skin-name');

        const modalImage = skinPreviewModal.querySelector('#skinPreviewImage');
        const modalName = skinPreviewModal.querySelector('#skinPreviewName');

        modalImage.src = skinImage;
        modalName.textContent = skinName;
    });
});
