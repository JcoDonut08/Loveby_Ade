<section id="todo-list" class="rounded-[1.25rem] border border-love-pink-100/70 bg-white/96 p-6 shadow-[0_22px_55px_-44px_rgba(81,36,56,0.42)]" data-admin-todo>
    <div>
        <h2 class="text-2xl font-extrabold text-[#3b1728]">To do list</h2>
        <p class="mt-1 text-base font-medium text-[#9a6c7b]">Editable admin tasks</p>
    </div>

    <form class="mt-6 flex gap-2" data-todo-form>
        <label class="sr-only" for="admin-todo-input">New admin task</label>
        <input class="min-w-0 flex-1 rounded-full border border-love-pink-100 bg-white px-4 py-3 text-sm font-semibold text-[#512438] outline-none transition placeholder:text-[#9a6c7b] focus:border-love-pink-300 focus:ring-4 focus:ring-love-pink-100/80" id="admin-todo-input" type="text" placeholder="Add a task" data-todo-input>
        <button class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-love-pink-400 text-white shadow-[0_16px_30px_-22px_rgba(236,72,153,0.9)] transition hover:-translate-y-0.5 hover:bg-love-pink-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="submit" aria-label="Add task">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
            </svg>
        </button>
    </form>

    <ul class="mt-6 grid gap-3" data-todo-list>
        <li class="grid grid-cols-[auto_1fr_auto] items-center gap-3 rounded-2xl bg-love-cream p-3" data-todo-item>
            <input class="h-5 w-5 rounded border-love-pink-200 text-love-pink-500 focus:ring-love-pink-300" type="checkbox" data-todo-checkbox>
            <input class="min-w-0 bg-transparent text-sm font-semibold text-[#512438] outline-none" type="text" value="Confirm tomorrow's cake cup inventory" data-todo-title>
            <button class="flex h-9 w-9 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-rose-100 hover:text-rose-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Delete task" data-todo-delete>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" />
                </svg>
            </button>
        </li>
        <li class="grid grid-cols-[auto_1fr_auto] items-center gap-3 rounded-2xl bg-love-cream p-3" data-todo-item>
            <input class="h-5 w-5 rounded border-love-pink-200 text-love-pink-500 focus:ring-love-pink-300" type="checkbox" data-todo-checkbox>
            <input class="min-w-0 bg-transparent text-sm font-semibold text-[#512438] outline-none" type="text" value="Reply to custom dessert inquiries" data-todo-title>
            <button class="flex h-9 w-9 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-rose-100 hover:text-rose-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Delete task" data-todo-delete>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" />
                </svg>
            </button>
        </li>
        <li class="grid grid-cols-[auto_1fr_auto] items-center gap-3 rounded-2xl bg-love-cream p-3" data-todo-item>
            <input class="h-5 w-5 rounded border-love-pink-200 text-love-pink-500 focus:ring-love-pink-300" type="checkbox" checked data-todo-checkbox>
            <input class="min-w-0 bg-transparent text-sm font-semibold text-[#9a6c7b] line-through outline-none" type="text" value="Post weekend promo banner" data-todo-title>
            <button class="flex h-9 w-9 items-center justify-center rounded-full text-[#9a6c7b] transition hover:bg-rose-100 hover:text-rose-500 focus:outline-none focus:ring-4 focus:ring-love-pink-100" type="button" aria-label="Delete task" data-todo-delete>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.75h10.5M10 7.75v-2h4v2M9 10.75v6M15 10.75v6M8 7.75l.75 11.5h6.5L16 7.75" />
                </svg>
            </button>
        </li>
    </ul>
</section>
