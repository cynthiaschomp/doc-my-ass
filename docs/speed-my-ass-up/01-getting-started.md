---
title: "Pre-Boot Static HTML Caching (advanced-cache.php)"
slug: "01-getting-started"
product: "speed-my-ass-up"
topic: "Getting Started"
order: 1
badge: "Sub-0.5ms TTFB"
version: "v1.0.0"
keywords: ["cache", "ttfb", "advanced-cache", "static html", "gzip"]
---

# Pre-Boot Static HTML Caching (`advanced-cache.php`)

**Speed My A$$ Up** is an uncompromising velocity and Core Web Vitals optimization engine. By executing during the absolute earliest PHP pre-boot stage via WordPress's native `WP_CACHE` drop-in (`advanced-cache.php`), it serves cached static HTML and compressed GZIP payloads in **sub-0.5 milliseconds**.

> [!NOTE]
> `advanced-cache.php` executes before MySQL connections, theme templates, or plugin files are loaded into memory, reducing server load by up to 98%.

---

## 1. Installation & Activation

```bash [terminal]
wp plugin activate speed-my-ass-up --path=/var/www/html --allow-root
```

Activation automatically:
1. Enables `WP_CACHE` in `wp-config.php`.
2. Installs the zero-overhead drop-in `/wp-content/advanced-cache.php`.
3. Initializes the static cache directory `/wp-content/cache/smau-cache/`.

---

## 2. Benchmark TTFB

Validate your time-to-first-byte performance instantly:

```bash [terminal]
wp smau benchmark https://securemyass.com --allow-root
```
