export const initializeAdminOrderModals = () => {
    document.querySelectorAll('[data-admin-order-management][data-backend-orders="true"]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.modalsInitialized === 'true') {
            return;
        }

        const cancelModal = section.querySelector('[data-cancel-modal]');
        const cancelForm = section.querySelector('[data-cancel-form]');
        const cancelInput = section.querySelector('[data-cancel-reason-input]');
        const walkInModal = section.querySelector('[data-walk-in-modal]');
        const walkInProducts = section.querySelector('[data-walk-in-products]');
        const walkInTemplate = section.querySelector('[data-walk-in-product-template]');
        const walkInOverallTotal = section.querySelector('[data-walk-in-overall-total]');
        const walkInRemoveProduct = section.querySelector('[data-walk-in-remove-product]');
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

            if (!section.querySelector('[data-walk-in-modal]:not(.hidden), [data-cancel-modal]:not(.hidden), [data-order-details]:not(.hidden)')) {
                document.body.classList.remove('overflow-hidden');
            }
        };

        const productRows = () => Array.from(section.querySelectorAll('[data-walk-in-product-row]'))
            .filter((row) => row instanceof HTMLElement);

        const selectedPrice = (select) => {
            if (!(select instanceof HTMLSelectElement)) {
                return 0;
            }

            const option = select.selectedOptions[0];
            const price = Number.parseFloat(option?.dataset.price || '0');

            return Number.isFinite(price) ? price : 0;
        };

        const reindexWalkInRows = () => {
            productRows().forEach((row, index) => {
                const select = row.querySelector('[data-walk-in-product-select]');
                const quantity = row.querySelector('[data-walk-in-quantity]');
                const lineTotal = row.querySelector('[data-walk-in-line-total]');

                if (select instanceof HTMLSelectElement) {
                    select.name = `products[${index}][product_id]`;
                    select.id = `walk-in-product-${index}`;
                }

                if (quantity instanceof HTMLInputElement) {
                    quantity.name = `products[${index}][quantity]`;
                    quantity.id = `walk-in-quantity-${index}`;
                }

                if (lineTotal instanceof HTMLInputElement) {
                    lineTotal.id = `walk-in-line-total-${index}`;
                }
            });

            if (walkInRemoveProduct instanceof HTMLButtonElement) {
                walkInRemoveProduct.disabled = productRows().length <= 1;
            }
        };

        const updateWalkInTotals = () => {
            let overallTotal = 0;

            productRows().forEach((row) => {
                const select = row.querySelector('[data-walk-in-product-select]');
                const quantity = row.querySelector('[data-walk-in-quantity]');
                const lineTotal = row.querySelector('[data-walk-in-line-total]');
                const safeQuantity = quantity instanceof HTMLInputElement
                    ? Math.max(1, Number.parseInt(quantity.value || '1', 10) || 1)
                    : 1;
                const total = selectedPrice(select) * safeQuantity;

                if (quantity instanceof HTMLInputElement) {
                    quantity.value = safeQuantity.toString();
                }

                if (lineTotal instanceof HTMLInputElement) {
                    lineTotal.value = total.toFixed(2);
                }

                overallTotal += total;
            });

            if (walkInOverallTotal instanceof HTMLInputElement) {
                walkInOverallTotal.value = overallTotal.toFixed(2);
            }
        };

        const addWalkInProductRow = () => {
            if (!(walkInProducts instanceof HTMLElement) || !(walkInTemplate instanceof HTMLTemplateElement)) {
                return;
            }

            walkInProducts.append(walkInTemplate.content.cloneNode(true));
            reindexWalkInRows();
            updateWalkInTotals();
        };

        const removeWalkInProductRow = () => {
            const rows = productRows();

            if (rows.length <= 1) {
                return;
            }

            rows.at(-1)?.remove();
            reindexWalkInRows();
            updateWalkInTotals();
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

        section.querySelectorAll('[data-walk-in-open]').forEach((button) => {
            button.addEventListener('click', () => {
                showLayer(walkInModal);
                updateWalkInTotals();
            });
        });

        section.querySelectorAll('[data-walk-in-close]').forEach((button) => {
            button.addEventListener('click', () => hideLayer(walkInModal));
        });

        section.querySelector('[data-walk-in-add-product]')?.addEventListener('click', addWalkInProductRow);
        walkInRemoveProduct?.addEventListener('click', removeWalkInProductRow);
        walkInProducts?.addEventListener('input', updateWalkInTotals);
        walkInProducts?.addEventListener('change', updateWalkInTotals);

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
                hideLayer(walkInModal);
                hideLayer(cancelModal);
                hideLayer(detailsModal);
            }
        });

        reindexWalkInRows();
        updateWalkInTotals();
        if (walkInModal instanceof HTMLElement && !walkInModal.classList.contains('hidden')) {
            document.body.classList.add('overflow-hidden');
        }

        section.dataset.modalsInitialized = 'true';
    });
};
