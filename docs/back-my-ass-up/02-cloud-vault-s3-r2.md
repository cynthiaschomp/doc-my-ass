---
title: "Offsite Cloud Vault (Cloudflare R2 & AWS S3)"
slug: "02-cloud-vault-s3-r2"
product: "back-my-ass-up"
topic: "Configuration"
order: 2
badge: "Zero-Composer SigV4"
version: "v1.2"
keywords: ["s3", "cloudflare r2", "cloud vault", "sigv4"]
---

# Offsite Cloud Vault (Cloudflare R2 & AWS S3)

Back My A$$ Up includes a pure-PHP, zero-dependency **AWS Signature Version 4 (SigV4)** connector. It communicates directly with Amazon S3 and Cloudflare R2 without bloated vendor Composer libraries.

---

## 1. Supported Cloud Storage Providers

* **Cloudflare R2**: Zero egress fees, high performance, S3-compatible API.
* **Amazon S3**: Multi-region storage classes including `STANDARD`, `INTELLIGENT_TIERING`, and `GLACIER`.
* **MinIO / Custom S3**: Self-hosted private S3 endpoints.

---

## 2. Configuration Parameters

Configure your cloud credentials via WP Admin or `wp-config.php` constants for enhanced security:

```php [wp-config.php]
// Cloudflare R2 Primary Vault
define('BMA_S3_ENDPOINT', 'https://<ACCOUNT_ID>.r2.cloudflarestorage.com');
define('BMA_S3_BUCKET', 'securemyass-vault-prod');
define('BMA_S3_REGION', 'auto');
define('BMA_S3_KEY', '7b4e28...masked');
define('BMA_S3_SECRET', 'd82c19...masked');
```

> [!TIP]
> Storing credentials in `wp-config.php` activates strict anti-shoulder-surfing OPSEC protection: keys cannot be viewed or modified by standard WordPress administrators.
