const activeFormatClasses = [
    'bg-love-pink-400',
    'text-white',
    'shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)]',
];

const inactiveFormatClasses = [
    'border',
    'border-love-pink-100',
    'bg-white',
    'text-[#512438]',
    'hover:bg-love-pink-100',
    'hover:text-love-pink-500',
];

export const initializeAdminReports = () => {
    document.querySelectorAll('[data-admin-reports]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.initialized === 'true') {
            return;
        }

        const formId = section.dataset.reportForm || 'admin-report-filter-form';
        const form = document.getElementById(formId);

        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        const buttons = Array.from(section.querySelectorAll('[data-report-format-button]'))
            .filter((button) => button instanceof HTMLButtonElement);
        const inputs = Array.from(document.querySelectorAll('[data-report-format-input]'))
            .filter((input) => input instanceof HTMLInputElement);

        const setButtonState = (button, isActive) => {
            activeFormatClasses.forEach((className) => button.classList.toggle(className, isActive));
            inactiveFormatClasses.forEach((className) => button.classList.toggle(className, !isActive));
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        };

        const clearGroup = (group) => {
            inputs
                .filter((input) => input.dataset.reportFormatInput === group)
                .forEach((input) => {
                    input.value = '';
                });

            buttons
                .filter((button) => button.dataset.reportFormatGroup === group)
                .forEach((button) => setButtonState(button, false));
        };

        const selectFormat = (group, value) => {
            inputs
                .filter((input) => input.dataset.reportFormatInput === group)
                .forEach((input) => {
                    input.value = value;
                });

            buttons
                .filter((button) => button.dataset.reportFormatGroup === group)
                .forEach((button) => setButtonState(button, button.dataset.reportFormatValue === value));
        };

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                const group = button.dataset.reportFormatGroup || '';
                const value = button.dataset.reportFormatValue || '';

                if (group && value) {
                    selectFormat(group, value);
                }
            });

            setButtonState(button, false);
        });

        form.addEventListener('submit', (event) => {
            const submitter = event.submitter;

            if (!(submitter instanceof HTMLButtonElement)) {
                return;
            }

            const group = submitter.dataset.reportDownload;

            if (!group) {
                return;
            }

            window.setTimeout(() => clearGroup(group), 1200);
        });

        section.dataset.initialized = 'true';
    });
};
