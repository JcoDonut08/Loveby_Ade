export const initializeAdminProductModals = () => {
    document.querySelectorAll('[data-admin-products][data-backend-products="true"]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.modalsInitialized === 'true') {
            return;
        }

        const modal = section.querySelector('[data-product-server-modal]');
        const openButton = section.querySelector('[data-product-server-open]');
        const closeButtons = section.querySelectorAll('[data-product-server-close]');
        const form = section.querySelector('[data-product-server-form]');
        const methodInput = section.querySelector('[data-product-form-method]');
        const modalTitle = section.querySelector('[data-product-modal-title]');
        const saveButton = section.querySelector('[data-product-save]');
        const searchInput = section.querySelector('[data-product-search]');
        const imageInput = form.querySelector('[data-product-images]');
        const previewContainer = section.querySelector('[data-product-image-preview]');

        if (!modal || !openButton || !(form instanceof HTMLFormElement)) {
            return;
        }

        const setFieldValue = (name, value) => {
            const field = form.querySelector(`[data-product-field="${name}"]`);

            if (field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement) {
                field.value = value ?? '';
            }
        };

        const renderImagePreview = (files) => {
            if (!(previewContainer instanceof HTMLElement)) {
                return;
            }

            if (!(files instanceof FileList) || files.length === 0) {
                previewContainer.innerHTML = `
                    <div class="rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream p-4 text-sm font-bold text-[#9a6c7b] sm:col-span-4">
                        No image selected yet.
                    </div>
                `;

                return;
            }

            previewContainer.innerHTML = Array.from(files).map((file) => `
                <figure class="relative overflow-hidden rounded-[1rem] border border-love-pink-100 bg-white shadow-sm">
                    <img class="aspect-square w-full object-cover" src="${URL.createObjectURL(file)}" alt="Product image preview">
                </figure>
            `).join('');
        };

        const openModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        const openAddModal = () => {
            form.reset();
            form.action = form.dataset.productStoreAction || form.action;

            if (methodInput instanceof HTMLInputElement) {
                methodInput.disabled = true;
            }

            if (modalTitle) {
                modalTitle.textContent = 'Add product';
            }

            if (saveButton) {
                saveButton.textContent = 'Save product';
            }

            openModal();
        };

        const openEditModal = (payload) => {
            form.reset();
            form.action = payload.action || form.action;

            if (methodInput instanceof HTMLInputElement) {
                methodInput.disabled = false;
            }

            setFieldValue('title', payload.title);
            setFieldValue('description', payload.description);
            setFieldValue('category', payload.category);
            setFieldValue('price', payload.price);
            setFieldValue('stock', payload.stock);

            if (modalTitle) {
                modalTitle.textContent = 'Edit product';
            }

            if (saveButton) {
                saveButton.textContent = 'Update product';
            }

            openModal();
            renderImagePreview(imageInput instanceof HTMLInputElement ? imageInput.files : null);
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        };

        const debouncedSubmit = (() => {
            let timeoutId;

            return (delay = 300) => {
                window.clearTimeout(timeoutId);
                timeoutId = window.setTimeout(() => {
                    if (searchInput instanceof HTMLInputElement) {
                        const formElement = searchInput.closest('form');

                        if (formElement instanceof HTMLFormElement) {
                            formElement.requestSubmit();
                        }
                    }
                }, delay);
            };
        })();

        if (searchInput instanceof HTMLInputElement) {
            searchInput.addEventListener('input', () => debouncedSubmit(350));
        }

        if (imageInput instanceof HTMLInputElement) {
            imageInput.addEventListener('change', () => renderImagePreview(imageInput.files));
        }

        openButton.addEventListener('click', () => {
            openAddModal();
            renderImagePreview(imageInput instanceof HTMLInputElement ? imageInput.files : null);
        });
        section.querySelectorAll('[data-product-edit]').forEach((button) => {
            button.addEventListener('click', () => {
                try {
                    openEditModal(JSON.parse(button.dataset.productPayload || '{}'));
                } catch (error) {
                    openAddModal();
                }
            });
        });
        section.querySelectorAll('[data-product-delete-form]').forEach((deleteForm) => {
            deleteForm.addEventListener('submit', (event) => {
                if (!window.confirm('Delete this product?')) {
                    event.preventDefault();
                }
            });
        });
        closeButtons.forEach((button) => button.addEventListener('click', closeModal));
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        section.dataset.modalsInitialized = 'true';
    });
};