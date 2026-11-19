---
name: masilia-editorial-toolbox
description: Install, configure and debug masilia/editorial-toolbox, the in-context editing toolbar and block sidebar that logged-in editors see on the public site of an Ibexa 4.6 or 5 project (edit modal, add/link/reorder/remove blocks, discard drafts, translation-aware). Use whenever the user wants editors to edit from the front office, the toolbar or sidebar does not show, `masilia_editorial_toolbox()`/`masilia_editorial_attrs()` in Twig, the admin-toolbar.js Encore entry, blocks_field or admin_siteaccess settings, or mentions masilia/editorial-toolbox, even without the package name.
---

# masilia/editorial-toolbox

A floating **edit toolbar** and a **block sidebar** for logged-in editors on the public site, plus the small JSON API behind them (routes under `/admin/api`). Works with Ibexa 4.6 and 5.0. Visitors see nothing.

## Install
1. `composer require masilia/editorial-toolbox`; enable `Masilia\Bundle\EditorialToolbox\MasiliaEditorialToolboxBundle`.
2. Routes: import `@MasiliaEditorialToolboxBundle/Resources/config/routing.yaml` with `prefix: /admin/api`.
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

## Troubleshooting
- Toolbar missing: user not logged in on the admin siteaccess or lacks edit rights; entry not built or not loaded by the theme; `masilia_editorial_toolbox()` not in the layout; check the browser console and network for `/admin/api` 403/404.
- Edit link points to the wrong back office: `admin_siteaccess` of the site (registry or `system.<group>`).
- Sidebar cannot add blocks: `blocks_field` is wrong for the page type, or the field restricts the block type.
- Editing a content without a translation in the site language asks what to do instead of creating a blank one: expected.
- Changes in the package: PHPUnit from the package directory; release with `masilia-package-release`.
