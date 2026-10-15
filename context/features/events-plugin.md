# Feature — Event list and detail plugins

*Status: Done (free version). Behaviour below is code-read; known defects live in `core.md`.*

## Setup

1. Include the static template "NsEvent" or the site set `nitsan/ns-event` (both also load the frontend
   bundle on every page, see `core.md`).
2. Create `tx_nsevent_domain_model_events` records in a folder.
3. Detail page: add "NS Event: Detail View".
4. List page: add "NS Event: List View", set "Records Per Page", "Detail Page" and the folder as
   "Record Storage Page".

## List View (`nsevent_pi1`, `EventsController::listAction()`)

- Reads `settings.recordsPerPage` from the FlexForm, current page from `tx_nsevent_pi1[currentPage]`.
- Loads all events of the storage page twice: once to count, once with offset/limit for the page.
- Pagination (`initiatePagination()`): `totalpage`, `previous`, `next`, a page window of current −5 … +3.
  Rendered by `Partials/Events/ArrayPagination.html` with `f:uri.action` links (`currentPage`, `pluginUid`).
- Each row (`Row/RowView.html`): poster image (or `Images/noimage.png`), start date badge, title,
  start–end date, "Show more" button. All links go to `settings.detailPageId` with `tx_nsevent_pi1[events]`.
- No events → nothing is rendered (not even the headline).

## Detail View (`nsevent_pi2`, `EventsController::showAction()`)

- Takes the event uid from `tx_nsevent_pi1[events]` (links from the List View) or `tx_nsevent_pi2[events]`.
- Renders image, title, teaser, description and a summary box (date/time, location). Without a valid
  event parameter it renders nothing.
- No FlexForm, no settings.

## Premium-only (not in this repo)

Layouts (Classic / Isotope / Calendar / Slider), pagination styles, filters and radius search, event
registration, light/dark mode, social sharing, custom field groups. Template switches such as
`settings.basicListLayout`, `settings.radioFilter`, `settings.calendarStyle` are premium leftovers.
