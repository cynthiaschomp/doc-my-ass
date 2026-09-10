---
title: "Zero-SaaS Image Hyper-Engine & Multi-Driver Pipeline"
slug: "01-getting-started"
product: "compress-my-ass"
topic: "Getting Started"
order: 1
badge: "Zero-SaaS"
version: "v1.0.0"
keywords: ["images", "compression", "webp", "avif", "imagick"]
---

# Zero-SaaS Image Hyper-Engine & Multi-Driver Pipeline

**Compress My A$$** is a zero-quota, zero-subscription local image hyper-engine. While commercial image optimization plugins charge monthly fees and ship your private media to third-party cloud APIs, Compress My A$$ compiles next-generation **WebP** and **AVIF** assets locally on your server using native binaries.

> [!NOTE]
> Enjoy unlimited image compression with zero third-party API keys, zero monthly quotas, and zero external network latency.

---

## 1. Multi-Driver Execution Fallback

Compress My A$$ automatically selects the most capable image processing driver available on your host environment:

1. **Imagick (ImageMagick PHP Extension)**: Highest fidelity AVIF and WebP encoder.
2. **GD Graphics Library**: Fast, ubiquitous PHP fallback.
3. **CLI System Binaries (`cwebp`, `avifenc`)**: Native terminal binaries for ultra-fast multi-core processing.

---

## 2. Automatic Generation on Upload

Whenever a user uploads media to WordPress (`image/jpeg`, `image/png`):
* Compiles `.webp` and `.avif` formats for the original master image.
* Compiles next-gen formats for all registered sub-sizes (`thumbnail`, `medium`, `large`, custom Divi sizes).
* Archives the uncompressed original into the protected **Master Vault**.
