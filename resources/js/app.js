import './bootstrap';

const initializePasswordToggles = () => {
    document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
        if (!(toggle instanceof HTMLButtonElement) || toggle.dataset.initialized === 'true') {
            return;
        }

        const targetId = toggle.dataset.passwordTarget;

        if (!targetId) {
            return;
        }

        const input = document.getElementById(targetId);

        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        const showIcon = toggle.querySelector('[data-password-icon="show"]');
        const hideIcon = toggle.querySelector('[data-password-icon="hide"]');

        const setVisibility = (isVisible) => {
            input.type = isVisible ? 'text' : 'password';
            toggle.setAttribute('aria-pressed', isVisible ? 'true' : 'false');
            toggle.setAttribute(
                'aria-label',
                isVisible ? 'Hide password' : 'Show password',
            );

            showIcon?.classList.toggle('hidden', isVisible);
            hideIcon?.classList.toggle('hidden', !isVisible);
        };

        setVisibility(false);

        toggle.addEventListener('click', () => {
            setVisibility(input.type === 'password');
        });

        toggle.dataset.initialized = 'true';
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePasswordToggles);
} else {
    initializePasswordToggles();
}
