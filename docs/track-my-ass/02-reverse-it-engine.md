---
title: "The 'Reverse It' Autonomous Rollback Engine"
slug: "02-reverse-it-engine"
product: "track-my-ass"
topic: "Disaster Recovery"
order: 2
badge: "1-Click Undo"
version: "v1.0.0"
keywords: ["rollback", "revert", "undo", "snapshots", "recovery"]
---

# The "Reverse It" Autonomous Rollback Engine

When an AI agent modifies post content incorrectly, toggles a breaking plugin, or changes a vital site option, **Track My A$$** enables immediate 1-click restoration to the pre-change state without requiring a full database restore.

---

## 1. How Pre-Change Snapshots Work

Prior to applying any state-changing WordPress hook (`pre_post_update`, `updated_option`, `activated_plugin`), the **TMA Audit Engine** saves a pre-change snapshot in `wpcma_tma_audit_log`:

```
[Incoming Change Event]
        │
        ▼
[Capture Pre-State Snapshot] ──> [Apply Change] ──> [Record Diff & Mark Reversible]
```

---

## 2. Reversion Handlers

- **Post / Page Content**: Restores exact prior `post_title`, `post_content`, `post_excerpt`, and `post_status`.
- **Options**: Restores previous scalar or serialized option value.
- **Plugins**: Reverses activation or deactivation status immediately.
- **User Roles**: Reverts unauthorized role elevations (e.g. demotes an elevated account back to subscriber).

---

## 3. Pre-Rollback Safety Snapshot

When reversing high-impact events, Track My A$$ coordinates with **Back My A$$ Up** (`back-my-ass-up`) to trigger a micro-snapshot before executing the state revert.
