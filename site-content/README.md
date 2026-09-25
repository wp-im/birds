# Birds demo-site content

This is the reviewed content source for the public `wordpress.im/birds/` demo site. It stays outside the deployable theme ZIP. The site explains and publishes the Birds Classic Theme; it is not a second content system.

## Site settings

- Site title: `Birds`
- Tagline: `A calm, feed-first publishing surface for WordPress.`
- Front page: `Birds` (static Page)
- Posts page: `Notes`
- Primary language: English
- Theme: Birds 0.4.0 fixed-palette release; the showcase deployment uses the demo switcher build
- Plugin: Birds Core 0.1.0

## Build order

1. Create the path-based site `birds` on the existing `wordpress.im` network.
2. Install and network-enable the exact Birds Theme ZIP; activate Birds only on this site.
3. Install the exact Birds Core ZIP; activate Birds Core only on this site.
4. Create the `Birds` front page and `Notes` posts page.
5. Create `About Birds`, `Install Birds`, `Design Notes`, and `Changelog` Pages.
6. Create the three reviewed Posts from `posts/`.
7. Set the static front page, menus, site title, and tagline.
8. Verify the public home, single post/thread, archive, search, page, and 404 routes.

The Theme remains a conventional Classic Theme: WordPress Posts, Comments, Users, Categories, Search, and Archives are the content model. Birds Core is a small optional companion plugin for front-end operations; it adds no custom tables, REST extension, follow system, notification system, presence system, or activity-ranking system.
