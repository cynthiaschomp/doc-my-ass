---
title: "Guard My Ass — WP-CLI Reference"
slug: "04-wp-cli-reference"
product: "guard-my-ass"
topic: "CLI Reference"
order: 4
badge: "Terminal"
version: "v1.3"
keywords: ["cli", "wp-cli", "gma", "commands"]
---

# Guard My Ass — WP-CLI Reference

Manage firewall status, test entropy strings, unban IP addresses, and inspect edge sync rules directly from the terminal.

---

## 1. Status & Health Check

Inspect the active pre-boot firewall state and recent block metrics.

```bash [terminal]
wp gma status --path=/var/www/html --allow-root
```

---

## 2. Test Shannon Entropy

Calculate the exact Shannon entropy of any test payload or query parameter string:

```bash [terminal]
# Test harmless string
wp gma test-entropy "search=wordpress+security+plugins" --path=/var/www/html --allow-root
# Result: Entropy: 3.42 [ALLOW]

# Test obfuscated shell payload
wp gma test-entropy "eval(base64_decode('c3lzdGVtKCdpZCcpOw=='))" --path=/var/www/html --allow-root
# Result: Entropy: 5.78 [BLOCK - CRITICAL ANOMALY]
```

---

## 3. Manual IP Ban & Unban

Manually blacklist or restore access for specific IP addresses or CIDR blocks:

:::tabs
@tab Ban IP
```bash [terminal]
wp gma ban 198.51.100.42 --reason="Automated SQLi probing" --path=/var/www/html --allow-root
```
@tab Unban IP
```bash [terminal]
wp gma unban 198.51.100.42 --path=/var/www/html --allow-root
```
@tab List Banned
```bash [terminal]
wp gma list-banned --limit=20 --path=/var/www/html --allow-root
```
:::
