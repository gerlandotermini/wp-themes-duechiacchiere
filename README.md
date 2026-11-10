# DueChiacchiere

**DueChiacchiere** is a custom WordPress theme designed for blogs and conversational websites, with a strong focus on community interaction, performance, and a polished reading experience.

It combines enhanced comment functionality, AJAX-powered features, dark mode, dynamic color schemes, and a lightweight frontend.

## 🎯 Features

- **Dark mode** — Seamlessly switch between light and dark themes, with color adjustments throughout the interface.
- **Dynamic monthly color schemes** — Automatically changing accent colors based on the month, with dark-mode compatibility.
- **Live search** — AJAX-powered search that filters posts in real time without requiring a page reload.
- **Enhanced comment system** — Custom comment functionality with improved reply handling and form repositioning.
- **Like comments and posts** — Integrated Like functionality using custom comment placeholders.
- **Online status indicator** — Displays an Online ribbon to visitors when an administrator has recently been active, tracking activity on both the frontend and WordPress administration area.
- **Responsive design** — Layout and components adapt to desktop, tablet, and mobile screens.
- **SCSS-based styling** — Modular stylesheets with reusable variables, components, and a structured development workflow.
- **Custom JavaScript functionality** — Interactive features designed to minimize unnecessary page reloads.

## 🚀 Installation

1. Download or clone the repository.
2. Copy the `duechiacchiere/` folder into your WordPress themes directory (`/wp-content/themes/`).
3. Activate **DueChiacchiere** from the WordPress administration area.

Compiled CSS and JavaScript assets are included, so no additional build tools are required for a standard installation.

## 🛠️ Development

The theme uses SCSS and JavaScript source files compiled through Gulp.

**Source structure:**

- `dev/assets/scss/` — SCSS source files, including variables, layouts, and components.
- `dev/assets/js/` — JavaScript source files.
- `assets/` — Compiled CSS and JavaScript files used by the theme.

After modifying source files, run the Gulp build workflow to compile and minify the assets.

**Development requirements:**
- Node.js
- npm
- Gulp and the project's npm dependencies

## 🔧 Key Files

- `functions.php` — Theme initialization, feature registration, asset enqueuing, and custom functionality.
- `index.php` — Main theme template.
- `theme.json` — WordPress block editor settings and global styles.
- `footer.php` — Footer markup and frontend components, including the online status indicator.
- `includes/` — Additional PHP functionality, including custom comment handling.
- `assets/` — Production-ready CSS and JavaScript.
- `dev/` — SCSS and JavaScript source files.
- `screenshot.jpg` — Theme preview for the WordPress administration area.

## ✏️ Customization

- **Colors and themes** — Modify SCSS variables and theme color definitions to customize the monthly palettes and dark-mode appearance.
- **Live search** — Customize the AJAX search implementation and frontend behavior in the JavaScript source files.
- **Comment functionality** — Modify the comment handling logic and associated JavaScript to customize Like and Reply interactions.
- **Online status** — Adjust the activity tracking and status-checking logic to change how administrator presence is detected and displayed.
- **Styles and layout** — Customize typography, spacing, breakpoints, and components through the SCSS source files.

## 🧪 Testing & Feedback

- Test across browsers and screen sizes.
- Report bugs or suggest improvements through GitHub Issues.
- Contributions and Pull Requests are welcome.