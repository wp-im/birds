# Birds 0.5.0 upgrade

## Boundary

Birds remains a conventional WordPress Classic Theme plus an optional Birds
Core plugin. Posts, Pages, Comments, Users, Categories, Search, Archives,
Menus, Widgets, and capabilities remain WordPress Core mechanisms.

The public packages do not contain Google-only login, Site Kit, OFBlog/Kakiji
copy, host cache rules, MU policy, custom tables, CPTs, custom taxonomies,
REST endpoints, AJAX, WebSocket, follows, mentions, notifications, or
activity ordering.

## Theme

- Classic Mac OS 8/9 feed, thread, page, archive, author, search, and 404 UI.
- Native `Rail — Primary` and `Rail — Secondary` widget areas with fallbacks.
- Small Birds Presentation Customizer controls.
- Fixed-palette packages remain separate from the showcase switcher.
- Generic Account page presentation.
- Theme contracts for login, Account, Composer, and Context Rail behavior.
- Shared danger-confirmation script and responsive navigation.

## Birds Core

- Front-end post creation, optional title and category, editing, Trash, and
  reply open/close.
- Native comment editing and Trash with capability and time-window checks.
- Generic current-user profile updates and local avatar user meta.
- Open Graph/Twitter fallback metadata and Birds social-card renderer contract.
- SEO provider detection, including The SEO Framework, with no duplicate tags.
- Traditional POST/redirect handling through `admin-post.php`.

## Validation

- Lint every PHP file in Theme and Core.
- Check JavaScript and `theme.json`.
- Check no site-specific identifiers enter public packages.
- Build six fixed-palette Theme ZIPs and one Core ZIP.
- Confirm fixed Theme ZIPs exclude `prototype/`, demo assets, and switcher code.
- Check Theme-only and Theme+Core behavior before production deployment.
