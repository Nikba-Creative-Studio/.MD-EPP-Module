# Prompt for AI IDE (cURL‑only)

**Goal:** From the “.MD EPP Server Description v1.4,” generate a production‑ready **PHP SDK** (single class) and a **Bootstrap 5 sandbox** UI that exercises every command and shows raw XML requests/responses. Use **cURL only** for transport (no sockets/streams). Include a robust **logging system** and **auto‑generated forms** with **demo autofill** for every attribute.

---

## Deliverables

1. **Library**: `src/EppMdClient.php` – a single class that implements:

   * `login()`, `logout()`
   * `check(array $domains)`
   * `create(array $payload)`
   * `update(array $payload)`
   * `info(string $domain)`
   * `renew(string $domain, string $currentExpiry, int $years)`
   * `delete(array $domains)`
   * `transferRequest(array $domains)`
   * `transferExecute(array $codes)`
2. **Config**: `.env.example` + `config/epp.php` with `EPP_BASE_URL` (HTTPS), timeouts, retries, and credentials (`clID`, `pw`, `account`, `account_pw`).
3. **Sandbox UI** (Bootstrap 5): `public/index.php`, `views/`, `public/assets/` (vanilla JS). Dynamic forms for each command, tabs for **Parsed**, **Request XML**, **Response XML**.
4. **XML Builders/Parsers**: Exact namespaces/order/attributes per doc examples.
5. **Examples**: Fixtures mirroring every example from the documentation.
6. **Tests**: PHPUnit for build/parse and logger redaction.
7. **Docs**: `README.md` with setup, config, and usage.

---

## Technical Requirements

* **PHP**: 8.2+, strict types, namespace `App\Epp`.
* **Transport (cURL‑only):**

  * Implement a single `CurlTransport` used internally by `EppMdClient` (no interfaces required).
  * Send XML via **HTTP(S) POST** to `EPP_BASE_URL`.
  * Headers: `Content-Type: application/xml; charset=UTF-8`, `Accept: application/xml`.
  * Configure with `.env`: `CONNECT_TIMEOUT`, `READ_TIMEOUT`, `RETRIES`, `VERIFY_SSL` (bool), `CA_BUNDLE` (optional), `USER_AGENT`.
  * Handle non‑2xx as transport errors; capture response body for diagnostics.
  * Optionally support basic auth headers if required by the gateway.
* **XML**: Use `DOMDocument` (preferred) for deterministic output; declaration `<?xml version="1.0" encoding="UTF-8"?>`.
* **EPP Namespaces**: `urn:ietf:params:xml:ns:epp-1.0`, `urn:ietf:params:xml:ns:domain-1.0` with matching `xsi:schemaLocation` exactly as in examples.
* **clTRID**: always include (generate `YmdHis` + random suffix).
* **Errors**: Map EPP result codes to exceptions (`AuthenticationException`, `SyntaxException`, `ProhibitedOperationException`, `TransportException`, base `EppException`). Include server `<msg>` and (if present) IDs.
* **Security**: Never log raw `<pw>`, `<domain:account_pw>`, `<domain:trcode>`.

---

## Logging System (required)

* **Where:** `storage/logs/epp/{YYYY-MM-DD}/`
* **Per exchange files:**

  * `{ISO8601}-{command}.request.xml` (redacted)
  * `{ISO8601}-{command}.response.xml`
* **Day file:** `storage/logs/epp/{YYYY-MM-DD}.ndjson` (one JSON per line):

  * `timestamp`, `command`, `clTRID`, `duration_ms`, `result_code`, `result_msg`, `request_path`, `response_path`, `error`
* **Redaction:** Replace secrets with `***REDACTED***` in both XML files and NDJSON (walk the DOM to find `<pw>`, `<domain:account_pw>`, `<domain:trcode>`).
* **Retention:** Keep last N days (default 14), simple cleanup method invoked at startup.
* **Toggles:** `LOG_EPP=1|0`, `LOG_REDACT=1|0`, `LOG_RETAIN_DAYS=14`.

---

## Sandbox (Bootstrap 5) – Dynamic Forms + Demo Autofill

* **Home UI:** Cards or dropdown for commands: Login, Logout, Check, Create, Update, Info, Renew, Delete, Transfer Request, Transfer Execute.
* **Form Generation:**

  * Use `public/assets/commandSchemas.js` defining **all attributes** per command (mirroring doc tags).
  * On selection, **generate inputs for every attribute**. Repeaters for lists (`<domain:name>`, nameservers, transfer codes).
  * **Fill Demo** button: pre‑populate with values **taken from the documentation examples** (e.g., `domain1.md`, `nic.md`, `YYYY-MM-DD`, `ns1.dns.md`, `1.2.3.4`, `hm@nic.md`, `years=2`, etc.).
  * **Reset** clears all fields; add/remove rows for repeatables.
  * Persist last inputs with `localStorage` per command.
* **Submission Flow:** POST to `index.php?action={command}`; server builds XML, calls cURL transport, parses response; render three tabs (**Parsed**, **Request XML**, **Response XML**), plus timing, `clTRID`, result code/message.
* **Validation:** client‑side required checks + server‑side; highlight invalid fields.

---

## Functional Coverage (match documentation exactly)

* Implement the exact XML structure, order, attributes, and namespaces shown in the doc for:

  * **Session:** `<login>`, `<logout>`
  * **Domain:** `<check>`, `<create>`, `<update>`, `<info>`, `<renew>`, `<delete>`, `<transfer op="request">`, `<transfer op="execute">`
* Parse `res` attributes and map to typed results; expose both parsed data and raw response.

---

## File Tree

```
project/
  composer.json
  .env.example
  config/
    epp.php
  src/
    EppMdClient.php
    Logger/
      EppLogger.php
      Redactor.php
  storage/
    logs/
      epp/
  public/
    index.php
    assets/
      script.js
      style.css
      commandSchemas.js
  views/
    layout.php
    commands/
      dynamic.php
  examples/
    login.request.xml
    login.response.xml
    ... (all commands)
  tests/
    XmlBuilderTest.php
    XmlParserTest.php
    LoggerRedactionTest.php
  README.md
```

---

## README Must Cover

* Installing deps (`composer install`), configuring `.env` (including `EPP_BASE_URL` and timeouts).
* Running the sandbox (`php -S localhost:8080 -t public`).
* Notes on the **cURL‑only transport**, required headers, and expected HTTP status codes.
* Logging paths, redaction, and retention.
* How to run tests.

---

## Acceptance Criteria

* The SDK uses **cURL only**, no sockets/streams, no alternate transports.
* Each command builds correct XML and sends it via HTTP(S) POST.
* Sandbox dynamically generates all fields for the selected command and can **autofill demo data**.
* Request/response are saved to disk (redacted request) and listed in the daily NDJSON.
* Result codes drive typed exceptions; UI shows friendly errors and raw XML tabs.
* Tests pass; static analysis clean; no secrets in logs.
