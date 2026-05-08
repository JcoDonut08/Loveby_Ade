const svg = (path) => `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">${path}</svg>`;

export const orderIcons = {
    clock: svg('<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25v6l3.5 2" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />'),
    chefHat: svg('<path stroke-linecap="round" stroke-linejoin="round" d="M6.5 10.25h11M7.75 10.25c-.5-3 1.55-5.5 4.25-5.5 1.35 0 2.4.5 3.1 1.35.35-.2.8-.35 1.4-.35 1.8 0 3.25 1.45 3.25 3.25 0 .45-.1.88-.25 1.25" /><path stroke-linecap="round" stroke-linejoin="round" d="m7.25 10.25 1.05 8h7.4l1.05-8M10 13.25h4M10.5 16h3" />'),
    truck: svg('<path stroke-linecap="round" stroke-linejoin="round" d="M4.75 7.75h9v8.5h-9zM13.75 10.25h3.5l2 2.25v3.75h-5.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5ZM17 18.25a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Z" />'),
    checkCircle: svg('<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12.25 10.9 15l4.85-5.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />'),
    xCircle: svg('<path stroke-linecap="round" stroke-linejoin="round" d="m9.25 9.25 5.5 5.5M14.75 9.25l-5.5 5.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />'),
    check: svg('<path stroke-linecap="round" stroke-linejoin="round" d="m5.75 12.5 4 4 8.5-9" />'),
    x: svg('<path stroke-linecap="round" stroke-linejoin="round" d="m6.75 6.75 10.5 10.5M17.25 6.75 6.75 17.25" />'),
    eye: svg('<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12s3-5.25 8.25-5.25S20.25 12 20.25 12s-3 5.25-8.25 5.25S3.75 12 3.75 12Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" />'),
};

export const statusIcons = {
    pending: orderIcons.clock,
    preparing: orderIcons.chefHat,
    out_for_delivery: orderIcons.truck,
    delivered: orderIcons.checkCircle,
    cancelled: orderIcons.xCircle,
};
