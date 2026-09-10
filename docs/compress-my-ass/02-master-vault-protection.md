---
title: "Non-Destructive Master Media Vault"
slug: "02-master-vault-protection"
product: "compress-my-ass"
topic: "Architecture"
order: 2
badge: "Vault Security"
version: "v1.0.0"
keywords: ["master vault", "non-destructive", "isolation", "original backup"]
---

# Non-Destructive Master Media Vault

Lossy compression should never be destructive. Compress My A$$ protects your original camera-raw photographs and graphic master files inside an isolated, non-destructive vault located at:

```
/wp-content/uploads/cma-vault/
```

---

## Triple Execution Barrier

To prevent malicious file execution if a non-image file is smuggled through an upload form, the master vault is fortified with triple-layer execution guards:

1. **`.htaccess` Directives**: Forces MIME types to static binary stream and prevents script execution.
2. **`.user.ini` PHP Lockdown**: Disables PHP execution in the vault directory.
3. **`index.php` Silent Drop**: Drops direct directory indexing attempts.

> [!TIP]
> You can restore original uncompressed images or regenerate WebP/AVIF variations with new quality parameters at any time from the Media Library.
