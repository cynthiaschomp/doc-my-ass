=== Doc My A$$ ===
Contributors: cynthiaschomp
Donate link: https://securemyass.com/
Tags: documentation, docs, markdown, developer, cmd-k
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Developer-first documentation system for WordPress. Sub-millisecond reader, Cmd+K instant fuzzy search, and Docs-as-Code Markdown synchronization.

== Description ==

**Doc My A$$** is an uncompromising, high-velocity developer documentation system designed specifically for web developers, engineering teams, and technical tool builders who hate clunky, slow knowledge bases.

Inspired by the developer experience of Stripe, Tailwind CSS, Laravel, and Astro, Doc My A$$ delivers a standalone 3-column reader that bypasses heavy page builder bloat and works seamlessly with static pre-boot caching.

### Key Features

* **Sub-Millisecond Reader**: Completely independent of heavy page builder stylesheets and overhead. Delivers pristine typography, deep anchor links, and zero-shift layout.
* **Instant Cmd+K Search**: Global client-side fuzzy search (`Cmd + K`, `Ctrl + K`, or `/`) indexing titles, products, topics, excerpts, and deep section headings with full keyboard navigation.
* **Docs-as-Code Pipeline**: Write documentation in pure Markdown (`.md`) with YAML frontmatter. Synchronize instantly from disk via WP-CLI (`wp dma sync`).
* **Multi-Tab Code Fences**: Single-click environment switching across WP-CLI, PHP, Bash, and configuration snippets.
* **1-Click Clipboard Copy**: One-click copying with animated checkmark feedback on every code block.
* **Dynamic Scroll-Spy**: Real-time "On This Page" table of contents tracking reader position as they scroll.
* **GitHub-Style Alert Callouts**: Styled `NOTE`, `TIP`, `IMPORTANT`, `WARNING`, and `CAUTION` banners.
* **Multi-Product Architecture**: Seamlessly organize documentation across multiple products, libraries, or service tiers.

== Installation ==

1. Upload the `doc-my-ass` folder to the `/wp-content/plugins/` directory, or install the ZIP file directly via the WordPress Plugins screen.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Access your documentation hub at `/docs/`.
4. Run `wp dma sync --allow-root` to sync any markdown docs placed in the plugin's `docs/` folder.

== Frequently Asked Questions ==

= Does Doc My A$$ require a specific theme? =
No. Doc My A$$ routes `/docs/` independently of your active theme to ensure optimal developer experience, lightning speed, and zero layout shift.

= Can I use it with static caching plugins? =
Yes! It is fully compatible with pre-boot page caching drop-ins (like Speed My A$$ Up's `advanced-cache.php`).

= How do I synchronize documentation? =
Run `wp dma sync --allow-root` or call `DMA_CLI_Command::sync()` within your CI/CD deployment pipeline.

== Changelog ==

= 1.0.0 =
* Initial public release.
* Standalone 3-column reader layout.
* Client-side Cmd+K fuzzy search modal.
* Docs-as-Code Markdown engine with YAML frontmatter parser.
* Multi-tab code snippets and 1-click clipboard copy.
* Dynamic scroll-spy table of contents.
