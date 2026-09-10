---
title: "Dynamic <picture> Tag Delivery & Fallbacks"
slug: "03-picture-rewriting"
product: "compress-my-ass"
topic: "Performance"
order: 3
badge: "HTML Rewriter"
version: "v1.0.0"
keywords: ["picture tag", "responsive", "avif delivery", "webp delivery"]
---

# Dynamic `<picture>` Tag Delivery & Fallbacks

Browsers vary in their support for bleeding-edge image formats (e.g. older iOS versions without full AVIF support). Compress My A$$ rewrites standard `<img>` tags on-the-fly into responsive `<picture>` elements:

```html [Rendered Output HTML]
<picture class="cma-optimized-picture">
  <!-- Preferred: High compression AVIF -->
  <source type="image/avif" srcset="/wp-content/uploads/2026/09/hero-1024x576.avif">
  
  <!-- Fallback 1: Broadly supported WebP -->
  <source type="image/webp" srcset="/wp-content/uploads/2026/09/hero-1024x576.webp">
  
  <!-- Fallback 2: Original legacy JPEG/PNG -->
  <img src="/wp-content/uploads/2026/09/hero-1024x576.jpg" width="1024" height="576" alt="Secure Server Architecture">
</picture>
```

> [!NOTE]
> Picture rewriting integrates seamlessly with **Speed My A$$ Up** static HTML caching: the compiled `<picture>` tags are cached statically to eliminate PHP regex execution on subsequent hits.
