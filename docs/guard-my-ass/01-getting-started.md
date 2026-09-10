---
title: "Quick Start & Pre-Boot Architecture"
slug: "01-getting-started"
product: "guard-my-ass"
topic: "Getting Started"
order: 1
badge: "Core WAF"
version: "v1.3"
keywords: ["waf", "pre-boot", "entropy", "firewall", "shannon"]
---

# Quick Start & Pre-Boot Architecture

**Guard My Ass** is a high-performance Web Application Firewall (WAF) engineered specifically for mission-critical WordPress sites. Unlike conventional security plugins that boot WordPress core before evaluating requests, Guard My Ass intercepts malicious ingress at the earliest PHP pre-boot stage.

> [!NOTE]
> Pre-boot filtering executes in **under 0.4 milliseconds** before database connections or WordPress core files are loaded into memory, eliminating CPU starvation during volumetric bot floods.

---

## 1. Installation & Pre-Boot Activation

Guard My Ass operates via PHP's native `auto_prepend_file` directive configured in `.user.ini` or `.htaccess`.

:::tabs
@tab WP-CLI
```bash [terminal]
# Activate the plugin
wp plugin activate guard-my-ass --path=/var/www/html --allow-root

# Verify pre-boot firewall status
wp gma status --path=/var/www/html --allow-root
```
@tab PHP Manual
```php [wp-config.php]
// Optional: Force emergency WAF bypass in development environments
define('GMA_DEV_BYPASS', true);
```
@tab Direct Shell
```bash [.user.ini]
; Automatically prepends Guard My Ass firewall before WordPress core
auto_prepend_file = '/var/www/html/wp-content/plugins/guard-my-ass/firewall.php'
```
:::

---

## 2. Core Detection Capabilities

Guard My Ass protects against modern automated exploitation techniques:

* **Shannon Entropy Analysis**: Detects obfuscated payloads, base64 webshells, and hexadecimal character chains dynamically.
* **Token Anomaly Ratios**: Flags suspicious function combinations (`passthru`, `proc_open`, `shell_exec`, `preg_replace /e`).
* **Virtual Honeypot Traps**: Traps automated vulnerability scanners targeting phantom endpoints (`/wp-config.bak`, `/phpmyadmin`, `/.env`).
* **Traefik Edge Reverse-Proxy Sync**: Automatically synchronizes hostile `/24` subnets to Traefik dynamic router rules.

> [!TIP]
> Combine Guard My Ass with **Speed My A$$ Up** for layered defense and instant static page delivery.
