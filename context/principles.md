# ns_event — Operating Principles

*Every change to code, context or docs must serve the goals below.*

---

## Goal 1 — Nothing that works may break

The extension is declared for TYPO3 v13 and v14 at the same time (Composer and Classic mode).

- Every change must keep both majors working. Follow the existing version switches
  (`version_main` checks in `Configuration/TCA/tx_nsevent_domain_model_events.php`, the
  `[typo3.branch >= "14.0"]` condition in `Configuration/page.tsconfig`) instead of inventing new ones.
- Prefer the smallest change that fixes the root cause. No drive-by refactors.
- Run `tasks/run-quality.md` and re-test the affected flow in a frontend and backend before claiming done.

## Goal 2 — Token-cost efficiency (agent context)

- **DRY context:** each fact lives in exactly ONE file. Reference, don't copy.
- **Load on demand:** read `context/features/<area>.md` for the task, not everything.
- **Prune as you go:** keep `context/session-state.md` short.

## Goal 3 — Accuracy and trust

- Answer from context + code, never from invented API, table or field names.
- Conflicting facts across files = fix the source file.

---

## Guardrails (non-negotiable)

- The static template (`Configuration/TypoScript/`) and the site set (`Configuration/Sets/ns_event/`) carry
  copies of the same setup. Change both, or neither.
- Both preview templates (`ListPluginTemplate.html` for v13, `ListPluginTemplatev14.html` for v14) must stay
  in sync when the Page module preview changes.
- Do not change ns_license from an ns_event task; reuse its module and trial modal.
- All UI text goes into `Resources/Private/Language/*.xlf` (English source only, translations via Crowdin),
  no hard-coded labels.
- FormEngine node resolver priorities must be unique per node name across all installed extensions
  (see `features/premium-cta.md`).

---

## Apply this whenever you

- Edit `context/` or `AGENTS.md`
- Land a feature or bugfix (update the matching `context/features/*.md` + `session-state.md`)
