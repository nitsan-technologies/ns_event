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
