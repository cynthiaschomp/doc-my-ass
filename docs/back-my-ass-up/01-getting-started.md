---
title: "Air-Gapped Vault & Encryption Overview"
slug: "01-getting-started"
product: "back-my-ass-up"
topic: "Getting Started"
order: 1
badge: "AES-256-CBC"
version: "v1.2"
keywords: ["backup", "vault", "encryption", "pbkdf2", "keycard"]
---

# Air-Gapped Vault & Encryption Overview

**Back My A$$ Up** creates cryptographically secured, air-gapped snapshots of your WordPress database and uploads directory. Unlike typical backup plugins that generate plaintext `.sql` or `.zip` files vulnerable to web shell discovery, every Back My A$$ Up snapshot is encrypted on-the-fly with **AES-256-CBC** and protected by PBKDF2 key derivation.

> [!IMPORTANT]
> Plaintext database dumps and zip archives are the #1 target of compromised admin accounts and web shell attackers. Back My A$$ Up guarantees zero plaintext archives on disk.

---

## 1. Quick Backup Execution

:::tabs
@tab WP-CLI
```bash [terminal]
# Trigger immediate pre-flight encrypted backup
wp bma backup --allow-root

# List all local and cloud vault snapshots
wp bma list --allow-root
```
@tab PHP API
```php [functions.php]
// Programmatically trigger a backup snapshot
$snapshot_id = BMA_Vault_Engine::create_snapshot([
    'type' => 'full',
    'destination' => 'cloud_mirror'
]);
```
:::

---

## 2. Keycard & Salt Derivation

During first initialization, Back My A$$ Up derives an unguessable 256-bit Master Keycard derived from unique site salts and a PBKDF2 iteration loop:

```
Master Keycard = PBKDF2(HMAC-SHA256, AUTH_KEY, SECURE_AUTH_SALT, 10000 iterations, 32 bytes)
```

> [!CAUTION]
> Download your **Emergency Master Keycard** immediately from the dashboard or via `wp bma keycard`. If your database container is wiped, this keycard is required by `bma-rescue.php` to restore your data.
