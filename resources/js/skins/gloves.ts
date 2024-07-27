import {hideLoader, showLoader} from "../utility/utility";

document.addEventListener('DOMContentLoaded', function () {
    const colorThief = new ColorThief();

    function applyGlow(img) {
        const color = colorThief.getColor(img);
        const rgbColor = `rgb(${color[0]}, ${color[1]}, ${color[2]})`;
        img.closest('.card').style.setProperty('--glow-color', rgbColor);
        img.closest('.card').classList.add('glow');
    }

    document.querySelectorAll('.card.style-6 img').forEach(function (img) {
        img.setAttribute('crossorigin', 'anonymous');
        if (img.complete) {
            applyGlow(img);
            img.previousElementSibling.classList.add('d-none'); // Hide loader if image is already loaded
        } else {
            img.addEventListener('load', function () {
                applyGlow(img);
                img.previousElementSibling.classList.add('d-none'); // Hide loader after image loads
            });
        }
    });

    document.querySelectorAll('.glove-select-button').forEach(button => {
        button.addEventListener('click', function () {
            const type = this.getAttribute('data-type');
            const targetTab = document.getElementById(`nav-${type}`);
            const lazyContent = targetTab.querySelector('.lazy-load-content[data-type="' + type + '"]');

                lazyContent.innerHTML = '<div class="loader-gloves"></div>';
                fetch(glovesLoadUrl + type)
            .then(response => response.text())
                    .then(data => {
                        lazyContent.innerHTML = data;
                        lazyContent.querySelectorAll('img').forEach(img => {
                            img.classList.add('lazy');
                            img.addEventListener('load', () => {
                                applyGlow(img);
                                img.previousElementSibling.classList.add('d-none'); // Hide loader after image loads
                                img.classList.add('loaded');
                            });
                        });
                        targetTabAction(targetTab);
                    });

        });
    });

    // Event listener for glove preview modal
    const glovePreviewModal = document.getElementById('glovePreviewModal');
    glovePreviewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const gloveImage = button.getAttribute('data-skin-image');
        const gloveName = button.getAttribute('data-skin-name');

        const modalImage = glovePreviewModal.querySelector('#glovePreviewImage');
        const modalName = glovePreviewModal.querySelector('#glovePreviewName');

        modalImage.src = gloveImage;
        modalName.textContent = gloveName;
    });
    defaultLoad();
    function defaultLoad() {
        showLoader('gloves_list_loader');
        const type = '★ Broken Fang Gloves';
        const targetTab = document.getElementById(`nav-${type}`);
        const lazyContent = targetTab.querySelector('.lazy-load-content[data-type="' + type + '"]');

        fetch(glovesLoadUrl + type)
            .then(response => response.text())
            .then(data => {
                lazyContent.innerHTML = data;
                lazyContent.querySelectorAll('img').forEach(img => {
                    img.classList.add('lazy');
                    img.addEventListener('load', () => {
                        applyGlow(img);
                        img.previousElementSibling.classList.add('d-none'); // Hide loader after image loads
                        img.classList.add('loaded');
                    });
                });
                targetTabAction(targetTab);
                hideLoader('gloves_list_loader');
            });
    }
});

function targetTabAction(targetTab) {
    const currentActiveTab = document.querySelector('.tab-pane.show.active');
    if (currentActiveTab) {
        currentActiveTab.classList.remove('show', 'active');
    }
    targetTab.classList.add('show', 'active');

    const currentActiveButton = document.querySelector('.nav-link.active');
    if (currentActiveButton) {
        currentActiveButton.classList.remove('active');
    }
    document.getElementById('glove-dropdown-tab').classList.add('active');
}
