# ns_event — Architecture

## Content elements (`ext_localconf.php`, `Configuration/TCA/Overrides/tt_content.php`)

| CType | Title | Controller → action (non-cacheable) | FlexForm | Icon |
|---|---|---|---|---|
| `nsevent_pi1` | NS Event: List View | `EventsController` → `list` | `Configuration/FlexForms/flexform_event.xml` | `ns_event-plugin-pi1` |
| `nsevent_pi2` | NS Event: Detail View | `EventsController` → `show` | — | `ns_event-plugin-pi2` |

- Both are registered with `registerPlugin()` in item group `plugins`; icons in `Configuration/Icons.php`
  (both `Resources/Public/Icons/PluginIcon.svg`). Frontend rendering is core's CType plugin TypoScript
  `tt_content.nsevent_pi1/pi2 =< lib.contentElement`, so the site needs a `lib.contentElement` (e.g.
  fluid_styled_content).
- List View edit form: tab `plugin` with `pi_flexform` and `pages` (Record Storage Page).
- FlexForm: one sheet `listConfig` with `settings.recordsPerPage` (int, default 5, lower 1) and
  `settings.detailPageId` (one page, required); attached with `addPiFlexFormValue()` (see `core.md`).
- Wizard entries in `Configuration/page.tsconfig` (`mod.wizards.newContentElement.wizardItems.plugins`, with
  `tt_content_defValues.CType`). TYPO3 also builds wizard items from the TCA group; the TSconfig entries use
  the same keys and override them, so each element appears once (code-read).
- Upgrade wizard `NsEventCTypeMigration` moves old `list` + `list_type nsevent_pi1/pi2` records to the CTypes
  (only runs while `tt_content.list_type` exists). It only updates `tt_content`; despite its description it
  does not migrate backend group permissions.

## Classes

| Class | Role |
|---|---|
| `Controller/EventsController` | `listAction()` (paginated list), `showAction()` (one event from `tx_nsevent_pi1[events]` or `tx_nsevent_pi2[events]`) |
| `Domain/Model/Events` | `title`, `startDate`, `endDate`, `teaser`, `posterImage` (FileReference), `description`, `address` |
| `Domain/Repository/EventsRepository` | `fetchData($offset, $limit)` (respects the storage page) |
| `Upgrades/NsEventCTypeMigration` | `list_type` → CType migration |
| `Backend/PremiumStrip` | Premium strip HTML + trial URL (see `features/premium-cta.md`) |
| `Form/Container/PremiumStripFlexFormNoTabsContainer` | Core `FlexFormNoTabsContainer` + strip on top |
| `Form/Resolver/PremiumStripFlexFormNoTabsContainerResolver` | Picks the container above for `nsevent_pi1` FlexForms |
| `ViewHelpers/Be/PremiumStripViewHelper` | `<ne:be.premiumStrip />` for the Page module preview |

## Table (`ext_tables.sql`, `Configuration/TCA/tx_nsevent_domain_model_events.php`)

`tx_nsevent_domain_model_events`: `title`, `start_date`, `end_date`, `teaser`, `poster_image` (FAL),
`description`, `address` + language, workspace, `starttime`/`endtime` columns.
TCA switches `poster_image` overrides on `version_main >= 14` (`FileType` enum vs. `File::FILETYPE_*`).
Allowed on standard pages (`security.ignorePageTypeRestriction`).

## Page module preview (`Configuration/page.tsconfig`)

| TYPO3 | Template | Variables |
|---|---|---|
| v13 | `Resources/Private/Backend/Templates/NsEventBackend/ListPluginTemplate.html` | `pi_flexform_transformed.settings.*`, `pages` |
| v14 (`[typo3.branch >= "14.0"]`) | `…/ListPluginTemplatev14.html` | `record.pi_flexform.sheets.listConfig.settings.*`, `record.rawRecord.pages` |

`Configuration/page.tsconfig` is loaded automatically for all pages (no include needed).
v14's Fluid preview renderer only assigns `record`, so the v13 template shows empty values there.
Both keys `preview.nsevent_pi1` and the legacy `preview.list.nsevent_pi1` are set. Fluid namespace
`ne` → `NITSAN\NsEvent\ViewHelpers`, declared in the template.

## Views (frontend)

| File | Used by |
|---|---|
| `Layouts/Default.html` | Wrapper `.tx-ns-event` (contains a `<body class="light--theme">` tag) |
| `Templates/Events/List.html` | Headline + `Partials/Events/Classic` → `ArrayPagination` → `Row/RowView` per event |
| `Templates/Events/Show.html` | `Partials/Events/Show/GeneralEvent.html` (image, title, teaser, description, summary box) |

## TypoScript

Static template "NsEvent" (`Configuration/TypoScript/`, registered in `TCA/Overrides/sys_template.php`) and
site set `nitsan/ns-event` (`Configuration/Sets/ns_event/`) — same setup: view paths for
`plugin.tx_nsevent_pi1` / `pi2`, `lib.contentTitleReplace`, unused module config, and `page.typoscript`
(frontend bundle on every page, see `core.md` known issues).

## Assets

- Frontend: `Resources/Public/public/dist/assets/css/theme.bundle.css`, `js/vendor.bundle.js`, `js/theme.bundle.js`.
- Backend: `Resources/Public/Css/backend-premium-strip.css` (strip only). `Css/global.css`, `Css/main.css`,
  `vendor/` and `fonts/` are not referenced by any template or TypoScript.

## Labels

`locallang.xlf` (record labels, premium strip), `locallang_db.xlf` (TCA, wizard), `locallang_flex.xlf`
(FlexForm, frontend texts).
