---
title: "Publishing Free Plugins to WordPress.org"
slug: "04-wordpress-org-repository-publishing"
product: "whole-ass-network"
topic: "Deployment & Distribution"
order: 4
badge: "WP.org SVN"
version: "v1.0.0"
keywords: ["wordpress.org", "svn", "plugin repository", "publishing", "github actions"]
---

# Publishing Free Plugins to WordPress.org

This guide details the exact procedure for publishing free plugins from the **Whole A$$ Network** (such as **Doc My A$$**) into the official **WordPress.org Plugin Directory**.

---

## 1. Pre-Submission Compliance Checklist

Before submitting a plugin, WordPress.org review engineers conduct manual and automated code audits:

1. **GPL Compatibility**: Entire codebase, libraries, and assets must be licensed under **GPLv2 or later**.
2. **No Obfuscation**: Code cannot be minified without source, encoded in base64, or obfuscated.
3. **No Phoning Home Without Consent**: Telemetry or external calls must require explicit opt-in.
4. **Sanitization & Nonces**: All database queries must use `$wpdb->prepare()`, inputs sanitized (`sanitize_text_field`), outputs escaped (`esc_html`, `esc_attr`).
5. **WordPress.org `readme.txt`**: Must parse cleanly through the official [WordPress.org Readme Validator](https://wordpress.org/plugins/developers/readme-validator/).

---

## 2. Submitting the Plugin for Review

1. Create a clean ZIP archive of your plugin (e.g. `doc-my-ass.zip` using `./deploy_plugin.py --package-dma 1.0.0`).
2. Log into your [WordPress.org Account](https://login.wordpress.org/).
3. Navigate to the **Add Your Plugin** page:
   [https://wordpress.org/plugins/developers/add/](https://wordpress.org/plugins/developers/add/)
4. Upload `doc-my-ass.zip`.
5. The WordPress Plugin Review Team audits the code (typically 1–7 business days). You will receive an approval email with your assigned SVN repository URL:
   ```
   https://plugins.svn.wordpress.org/doc-my-ass/
   ```

---

## 3. SVN Repository Architecture

WordPress.org uses Apache Subversion (SVN) rather than Git:

```
doc-my-ass/
├── assets/       # Banners, icons, and screenshots displayed in directory UI
├── trunk/        # Current development/latest active code
└── tags/         # Immutable release snapshots
    ├── 1.0.0/
    └── 1.0.1/
```

---

## 4. First-Time SVN Deployment Workflow

:::tabs
@tab 1. Checkout SVN Repo
```bash [terminal]
# Checkout the empty SVN directory
svn checkout https://plugins.svn.wordpress.org/doc-my-ass/ ~/dma-svn
cd ~/dma-svn
```
@tab 2. Populate Trunk
```bash [terminal]
# Copy plugin files into trunk (excluding .git)
rsync -av --exclude='.git' /home/cynthia/Work/doc-my-ass/ trunk/

# Check SVN status
svn status
```
@tab 3. Commit Assets & Code
```bash [terminal]
# Add untracked files
svn add trunk/* --force
svn add assets/* --force

# Commit trunk to WordPress.org
svn commit -m "Initial commit for Doc My A$$ v1.0.0" --username cynthiaschomp
```
@tab 4. Tag Release
```bash [terminal]
# Create immutable release tag matching Stable tag in readme.txt
svn copy trunk/ tags/1.0.0/
svn commit -m "Tagging release v1.0.0" --username cynthiaschomp
```
:::

---

## 5. Assets Directory (Banners & Icons)

Place your graphic assets inside the top-level `assets/` folder (NOT inside `trunk/`):

* `icon-128x128.png` & `icon-256x256.png` — Directory icon.
* `banner-772x250.png` & `banner-1544x500.png` — Directory hero header banner.
* `screenshot-1.png`, `screenshot-2.png` — Referenced in `readme.txt` under `== Screenshots ==`.

---

## 6. Automated GitHub Actions CI/CD Pipeline

To eliminate manual SVN commands for future updates, configure automated deployment via GitHub Actions:

```yaml [.github/workflows/deploy-wporg.yml]
name: Deploy to WordPress.org
on:
  push:
    tags:
      - '*'

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout Source Code
        uses: actions/checkout@v4

      - name: Deploy to WordPress.org Plugin Repository
        uses: 10up/action-wordpress-plugin-deploy@stable
        env:
          SVN_USERNAME: ${{ secrets.WPORG_SVN_USERNAME }}
          SVN_PASSWORD: ${{ secrets.WPORG_SVN_PASSWORD }}
          SLUG: doc-my-ass
```
