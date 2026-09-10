---
title: "Traefik Edge Reverse-Proxy Sync"
slug: "03-traefik-edge-sync"
product: "guard-my-ass"
topic: "Architecture"
order: 3
badge: "Edge Firewall"
version: "v1.3"
keywords: ["traefik", "reverse-proxy", "auto-ban", "ipAllowList"]
---

# Traefik Edge Reverse-Proxy Sync

For maximum efficiency, repeated attack traffic should never touch the PHP application server. Guard My Ass interfaces directly with **Traefik v3** dynamic edge routing to drop malicious packets at the network layer.

> [!IMPORTANT]
> Edge-level blocking prevents PHP thread exhaustion and keeps CPU utilization under 5% even during sustained DDoS brute-force campaigns.

---

## Architecture Flow

```
[Hostile Ingress] ──> [Traefik Edge Router] ──(403 Drop)──X (No PHP Boot)
                                ▲
                                │ Sync daemon (sync_traefik_swarm.py)
                                │
[Guard My Ass WAF] ──> [Swarm Telemetry Table]
```

1. **Detection**: Guard My Ass detects anomalous payload or brute-force behavior.
2. **Swarm Ingestion**: Malicious `/24` subnet recorded in `wpcma_sma_swarm_telemetry`.
3. **Daemon Hot-Reload**: `sync_traefik_swarm.py` generates dynamic configuration `/root/traefik-dynamic/swarm-block.yml`.
4. **Edge Drop**: Traefik applies high-priority routing rule (`priority: 50000`) to return immediate HTTP 403.

---

## Dynamic Configuration Example

```yaml [/root/traefik-dynamic/swarm-block.yml]
http:
  routers:
    securemyass-swarm-block:
      rule: "Host(`securemyass.com`)"
      priority: 50000
      service: "noop@internal"
      middlewares:
        - "swarm-ip-block@file"
  middlewares:
    swarm-ip-block:
      ipAllowList:
        sourceRange:
          - "0.0.0.0/0"
          - "!198.51.100.0/24"
          - "!203.0.113.0/24"
```
