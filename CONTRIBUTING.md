# Contributing to Blush Salon 🌸

Thank you for your interest in contributing to **Blush Salon**! We welcome contributions from developers, designers, and WordPress enthusiasts to make this theme even better.

---

## 📌 Code of Conduct

By participating in this project, you agree to abide by our [Code of Conduct](CODE_OF_CONDUCT.md). Please treat all contributors with respect and kindness.

---

## 🚀 How to Contribute

### 1. Reporting Bugs
- Search existing [Issues](https://github.com/yaasiinaxmed/blush-salon-theme-wordpress/issues) to avoid duplicates.
- If not found, open a new issue with:
  - Clear and descriptive title.
  - Steps to reproduce the problem.
  - Expected vs. actual behavior.
  - WordPress version, PHP version, and browser used.
  - Screenshots or console logs if applicable.

### 2. Suggesting Enhancements
- Open an issue labeled `enhancement` describing the idea and why it would benefit users.
- Provide mockup designs or code snippets if relevant.

### 3. Pull Requests (PR) Workflow
1. **Fork the repository** on GitHub.
2. **Clone your fork**:
   ```bash
   git clone https://github.com/YOUR-USERNAME/blush-salon-theme-wordpress.git
   ```
3. **Create a new branch**:
   ```bash
   git checkout -b feature/your-feature-name
   # or
   git checkout -b fix/your-bugfix-name
   ```
4. **Make your changes**:
   - Follow WordPress Coding Standards (WPCS).
   - Sanitize all inputs using `sanitize_text_field()`, `esc_url()`, etc.
   - Escape all outputs using `esc_html()`, `esc_attr()`, `wp_kses_post()`.
   - Ensure translation readiness with `__()`, `esc_html__()`, etc. using the `blush-salon` textdomain.
5. **Test your code**:
   - Verify there are zero PHP syntax errors (`php -l filename.php`).
   - Test both with Elementor active and inactive.
6. **Commit your changes**:
   Use clear, conventional commit messages:
   ```bash
   git commit -m "feat: add appointment calendar shortcode parameter"
   ```
7. **Push to your fork and submit a PR**:
   ```bash
   git push origin feature/your-feature-name
   ```
8. Open a Pull Request against the `main` branch with a clear description of changes.

---

## 📋 Coding Standards

- **PHP:** Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
- **CSS:** Use modular, BEM-like class naming (`.service-card__body`, `.btn--accent`).
- **Security:** Never execute unescaped SQL queries or trust raw `$_POST`/`$_GET` input without nonces and capability checks.
- **Privacy:** Do not introduce external CDN scripts or fonts without ensuring GDPR compliance.
