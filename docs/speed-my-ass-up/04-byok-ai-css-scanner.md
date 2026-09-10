---
title: "BYOK AI CSS Scanner (Zero-SaaS)"
slug: "04-byok-ai-css-scanner"
product: "speed-my-ass-up"
topic: "AI Optimization"
order: 4
badge: "BYOK AI"
version: "v1.0.0"
keywords: ["byok", "ai css", "gemini", "claude", "unused css"]
---

# BYOK AI CSS Scanner (Zero-SaaS)

Bloated themes and page builders output thousands of lines of unused CSS rules that block the browser rendering engine. Speed My A$$ Up features a **Bring-Your-Own-Key (BYOK)** AI CSS auditor.

> [!TIP]
> Use your own API key (Google Gemini, Anthropic Claude, OpenAI, or OpenRouter). Zero third-party SaaS middleman subscriptions, zero monthly fees.

---

## How It Works

1. Crawls target template pages and captures DOM structures.
2. Extracts loaded stylesheets.
3. Sends stylesheet blocks to your chosen AI model with a strict AST prompt.
4. Returns:
   * **Unused Selectors**: Dead classes from inactive plugins.
   * **Broken Rules**: Malformed vendor prefixes or syntax errors.
   * **Purged Critical CSS**: Clean critical CSS snippet ready for inline injection into `<head>`.
