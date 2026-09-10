---
title: "Platform Overview & Central API Architecture"
slug: "01-getting-started"
product: "sentinel-api"
topic: "Getting Started"
order: 1
badge: "Platform API"
version: "v1.0.0"
keywords: ["api", "rest api", "endpoints", "sentinel platform"]
---

# Platform Overview & Central API Architecture

The **Secure My Ass** platform operates a centralized licensing, release distribution, and swarm threat intelligence server hosted directly on `securemyass.com`.

---

## 1. Primary REST Endpoints

All platform endpoints are served over HTTPS under the `/wp-json/sma-license/v1/` namespace:

| Endpoint | Method | Purpose | Authentication |
| :--- | :--- | :--- | :--- |
| `/verify` | `POST` | Validates client license keys & capabilities | HMAC-SHA256 Signature |
| `/update-check` | `GET/POST` | Queries latest plugin version & download packages | License Key |
| `/swarm/telemetry` | `POST` | Ingests anonymized `/24` subnet threat telemetry | Client License Token |
| `/swarm/feed` | `GET` | Distributes aggregated malicious CIDR lists | Public / Authenticated |
| `/stripe/webhook` | `POST` | Provisions licenses on Stripe checkout events | Stripe Webhook Secret |

---

## 2. Cryptographic Token Verification

All client-server handshakes are signed with an HMAC-SHA256 signature using the site's unique license key secret to prevent replay attacks and tampering.
