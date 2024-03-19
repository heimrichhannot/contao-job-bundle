# Changelog

All notable changes to this project will be documented in this file.

## [1.6.2] - 2024-03-19
- Fixed: possible exception with default template
- Fixed: job posting content elements not outputted in default template
- Fixed: outdated namespaces

## [1.6.1] - 2023-09-06
- Fixed: php8 warning: undefined array key `deleteConfirm`

## [1.6.0] - 2022-03-01
- Changed: allow php 8


## [1.5.0] - 2021-07-12

- service refactoring
- fixed published toggle

## [1.4.2] - 2021-07-12

- added license file
- removed symfony 3 dependency for contao 4.9+

## [1.4.1] - 2019-06-06

### Fixed

- remove NewsItemTrait getEnclosure method

## [1.4.0] - 2019-06-06

### Added

- `tl_job.teaser` and `tl_job.subheadline` fields
- `tl_content` support, for better custom job detail pages

### Changed

- refactored `DataContainer` classes

## [1.3.1] - 2019-06-06

### Fixed

- `tl_job` onsubmit_callback `adjustTime` did not properly set time from date and time input

## [1.3.0] - 2019-02-14

### Added

- fields `date` and `time` added to `tl_job` and structured fieldsets

### Fixed

- Permission handling for non admins to copy, and do multiple actions like copyAll in `tl_job` dca

## [1.2.0] - 2018-12-06

### Added

- support for sitemap generation

## [1.1.0] - 2018-12-05

### Added

- optional support for heimrichhannot/contao-list-bundle and heimrichhannot/contao-reader-bundle

## [1.0.2] - 2018-12-05

### Added

- singleSRC field

## [1.0.1] - 2018-09-04

### Changed

- dependency to company bundle to 1.0.0

## [1.0.0] - 2018-09-04

### Added

- initial version
