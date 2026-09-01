# Changelog

All notable changes to this project will be documented in this file.

## [2.0.0] - 2026-09-01
### Changed
- Changed vendor name to `winningsoftware`.
- Increased Symfony version requirements.

### Removed
- Removed all `CloudBase` references, including namespaces.

## [1.2.1] - 2026-05-21
### Changed
- Updated required Symfony packages.

## [1.2.0] - 2025-11-18
### Added
- Added `generateUrl` method to `LatteAwareApplication`.

## [1.1.0] - 2025-11-17
### Added
- Added CSRF support.

## [1.0.0] - 2025-11-03
### Added
- Added `LatteEngineFactory` to provide a more flexible way to configure the Latte engine.
- Added `config/services.yaml` to register the `LatteEngineFactory` as a service.

### Changed
- Controllers now require additional service configuration.

### Removed
- Removed `EngineBuilder` in favour of `LatteEngineFactory`.

## [0.3.5] - 2025-10-23
### Added
- Added automated CI pipeline.

### Changed
- Refactored unit test for `AbstractLatteController` to break down into smaller methods.

## [0.3.4] - 2025-10-22
### Added
- Added PHP Stan at its strictest level.
- Added PHP CS Fixer.

### Changed
- Increased overall code quality.
- Increased test coverage from 2% to 75%.

## [0.3.3] - 2025-10-22
### Added
- Add PHP unit tests.

### Fixed
- Actual fix for template path resolution issue.

## [0.3.2] - 2025-10-22
### Fixed
- Fix for template path resolution issue.

## [0.3.1] - 2025-10-21
### Fixed
- Fixed error setting `$app->user` in `LatteAwareApplication`.

## [0.3.0] - 2025-10-21
### Added
- Added `LatteAwareApplication` to provide `$app` to templates.
- Provides: `getUser`, `getSession`, `getRequestStack`, `getFlashes` methods.
- Added the ability to register custom Latte extensions via a `config/latte.php` file in your Symfony project.

### Changed
- Changed `AbstractLatteController::globalData` method to always pass an instance of `LatteAwareApplication`. Ensure to
  merge `parent::globalData()` into your own data if you require `$app` overriding the method in your controllers.

## [0.2.0] - 2025-10-20
### Fixed
- `AbstractLatteController` now extends `AbstractController`.

## [0.1.2] - 2025-10-17
### Fixed
- Fixed a path resolution issue for templates.

## [0.1.1] - 2025-10-17
### Fixed
- Fixed namespacing issue.

## [0.1.0] - 2025-10-16
### Added
- Initial dev release.
- Added `AbstractLatteController`.
- Added `EngineBuilder`.