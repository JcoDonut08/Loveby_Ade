export const initializeAdminOrderModals = () => {
    document.querySelectorAll('[data-admin-order-management][data-backend-orders="true"]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.modalsInitialized === 'true') {
            return;
        }

        const cancelModal = section.querySelector('[data-cancel-modal]');
        const cancelForm = section.querySelector('[data-cancel-form]');
        const cancelInput = section.querySelector('[data-cancel-reason-input]');
        const detailsModal = section.querySelector('[data-order-details]');
        const detailsTitle = section.querySelector('[data-details-title]');
        const detailsContent = section.querySelector('[data-order-details-content]');

        const showLayer = (layer) => {
            layer?.classList.remove('hidden');
            layer?.classList.add('flex');
            layer?.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        const hideLayer = (layer) => {
            layer?.classList.add('hidden');
            layer?.classList.remove('flex');
            layer?.setAttribute('aria-hidden', 'true');

            if (!section.querySelector('[data-cancel-modal]:not(.hidden), [data-order-details]:not(.hidden)')) {
                document.body.classList.remove('overflow-hidden');
            }
        };

        section.querySelectorAll('[data-admin-cancel-open]').forEach((button) => {
            button.addEventListener('click', () => {
                if (cancelForm instanceof HTMLFormElement) {
                    cancelForm.action = button.dataset.cancelAction || '';
                }

                if (cancelInput instanceof HTMLTextAreaElement) {
                    cancelInput.value = '';
                }

                showLayer(cancelModal);
                cancelInput?.focus();
            });
        });

        section.querySelectorAll('[data-cancel-reason]').forEach((button) => {
            button.addEventListener('click', () => {
                if (cancelInput instanceof HTMLTextAreaElement && button instanceof HTMLButtonElement) {
                    cancelInput.value = button.dataset.cancelReason || '';
                    cancelInput.focus();
                }
            });
        });

        section.querySelectorAll('[data-cancel-close]').forEach((button) => {
            button.addEventListener('click', () => hideLayer(cancelModal));
        });

        section.querySelectorAll('[data-admin-details-open]').forEach((button) => {
            button.addEventListener('click', () => {
                const templateId = button.dataset.detailsTemplate || '';
                const template = document.getElementById(templateId);

                if (!(template instanceof HTMLTemplateElement) || !(detailsContent instanceof HTMLElement)) {
                    return;
                }

                if (detailsTitle instanceof HTMLElement) {
                    detailsTitle.textContent = button.dataset.detailsHeading || 'Order';
                }

                detailsContent.replaceChildren(template.content.cloneNode(true));
                showLayer(detailsModal);
            });
        });

        detailsContent?.addEventListener('click', (event) => {
            const button = event.target instanceof Element ? event.target.closest('[data-details-show-more]') : null;

            if (!(button instanceof HTMLButtonElement) || !(detailsContent instanceof HTMLElement)) {
                return;
            }

            detailsContent.querySelectorAll('[data-details-extra-product]').forEach((product) => {
                product.classList.remove('hidden');
                product.classList.add('flex');
            });

            button.remove();
        });

        section.querySelectorAll('[data-details-close]').forEach((button) => {
            button.addEventListener('click', () => hideLayer(detailsModal));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                hideLayer(cancelModal);
                hideLayer(detailsModal);
            }
        });

        section.dataset.modalsInitialized = 'true';
    });
};
