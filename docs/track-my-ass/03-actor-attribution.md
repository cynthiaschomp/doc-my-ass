---
title: "Precision Actor & Origin Attribution"
slug: "03-actor-attribution"
product: "track-my-ass"
topic: "Attribution Engine"
order: 3
badge: "AI & MCP"
version: "v1.0.0"
keywords: ["actor", "mcp", "ai agent", "wp-cli", "attribution"]
---

# Precision Actor & Origin Attribution

A critical requirement of managing high-security WordPress infrastructure is non-repudiation: knowing exactly what process or human triggered an action.

---

## 1. Actor Classification Taxonomy

Track My A$$ categorizes every action into one of five distinct actor profiles:

1. **`ai_mcp` (AI Agent / Cynthia MCP Bridge)**:
   - Invocations originating from Cynthia MCP (`cyn_docker_exec`), base64 stdin wrappers, Antigravity AI, or automation scripts (`deploy_plugin.py`, `publish_post.py`).
2. **`wp_cli` (WP-CLI Command Line)**:
   - Direct terminal executions by `root` or `www-data`, logging the exact command and arguments run.
3. **`human_admin` (Authenticated Administrator)**:
   - Web browser sessions logged in with `manage_options` capability (e.g. `@CynthiaSchomp`).
4. **`rest_api` (REST API / Webhook)**:
   - Automated external API clients or webhooks.
5. **`system_cron` (WP-Cron Routine)**:
   - Background tasks triggered by `wp-cron.php`.

---

## 2. Anti-Shoulder-Surfing OPSEC

In compliance with Suite Rule 7.A:
- Sensitive credentials (passwords, salts, API keys) modified in options are masked behind eye toggles (`••••••••`).
- IP addresses and internal paths are masked by default in public views.
