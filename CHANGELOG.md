# Changelog

A living summary of improvements applied so far. Scope covers UI/UX and, where noted, supporting backend endpoints.

## Conventions
- Track new work under "Unreleased" using categories: Added, Changed, Fixed, Removed.
- When you ship, move items from Unreleased to a new version heading like `## 0.x.y — YYYY-MM-DD`.
- Keep entries short and user-facing; reference files only when helpful.

## Unreleased
- Added: Edit flow now includes payment selection when editing une demande; après la mise à jour des détails, l’utilisateur passe à l’étape 3 pour (re)choisir le mode de paiement.
  - Nouveaux endpoints (mode édition):
    - POST `/app/requests/<id>/payment-method/` — met à jour `payment_method` pour les statuts `draft`/`pending_payment`.
    - POST `/app/requests/<id>/resubmit/` — régénère le jeton de paiement, passe le statut à `pending_payment` et renvoie l’email de paiement.
- Changed: Pour les demandes `paid`, l’édition est limitée aux documents et ignore l’étape paiement; retour à l’historique après enregistrement.
- Improved: Rendu des vignettes et libellés des documents Visa/Carte unifié côté utilisateur et staff.
- Added: Staff backoffice at `/app/backoffice/` (staff-only), inspired by the user dashboard.
  - KPI cards consume `/app/admin/stats/`.
  - Requests grid with search and status chips consumes `/app/admin/requests/`.
  - Detail modal uses `/app/admin/requests/<id>/`.
  - Shares the same light/dark theme tokens.
  - Detail modal now renders Visa/Carte document thumbnails (from `MEDIA_URL`) with links to originals and basic HTML escaping.
  - Staff profile panel: read/update endpoints wired; inline validation.
  - Users management: list/search with staff filter, create, and per-user detail/update (flags and groups).
    - GET `/app/admin/users/` — list with optional `q` and `staff` filters.
    - POST `/app/admin/users/create/` — create a user (basic validation, optional staff flag).
    - GET `/app/admin/users/<id>/` — user details plus groups and all_groups.
    - POST `/app/admin/users/<id>/update/` — update profile fields, active/staff/superuser/email_verified, and groups (permission checks enforced).

- Changed: Staff and superusers are redirected to `/app/backoffice/` after login.

- Changed: Visa uploads are processed with Pillow (resize, JPEG normalize, thumbnails) in both `submit_request` and `wizard_details`; stored in JSON as `{ path, thumb, format }` per field.
 - Changed: Edit flow is now professional: clicking “Modifier” in request details redirects to the Wizard with step 2 open and the form prefilled; submitting updates the existing request via `/app/requests/<id>/update/` and returns to Historique.

## Highlights
- Modern visual theme
  - Light gray surfaces for cards/backgrounds; sidebar switched to a premium gray style.
  - Consistent design tokens (ink, muted, bg, surface, sidebar, border, primary).
- Inline form validation
  - On wizard "Suivant" and standalone forms: missing fields are highlighted inline (`.ui-error`) with message blocks (`.field-error`).
  - Wizard hides inner submit buttons to avoid confusion.
- Personalization and navigation
  - Sidebar brand shows the logged-in user’s email (ellipsized).
  - Home hero greets with "Bonjour, FULL NAME" (uppercase) with email fallback.
  - Home CTA flows simplified; removed extra buttons and centered hero. Link points to the Wizard.
  - Client-side routing fixed so "Nouvelle demande" navigates to the Wizard.
- Historique (Mes demandes) redesign
  - Toolbar with search input and filter chips (All, Pending payment, Paid, Canceled).
  - Premium card layout in a responsive grid; hover lift and subtle border highlight.
  - Skeleton loading state while fetching.
  - Client-side filtering by status and query.
- Request details popup redesign
  - Premium modal with icon, status pill, and a key–value grid of submitted data.
  - Structured meta line (ID and timestamp). Footer and header use surface/border tokens.
- Dark/Light theme support
  - Theme toggle persists in `localStorage` and defaults to the system preference on first visit.
  - CSS variables switch with `html[data-theme="dark"]` for ink/muted/bg/surface/sidebar/border/primary.
  - Forms now consume tokens instead of hard-coded colors (text uses `--ink`, placeholders use `--muted`, surfaces use `--surface`).
  - Choice/checkbox "chips" get dark-friendly backgrounds and checked states; inputs/file inputs respect theme.
  - Status pills adjusted for dark mode with translucent backgrounds and readable text.

## Notable UX details
- Wizard flow
  - Stepper states (active/done) visually clear; step dots use themed border and muted label colors.
  - Step 2 performs inline validation before proceeding.
  - Step 4 confirmation modal summarizes key info before final submit.
- Forms UX
  - Toggle-based controls initialized on dynamic injection via `FormsInit.initAll`.
  - File inputs and grouped sections (TDG/Visa/etc.) styled with themed surfaces and borders.

## Files updated
- `templates/dashboard/space.html`
  - Sidebar: gray theme; brand shows user email; theme toggle moved to the sidebar footer.
  - Home (hero): greeting in uppercase, centered layout; simplified CTAs to point to Wizard.
  - Wizard: hides inner submit buttons; inline validation; payment hint; summary/confirmation modal.
  - Historique: toolbar (search + chips), premium card grid, themed status pills, skeleton, detail modal.
  - Request detail modal: premium layout; themed header/footer; key–value grid.
  - Dark/Light theme: CSS variables, toggle button, and JS to apply/persist theme.
- `dashboard/static/components/forms/forms.css`
  - Surfaces/borders/text/placeholder colors use tokens (`--surface`, `--border`, `--ink`, `--muted`).
  - Choice/checkbox chips and file inputs themed; dark-mode friendly checked state.
  - Inline error styling (`.ui-error`, `.field-error`) preserved.
- `templates/dashboard/backoffice.html`
  - Staff backoffice UI: KPIs, requests, profile; added Users tab with search/staff filter, create-user modal, and User Detail modal (flags + groups), wired to admin APIs.

## How to use
- Theme: Use the toggle at the bottom of the sidebar to switch Light/Dark; state persists across reloads.
- Historique: Use the search box and chips to filter; click a card to open the detail modal.
- Forms/Wizard: Fill required fields; inline hints will guide corrections.
 - Backoffice → Users: Click a user card to open details; edit flags and groups, then Enregistrer to save.

## Next ideas (optional)
- Add date-range filter and CSV export to Historique.
- Add quick actions (e.g., Pay/Cancel) inside request cards.
- Persist Visa/Carte uploads via `FileField` and show previews in Admin.
