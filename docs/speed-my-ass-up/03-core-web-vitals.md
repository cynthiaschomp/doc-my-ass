---
title: "Core Web Vitals: LCP Turbo & CLS Zero-Shift"
slug: "03-core-web-vitals"
product: "speed-my-ass-up"
topic: "Performance"
order: 3
badge: "CWV Perfection"
version: "v1.0.0"
keywords: ["lcp", "cls", "fetchpriority", "aspect-ratio", "lazy-load"]
---

# Core Web Vitals: LCP Turbo & CLS Zero-Shift

Speed My A$$ Up targets the two most common causes of low Google PageSpeed scores: slow hero image loading (LCP) and visual layout jumping (CLS).

---

## 1. LCP Turbo Priority Injector

The Largest Contentful Paint image is prioritized over all secondary network assets:
* Injects `fetchpriority="high"`.
* Injects `loading="eager"`.
* Adds `<link rel="preload" as="image">` into the document `<head>`.
* Strips lazy-loading flags from above-the-fold hero elements.

---

## 2. CLS Zero-Shift Dimension Enforcement

Cumulative Layout Shift occurs when images load without explicit dimensions, causing page text to jump down. Speed My A$$ Up inspects HTML output dynamically:
* Injects missing `width` and `height` attributes based on media library metadata.
* Enforces modern CSS `aspect-ratio` rules to preserve reserved viewport space.
