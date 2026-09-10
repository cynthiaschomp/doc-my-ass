---
title: "Back My A$$ Up — WP-CLI Reference"
slug: "05-wp-cli-reference"
product: "back-my-ass-up"
topic: "CLI Reference"
order: 5
badge: "Terminal"
version: "v1.2"
keywords: ["cli", "bma", "backup commands", "restore"]
---

# Back My A$$ Up — WP-CLI Reference

Complete terminal command reference for air-gapped backup creation, verification, and restoration.

---

## 1. Create Backup

:::tabs
@tab Full Snapshot
```bash [terminal]
wp bma backup --type=full --allow-root
```
@tab Database Only
```bash [terminal]
wp bma backup --type=db --allow-root
```
@tab Uploads Only
```bash [terminal]
wp bma backup --type=uploads --allow-root
```
:::

---

## 2. List & Verify Snapshots

```bash [terminal]
# List all snapshots with checksum verification
wp bma list --allow-root

# Verify SHA-256 integrity of a snapshot
wp bma verify snapshot-2026-09-09-abcd123.enc --allow-root
```

---

## 3. Restore Snapshot

```bash [terminal]
wp bma restore snapshot-2026-09-09-abcd123.enc --keycard="YOUR-KEYCARD-TOKEN" --allow-root
```
