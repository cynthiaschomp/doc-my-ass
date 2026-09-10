---
title: "Endpoint Defense & Dual Operational Modes"
slug: "01-getting-started"
product: "secure-my-ass"
topic: "Getting Started"
order: 1
badge: "Core Endpoint"
version: "v1.3.3"
keywords: ["endpoint", "hardening", "dual mode", "welcome wizard"]
---

# Endpoint Defense & Dual Operational Modes

**Secure My Ass** is the flagship endpoint security platform for WordPress. Engineered for both site owners needing zero-friction automation and enterprise SOC analysts requiring deep technical forensic telemetry, it features two distinct operational postures:

* **Easy Mode**: 1-click toggles, automated background health checks, and friendly status indicators.
* **Expert Mode**: Raw AST heuristics, Shannon entropy histograms, forensic threat response dossiers, and granular access cloaking.

---

## 1. Quick Installation & Activation

```bash [terminal]
wp plugin activate secure-my-ass --path=/var/www/html --allow-root
```

---

## 2. Core Security Pillars

1. **Autonomous Web Shell Quarantine Engine**: Real-time filesystem scanner neutralizing zero-day PHP backdoors behind triple execution barriers.
2. **Access Cloaking & Credential Defense**: Obfuscates `/wp-admin` and `/wp-login.php`, integrates HaveIBeenPwned API breach checks, and rotates WordPress salts.
3. **Cloud AI SOC Analyst**: Incident briefings powered by Google Gemini 2.5 Flash and deterministic heuristic fallback engines.
4. **Anti-Shoulder-Surfing OPSEC Standard**: Sensitive technical details (database prefixes, `.user.ini` paths, rescue tokens) are masked by default behind interactive eye toggles.
