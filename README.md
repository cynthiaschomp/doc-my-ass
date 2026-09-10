# Doc My A$$ (`doc-my-ass`)

**Doc My A$$** is the developer-first documentation system and WordPress plugin for the **Whole A$$ Network** (`securemyass.com`).

Built to meet the uncompromising standards of modern web developers (inspired by Stripe, Tailwind CSS, Laravel, and Astro), it features:

* **Sub-Millisecond Reader**: Completely independent of heavy page builder overhead, fully compatible with Speed My A$$ Up's pre-boot static HTML cache.
* **Instant Command+K Fuzzy Search**: Zero server lag, client-side indexing with arrow-key navigation and heading deep links.
* **"Docs-as-Code" Workflow**: Ingests pure Markdown (`.md`) files with YAML frontmatter via WP-CLI (`wp dma sync`) or git hooks.
* **Multi-Tab Code Fences**: Single-click environment switching across `WP-CLI`, `PHP`, `Bash`, `Config`.
* **Dynamic Scroll-Spy**: Real-time "On This Page" table of contents tracking reader position.
* **GitHub-Style Alert Callouts**: Styled `NOTE`, `TIP`, `IMPORTANT`, `WARNING`, and `CAUTION` banners.
* **Anti-Shoulder-Surfing OPSEC Standard**: Integrated secret masking and high-contrast WCAG AAA dark slate design.

---

## Suite Products Documented

1. **Guard My Ass** (`guard-my-ass`): Real-time WAF, pre-boot Shannon entropy analysis, Traefik edge sync.
2. **Back My A$$ Up** (`back-my-ass-up`): Air-gapped backups, PBKDF2 salt derivation, S3/R2 cloud vault, `bma-rescue.php`.
3. **Secure My Ass** (`secure-my-ass`): AST web shell scanner, autonomous quarantine, login cloaking, Gemini AI SOC Analyst.
4. **Speed My A$$ Up** (`speed-my-ass-up`): Sub-0.5ms pre-boot HTML caching, W3C speculation rules, BYOK AI CSS scanner.
5. **Compress My A$$** (`compress-my-ass`): Zero-SaaS WebP/AVIF local compilation, master vault, `<picture>` rewriting.
6. **Whole A$$ API & Network** (`whole-ass-network`): Swarm threat intelligence telemetry, licensing lifecycle, auto-updates.

---

## WP-CLI Commands

```bash
# Synchronize markdown documentation from disk into WordPress
wp dma sync --allow-root

# Rebuild client-side Cmd+K search index
wp dma build-index --allow-root

# List all indexed documentation articles
wp dma list --allow-root
```
