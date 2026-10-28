# ns_event — Core facts

## Identity

| Item | Value |
|---|---|
| Extension key | `ns_event` |
| Composer name | `nitsan/ns-event` |
| Title (ext_emconf) / product name | TYPO3 Event Manager |
| Namespace | `NITSAN\NsEvent\` → `Classes/` |
| Version (ext_emconf) | 14.1.0 (latest tag 14.0.1) |
| Repository | https://github.com/nitsan-technologies/ns_event |
| Licensing status (NITSAN Free/Freemium sheet) | **Freemium** — this repo is the free version |
| Premium version (sheet) | https://t3planet.de/typo3-event-erweiterung (€199/year) |

## Compatibility

| Source | TYPO3 | PHP |
|---|---|---|
| `ext_emconf.php` | `13.0.0-14.9.99` | `8.2.0-8.5.99` |
| `composer.json` | `^13 \|\| ^14` | `>=8.2 <8.6` |
| ns_license 14.5.4 (dependency) | `12.4.18-14.9.99` | `>=8.1 <8.6` |

- Effective range is **TYPO3 13–14** and **PHP 8.2–8.5** (official TYPO3×PHP matrix for v13/v14). Range is capped at 8.5; 8.6 is not claimed.
- Live-verified 2026-10-28: list plugin, pagination and detail plugin on **TYPO3 13.4.35** and **TYPO3 14.3.7**, each on PHP **8.2, 8.3, 8.4 and 8.5** (DDEV). No PHP deprecations from `Classes/` or `Configuration/`. Smoke test: `Tests/run-live-smoke.php`.

## Dependencies

| Package | Constraint | Why |
|---|---|---|
| `typo3/cms-core` | `^13 \|\| ^14` | core (Extbase, Fluid, FormEngine used but not declared separately) |
| `nitsan/ns-license` / `ns_license` | `^14.5.4` / `14.5.4-14.9.99` | TYPO3 Shop module + free-trial deep link (see `features/premium-cta.md`) |

## Tooling

- No PHPUnit, PHPStan, CS fixer or root `package.json` in the repo.
- Frontend sources: `Resources/Public/public/` (webpack project with `src/`, `dist/`, `package.json`);
  only `dist/assets/` is loaded.
- CI: `.github/workflows/ter-release.yml` only (publishes tags to TER).
- `.gitattributes` export-ignores `.crowdin.yml`, `.github/`, `.gitignore`, `AGENTS.md`, `CLAUDE.md`, `context/`,
  `tasks/` and itself, so they are not in the GitHub/Composer archive or the TER package (the TER release uploads
  the GitHub tag archive).
- Translations: Crowdin (`.crowdin.yml`), source files `Resources/Private/Language/*.xlf`.

## Conventions

- Extbase plugins registered as content elements (`ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT`).
- Constructor injection in `EventsController`; `Configuration/Services.yaml` autowires `NITSAN\NsEvent\`.
- Backend additions (premium strip) use core icons, Bootstrap utilities and their own stylesheet in
  `Resources/Public/Css/`, `light-dark()` for both backend colour schemes.

## Known issues (existing, not fixed)

Behaviour details: `features/events-plugin.md`. All code-read unless marked.

- `Configuration/TypoScript/page.typoscript` (imported by the static template and the site set) redeclares
  `page = PAGE`, removes `page.includeCSS.10` and adds the theme bundle CSS/JS on every page. This can drop
  the site's own stylesheet registered at `includeCSS.10`.
- `EventsController::listAction()`: `(int) $this->settings['recordsPerPage'] ?? 10` never falls back
  (the cast runs first). An empty value gives 0 → division by zero in `initiatePagination()`.
  `$data` is undefined, so the `pluginUid` reset never runs; two List Views on a page share `currentPage`.
- Events are listed in uid order (no `default_orderings`, no TCA `sortby`), not by start date.
- TCA `enablecolumns` has no `disabled` → events can't be hidden although `ext_tables.sql` has `hidden`.
  `ctrl.searchFields` names a non-existent `link` column; `ctrl.cruser_id` is obsolete since v12.
- `EventsController::getCurrentVersion()` is unused; `showAction()` assigns `currantPage` (typo, unused); the
  constructor injects a `ContentObjectRenderer` that is never used.
- `TCA/Overrides/tt_content.php` uses `ExtensionManagementUtility::addPiFlexFormValue()`, deprecated in
  TYPO3 14 (Deprecation-107047, removal in v15): it triggers a deprecation notice on v14.
- `module.tx_nsevent_web_nseventm1` TypoScript configures a backend module that isn't registered, and
  uses undefined constants `module.tx_nsevent_m1.*`. `plugin.tx_nsevent_pi2.view.*` constants are undefined too.
- Templates use settings the free version never sets (`basicListLayout`, `radioFilter`, `calendarStyle`).
  `RowView.html` has an invalid inline style `background-color: 000000`. `GeneralEvent.html` has
  mismatched `<p>` / `<h5>` tags. Pagination labels "Previous"/"Next" are hard-coded.
- Duplicate xlf ids: `eventlist` in `locallang_flex.xlf`, `tx_nsevent_domain_model_events.address` in
  `locallang_db.xlf`.
- "NS Event: Detail View" gets an empty "plugin" tab in the edit form (`--div--;plugin,`). Its tab label
  and the List View's are the literal string `plugin`, not a label reference.
- TCA override also writes `types.list.subtypes_*` for the CTypes (leftover from `list_type` plugins; no effect).
- Site set label is "event Sets", static template title "NsEvent". The set defines no settings.
- README badge says v2.0.2 / TYPO3 13 / GPL-3.0 (composer: GPL-2.0-or-later) and links to t3planet.com
  instead of the sheet's premium URL.
- `ter-release.yml` uses PHP 7.4 and the deprecated `::set-output`.
- Fixed 2026-10-14: the Page module preview showed empty values on v14 (verified live; see `architecture.md`).
