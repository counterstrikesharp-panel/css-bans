import { hideLoader, showLoader } from "../utility/utility";

const colorThief = new ColorThief();

function applyGlow(img) {
    const color = colorThief.getColor(img);
    const rgbColor = `rgb(${color[0]}, ${color[1]}, ${color[2]})`;
    img.closest('.card').style.setProperty('--glow-color', rgbColor);
    img.closest('.card').classList.add('glow');
    img.previousElementSibling.classList.add('d-none');
}

document.addEventListener('DOMContentLoaded', function () {
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

    // Lazy loading and AJAX content loading
    document.querySelectorAll('.knife-select-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-category');
            const targetTab = document.querySelector('#knife-' + category + '-pane');
            const lazyContent = targetTab.querySelector('.lazy-content[data-type="' + category + '"]');

            showLoader('knife_list_loader');
            document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('show', 'active'));

            fetch(knivesLoadUrl + category)
                .then(response => response.text())
                .then(data => {
                    lazyContent.innerHTML = data;
                    lazyContent.querySelectorAll('img').forEach(img => {
                        img.classList.add('lazy');
                        img.addEventListener('load', () => {
                            applyGlow(img);
                            img.classList.add('loaded');
                        });
                    });
                    hideLoader('knife_list_loader');
                    targetTab.classList.add('show', 'active');
                });
        });
    });
});

function defaultLoad() {
    showLoader('knife_list_loader');
    const defaultCategory = 500;
    const defaultTargetTab = document.querySelector('#knife-' + defaultCategory + '-pane');
    const defaultLazyContent = defaultTargetTab.querySelector('.lazy-content[data-type="' + defaultCategory + '"]');

    fetch(knivesLoadUrl + defaultCategory)
        .then(response => response.text())
        .then(data => {
            defaultLazyContent.innerHTML = data;
            defaultLazyContent.querySelectorAll('img').forEach(img => {
                img.classList.add('lazy');
                img.addEventListener('load', () => {
                    applyGlow(img);
                    img.classList.add('loaded');
                });
                hideLoader('knife_list_loader');
            });
            defaultTargetTab.classList.add('show', 'active');
        });
}

defaultLoad();
