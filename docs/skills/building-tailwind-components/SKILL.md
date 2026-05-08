---
name: building-tailwind-components
description: Build or modify Tailwind CSS UI components in Laravel Blade views. Use when creating cards, forms, navbars, buttons, tables, product grids, admin panels, responsive sections, reusable Blade components, or Tailwind utility class changes.
---

# Building Tailwind Components

Create production-ready Blade and Tailwind UI that matches this project and remains easy to maintain.

## Instructions

- Check sibling Blade components before creating a new component.
- Use existing layouts and components when possible.
- Keep component files focused and small; split large components instead of growing one file.
- Use Tailwind utility classes instead of custom CSS unless the design cannot be expressed cleanly with utilities.
- Do not use inline `style` attributes.
- Do not add `!important`.
- Keep `resources/css/app.css` minimal unless theme tokens are genuinely needed.
- Organize Tailwind classes in this order: layout, flex/grid, spacing, sizing, typography, colors, borders, effects, state.
- Use semantic HTML elements before styling generic `div` elements.
- Use labels, helper text, validation states, disabled states, hover states, and focus states for form controls.
- Use stable sizing for icon buttons, cards, counters, table columns, image frames, and repeated grid items so content changes do not shift the layout.
- Prefer icons for common actions when an icon library is already used by the project.

## Project Style

- Use the palette in `docs/Colors & Fonts.md`.
- Use Playfair Display for brand/editorial headings when appropriate.
- Use Inter for operational UI, forms, tables, nav, and body text.
- Keep rounded corners restrained and avoid overly soft, pill-heavy layouts unless an existing component establishes that pattern.
- Avoid decorative backgrounds that make product content or admin data harder to read.

## Before Finishing

- Check mobile, tablet, and desktop behavior.
- Check text wrapping in buttons, cards, nav, and form controls.
- Confirm repeated UI patterns are extracted into Blade components when reused.

