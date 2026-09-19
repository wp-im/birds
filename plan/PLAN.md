# Birds 0.3.0 plan

## Objective

Build and publish `Birds` as a small WordPress Classic Theme and use it to create the public path-based subsite `https://wordpress.im/birds/`. The site explains the theme and publishes a small set of notes about its feed-first approach.

## V1 boundary

- WordPress Core Posts, Pages, Comments, Users, Categories, Search, Archives, and native menus.
- Classic Theme templates and CSS; no build framework or front-end framework.
- Front-end presentation hooks for the optional `birds-core` operations plugin.
- Native comments for thread replies.
- No custom table, custom REST endpoint, follow, mention, notification, presence, or activity bump system.
- Deployable ZIP excludes `prototype/`, `site-content/`, `plan/`, and `build/`.

## Completed

1. Renamed the theme project from `classic-mac-p2` to `birds` and unified the Birds identity and text domain.
2. Kept site copy outside the deployable theme in `site-content/`.
3. Validated PHP syntax, `theme.json`, ZIP integrity, and the V1 boundary.
4. Created the existing-network path site `/birds/` without changing the main site or sibling sites.
5. Installed and network-enabled `birds-0.2.0.zip`; activated Birds only on `/birds/`.
6. Published the Birds front page, Notes posts page, About, Install, Design Notes, and Changelog pages.
7. Published three theme notes under native WordPress categories: Notes, Design, and Development.
8. Configured the static front page, Notes posts page, site language, tagline, and `Birds Primary` menu.
9. Updated the theme to render the configured primary menu, with a core-only fallback when no menu is assigned.
10. Moved the new site's default Hello World post to Draft so it does not enter the public feed.
11. Added Theme hooks for Birds Core post and comment actions; publishing logic is now provided by the companion plugin.
12. Refreshed the public Birds product copy across the front page, four supporting pages, and three theme notes to describe the Theme/Core/WordPress product boundary.
13. Upgraded the theme locally with front-page introduction support, composer rhythm, Admin Bar-aware sticky navigation, symmetric titlebar controls, visible footer, semantic headings, post titles, multipage support, and adjacent-post navigation.

## Current local candidate

- Theme version: Birds 0.3.0
- Theme package: \`build/birds-0.3.0.zip\`
- Theme package SHA-256: \`da71edb4144241a61e26139e16e802a59b15d0fde1dc3b5db01b7f8d15141d34\`
- Production deployment completed on 2026-09-19 after the 0.3.0 package was reviewed.

## Production evidence

- Public URL: `https://wordpress.im/birds/`
- WordPress: 7.1.1
- Theme: Birds 0.3.0, active on the Birds subsite only
- Companion plugin: Birds Core 0.1.0, active only on the Birds subsite
- Theme package: `build/birds-0.3.0.zip`
- Theme package SHA-256: `ac3ad98ad37d47d70a26c869186d116985a139578a5ec71d44547699875a1e88`
- Birds Core package: `birds-core/build/birds-core-0.1.0.zip`
- Birds Core package SHA-256: `8464b68c40c2dc1abd82531db0db3c1b4e40f8d16f7cacd05cf67827aa165b05`
- Browser check: WordPress reports Birds 0.3.0 active; the public stylesheet loads with `ver=0.3.0`; the homepage shows the intro window, composer, feed, Topics/About rail, and `Birds 0.3.0 · WordPress Core` footer.
- Public route checks: home, About, Install, Design Notes, Changelog, and the Birds and WordPress Core single post all rendered with one page title, a visible footer, and no fatal, parse, or warning output.
+ Navigation check: the `#topics` view keeps the header menu visible and renders the Topics rail; Birds Core remains active at version 0.1.0.

## GitHub publication

- Public repository: `https://github.com/wp-im/birds`
- Default branch: `main`
- Release tag: `v0.3.0`
- Release asset: `build/birds-0.3.0.zip`
- Release asset SHA-256: `da71edb4144241a61e26139e16e802a59b15d0fde1dc3b5db01b7f8d15141d34`

## Deferred

Activity bumping, richer notifications, follows, mentions, presence, custom REST endpoints, and real-time transport remain out of scope until actual use demonstrates a need.
