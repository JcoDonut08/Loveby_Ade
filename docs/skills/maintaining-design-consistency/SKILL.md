---
name: maintaining-design-consistency
description: Keep new and existing frontend work visually consistent with the Loveby Ade Laravel Blade and Tailwind design system. Use when adding pages, components, colors, typography, spacing, buttons, cards, forms, nav, dashboards, or product UI that should match the existing brand.
---

# Maintaining Design Consistency

Preserve a coherent Loveby Ade interface as the app grows.

## Instructions

- Inspect existing views, layouts, components, and docs before adding new visual patterns.
- Reuse established Blade components when they fit the need.
- Keep colors aligned with `docs/Colors & Fonts.md`.
- Use Playfair Display and Inter according to existing usage.
- Match existing radius, border, shadow, spacing, and button patterns.
- Keep cards restrained and avoid nesting cards inside cards.
- Do not introduce a new visual language for one page unless the user explicitly asks for a redesign.
- Keep admin and operational screens quiet, dense, and easy to scan.
- Keep product and storefront screens expressive enough for the brand while preserving usability.
- Avoid adding custom CSS when Tailwind utilities or existing components are enough.
- When introducing a repeated UI pattern, create or update a reusable Blade component.

## Consistency Checklist

- Colors fit the project palette and do not create a one-off theme.
- Typography scale matches the surrounding page.
- Buttons have consistent hierarchy and states.
- Forms use consistent labels, spacing, validation, and focus styles.
- Cards and sections share spacing, borders, shadows, and radius with nearby UI.
- Responsive behavior matches similar screens.
- New components live in the correct `resources/views/components/` location.

