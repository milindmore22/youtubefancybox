# Video Lightbox for YouTube/Vimeo

[![WordPress Plugin](https://img.shields.io/badge/WordPress.org-Plugin-blue.svg)](https://wordpress.org/plugins/youtubefancybox/)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.1-777bb4.svg)](https://www.php.net/)
[![WordPress](https://img.shields.io/badge/WordPress-%3E%3D6.7-21759b.svg)](https://wordpress.org/)

**Video Lightbox for YouTube/Vimeo** is a lightweight, responsive WordPress plugin that displays customizable YouTube and Vimeo video thumbnails that smoothly expand into a lightbox popup overlay when clicked.

---

## 🚀 Features

- **YouTube & Vimeo Support**: Embed any public video using clean, flexible shortcodes.
- **Responsive Popup Lightbox**: Seamless modal playback using Colorbox.
- **AMP Ready**: First-class support for the official WordPress AMP plugin (`amp-lightbox`, `amp-youtube`, and `amp-vimeo`).
- **Dashboard Shortcode Generator**: Dedicated generator panels in the admin dashboard to generate shortcodes in seconds.
- **Customizable Defaults**: Easily define global height, width, and autoplay preferences.
- **Conditional Asset Loading**: Scripts and stylesheets are enqueued strictly where needed to ensure optimal Core Web Vitals.

---

## 📦 Installation

### From WordPress Dashboard:
1. Go to **Plugins > Add New**.
2. Search for `Video Lightbox for YouTube/Vimeo`.
3. Click **Install Now** and **Activate**.

### Manual Installation:
1. Download or clone this repository into `/wp-content/plugins/youtubefancybox`.
2. Run `composer install` and `npm run build:prod` if installing from source.
3. Activate the plugin via **Plugins** in the WordPress admin.

---

## 💡 Usage & Shortcodes

### YouTube Shortcodes
```text
[youtube url="https://www.youtube.com/watch?v=dQw4w9WgXcQ"]
[youtube videoid="dQw4w9WgXcQ" width="400" height="350"]
```

### Vimeo Shortcodes
```text
[vimeo url="https://vimeo.com/76979871"]
[vimeo videoid="76979871" width="400" height="350"]
```

### Shortcode Attributes:
| Attribute | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `url` | string | `""` | Full YouTube or Vimeo URL. |
| `videoid` | string | `""` | Direct video ID (e.g. YouTube video ID or Vimeo ID). |
| `width` | integer | `400` | Width of the video thumbnail in pixels. |
| `height` | integer | `350` | Height of the video thumbnail in pixels. |

---

## 🛠️ Development

### Requirements:
- PHP >= 8.1
- Node.js >= 22.0.0
- Composer

### Setup & Commands:
```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Build dev assets
npm run build:dev

# Build production assets
npm run build:prod

# Lint code (PHPCS, Stylelint, ESLint)
npm run lint
npm run lint:css
npm run lint:js
npm run lint:php
```

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Please check out [CONTRIBUTING.md](./CONTRIBUTING.md) for contribution guidelines.

---

## 📄 License

This project is licensed under the [GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html) license.