# Feature — Premium strip and ns_license free trial

*Status: Done. Verified live on TYPO3 14.3.7 (edit form, Page module, trial click-through). Requires
ns_license 14.5.4 (deep-link support) to be released.*

## Licensing status

Freemium per the NITSAN Free/Freemium sheet. Premium product: https://t3planet.de/typo3-event-erweiterung
(not linked from the UI; the trial stays inside the TYPO3 backend). Premium features:
`features/events-plugin.md`.

## Dependency

`composer.json` `"nitsan/ns-license": "^14.5.4"`, `ext_emconf.php` `'ns_license' => '14.5.4-14.9.99'`.

## Strip

Same implementation pattern as ns_revolution_slider (`Classes/Backend/PremiumStrip.php`).

- `Backend\PremiumStrip::render()` returns the HTML, or `''` if the ns_license route is missing.
- Content: `actions-rocket` core icon (`IconSize::SMALL`), headline and description on two lines, one
  `btn btn-primary` "Start Free Trial →" link (the arrow is `aria-hidden` markup, not part of the label; same tab).
- Wording and design are the team-wide standard from the 2026-10-14 review: "You're using the free version of
  [Extension Name]" / "Unlock premium features and get more out of [Extension Name]."
- Labels in `locallang.xlf`:
  - `premium.strip.headline`: "You're using the free version of NS Event"
  - `premium.strip.description`: "Unlock premium features and get more out of NS Event."
  - `premium.strip.trial`: "Start Free Trial"
- Styling: `.ns-event-premium-strip` (+ `-icon`, `-headline`, `-description`) in
  `Resources/Public/Css/backend-premium-strip.css` — light-blue card (`#e7f2fc`, border `#8eb6e8`, 4px `#2b6cb0`
  left accent, 6px radius, padding 14px 18px, text `#0d2c4a`), semibold 1.1em headline, `light-dark()` for dark
  mode, button `#007bff` (hover `#0062cc`, no wrap). Inside the FlexForm panel (`.panel > …`) it is inset by 16px.
  Same design as the ns_gallery / ns_helpdesk / ns_social_login strips.

## Placements

1. **Edit form of "NS Event: List View", tab "plugin", top of the FlexForm panel, above "Records Per Page".**
   Node resolver `1791974738` (**priority 41**) on `flexFormNoTabsContainer` (the FlexForm has one sheet).
   `PremiumStripFlexFormNoTabsContainerResolver` only matches `tt_content`, field `pi_flexform`,
   `recordTypeValue === 'nsevent_pi1'`, not an inline child. The container extends core
   `FlexFormNoTabsContainer`, prepends the strip and adds the stylesheet via `stylesheetFiles`.
   - Priority must be unique per node name across all installed extensions: core's `NodeFactory` throws
     #1433155705 "already has a resolver registered with priority …". Taken: ns_news_comments 40,
     ns_event 41, ns_gallery 42, ns_helpdesk 43, ns_social_login 44, ns_faq 45, ns_open_streetmap 46. Each resolver returns `null` for foreign plugins, so they chain safely.
   - If the FlexForm ever gets a second sheet, switch to `flexFormTabsContainer` as in ns_revolution_slider.
2. **Page module preview.** `<ne:be.premiumStrip />` at the top of `ListPluginTemplate.html` (v13) and
   `ListPluginTemplatev14.html` (v14); `PremiumStripViewHelper` adds the CSS via `PageRenderer::addCssFile()`.
   A ViewHelper (not a `PageContentPreviewRenderingEvent` listener) because setting preview content in that
   event suppresses the TSconfig Fluid preview.

"NS Event: Detail View" has no FlexForm and no preview template, so it shows no strip.

## Trial link

`UriBuilder::buildUriFromRoute('nitsan_nslicensemodule', ['trial' => 'ns_event'])` + `#free-section`.
ns_license's `get-license.js` (`maybeOpenTrialDeepLink()`) reads `?trial=`, removes it from the URL, activates
"My Extensions" or the Extensions catalog tab and opens its "Get New License" wizard in trial mode
(`gl-product-select=ns_event`, `gl-mode=trial`).

The ns_license module ("T3Planet Shop") requires **system maintainer** access. Other users (even admins) are
redirected to the Dashboard with "you currently can't access the T3Planet Shop module"; the strip is still
shown to them.
