# Changelog

All notable changes to `masilia/editorial-toolbox` are documented here. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the project uses [Semantic Versioning](https://semver.org/) (before 1.0, a minor version may contain breaking changes).

## [Unreleased]

### Fixed
- Publishing in the in-context editor no longer ends with an HTTP 500 (`AdminMetaBuilder::build(): Argument #1 ($content) must be of type Content, null given`): the view that the editor renders after the publish has no content, and the listener that adds `admin_meta` now leaves such a view alone. The content was published all along; the modal now closes.
- Docs: the palette lists block types only when the `blocks` field of the page restricts the content types it accepts (`selectionContentTypes`); on an unrestricted field it shows "No block types available". After restoring a database, clear every cache pool (`cache:pool:clear --all`).

## [0.4.1] - 2026-11-20

### Added
- Docs: a *Stay informed* section in the README: how to be told of new releases (GitHub Watch, the releases feed, `composer outdated`, Dependabot) and where to read what changes.

## [0.4.0] - 2026-11-20

### Changed
- A new block of a page is made in a folder under that page, named after it (`<page name> Blocks`, settings `blocks_folder_suffix` default `Blocks` and `folder_type` default `folder`), found by the remote id `<page remote id>_blocks` and made when missing; when that is not possible (the page is not a container, the editor may not create a folder) the block goes under the page as before. An empty `blocks_folder_suffix` keeps the old behaviour. Blocks that exist elsewhere are not moved.

## [0.3.6] - 2026-11-19

### Added
- Docs: a *With Symfony Flex* section in the README and in the skill: the Masilia recipes (`MasiliaLabs/recipes`) enable the bundle and write its route and config files on `composer require`.

## [0.3.5] - 2026-11-19

### Changed
- The repository is now `MasiliaLabs/ibexa-editorial-toolbox`: the links of `composer.json` and of the docs follow (the Packagist name stays `masilia/editorial-toolbox`).

### Fixed
- Docs: the routes prefix is `/api` (not `/admin/api`) when the admin siteaccess is matched by URI (`/admin`), because Ibexa strips `/admin` before routing; the 500 answered to an anonymous call comes from Ibexa's access check of the admin siteaccess. README, `routing.yaml` and skill corrected.

## [0.3.4] - 2026-11-19

### Added
- An agent skill, `skills/masilia-editorial-toolbox`, for AI coding agents that work with the package; the README says how to install it.

## [0.3.3] - 2026-11-18

### Fixed
- `AdminMetaViewListener` no longer tests a content view's content for `null`, which it never is.

### Changed
- `ToolboxExtension` no longer takes a translator it never used. Passing one still works (PHP ignores the extra argument).
- PHPStan (level 5) runs in the continuous integration. `ibexa/automated-translation` is optional: its services are the only ones the analysis is told to ignore.

## [0.3.2] - 2026-11-17

### Changed
- Declares the dependencies the code uses directly; examples and test fixtures are neutral.

## [0.3.1] - 2026-11-07

### Fixed
- The fullscreen message is not posted to an iframe that has not loaded the admin page.

## [0.3.0] - 2026-11-07

### Added
- Asks the editor what to do when the content has no translation in the site language.

## [0.2.2] - 2026-11-07

### Fixed
- Edits the language the content is displayed in, and never creates a blank translation.

## [0.2.1] - 2026-11-07

### Fixed
- Renders correctly on themes without Font Awesome or a global border-box reset.

## [0.2.0] - 2026-11-07

### Added
- The Twig function `masilia_editorial_attrs()` marks a template root as editable.

## [0.1.0] - 2026-11-07

First release: the in-context edit toolbar and the block sidebar.

[Unreleased]: https://github.com/MasiliaLabs/ibexa-editorial-toolbox/compare/0.4.1...HEAD
[0.4.1]: https://github.com/MasiliaLabs/ibexa-editorial-toolbox/compare/0.4.0...0.4.1
[0.4.0]: https://github.com/MasiliaLabs/ibexa-editorial-toolbox/compare/0.3.6...0.4.0
[0.3.6]: https://github.com/MasiliaLabs/ibexa-editorial-toolbox/compare/0.3.5...0.3.6
[0.3.5]: https://github.com/MasiliaLabs/ibexa-editorial-toolbox/compare/0.3.4...0.3.5
[0.3.4]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.3.3...0.3.4
[0.3.3]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.3.2...0.3.3
[0.3.2]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.3.1...0.3.2
[0.3.1]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.3.0...0.3.1
[0.3.0]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.2.2...0.3.0
[0.2.2]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.2.1...0.2.2
[0.2.1]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/MasiliaLabs/editorial-toolbox/releases/tag/0.1.0
