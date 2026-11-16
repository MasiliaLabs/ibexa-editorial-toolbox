# Contributing to masilia/editorial-toolbox

Thank you for taking the time to contribute. Bug reports, ideas and pull requests are welcome.

## Reporting a bug or asking for a feature

Open an [issue](https://github.com/MasiliaLabs/editorial-toolbox/issues/new/choose) and fill the template. For a bug, give the versions of the package, PHP and Ibexa, what you did, what you expected and what happened. Security problems go through the [security policy](SECURITY.md), never through a public issue.

## Working on the code

```bash
git clone https://github.com/MasiliaLabs/editorial-toolbox.git
cd editorial-toolbox
composer install
```

Before you open a pull request, run what the continuous integration runs:

```bash
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=2G --no-progress
```

## Pull requests

- Target the `master` branch and keep the change focused on one thing.
- Add or update the tests that cover it.
- Describe what changes for the people who use the package, and update the `CHANGELOG.md` (the `Unreleased` section).
- Commit messages start with `feat:`, `fix:`, `refactor:`, `docs:` or `chore:`.

By contributing you agree that your contribution is released under the [MIT license](LICENSE) of the project.
