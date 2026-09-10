---
title: "Access Cloaking & Credential Hardening"
slug: "03-access-cloaking"
product: "secure-my-ass"
topic: "Configuration"
order: 3
badge: "Cloaking"
version: "v1.3.3"
keywords: ["login cloak", "pwned passwords", "salt rotation", "2fa"]
---

# Access Cloaking & Credential Hardening

Exposing standard WordPress login endpoints invites automated brute-force bots and credential-stuffing credential attacks.

---

## 1. Custom Secret Login Slug

Secure My Ass rewrites authentication endpoints, rendering `/wp-login.php` and `/wp-admin` inaccessible to unauthorized visitors:

```http [HTTP Response on /wp-login.php]
HTTP/1.1 404 Not Found
Content-Type: text/html
```

Legitimate administrators authenticate through an unguessable slug (e.g. `https://yourdomain.com/ass-gate-884/`).

---

## 2. 8-Key Non-Destructive Salt Cycles

Rotating WordPress security keys (`AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, etc.) terminates all compromised admin sessions instantly.

```bash [terminal]
wp sma rotate-salts --allow-root
```

> [!NOTE]
> Secure My Ass automatically takes a pre-flight backup of `wp-config.php` before rotating keys, guaranteeing zero risk of site corruption.
