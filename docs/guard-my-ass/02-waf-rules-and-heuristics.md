---
title: "WAF Rules & Shannon Entropy Heuristics"
slug: "02-waf-rules-and-heuristics"
product: "guard-my-ass"
topic: "Configuration"
order: 2
badge: "Statistical Analysis"
version: "v1.3"
keywords: ["entropy", "heuristics", "sql injection", "xss", "lfi"]
---

# WAF Rules & Shannon Entropy Heuristics

Guard My Ass uses deterministic statistical algorithms rather than fragile signature lists that attackers easily bypass using polyglot payloads.

---

## 1. Shannon Entropy Calculation

Shannon entropy measures the uncertainty or unpredictability of characters in a string. Plain English text averages an entropy score of **3.2 to 4.2**, whereas encrypted strings, web shell payloads, and base64-encoded malware typically exceed **5.2**.

```
H(X) = - ∑ P(x_i) * log2(P(x_i))
```

| Ingress Payload Type | Average Entropy Score | Action Taken |
| :--- | :--- | :--- |
| Standard Search Query | 3.10 – 3.85 | Pass (Allowed) |
| Long JSON Request Body | 4.10 – 4.70 | Inspected with Tokenizer |
| Base64-Encoded Web Shell | 5.35 – 6.10 | **Immediate 403 Forbidden Drop** |
| Polyglot Hex Exploit Chain | 5.60 – 6.40 | **Immediate Traefik Host Ban** |

> [!WARNING]
> Requests exceeding an entropy threshold of `5.4` on GET query parameters or POST bodies are immediately terminated without reaching WordPress database layers.

---

## 2. Token Anomaly Inspection

When an incoming payload falls in the suspicious range (4.5 – 5.2 entropy), Guard My Ass decomposes the string into lexical tokens to analyze malicious intent:

```php [class-waf-heuristics.php]
// Evaluates dangerous PHP AST token signatures
$anomaly_tokens = ['eval', 'assert', 'passthru', 'shell_exec', 'system', 'base64_decode'];
```

---

## 3. Virtual Honeypot Traps

Guard My Ass injects hidden virtual trap endpoints into your site's robots file and monitors common scanner bait:

* `/.env`
* `/wp-config.php.bak`
* `/aws.yml`
* `/.git/config`

Any IP requesting these paths is instantly added to the local SQLite and MariaDB temporary ban list for 24 hours.
