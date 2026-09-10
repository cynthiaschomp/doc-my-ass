---
title: "Swarm Threat Intelligence Telemetry Ingestion"
slug: "02-swarm-telemetry"
product: "sentinel-api"
topic: "Threat Intelligence"
order: 2
badge: "Swarm Defense"
version: "v1.0.0"
keywords: ["swarm", "telemetry", "opsec", "cidr", "threat feed"]
---

# Swarm Threat Intelligence Telemetry Ingestion

The Security Sentinel Suite connects individual WordPress instances into a decentralized defense swarm. When an attacker probes an endpoint on one site, their subnet is neutralized across all protected nodes.

---

## Zero-PII Swarm Telemetry Policy

In strict accordance with our OPSEC standards, swarm telemetry contains **zero personal identifiable information (PII)**:

```json [Sample Ingested Swarm Payload]
{
  "subnet": "198.51.100.0/24",
  "threat_type": "sql_injection",
  "shannon_entropy": 5.82,
  "payload_hash": "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855",
  "cwe_id": 89,
  "timestamp": 1725912000
}
```

* **No Domain Names**: Hostnames are never broadcast.
* **No User Data**: Passwords, cookies, user agents, and form inputs are stripped before transmission.
* **Subnet Anonymization**: IP addresses are masked to `/24` class blocks.
