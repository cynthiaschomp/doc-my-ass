---
title: "Speed My A$$ Up — WP-CLI Reference"
slug: "05-wp-cli-reference"
product: "speed-my-ass-up"
topic: "CLI Reference"
order: 5
badge: "Terminal"
version: "v1.0.0"
keywords: ["cli", "smau", "cache commands", "purge"]
---

# Speed My A$$ Up — WP-CLI Reference

Complete terminal command reference for cache purging, benchmarking, and AI CSS scanning.

---

## 1. Cache Management

:::tabs
@tab Purge Everything
```bash [terminal]
wp smau purge --all --allow-root
```
@tab Purge Specific URL
```bash [terminal]
wp smau purge --url=https://securemyass.com/docs/ --allow-root
```
@tab Inspect Cache Stats
```bash [terminal]
wp smau stats --allow-root
```
:::

---

## 2. TTFB Benchmark

Run real HTTP latency benchmarks directly against the local or remote endpoint:

```bash [terminal]
wp smau benchmark --iterations=10 --allow-root
```
