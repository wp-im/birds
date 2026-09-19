# Birds

Birds is a small WordPress Classic Theme shaped like a calm, feed-first publishing surface. It brings the immediate rhythm of early P2 to ordinary WordPress Posts, Pages, Comments, Users, Categories, Search, Archives, and native menus.

The visual language is inspired by Classic Mac OS 9: compact window chrome, restrained grey surfaces, clear title bars, and a readable live feed. The interface is designed for immediate publishing, reading, and threaded replies without replacing WordPress with a separate application.

## What is included

- A Classic Theme with front-page composer and live feed.
- Reusable post rendering across the home feed, archives, search, author views, and single threads.
- Native WordPress comments for replies and nested discussion.
- Pages, categories, search, archives, menus, semantic headings, pagination, and adjacent-post navigation.
- Responsive layout with an Admin Bar-aware sticky menu and a visible footer.
- Optional Birds Core operations are maintained separately and are not required for the theme to render.

Birds deliberately does not add custom database tables, post types, taxonomies, REST endpoints, follows, mentions, notifications, presence, or activity ordering.

## Installation

1. Download the latest birds-*.zip from [GitHub Releases](https://github.com/wp-im/birds/releases).
2. In WordPress, open **Appearance → Themes → Add New → Upload Theme**.
3. Upload the ZIP, install it, and activate Birds.
4. For front-end publishing and moderation actions, install the companion Birds Core plugin when it is released.

The public demonstration site is [wordpress.im/birds](https://wordpress.im/birds/).

## Development

The deployable theme source is kept at the repository root. Project notes, demo-site content, and the original prototype are kept in plan/, site-content/, and prototype/. Generated ZIP files under build/ are ignored from source control and attached to releases instead.

Basic checks:

```sh
find . -name '*.php' -exec php -l {} \;
jq empty theme.json
```

## License

Birds is distributed under the GNU General Public License v2 or later. See [LICENSE](LICENSE).
