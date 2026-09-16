---
title: "Real-Time AI & MCP Audit Intelligence"
slug: "01-getting-started"
product: "track-my-ass"
topic: "Getting Started"
order: 1
badge: "Real-Time"
version: "v1.0.0"
keywords: ["audit", "logging", "ai agent", "mcp", "telemetry", "sse"]
---

# Real-Time AI & MCP Audit Intelligence

**Track My A$$** provides continuous, real-time audit logging and disaster recovery for WordPress sites managed by autonomous AI agents, remote MCP bridges, command-line scripts, and human administrators.

> [!NOTE]
> When multiple AI agents and developers make live modifications to a site, Track My A$$ records **who did the work**, **what exact changes were applied**, and **what time it occurred**—with an instant **"Reverse It"** rollback button.

---

## 1. Real-Time Server-Sent Events (SSE)

Unlike traditional activity log plugins that require page reloads or aggressive polling, Track My A$$ streams change events over a persistent, non-blocking **Server-Sent Events (SSE)** connection:

- **Endpoint**: `/wp-json/track-my-ass/v1/live-stream`
- **Latency**: $<100\text{ms}$ delivery from hook interception to browser display.
- **Heartbeat & Resiliency**: Emits periodic ping heartbeats and seamlessly falls back to REST polling if SSE is blocked by intermediate network proxies.

---

## 2. Supported Event Categories

Track My A$$ automatically intercepts and logs:

| Category | Actions Tracked | Rollback Capable |
| :--- | :--- | :--- |
| **Posts & Pages** | Updates, insertions, moves to trash, restores, deletions | ✅ Yes |
| **Options & Settings** | Core & third-party option updates, creations | ✅ Yes |
| **Plugins** | Activations, deactivations | ✅ Yes |
| **Themes** | Theme switches | ✅ Yes |
| **Users & Roles** | Role elevations, profile updates, registrations | ✅ Yes |
| **Media** | Asset uploads, deletions | ✅ Partial |
| **Authentication** | Logins, failed attempts | ℹ️ Audit Only |
