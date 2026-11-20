# Editorial Toolbox

[![CI](https://github.com/MasiliaLabs/ibexa-editorial-toolbox/actions/workflows/ci.yml/badge.svg)](https://github.com/MasiliaLabs/ibexa-editorial-toolbox/actions/workflows/ci.yml)
[![Latest version](https://img.shields.io/packagist/v/masilia/editorial-toolbox.svg)](https://packagist.org/packages/masilia/editorial-toolbox)
[![Downloads](https://img.shields.io/packagist/dt/masilia/editorial-toolbox.svg)](https://packagist.org/packages/masilia/editorial-toolbox/stats)
[![PHP](https://img.shields.io/packagist/php-v/masilia/editorial-toolbox.svg)](https://packagist.org/packages/masilia/editorial-toolbox)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

In-context editing for the front office of an Ibexa site: an edit toolbar, a block sidebar and the JSON API behind them.

`masilia/editorial-toolbox` is part of a family of packages for [Ibexa](https://www.ibexa.co) that work together or alone. See [the other packages](#the-masilia-packages).

## What it is

A floating **edit toolbar** and a **block sidebar** that logged-in editors see on the public site of an Ibexa project, to edit what they are looking at without going to the back office first — plus the small JSON API behind them. It works with Ibexa 4.6 and 5.0.

## Features

- **An edit toolbar** on the page: edit the content in context (in an edit modal over the page), and discard the draft.
- **A block sidebar** for pages made of blocks: a palette of block types to add, an existing content to link as a block, drag and drop to reorder, a trash zone to remove a block, and the draft of a block to discard.
- **Translation-aware**: it edits the language the content is displayed in and, when the content has no translation in the site language, asks the editor what to do instead of creating a blank one.
- **Opt-in markup** with the Twig function `masilia_editorial_attrs()`, which marks a template root as editable.
- **Per-site configuration**: the field that holds the blocks and the admin siteaccess used to build back-office URLs from the front office.

## Requirements

- PHP 8.1 or later
- Ibexa 4.6 and 5.0 (Symfony 5.4, 6.4 or 7)

Works better with (optional):

- [`masilia/ibexa-blocks`](https://github.com/MasiliaLabs/ibexa-blocks) marks pages and blocks for in-context editing
- [`masilia/ibexa-sites`](https://github.com/MasiliaLabs/ibexa-sites) gives the admin siteaccess of each site

## Installation

```bash
composer require masilia/editorial-toolbox
```

1. Enable the bundle in `config/bundles.php`:

   ```php
   Masilia\Bundle\EditorialToolbox\MasiliaEditorialToolboxBundle::class => ['all' => true],
   ```

2. Import the API routes (`config/routes/masilia_editorial_toolbox.yaml`):

   ```yaml
   masilia_editorial_toolbox:
       resource: '@MasiliaEditorialToolboxBundle/Resources/config/routing.yaml'
       prefix: /admin/api
   ```

   `/admin/api` is right when the admin siteaccess is matched by **host**. When it is matched by **URI** (`/admin`), Ibexa strips `/admin` before routing: use `prefix: /api`, the browser still calls `/admin/api/...`. Without it `GET /admin/api/csrf-token` answers 404 "No route found". An anonymous request to that endpoint answers 500 "Anonymous user cannot login to the current siteaccess": that is Ibexa's access check on the admin siteaccess, before this package's controller.

3. Configure it (`config/packages/masilia_editorial_toolbox.yaml`):

   ```yaml
   masilia_editorial_toolbox:
       system:
           default:
               blocks_field: blocks       # the relation list that holds the ordered blocks of a page
               admin_siteaccess: admin    # the siteaccess of the back office
               blocks_folder_suffix: Blocks   # new blocks of a page are made in the folder "<page name> Blocks" under it; empty = under the page
               folder_type: folder        # the content type of that folder
           my_site_group:
               admin_siteaccess: admin_my_site
   ```

   With [`masilia/ibexa-sites`](https://github.com/MasiliaLabs/ibexa-sites), the admin siteaccess of each site comes from its registry.

4. Add the entry to the front-office Encore build (`webpack.config.js`):

   ```js
   const masilia = require('./vendor/masilia/editorial-toolbox/src/bundle/Resources/encore/masilia.config.js');
   masilia.addEntry(Encore, 'admin-toolbar.js');      // before Encore.getWebpackConfig()
   const config = Encore.getWebpackConfig();
   masilia.patchConfig(config);                       // after it: lets the package resolve your node_modules
   ```

5. Render the toolbox in the layout of your theme, and load the `admin-toolbar.js` entry in its scripts (with `masilia/ibexa-blocks`, list it under `assets.extra` in the `theme.yaml`):

   ```twig
   {{ masilia_editorial_toolbox() }}
   ```

   Mark a template root as editable with `{{ masilia_editorial_attrs() }}`.

### With Symfony Flex

The Masilia recipes do the first steps for you. Add their endpoint once to the `composer.json` of your project, after the one of Ibexa:

```json
"extra": {
    "symfony": {
        "endpoint": [
            "https://api.github.com/repos/ibexa/recipes/contents/index.json?ref=flex/main",
            "https://api.github.com/repos/MasiliaLabs/recipes/contents/index.json?ref=flex/main",
            "flex://defaults"
        ]
    }
}
```

Then `composer require masilia/editorial-toolbox` enables the bundle and writes `config/routes/masilia_editorial_toolbox.yaml` (prefix `/api`, for an admin matched by URI: use `/admin/api` for an admin matched by host) and `config/packages/masilia_editorial_toolbox.yaml`. What is left is yours: the Encore entry of the front office and `{{ masilia_editorial_toolbox() }}` in your layout. The manual steps above and below stay valid.

**Where new blocks are made**: in a folder under the page, named after it (`About Blocks`), found by the remote id of the page and `_blocks` and made when it is missing (the editor needs the right to create it; otherwise, or when the page is not a container, the block goes under the page as before). Set `blocks_folder_suffix` to an empty value to keep the old behaviour. Blocks that exist elsewhere stay where they are.

## Usage

Log in to the back office, then browse the public site: the toolbar appears for users who may edit the content. Visitors see nothing.

## Tests

```bash
composer install
vendor/bin/phpunit
```

## AI agent skill

This package ships a skill for AI coding agents (Claude Code and other agents that read `SKILL.md` skills): how to install, configure, operate and troubleshoot `masilia/editorial-toolbox`. It lives in [`skills/masilia-editorial-toolbox`](skills/masilia-editorial-toolbox/SKILL.md) and comes with the package.

```bash
# For the project (committed with it): from the installed package
mkdir -p .claude/skills && cp -r vendor/masilia/editorial-toolbox/skills/masilia-editorial-toolbox .claude/skills/

# For all your projects: global
mkdir -p ~/.claude/skills && cp -r vendor/masilia/editorial-toolbox/skills/masilia-editorial-toolbox ~/.claude/skills/
```

Restart the agent (or reload its skills) to pick it up. Update the copy after `composer update`.

## The Masilia packages

| Package | What it does |
| --- | --- |
| [`masilia/ibexa-admin-kit`](https://github.com/MasiliaLabs/ibexa-admin-kit) | React components in the design of the Ibexa admin, for admin pages |
| [`masilia/ibexa-sites`](https://github.com/MasiliaLabs/ibexa-sites) | A registry of sites that Ibexa serves without a deploy |
| [`masilia/ibexa-ai`](https://github.com/MasiliaLabs/ibexa-ai) | The AI assistant for Ibexa: capabilities, providers, chat, approval flow |
| [`masilia/ibexa-ai-craft`](https://github.com/MasiliaLabs/ibexa-ai-craft) | The assistant builds sites and pages out of blocks |
| [`masilia/ibexa-ai-editor`](https://github.com/MasiliaLabs/ibexa-ai-editor) | AI suggestions and image generation in the content editing form |
| [`masilia/ibexa-blocks`](https://github.com/MasiliaLabs/ibexa-blocks) | Front-office rendering of pages made of blocks, with themes |
| [`masilia/editorial-toolbox`](https://github.com/MasiliaLabs/ibexa-editorial-toolbox) | In-context editing toolbar for the front office |

**What requires what** (Composer installs it for you):

- `ibexa-sites` requires `ibexa-admin-kit`
- `ibexa-ai` requires `ibexa-admin-kit`
- `ibexa-ai-editor` requires `ibexa-ai`
- `ibexa-ai-craft` requires `ibexa-ai`, `ibexa-blocks` and `ibexa-sites`
- `ibexa-blocks` and `editorial-toolbox` require none of the others; they are happy to find `ibexa-sites` (and each other) when installed.

## Compatibility

| Package version | PHP | Ibexa |
| --- | --- | --- |
| `0.3.x` | 8.1+ | 4.6 and 5.0 |

## Download

- **Composer** (recommended): `composer require masilia/editorial-toolbox` — [editorial-toolbox on Packagist](https://packagist.org/packages/masilia/editorial-toolbox)
- **Archive** of a release: [GitHub releases](https://github.com/MasiliaLabs/ibexa-editorial-toolbox/releases) (each release lists what changed)
- **Source**: `git clone https://github.com/MasiliaLabs/ibexa-editorial-toolbox.git`
- What changed between versions: [CHANGELOG.md](CHANGELOG.md)

## Support, contributing and security

- Questions and bug reports: [GitHub issues](https://github.com/MasiliaLabs/ibexa-editorial-toolbox/issues)
- Pull requests are welcome, see [CONTRIBUTING.md](CONTRIBUTING.md)
- Security problems: please follow [SECURITY.md](SECURITY.md) and do not open a public issue

## License

Released under the [MIT license](LICENSE). Copyright © 2026 MasiliaLabs.

Ibexa itself is distributed under its own licenses (Ibexa OSS: GPL-2.0), which apply to the project that uses this package. This package is an independent project, not affiliated with or endorsed by Ibexa AS; "Ibexa" is a trademark of its owner.
