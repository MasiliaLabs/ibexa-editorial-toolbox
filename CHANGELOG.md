# Changelog

All notable changes to `masilia/editorial-toolbox` are documented here. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the project uses [Semantic Versioning](https://semver.org/) (before 1.0, a minor version may contain breaking changes).

## [Unreleased]

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

[Unreleased]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.3.1...HEAD
[0.3.1]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.3.0...0.3.1
[0.3.0]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.2.2...0.3.0
[0.2.2]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.2.1...0.2.2
[0.2.1]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/MasiliaLabs/editorial-toolbox/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/MasiliaLabs/editorial-toolbox/releases/tag/0.1.0
