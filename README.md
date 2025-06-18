# DueChiacchiere

A custom WordPress theme designed for optimized comment interaction, dark mode support, live search, and a built-in “Like” feature. Ideal for blogs and conversational websites.

## 🎯 Features

- **Dark mode toggle** — Seamlessly switch between light and dark themes.
- **Live search** — AJAX-powered search to filter posts in real-time.
- **Like comments** — Users can “Like” posts via integrated comment placeholders.
- **Improved reply system** — Reworked JavaScript for comment replies and form repositioning.
- **SCSS-based styling** — Modular styles with a structured SASS workflow.

## 🚀 Installation

1. Copy the folder `duechiacchiere/` into your theme directory (typically `/wp-content/themes/`).
2. Activate the theme via WordPress admin.
3. Load the theme’s asset pipeline (setup SASS if in dev mode).

## 🛠️ Development

- **Source folder**: `dev/`
  - `dev/assets/scss/` — SASS sources (variables, layout, components)
  - `dev/assets/js/script.js` — Main JS with comment handling and live search
- After making changes, compile assets into `assets/` via your preferred build tool (e.g., Gulp, Webpack).

## 🔧 Key Files

- `functions.php` — Registers theme supports, enqueues styles/scripts, and custom comment functionality.
- `index.php` — Theme template including dark mode logic and live-search integration.
- `theme.json` — Configures core block styles.
- `assets/` — Compiled CSS/JS for production.
- `screenshot.jpg` — Theme preview for WordPress admin.

## ✏️ Customization

- **Dark mode**: Modify CSS variables in SASS or adjust `theme.json` color palettes.
- **Live search**: Tweak AJAX endpoints in `dev/assets/js/script.js` and adapt the HTML structure.
- **Like button**: Server-side handling uses placeholder comment `[##like##]` to represent likes — update logic in `includes/comments.php` if necessary.
- **Styles**: Change fonts, spacing, and layout by editing SCSS variables in `dev/assets/scss/abstracts/variables.scss`.

## 🧪 Testing & Feedback

- Test across browsers and devices.
- Submit bug reports or suggestions via GitHub Issues or Pull Requests.

