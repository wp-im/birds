# Birds

Birds is a small WordPress Classic Theme shaped like a calm, feed-first publishing surface. It brings the immediate rhythm of early P2 to ordinary WordPress Posts, Pages, Comments, Users, Categories, Search, Archives, native menus, and native Classic Widgets.

The visual language is inspired by Classic Mac OS 9: compact window chrome, restrained grey surfaces, clear title bars, and a readable live feed. The interface is designed for immediate publishing, reading, and threaded replies without replacing WordPress with a separate application.

## What is included

- A Classic Theme with front-page composer and live feed.
- Reusable post rendering across the home feed, archives, search, author views, and single threads.
- Native WordPress comments for replies and nested discussion.
- Pages, categories, search, archives, menus, semantic headings, pagination, and adjacent-post navigation.
- Two native Context Rail widget areas with Birds window fallbacks.
- Small Customizer settings for feed presentation, without changing the fixed palette.
- An optional Account page presentation; profile updates are supplied by Birds Core.
- Responsive layout with an Admin Bar-aware sticky menu and a visible footer.
- Mac OS 8/9 social preview cards for public posts and pages when Birds Core is active, with a cached 1200×630 Birds window image.
- Optional Birds Core operations are maintained separately and are not required for the theme to render.

Birds deliberately does not add custom database tables, post types, taxonomies, REST endpoints, follows, mentions, notifications, presence, or activity ordering.

The Theme supplies the Birds window renderer for social cards. Birds Core owns the fallback Open Graph/Twitter metadata and calls that renderer when available. WordPress uploads and GD are used when the server provides a readable TrueType font; featured images and the site icon remain safe fallbacks. SEO plugins that already own social metadata disable the Birds fallback through the Core contract.

## Installation

1. Open the [GitHub Releases](https://github.com/wp-im/birds/releases) page.
2. Choose one fixed-palette asset: Classic, Sunlit Yellow, Mist Green, Mist Blue, Mist Red, or Mist Gray.
3. In WordPress, open **Appearance → Themes → Add New → Upload Theme**.
4. Upload that ZIP, install it, and activate it. The installable package has no palette switcher.
5. For front-end publishing, profile updates, moderation actions, and social metadata fallback, install the companion Birds Core plugin.

The public demonstration site is [wordpress.im/birds](https://wordpress.im/birds/).

## Development

The deployable Theme source is kept at the repository root. Palette values live in `scripts/palettes.json`; the build script produces one fixed Theme package per palette. The prototype uses the same root `style.css` plus generated `prototype/palettes.css` for the showcase switcher. Project notes and demo-site content stay in `plan/` and `site-content/`. Generated ZIP files under `build/` are ignored from source control and attached to releases instead.

Basic checks:

```sh
find . -name '*.php' -exec php -l {} \;
jq empty theme.json
php scripts/build-theme.php --demo
php scripts/build-theme.php --all --version=0.5.2
```

The repository's source archive is not an installable palette choice. Use the named ZIP assets on the Release page.

## License

Birds is distributed under the GNU General Public License v2 or later. See [LICENSE](LICENSE).
