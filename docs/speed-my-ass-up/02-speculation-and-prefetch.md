---
title: "W3C Speculation Rules API & Hover Prefetch"
slug: "02-speculation-and-prefetch"
product: "speed-my-ass-up"
topic: "Architecture"
order: 2
badge: "W3C Next-Gen"
version: "v1.0.0"
keywords: ["speculation rules", "prefetch", "prerender", "hover-intent"]
---

# W3C Speculation Rules API & Hover Prefetch

Modern Chromium browsers support the **W3C Speculation Rules API**, allowing web applications to prerender pages in the background before the user clicks a link.

---

## Dynamic JSON Speculation Injection

Speed My A$$ Up injects native script tags instructing modern browsers to prerender likely destination pages based on cursor proximity and viewport visibility:

```html [Rendered Page Source]
<script type="speculationrules">
{
  "prerender": [
    {
      "source": "list",
      "urls": ["/docs/", "/about/", "/pricing/"],
      "eagerness": "moderate"
    }
  ]
}
</script>
```

For older or non-Chromium browsers (Safari, Firefox), the engine seamlessly falls back to high-performance hover-intent `<link rel="prefetch">` injection with debounce thresholds.
