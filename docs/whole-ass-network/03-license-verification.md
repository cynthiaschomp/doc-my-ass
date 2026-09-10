---
title: "Licensing Lifecycle & Auto-Update Distribution"
slug: "03-license-verification"
product: "whole-ass-network"
topic: "Licensing"
order: 3
badge: "Commercial"
version: "v1.0.0"
keywords: ["licensing", "updates", "stripe", "releases"]
---

# Licensing Lifecycle & Auto-Update Distribution

Secure My Ass manages commercial licensing and automated updates without relying on third-party SaaS licensing middle-ware.

---

## 1. Stripe Checkout Automation

When a customer completes a purchase on `securemyass.com`, Stripe fires a `checkout.session.completed` webhook. The server:

1. Validates the Stripe webhook cryptographic signature.
2. Derives an unguessable license key (e.g. `SMA-PRO-8894-B7A$$-LIVE`).
3. Sends a welcome email containing the license token and installation instructions.
4. Activates the client's automated release update stream.

---

## 2. Native WordPress Update Integration

Plugins in the Whole A$$ Network hook into WordPress's native `site_transient_update_plugins` filter:

```php [class-license-client.php]
// Queries SecureMyAss.com release server
$remote_release = wp_remote_post('https://securemyass.com/wp-json/sma-license/v1/update-check', [
    'body' => [
        'license_key' => $license_key,
        'plugin'      => 'secure-my-ass',
        'version'     => SMA_VERSION
    ]
]);
```

Updates appear directly in the WordPress Admin dashboard with standard 1-click update support.
