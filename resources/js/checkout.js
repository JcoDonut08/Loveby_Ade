const setPaymentCardState = (card, isSelected) => {
    card.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
    card.classList.toggle('border-love-pink-300', isSelected);
    card.classList.toggle('bg-love-pink-100/40', isSelected);
    card.classList.toggle('shadow-[0_24px_54px_-34px_rgba(236,72,153,0.52)]', isSelected);
    card.classList.toggle('border-white/80', !isSelected);
    card.classList.toggle('bg-white', !isSelected);
    card.classList.toggle('shadow-[0_18px_42px_-34px_rgba(15,23,42,0.28)]', !isSelected);
};

const setProgressCheckState = (check, isCompleted) => {
    check.classList.toggle('hidden', !isCompleted);
    check.classList.toggle('scale-100', isCompleted);
    check.classList.toggle('opacity-100', isCompleted);
    check.classList.toggle('scale-50', !isCompleted);
    check.classList.toggle('opacity-0', !isCompleted);
};

const initializeCheckoutFlow = () => {
    document.querySelectorAll('[data-checkout-page]').forEach((page) => {
        if (!(page instanceof HTMLElement) || page.dataset.initialized === 'true') {
            return;
        }

        const form = page.querySelector('[data-checkout-form]');
        const stepPanels = Array.from(page.querySelectorAll('[data-checkout-step]')).filter((panel) => panel instanceof HTMLElement);
        const progressSteps = Array.from(page.querySelectorAll('[data-checkout-progress-step]')).filter((step) => step instanceof HTMLElement);
        const paymentCards = Array.from(page.querySelectorAll('[data-payment-card]')).filter((card) => card instanceof HTMLButtonElement);
        const selectedPaymentInput = page.querySelector('[data-selected-payment-input]');
        const confirmationCheck = page.querySelector('[data-confirmation-check]');
        let currentStep = 1;

        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        const updateProgress = () => {
            progressSteps.forEach((step) => {
                const stepNumber = Number.parseInt(step.dataset.checkoutProgressStep || '1', 10);
                const circle = step.querySelector('[data-checkout-progress-circle]');
                const number = step.querySelector('[data-checkout-progress-number]');
                const check = step.querySelector('[data-checkout-progress-check]');
                const ping = step.querySelector('[data-checkout-progress-ping]');
                const icon = step.querySelector('[data-checkout-progress-icon]');
                const isActive = stepNumber === currentStep;
                const isCompleted = stepNumber < currentStep;

                step.classList.toggle('border-love-pink-200', isActive || isCompleted);
                step.classList.toggle('bg-love-pink-100/50', isActive);
                step.classList.toggle('-translate-y-1', isActive);
                step.classList.toggle('shadow-[0_24px_54px_-36px_rgba(236,72,153,0.36)]', isActive);

                if (circle instanceof HTMLElement) {
                    circle.classList.toggle('border-love-pink-400', isActive || isCompleted);
                    circle.classList.toggle('bg-love-pink-400', isActive || isCompleted);
                    circle.classList.toggle('text-white', isActive || isCompleted);
                    circle.classList.toggle('scale-105', isActive || isCompleted);
                    circle.classList.toggle('border-slate-200', !isActive && !isCompleted);
                    circle.classList.toggle('text-slate-500', !isActive && !isCompleted);
                }

                if (number instanceof HTMLElement) {
                    number.classList.toggle('hidden', isCompleted);
                }

                if (check instanceof SVGElement) {
                    setProgressCheckState(check, isCompleted);
                }

                if (ping instanceof HTMLElement) {
                    ping.classList.toggle('hidden', !isActive);
                }

                if (icon instanceof HTMLElement) {
                    icon.classList.toggle('bg-love-pink-500', isActive || isCompleted);
                    icon.classList.toggle('text-white', isActive || isCompleted);
                    icon.classList.toggle('bg-love-pink-100', !isActive && !isCompleted);
                    icon.classList.toggle('text-love-pink-500', !isActive && !isCompleted);
                }
            });
        };

        const updateReview = () => {
            page.querySelectorAll('[data-checkout-input]').forEach((input) => {
                if (!(input instanceof HTMLInputElement) && !(input instanceof HTMLTextAreaElement)) {
                    return;
                }

                const target = page.querySelector(`[data-review-field="${input.dataset.checkoutInput}"]`);

                if (target instanceof HTMLElement) {
                    const fallback = input.name === 'delivery_notes' ? 'No delivery notes' : 'Not provided';
                    target.textContent = input.value.trim() || fallback;
                }
            });

            const selectedPayment = paymentCards.find((card) => card.getAttribute('aria-pressed') === 'true');
            const title = page.querySelector('[data-review-payment-title]');
            const description = page.querySelector('[data-review-payment-description]');
            const note = page.querySelector('[data-review-payment-note]');

            if (selectedPayment instanceof HTMLButtonElement) {
                if (title instanceof HTMLElement) {
                    title.textContent = selectedPayment.dataset.paymentTitle || 'GCash';
                }

                if (description instanceof HTMLElement) {
                    description.textContent = selectedPayment.dataset.paymentDescription || '';
                }

                if (note instanceof HTMLElement) {
                    note.textContent = selectedPayment.dataset.paymentTitle === 'Cash on Delivery'
                        ? 'No upfront charges'
                        : 'Secure wallet payment';
                }
            }
        };

        const setStep = (stepNumber) => {
            currentStep = stepNumber;

            stepPanels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.checkoutStep !== stepNumber.toString());
            });

            updateProgress();

            if (currentStep === 3) {
                updateReview();
            }

            if (currentStep === 4 && confirmationCheck instanceof HTMLElement) {
                window.setTimeout(() => {
                    confirmationCheck.classList.remove('scale-75', 'opacity-0');
                    confirmationCheck.classList.add('scale-100', 'opacity-100');
                }, 80);
            }
        };

        paymentCards.forEach((card) => {
            card.addEventListener('click', () => {
                paymentCards.forEach((paymentCard) => setPaymentCardState(paymentCard, paymentCard === card));

                if (selectedPaymentInput instanceof HTMLInputElement) {
                    selectedPaymentInput.value = card.dataset.paymentTitle || '';
                }
            });

            setPaymentCardState(card, card.getAttribute('aria-pressed') === 'true');
        });

        page.querySelectorAll('[data-checkout-next]').forEach((button) => {
            button.addEventListener('click', () => {
                if (currentStep === 1 && !form.reportValidity()) {
                    return;
                }

                setStep(Math.min(currentStep + 1, 4));
            });
        });

        page.querySelectorAll('[data-checkout-back]').forEach((button) => {
            button.addEventListener('click', () => setStep(Math.max(currentStep - 1, 1)));
        });

        page.querySelector('[data-place-order]')?.addEventListener('click', () => {
            updateReview();

            if (!form.reportValidity()) {
                return;
            }

            const button = page.querySelector('[data-place-order]');

            if (button instanceof HTMLButtonElement) {
                button.disabled = true;
                button.textContent = 'Placing order...';
            }

            form.requestSubmit();
        });

        setStep(1);
        page.dataset.initialized = 'true';
    });
};

export { initializeCheckoutFlow };
