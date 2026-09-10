---
title: "Bulk Optimization & WP-CLI Processing"
slug: "04-bulk-optimization"
product: "compress-my-ass"
topic: "CLI Reference"
order: 4
badge: "Terminal"
version: "v1.0.0"
keywords: ["cli", "cma", "bulk compression", "batch"]
---

# Bulk Optimization & WP-CLI Processing

Convert entire existing media libraries with thousands of images using non-blocking, chunked batch operations.

---

## 1. Bulk Optimization via WP-CLI

:::tabs
@tab Optimize All Images
```bash [terminal]
wp cma optimize --all --quality=82 --allow-root
```
@tab Optimize Uncompressed Only
```bash [terminal]
wp cma optimize --unoptimized-only --batch-size=50 --allow-root
```
@tab Revert to Originals
```bash [terminal]
wp cma restore --all --allow-root
```
:::

---

## 2. Inspect Compression Savings

View aggregate storage footprint reductions across your uploads directory:

```bash [terminal]
wp cma stats --allow-root
```
