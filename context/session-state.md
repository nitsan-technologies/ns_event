# ns_event — Session state

## 2026-10-14 — ns_license integration, premium strip, agent context

- Branch `master` (HEAD f5f4ab2). All changes uncommitted.
- Added `nitsan/ns-license ^14.5.4` / `ns_license 14.5.4-14.9.99`.
- Premium strip with "Start Free Trial" → ns_license module `?trial=ns_event#free-section` in the List View
  edit form and the Page module preview (see `features/premium-cta.md`). Resolver priority 41 because
  ns_news_comments already uses 40 for `flexFormNoTabsContainer`.
- Fixed the empty Page module preview on v14: `[typo3.branch >= "14.0"]` now uses `ListPluginTemplatev14.html`.
- Verified live on TYPO3 14.3.7 classic (temporary admin, made system maintainer for the trial check; test
  content element, user and maintainer entry removed afterwards). Not tested on v13. Frontend not tested.
- Created agent context (`AGENTS.md`, `CLAUDE.md`, `context/`, `tasks/`); no previous context existed.

## 2026-10-14 — Banner redesign from review

- Team-wide wording ("You're using the free version of NS Event" / "Unlock premium features and get more out
  of NS Event."), two-line layout, arrow on the button, more padding, rounded card, inset inside the FlexForm
  panel (see `features/premium-cta.md`). Version 14.0.1 → 14.1.0 (`ext_emconf.php`).
- Page module verified live on 14.3.7; test data removed. Uncommitted.

## 2026-10-15 — Context audit

- 2026-10-15: no wrong statements found; added `lib.contentElement` rendering, `Icons.php`, wizard/TCA
  merge, global `page.tsconfig` loading, upgrade wizard scope, deprecated `addPiFlexFormValue()` on v14 and
  unused `ContentObjectRenderer` (code-read).

## 2026-10-28 — PHP 8.5 compatibility

- Declared PHP **8.2–8.5**: `composer.json` `>=8.2 <8.6`, `ext_emconf.php` `8.2.0-8.5.99` (was `^8.2` /
  `8.2.0-8.99.99`, which implied untested 8.6+). No runtime code changes: the 14 PHP files already use
  8.2+ syntax and none of the PHP 8.5 deprecations. Syntax + class load checked on 8.2–8.5.

## 2026-10-28 — Live matrix v13/v14 × PHP 8.2–8.5

- `Tests/run-live-smoke.php`: isolated site, 5 events, list (2 per page), page-2 pagination, detail from
  generated cHash links. 35/35 on each cell. DDEV PHP restored to 8.5. Test pages torn down afterwards.
- Instances: theme-camino TYPO3 14.3.7; typo3-src-13.4.35 TYPO3 13.4.35 (ns_event copied in for the run).

## 2026-10-28 — Premium strip product name

- Strip labels now say "Event" not "NS Event" (`premium.strip.headline` / `premium.strip.description` in
  `locallang.xlf`), matching ns_comments / ns_news_comments / ns_faq. Plugin titles unchanged.
