document.addEventListener('DOMContentLoaded', () => {

    const config = window.StoreSettingsConfig || {
        hasThemeBgImage: false,
        themeBgImageUrl: '',
        hasThemeBodyBgImage: false,
        themeBodyBgImageUrl: ''
    };

    // ── Workflow radio styling ────────────────────────────────────────
    const radios = document.querySelectorAll('.workflow-radio');
    const labels = { direct: document.getElementById('label_direct'), queue: document.getElementById('label_queue') };
    const dots = { direct: document.getElementById('dot_direct'), queue: document.getElementById('dot_queue') };

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            ['direct', 'queue'].forEach(val => {
                if (!labels[val] || !dots[val]) return;

                const active = radio.value === val && radio.checked;
                labels[val].classList.toggle('border-slate-900', active);
                labels[val].classList.toggle('bg-slate-50', active);
                labels[val].classList.toggle('border-slate-200', !active);
                labels[val].classList.toggle('bg-white', !active);
                dots[val].classList.toggle('border-slate-900', active);
                dots[val].classList.toggle('bg-slate-900', active);
                dots[val].classList.toggle('border-slate-300', !active);
                dots[val].classList.toggle('bg-white', !active);
            });
        });
    });

    // ── Logo preview (new upload) ─────────────────────────────────────
    const logoInput = document.getElementById('logo');
    const logoPreviewWrapper = document.getElementById('logo-preview-wrapper');
    const logoPreviewNew = document.getElementById('logo-preview-new');

    if (logoInput) {
        logoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && logoPreviewWrapper && logoPreviewNew) {
                const reader = new FileReader();
                reader.onload = e => {
                    logoPreviewNew.src = e.target.result;
                    logoPreviewWrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // ── Theme color live preview ──────────────────────────────────────
    const primaryInput = document.getElementById('theme_primary_color');
    const bgInput = document.getElementById('theme_bg_color');
    const textInput = document.getElementById('theme_text_color');

    const previewHeader = document.getElementById('preview-header');
    const previewTitle = document.getElementById('preview-title');
    const previewPill = document.getElementById('preview-pill');
    const previewBtn = document.getElementById('preview-btn');
    const previewCart = document.getElementById('preview-cart');

    function updateHexDisplay(input) {
        const display = input.parentElement.nextElementSibling;
        if (display && display.classList.contains('color-hex-display')) {
            display.textContent = input.value.toUpperCase();
        }
    }

    if (primaryInput) {
        primaryInput.addEventListener('input', e => {
            const color = e.target.value;
            if (previewPill) previewPill.style.backgroundColor = color;
            if (previewBtn) previewBtn.style.backgroundColor = color;
            if (previewCart) previewCart.style.backgroundColor = color;
            updateHexDisplay(e.target);
        });
    }

    if (bgInput) {
        bgInput.addEventListener('input', e => {
            const color = e.target.value;
            const bgImgInput = document.getElementById('theme_bg_image');
            if (previewHeader && (!bgImgInput || !bgImgInput.files || bgImgInput.files.length === 0)) {
                previewHeader.style.backgroundColor = color;
            }
            updateHexDisplay(e.target);
        });
    }

    if (textInput) {
        textInput.addEventListener('input', e => {
            if (previewTitle) previewTitle.style.color = e.target.value;
            updateHexDisplay(e.target);
        });
    }

    // ── Background image live preview ─────────────────────────────────
    const bgImageInput = document.getElementById('theme_bg_image');
    const removeBgCheckbox = document.getElementById('remove_bg_image');

    if (bgImageInput) {
        bgImageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && previewHeader) {
                const reader = new FileReader();
                reader.onload = e => { previewHeader.style.backgroundImage = `url('${e.target.result}')`; };
                reader.readAsDataURL(file);
                if (removeBgCheckbox) removeBgCheckbox.checked = false;
            } else if (previewHeader && bgInput) {
                previewHeader.style.backgroundImage = 'none';
                previewHeader.style.backgroundColor = bgInput.value;
            }
        });
    }

    if (removeBgCheckbox) {
        removeBgCheckbox.addEventListener('change', function () {
            if (this.checked) {
                if (previewHeader) {
                    previewHeader.style.backgroundImage = 'none';
                    if (bgInput) previewHeader.style.backgroundColor = bgInput.value;
                }
                if (bgImageInput) bgImageInput.value = '';
            } else if (config.hasThemeBgImage) {
                if (!bgImageInput || !bgImageInput.files || bgImageInput.files.length === 0) {
                    if (previewHeader) previewHeader.style.backgroundImage = `url('${config.themeBgImageUrl}')`;
                }
            }
        });
    }

    // ── Body background image live preview ────────────────────────────
    const bodyImageInput = document.getElementById('theme_body_bg_image');
    const removeBodyCheckbox = document.getElementById('remove_body_bg_image');
    const previewBody = document.getElementById('preview-body');

    if (bodyImageInput) {
        bodyImageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && previewBody) {
                const reader = new FileReader();
                reader.onload = e => { previewBody.style.backgroundImage = `url('${e.target.result}')`; };
                reader.readAsDataURL(file);
                if (removeBodyCheckbox) removeBodyCheckbox.checked = false;
            } else if (previewBody) {
                previewBody.style.backgroundImage = 'none';
            }
        });
    }

    if (removeBodyCheckbox) {
        removeBodyCheckbox.addEventListener('change', function () {
            if (this.checked) {
                if (previewBody) previewBody.style.backgroundImage = 'none';
                if (bodyImageInput) bodyImageInput.value = '';
            } else if (config.hasThemeBodyBgImage) {
                if (!bodyImageInput || !bodyImageInput.files || bodyImageInput.files.length === 0) {
                    if (previewBody) previewBody.style.backgroundImage = `url('${config.themeBodyBgImageUrl}')`;
                }
            }
        });
    }

});
