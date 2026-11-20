---
name: masilia-editorial-toolbox
description: Install, configure and debug masilia/editorial-toolbox, the in-context editing toolbar and block sidebar that logged-in editors see on the public site of an Ibexa 4.6 or 5 project (edit modal, add/link/reorder/remove blocks, discard drafts, translation-aware). Use whenever the user wants editors to edit from the front office, the toolbar or sidebar does not show, `masilia_editorial_toolbox()`/`masilia_editorial_attrs()` in Twig, the admin-toolbar.js Encore entry, blocks_field or admin_siteaccess settings, or mentions masilia/editorial-toolbox, even without the package name.
---

# masilia/editorial-toolbox

A floating **edit toolbar** and a **block sidebar** for logged-in editors on the public site, plus the small JSON API behind them (routes under `/admin/api`). Works with Ibexa 4.6 and 5.0. Visitors see nothing.

## Install
1. `composer require masilia/editorial-toolbox`; enable `Masilia\Bundle\EditorialToolbox\MasiliaEditorialToolboxBundle`.
2. Routes: import `@MasiliaEditorialToolboxBundle/Resources/config/routing.yaml` with `prefix: /admin/api` when the admin siteaccess is matched by host, or `prefix: /api` when it is matched by URI (`/admin`: Ibexa strips it before routing; the browser still calls `/admin/api/...`).
3. Config:
```yaml
masilia_editorial_toolbox:
    system:
        default: { blocks_field: blocks, admin_siteaccess: admin }
        my_site_group: { admin_siteaccess: admin_my_site }
```
With `masilia/ibexa-sites` the admin siteaccess of each site comes from its registry; keep only `default`.
4. Front-office Encore (`webpack.config.js`): `const masilia = require('./vendor/masilia/editorial-toolbox/src/bundle/Resources/encore/masilia.config.js'); masilia.addEntry(Encore, 'admin-toolbar.js');` before `Encore.getWebpackConfig()`, then `masilia.patchConfig(config)` after it (lets the package resolve the project's node_modules).
5. In the theme layout: `{{ masilia_editorial_toolbox() }}` and load the `admin-toolbar.js` entry (with `masilia/ibexa-blocks`, list it under `assets.extra` of `theme.yaml`). Mark an editable template root with `{{ masilia_editorial_attrs() }}`.
Build: `yarn encore dev` (in DDEV: `ddev exec yarn encore dev`).

With the Masilia recipes endpoint in the `composer.json` (`https://api.github.com/repos/MasiliaLabs/recipes/contents/index.json?ref=flex/main` in `extra.symfony.endpoint`, after Ibexa's), `composer require` already enables the bundle and writes the route and config files (see the README, section *With Symfony Flex*); then do what is left. Without the endpoint, do everything by hand.

## Troubleshooting
- Toolbar missing: user not logged in on the admin siteaccess or lacks edit rights; entry not built or not loaded by the theme; `masilia_editorial_toolbox()` not in the layout; check the browser console and network for `/admin/api` 403/404.
- `/admin/api/csrf-token` answers 404: wrong route prefix for a URI-matched admin (use `/api`). An anonymous call answers 500 "Anonymous user cannot login to the current siteaccess": Ibexa's check, not a bug of the package.
- Edit link points to the wrong back office: `admin_siteaccess` of the site (registry or `system.<group>`).
- New blocks go in the folder `<page name> Blocks` under the page (remote id `<page remote id>_blocks`, made when missing; settings `blocks_folder_suffix` and `folder_type`; empty suffix = under the page as before). If the folder cannot be made (page not a container, no right to create) the block goes under the page.
- Palette says "No block types available": the `blocks` field accepts every type (unrestricted); restrict its `selectionContentTypes` to the block types (ibexa-blocks: `allowed_on` + `model:sync-blocks`).
- After restoring a database dump, `cache:clear` is not enough: Ibexa's persistence cache lives in the app pools; run `cache:pool:clear --all` (a stale cache gives "Could not find content with identifier contentId: X").
- Sidebar cannot add blocks: `blocks_field` is wrong for the page type, or the field restricts the block type.
- Editing a content without a translation in the site language asks what to do instead of creating a blank one: expected.
- Changes in the package: PHPUnit from the package directory; release with `masilia-package-release`.
