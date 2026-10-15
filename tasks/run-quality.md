# Run quality checks

The repo has no PHPUnit, PHPStan or CS config. Available checks:

```bash
# PHP syntax, once per installed PHP version (8.2+)
for f in $(find . -name "*.php" -not -path "./.git/*"); do php -l "$f"; done

composer validate --no-check-publish

# XLF well-formedness
for f in Resources/Private/Language/*.xlf; do xmllint --noout "$f"; done
```

Fluid templates can't be checked with `xmllint` (conditions like `{a} && {b}` are not valid XML).
After adding or renaming classes in Classic mode run `typo3 dumpautoload`, then `typo3 cache:flush`.

## Manual test (per TYPO3 major 13 / 14)

Setup: static template "NsEvent" or site set `nitsan/ns-event`; a folder with a few events; a detail page
with "NS Event: Detail View"; a list page with "NS Event: List View" (Records Per Page, Detail Page,
Record Storage Page set).

1. Backend edit form of the List View → tab "plugin": one premium strip at the top of the FlexForm panel,
   above "Records Per Page". The Detail View and other plugins' FlexForms have none.
2. Page module: one strip at the top of the List View preview, and the table shows the real
   "Records Per Page" and "Detail Page" values (v14 uses `ListPluginTemplatev14.html`).
3. "Start Free Trial" opens the ns_license module and its trial wizard for `ns_event`
   (needs a system maintainer). Check light and dark backend themes.
4. Frontend list page: events render, pagination appears with more events than Records Per Page,
   page links work. "Show more" opens the detail page with the right event.
5. Check that the site's own CSS is still loaded (`page.includeCSS.10` is removed by the extension,
   see `context/core.md`).

Remove test events, content elements and temporary backend users afterwards.
