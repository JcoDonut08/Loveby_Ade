---
name: checking-responsive-ui
description: Review responsive behavior for Laravel Blade and Tailwind screens. Use when checking mobile, tablet, desktop, navigation, grids, forms, tables, dashboards, product cards, text wrapping, overflow, spacing, sticky headers, or breakpoint-specific UI issues.
---

# Checking Responsive UI

Verify that screens work across realistic viewport sizes without overflow, cramped spacing, or broken hierarchy.

## Instructions

- Check mobile first, then tablet, laptop, and wide desktop.
- Inspect navigation, forms, product grids, dashboards, tables, modals, and repeated cards at each breakpoint.
- Look for horizontal overflow, clipped content, cramped controls, awkward line breaks, hidden focus states, and overlapping text.
- Ensure buttons and links remain easy to tap on mobile.
- Make tables scroll or transform intentionally on small screens.
- Give images and media stable aspect ratios so layout does not jump.
- Ensure fixed, sticky, and absolute elements do not cover content.
- Verify empty, loading, error, and long-content states when they can affect layout.
- Prefer Tailwind breakpoint utilities that match the current layout patterns.
- Keep responsive changes targeted to the affected view or component.

## Common Fixes

- Use `min-w-0` when flex or grid children need to shrink.
- Use `overflow-x-auto` for wide tables or dense horizontal content.
- Use responsive grid columns instead of fixed widths.
- Use `max-w-*`, `w-full`, and `mx-auto` to prevent overly wide content on large screens.
- Use `aspect-*` utilities or explicit responsive sizing for images and repeated media.

