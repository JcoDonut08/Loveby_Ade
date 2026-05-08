---
name: frontend-design
description: Plan frontend page structure, UX flow, visual hierarchy, and component composition for this Laravel Blade and Tailwind application. Use when designing new pages, redesigning existing views, choosing layout patterns, or deciding how a user should move through a screen before implementation.
---

# Frontend Design

Design screens that feel intentional, useful, and aligned with the existing Loveby Ade interface before writing code.

## Instructions

- Inspect nearby Blade files, layouts, and components before proposing or changing a design.
- Use the project brand palette from `docs/Colors & Fonts.md`: pinks, sky/cyan, green, amber, purple, orange, white, and black.
- Use Playfair Display for expressive headings and Inter for interface text when typography is being touched.
- Prefer full usable screens over marketing-style explanations.
- Keep the page purpose obvious in the first viewport.
- Establish hierarchy with spacing, type scale, contrast, and component grouping before adding decoration.
- Design around real user tasks: browsing, choosing, comparing, ordering, administering, or reviewing.
- Prefer reusable Blade components for repeated UI patterns.
- Keep cards restrained, with radius at 8px or less unless existing components clearly use another pattern.
- Avoid nested cards, decorative gradient blobs, and one-note palettes.
- Ensure the design includes expected states such as empty, loading, error, disabled, active, hover, and focus when relevant.

## Laravel And Tailwind Fit

- Put page-level views under `resources/views/pages/`.
- Put reusable pieces under `resources/views/components/`.
- Use named routes with `route()` for navigation links.
- Use Tailwind utilities first and avoid inline `style` attributes.
- Follow the class order from `docs/ProjectStandards.md`: layout, flex/grid, spacing, sizing, typography, colors, borders, effects, state.

