# AGENTS.md — ns_event (TYPO3 Event Manager)

Agent router for the TYPO3 Event Manager free extension. Load only what your task needs.

> **Principles:** `context/principles.md`
> **Session log:** `context/session-state.md`
> **Doc index:** `context/docs-map.md`

---

## Always load

| File | Why |
|---|---|
| `context/principles.md` | Guardrails, "nothing that works may break" rule |
| `context/core.md` | Identity, TYPO3/PHP/ns_license constraints, tooling, known issues |

---

## Task router

| Task | Load |
|---|---|
| List / Detail plugin, events records, pagination, TypoScript / site set, frontend assets | `context/features/events-plugin.md` |
| Premium strip / free trial / ns_license dependency | `context/features/premium-cta.md` |
| Classes, tables, TCA, templates, Page module preview, upgrade wizard | `context/architecture.md` |
| Lint / validate / manual test | `tasks/run-quality.md` |
| Update agent context after a change | `tasks/context-update.md` |

---

## Feature status

| Feature | Status | Agent entry |
|---|---|---|
| "NS Event: List View" content element (paginated event list, link to detail page) | Done | `context/features/events-plugin.md` |
| "NS Event: Detail View" content element | Done | `context/features/events-plugin.md` |
| Page module preview of the List View (v13 and v14 templates) | Done | `context/architecture.md` |
| Premium strip + ns_license free-trial deep link | Done (needs ns_license 14.5.4 release) | `context/features/premium-cta.md` |

---

## Quick commands

```bash
cd packages/ns_event            # or typo3conf/ext/ns_event in Classic mode
for f in $(find . -name "*.php" -not -path "./.git/*"); do php -l "$f"; done
composer validate --no-check-publish
for f in Resources/Private/Language/*.xlf; do xmllint --noout "$f"; done
```
