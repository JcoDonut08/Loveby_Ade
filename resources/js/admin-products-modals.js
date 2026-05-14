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
        const existingImagesContainer = form.querySelector('[data-product-existing-images]');
        const previewContainer = section.querySelector('[data-product-image-preview]');
        let existingImages = [];
        let selectedFiles = [];

        if (!modal || !openButton || !(form instanceof HTMLFormElement)) {
            return;
        }

        const setFieldValue = (name, value) => {
            const field = form.querySelector(`[data-product-field="${name}"]`);

            if (field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement) {
                field.value = value ?? '';
            }
        };

        const escapeAttribute = (value) => value
            .toString()
            .replaceAll('&', '&amp;')
            .replaceAll('"', '&quot;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;');

        const syncSelectedFiles = () => {
            if (!(imageInput instanceof HTMLInputElement) || typeof DataTransfer === 'undefined') {
                return;
            }

            const transfer = new DataTransfer();
            selectedFiles.forEach((file) => transfer.items.add(file));
            imageInput.files = transfer.files;
        };

        const syncExistingImages = () => {
            if (!(existingImagesContainer instanceof HTMLElement)) {
                return;
            }

            existingImagesContainer.innerHTML = existingImages
                .filter((image) => image.path)
                .map((image) => `<input type="hidden" name="existing_images[]" value="${escapeAttribute(image.path)}">`)
                .join('');
        };

        const selectedFilePreviews = () => selectedFiles.map((file) => ({
            src: URL.createObjectURL(file),
            alt: file.name,
        }));

        const renderImagePreview = () => {
            if (!(previewContainer instanceof HTMLElement)) {
                return;
            }

            const imageItems = [
                ...existingImages,
                ...selectedFilePreviews(),
            ].slice(0, 4);

            if (imageItems.length === 0) {
                previewContainer.innerHTML = `
                    <div class="rounded-[1.25rem] border border-dashed border-love-pink-200 bg-love-cream p-4 text-sm font-bold text-[#9a6c7b] sm:col-span-4">
                        No image selected yet.
                    </div>
                `;

                return;
            }

            previewContainer.innerHTML = imageItems.map((image, index) => `
                <figure class="relative overflow-hidden rounded-[1rem] border border-love-pink-100 bg-white shadow-sm">
                    <img class="aspect-square w-full object-cover" src="${escapeAttribute(image.src)}" alt="${escapeAttribute(image.alt || 'Product image preview')}">
                    ${index === 0 ? '<figcaption class="absolute bottom-2 left-2 rounded-full bg-white/92 px-2.5 py-1 text-xs font-extrabold text-[#512438] shadow-sm backdrop-blur-sm">Primary</figcaption>' : ''}
                </figure>
            `).join('');
        };

        const clearImages = () => {
            existingImages = [];
            selectedFiles = [];
            syncSelectedFiles();
            syncExistingImages();
            renderImagePreview();
        };

        const setExistingImages = (images) => {
            existingImages = Array.isArray(images) ? images.slice(0, 4) : [];
            selectedFiles = [];
            syncSelectedFiles();
            syncExistingImages();
            renderImagePreview();
        };

        const appendSelectedFiles = (files) => {
            const nextFiles = Array.from(files || [])
                .filter((file) => file instanceof File && file.type.startsWith('image/'));
            const remainingSlots = Math.max(0, 4 - existingImages.length - selectedFiles.length);

            selectedFiles = [
                ...selectedFiles,
                ...nextFiles.slice(0, remainingSlots),
            ];

            syncSelectedFiles();
            renderImagePreview();
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

            clearImages();
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

            if (imageInput instanceof HTMLInputElement) {
                imageInput.value = '';
            }

            if (modalTitle) {
                modalTitle.textContent = 'Edit product';
            }

            if (saveButton) {
                saveButton.textContent = 'Update product';
            }

            setExistingImages(payload.images || []);
            openModal();
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        };

        const refreshProducts = async (url) => {
            if (!window.axios) {
                window.location.href = url;

                return;
            }

            section.classList.add('opacity-70');

            try {
                const response = await window.axios.get(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const template = document.createElement('template');
                template.innerHTML = response.data.html || '';
                const nextSection = template.content.querySelector('[data-admin-products]');

                if (!(nextSection instanceof HTMLElement)) {
                    window.location.href = url;

                    return;
                }

                section.replaceWith(nextSection);
                window.history.replaceState({}, '', url);
                initializeAdminProductModals();
            } finally {
                section.classList.remove('opacity-70');
            }
        };

        const productUrlFromForm = () => {
            const formElement = searchInput instanceof HTMLInputElement ? searchInput.closest('form') : null;
            const url = new URL(formElement instanceof HTMLFormElement ? formElement.action : window.location.href, window.location.origin);

            if (formElement instanceof HTMLFormElement) {
                new FormData(formElement).forEach((value, key) => {
                    if (value.toString() !== '') {
                        url.searchParams.set(key, value.toString());
                    } else {
                        url.searchParams.delete(key);
                    }
                });
            }

            url.searchParams.delete('page');

            return url.toString();
        };

        const debouncedRefresh = (() => {
            let timeoutId;

            return (delay = 300) => {
                window.clearTimeout(timeoutId);
                timeoutId = window.setTimeout(() => {
                    refreshProducts(productUrlFromForm());
                }, delay);
            };
        })();

        if (searchInput instanceof HTMLInputElement) {
            searchInput.addEventListener('input', () => debouncedRefresh(150));

            const searchForm = searchInput.closest('form');

            if (searchForm instanceof HTMLFormElement) {
                searchForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    refreshProducts(productUrlFromForm());
                });
            }
        }

        if (imageInput instanceof HTMLInputElement) {
            imageInput.addEventListener('change', () => {
                appendSelectedFiles(imageInput.files || []);
                imageInput.setCustomValidity('');
            });
        }

        openButton.addEventListener('click', () => {
            openAddModal();
        });
        section.querySelectorAll('a[href*="/admin/products"]').forEach((link) => {
            link.addEventListener('click', (event) => {
                if (!(link instanceof HTMLAnchorElement)) {
                    return;
                }

                event.preventDefault();
                refreshProducts(link.href);
            });
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
