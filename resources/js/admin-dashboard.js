const initializeAdminSalesFilters = () => {
    document.querySelectorAll('[data-sales-performance]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.initialized === 'true') {
            return;
        }

        const total = section.querySelector('[data-sales-total]');
        const caption = section.querySelector('[data-sales-caption]');
        const buttons = section.querySelectorAll('[data-sales-filter]');
        const charts = section.querySelectorAll('[data-sales-bars]');
        const activeClasses = ['bg-white', 'text-[#512438]', 'shadow-sm'];
        const inactiveClasses = ['text-[#9a6c7b]'];

        const showChart = (period) => {
            charts.forEach((chart) => {
                if (!(chart instanceof HTMLElement)) {
                    return;
                }

                chart.hidden = chart.dataset.salesBars !== period;
            });

            buttons.forEach((button) => {
                if (!(button instanceof HTMLButtonElement)) {
                    return;
                }

                const isActive = button.dataset.salesFilter === period;

                button.classList.toggle(activeClasses[0], isActive);
                button.classList.toggle(activeClasses[1], isActive);
                button.classList.toggle(activeClasses[2], isActive);
                button.classList.toggle(inactiveClasses[0], !isActive);
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');

                if (isActive) {
                    if (total instanceof HTMLElement && button.dataset.salesTotal) {
                        total.textContent = button.dataset.salesTotal;
                    }

                    if (caption instanceof HTMLElement && button.dataset.salesCaption) {
                        caption.textContent = button.dataset.salesCaption;
                    }
                }
            });
        };

        buttons.forEach((button) => {
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            button.addEventListener('click', () => {
                showChart(button.dataset.salesFilter || 'daily');
            });
        });

        showChart(section.dataset.salesDefault || 'daily');
        section.dataset.initialized = 'true';
    });
};

const initializeAdminTodoLists = () => {
    document.querySelectorAll('[data-admin-todo]').forEach((section, sectionIndex) => {
        if (!(section instanceof HTMLElement) || section.dataset.initialized === 'true') {
            return;
        }

        const form = section.querySelector('[data-todo-form]');
        const input = section.querySelector('[data-todo-input]');
        const list = section.querySelector('[data-todo-list]');
        const storageKey = `lovebyade.admin.todos.${sectionIndex}`;

        if (!(form instanceof HTMLFormElement) || !(input instanceof HTMLInputElement) || !(list instanceof HTMLUListElement)) {
            return;
        }

        const readTasksFromDom = () => Array.from(list.querySelectorAll('[data-todo-item]')).map((item) => {
            const checkbox = item.querySelector('[data-todo-checkbox]');
            const title = item.querySelector('[data-todo-title]');

            return {
                title: title instanceof HTMLInputElement ? title.value : '',
                completed: checkbox instanceof HTMLInputElement ? checkbox.checked : false,
            };
        }).filter((task) => task.title.trim() !== '');

        const getStoredTasks = () => {
            try {
                const storedTasks = window.localStorage.getItem(storageKey);

                return storedTasks ? JSON.parse(storedTasks) : null;
            } catch {
                return null;
            }
        };

        const saveTasks = () => {
            try {
                window.localStorage.setItem(storageKey, JSON.stringify(readTasksFromDom()));
            } catch {
                return;
            }
        };

        const setTitleState = (titleInput, completed) => {
            titleInput.classList.toggle('text-[#9a6c7b]', completed);
            titleInput.classList.toggle('line-through', completed);
            titleInput.classList.toggle('text-[#512438]', !completed);
        };

        const createTaskElement = (task) => {
            const item = document.createElement('li');
            item.className = 'grid grid-cols-[auto_1fr_auto] items-center gap-3 rounded-2xl bg-love-cream p-3';
            item.dataset.todoItem = 'true';

            const checkbox = document.createElement('input');
            checkbox.className = 'h-5 w-5 rounded border-love-pink-200 text-love-pink-500 focus:ring-love-pink-300';
            checkbox.type = 'checkbox';
            checkbox.checked = Boolean(task.completed);
            checkbox.dataset.todoCheckbox = 'true';

            const title = document.createElement('input');
            title.className = 'min-w-0 bg-transparent text-sm font-semibold outline-none';
            title.type = 'text';
            title.value = task.title;
            title.dataset.todoTitle = 'true';
            setTitleState(title, checkbox.checked);

            const remove = document.createElement('button');
            remove.className = 'flex h-9 w-9 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-rose-100 hover:text-rose-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100';
            remove.type = 'button';
            remove.setAttribute('aria-label', 'Delete task');
            remove.dataset.todoDelete = 'true';
            remove.innerHTML = '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" /></svg>';

            checkbox.addEventListener('change', () => {
                setTitleState(title, checkbox.checked);
                saveTasks();
            });

            title.addEventListener('input', saveTasks);

            remove.addEventListener('click', () => {
                item.remove();
                saveTasks();
            });

            item.append(checkbox, title, remove);

            return item;
        };

        const renderTasks = (tasks) => {
            list.replaceChildren(...tasks.map(createTaskElement));
            saveTasks();
        };

        const storedTasks = getStoredTasks();

        if (Array.isArray(storedTasks)) {
            renderTasks(storedTasks);
        } else {
            renderTasks(readTasksFromDom());
        }

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const title = input.value.trim();

            if (!title) {
                return;
            }

            list.append(createTaskElement({ title, completed: false }));
            input.value = '';
            input.focus();
            saveTasks();
        });

        section.dataset.initialized = 'true';
    });
};

const initializeAdminOrderPagination = () => {
    document.querySelectorAll('[data-admin-orders]').forEach((section) => {
        if (!(section instanceof HTMLElement) || section.dataset.orderPaginationInitialized === 'true') {
            return;
        }

        const rows = Array.from(section.querySelectorAll('[data-order-row]')).filter((row) => row instanceof HTMLElement);
        const pageSizeControl = section.querySelector('[data-order-page-size]');
        const status = section.querySelector('[data-order-pagination-status]');
        const pageButtons = Array.from(section.querySelectorAll('[data-order-page-button]')).filter((button) => button instanceof HTMLButtonElement);
        const previous = section.querySelector('[data-order-page-previous]');
        const next = section.querySelector('[data-order-page-next]');
        const basePageButtonClasses = 'inline-flex h-10 min-w-10 items-center justify-center rounded-full px-3 text-sm font-extrabold transition';
        const activePageButtonClasses = 'bg-love-pink-400 text-white shadow-[0_14px_28px_-20px_rgba(236,72,153,0.9)] hover:bg-love-pink-500';
        const inactivePageButtonClasses = 'border border-love-pink-100 text-[#512438] hover:bg-love-pink-100 hover:text-love-pink-500';
        let currentPage = 1;

        if (!(pageSizeControl instanceof HTMLSelectElement) || !(previous instanceof HTMLButtonElement) || !(next instanceof HTMLButtonElement)) {
            return;
        }

        const getPageSize = () => {
            const selectedPageSize = Number.parseInt(pageSizeControl.value || '5', 10);

            return Number.isFinite(selectedPageSize) && selectedPageSize > 0 ? selectedPageSize : 5;
        };

        const renderPage = () => {
            const pageSize = getPageSize();
            const totalRows = rows.length;
            const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
            currentPage = Math.min(currentPage, totalPages);

            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = Math.min(startIndex + pageSize, totalRows);

            rows.forEach((row, index) => {
                row.hidden = index < startIndex || index >= endIndex;
            });

            if (status instanceof HTMLElement) {
                const startRow = totalRows === 0 ? 0 : startIndex + 1;

                status.textContent = `Showing ${startRow}-${endIndex} of ${totalRows} orders`;
            }

            pageButtons.forEach((button) => {
                const page = Number.parseInt(button.dataset.orderPageButton || '1', 10);
                const isAvailable = page <= totalPages;
                const isActive = page === currentPage;

                button.hidden = !isAvailable;
                button.className = `${basePageButtonClasses} ${isActive ? activePageButtonClasses : inactivePageButtonClasses}`;

                if (isActive) {
                    button.setAttribute('aria-current', 'page');
                } else {
                    button.removeAttribute('aria-current');
                }
            });

            previous.disabled = currentPage === 1;
            next.disabled = currentPage === totalPages;
        };

        pageSizeControl.addEventListener('change', () => {
            currentPage = 1;
            renderPage();
        });

        pageButtons.forEach((button) => {
            button.addEventListener('click', () => {
                currentPage = Number.parseInt(button.dataset.orderPageButton || '1', 10);
                renderPage();
            });
        });

        previous.addEventListener('click', () => {
            currentPage = Math.max(1, currentPage - 1);
            renderPage();
        });

        next.addEventListener('click', () => {
            currentPage += 1;
            renderPage();
        });

        renderPage();
        section.dataset.orderPaginationInitialized = 'true';
    });
};

export const initializeAdminDashboard = () => {
    initializeAdminSalesFilters();
    initializeAdminTodoLists();
    initializeAdminOrderPagination();
};
