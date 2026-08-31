# Contributing to Video Lightbox for YouTube/Vimeo

Thank you for your interest in contributing to **Video Lightbox for YouTube/Vimeo**! We welcome bug reports, feature suggestions, documentation improvements, and pull requests.

---

## 📋 Code of Conduct

Please help us maintain a friendly, welcoming, and inclusive environment. Be respectful, constructive, and collaborative in all discussions.

---

## 🐛 Reporting Bugs & Suggesting Features

Before opening a new issue:
1. **Search existing issues and PRs** to make sure the topic hasn't already been reported or discussed.
2. When creating a bug report, include:
   - WordPress version and PHP version.
   - Browser version and operating system.
   - Clear, step-by-step instructions to reproduce the issue.
   - Expected behavior vs. actual behavior.
   - Screenshots or console errors if applicable.

---

## 💻 Local Development Setup

### Prerequisites
- **PHP** >= 8.1
- **Composer**
- **Node.js** >= 22.0.0
- **npm** >= 10.0.0

### Getting Started
1. **Fork & Clone** the repository:
   ```bash
   git clone https://github.com/milindmore22/youtubefancybox.git
   cd youtubefancybox
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Development Build**:
   ```bash
   # Start file watcher for real-time asset compilation
   npm start

   # Or create a one-time development build
   npm run build:dev
   ```

---

## 🎨 Coding Standards & Testing

All code must adhere to WordPress coding standards and pass static analysis:

### 1. Linting & Formatting
```bash
# Run all linters (PHP, CSS, JS)
npm run lint

# Lint & fix PHP coding standards (PHPCS / PHPCBF)
npm run lint:php
npm run lint:php:fix

# Lint CSS (Stylelint)
npm run lint:css
npm run lint:css:fix

# Lint JavaScript (ESLint)
npm run lint:js
npm run lint:js:fix
```

### 2. Static Analysis
```bash
# Run PHPStan static analysis
npm run lint:php:stan
```

---

## 🚀 Pull Request Workflow

1. Create a new branch from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```
2. Make your changes with concise, meaningful commit messages.
3. Verify that all linters and tests pass locally (`npm run lint`).
4. Push your branch to your fork and submit a Pull Request.
5. Provide a clear summary of your changes in the PR description, including references to any related issues.

---

## 📄 License

By contributing to this repository, you agree that your contributions will be licensed under the [GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html) license.
