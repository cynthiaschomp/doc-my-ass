---
title: "SOC Threat Response Dossier & AI Analyst"
slug: "04-soc-threat-response"
product: "secure-my-ass"
topic: "SOC & Forensics"
order: 4
badge: "AI Brain"
version: "v1.3.3"
keywords: ["soc", "dossier", "gemini", "ai analyst", "forensics"]
---

# SOC Threat Response Dossier & AI Analyst

When an intrusion attempt or WAF violation occurs, security teams need actionable forensic data, not raw log noise.

---

## 1. Threat Response Dossier

The built-in SOC Dossier (`ThreatResponseReportModal`) aggregates:
* Ingress IP and resolved autonomous system (ASN).
* Shannon entropy score of the offending payload.
* Matched CWE classifications (e.g. CWE-89 SQLi, CWE-79 XSS, CWE-94 Code Injection).
* Vulnerable vs. Secure side-by-side WordPress code remediations (`$wpdb->prepare()`, `esc_html()`).

---

## 2. A$$ AI SOC Analyst (Google Gemini 2.5 Flash)

By clicking **Ask AI SOC Analyst**, incident telemetry is securely synthesized by Gemini 2.5 Flash:
* Identifies threat actor TTPs (Tactics, Techniques, and Procedures).
* Correlates attack signatures to MITRE ATT&CK framework vectors.
* Provides tailored, site-specific mitigation advice.
