---
title: "Multi-Region Redundant Cloud Vault Replication"
slug: "03-multi-region-replication"
product: "back-my-ass-up"
topic: "Architecture"
order: 3
badge: "Disaster Recovery"
version: "v1.2"
keywords: ["replication", "glacier", "redundancy", "multi-region"]
---

# Multi-Region Redundant Cloud Vault Replication

To survive catastrophic datacenter failures or provider outages, Back My A$$ Up supports automated dual-target replication.

```
[WordPress Snapshot] ──> [AES-256 Encryption]
                                │
        ┌───────────────────────┴───────────────────────┐
        ▼                                               ▼
[Primary Cloud Vault]                         [Secondary Vault Mirror]
Cloudflare R2 (Fast Ingress)                  AWS S3 Glacier (Cold Vault)
```

---

## Storage Class Tiering

When pushing backups to the secondary mirror, Back My A$$ Up can instruct AWS S3 to transition objects immediately into cold storage:

```http [HTTP Request Header]
x-amz-storage-class: GLACIER
```

This reduces archival storage costs by up to 90% while guaranteeing long-term immutable compliance.
