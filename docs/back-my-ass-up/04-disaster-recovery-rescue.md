---
title: "Disaster Recovery with bma-rescue.php"
slug: "04-disaster-recovery-rescue"
product: "back-my-ass-up"
topic: "Troubleshooting"
order: 4
badge: "Zero-Dependency Recovery"
version: "v1.2"
keywords: ["rescue", "disaster recovery", "standalone", "emergency"]
---

# Disaster Recovery with `bma-rescue.php`

When a site suffers complete database corruption, kernel panic, or web shell ransomware lockout, the WordPress admin interface is unavailable. Back My A$$ Up includes an emergency recovery agent: **`bma-rescue.php`**.

> [!IMPORTANT]
> `bma-rescue.php` is a single standalone PHP script that requires zero WordPress core files, zero plugins, and zero external packages to run.

---

## 1. Emergency Restoration Workflow

1. Place `bma-rescue.php` into your web root (e.g. `/var/www/html/bma-rescue.php`).
2. Navigate in your browser to `https://yourdomain.com/bma-rescue.php`.
3. Authenticate with your **Emergency Master Keycard** token.
4. Select the target snapshot from your local `/wp-content/uploads/sma-vault/` directory or stream directly from Cloudflare R2 / AWS S3.
5. Click **Initiate Restoral**:
   * Drops corrupt tables.
   * Decrypts chunks with AES-256-CBC.
   * Streams SQL transactions in batches to avoid PHP timeouts.
6. **Automatic Self-Destruct**: Upon successful restoration, `bma-rescue.php` securely deletes itself from disk to prevent unauthorized re-use.
