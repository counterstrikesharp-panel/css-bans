import {hideLoader, showLoader} from "../utility/utility";

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
    document.querySelectorAll('.weapon-select-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const type = this.getAttribute('data-type');
            const targetTab = document.querySelector('#weapon-' + type + '-pane');
            const lazyContent = targetTab.querySelector('.lazy-content[data-type="' + type + '"]');
                showLoader('skins_list_loader');
                $('.tab-pane.active').hide();
                fetch(weaponsLoadUrl + type)
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
                        hideLoader('skins_list_loader');
                        $('.tab-pane.active').show();
                        // Switch to the target tab
                        targetTabAction(targetTab);
                    });

        });
    });
});

function targetTabAction(targetTab) {
    const currentActiveTab = document.querySelector('.tab-pane.show.active');
    currentActiveTab.classList.remove('show', 'active');
    targetTab.classList.add('show', 'active');

    // Update active state of buttons
    const currentActiveButton = document.querySelector('.nav-link.active');
    if (currentActiveButton) {
        currentActiveButton.classList.remove('active');
    }
    document.getElementById('weapon-dropdown-tab').classList.add('active');
}
defaultLoad();
function defaultLoad() {
    showLoader('skins_list_loader');
    const defaultType = 'deagle';
    const defaultTargetTab = document.querySelector('#weapon-'+defaultType+'-pane');
    const defaultLazyContent = defaultTargetTab.querySelector('.lazy-content[data-type="' + defaultType + '"]');
    fetch(weaponsLoadUrl + defaultType)
.then(response => response.text())
        .then(data => {
            defaultLazyContent.innerHTML = data;
            defaultLazyContent.querySelectorAll('img').forEach(img => {
                img.classList.add('lazy');
                img.addEventListener('load', () => {
                    applyGlow(img);
                    img.classList.add('loaded');
                });
                hideLoader('skins_list_loader');
            });
            // Switch to the target tab
            targetTabAction(defaultTargetTab);
        });
}
