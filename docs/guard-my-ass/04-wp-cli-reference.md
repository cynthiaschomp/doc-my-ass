---
title: "Guard My A$$ — WP-CLI Reference"
slug: "04-wp-cli-reference"
product: "guard-my-ass"
topic: "CLI Reference"
order: 4
badge: "Terminal"
version: "v1.3"
keywords: ["cli", "wp-cli", "gma", "commands"]
---

# Guard My A$$ — WP-CLI Reference

Manage firewall posture, test payload entropy, manage IP allowlists and bans, and inspect active rule sets directly from the terminal.

---

## 1. Status & Health Check

Inspect the active pre-boot firewall state, attack counters, and telemetry metrics.

```bash [terminal]
wp gma status --path=/var/www/html --allow-root
```

---

## 2. Test Payload Heuristics & Shannon Entropy

Calculate the exact Shannon entropy of any test payload, SQL snippet, or obfuscated query string:

```bash [terminal]
# Test harmless string
wp gma test-payload "search=wordpress+security+plugins" --path=/var/www/html --allow-root

# Test obfuscated shell payload
wp gma test-payload "eval(base64_decode('c3lzdGVtKCdpZCcpOw=='))" --path=/var/www/html --allow-root
```

---

## 3. IP Management: Whitelist, Ban & Unban

> [!TIP]
> **Whitelisting Your Own IP**: Replace `<YOUR_IP>` with your personal workstation or office public IP address. To quickly determine your public IP, run:
> ```bash
> curl -s https://ifconfig.me
> ```
> Adding your own IP to the allowlist ensures you will never be locked out or rate-limited by administrative actions.

> [!CAUTION]
> **Banning Offending IPs**: Replace `<ATTACKER_IP>` with the malicious IP address or CIDR subnet identified in your access logs. **Do not use your own IP address**, or you will block yourself from accessing your website!

:::tabs
@tab Whitelist Your IP
```bash [terminal]
# Replace <YOUR_IP> with your own public IP address (e.g. from curl -s ifconfig.me)
wp gma whitelist add <YOUR_IP> --path=/var/www/html --allow-root
```
@tab List Whitelisted
```bash [terminal]
wp gma whitelist list --path=/var/www/html --allow-root
```
@tab Remove From Whitelist
```bash [terminal]
wp gma whitelist remove <YOUR_IP> --path=/var/www/html --allow-root
```
@tab Ban Attacker IP
```bash [terminal]
# Replace <ATTACKER_IP> with the threat actor's IP from your logs
wp gma ban add <ATTACKER_IP> --reason="Automated SQLi probing" --path=/var/www/html --allow-root
```
@tab Unban IP
```bash [terminal]
wp gma ban remove <ATTACKER_IP> --path=/var/www/html --allow-root
```
@tab List Banned
```bash [terminal]
wp gma ban list --path=/var/www/html --allow-root
```
:::

---

## 4. Rule Catalog Inspection

Inspect all active pre-boot heuristic and mathematical rules along with their CWE mappings:

```bash [terminal]
wp gma rules --path=/var/www/html --allow-root
```

