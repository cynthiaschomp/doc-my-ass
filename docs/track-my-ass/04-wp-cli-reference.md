---
title: "WP-CLI Command Reference"
slug: "04-wp-cli-reference"
product: "track-my-ass"
topic: "CLI Reference"
order: 4
badge: "WP-CLI"
version: "v1.0.0"
keywords: ["wp-cli", "tma", "cli", "terminal", "commands"]
---

# WP-CLI Command Reference

Track My A$$ provides a complete command suite under the `wp tma` namespace.

---

## 1. `wp tma list`

List recent audit events with optional actor and category filters:

```bash
# List last 20 events
wp tma list

# Filter by AI & MCP actions
wp tma list --actor=ai_mcp --limit=50

# Output as JSON
wp tma list --format=json
```

---

## 2. `wp tma inspect <id>`

Inspect a full event dossier, including exact before-and-after diffs and context metadata:

```bash
wp tma inspect 42
```

---

## 3. `wp tma revert <id>`

Execute a 1-click "Reverse It" rollback directly from the command line:

```bash
wp tma revert 42 --yes
```

---

## 4. `wp tma purge`

Clean historical logs older than a specified retention period:

```bash
wp tma purge --days=90 --yes
```
