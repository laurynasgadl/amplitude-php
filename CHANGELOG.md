# Changelog

## [3.1.0] - 2023-05-11
### Added
- Amplitude v2 API support

## [3.0.0] - 2021-12-22
### Added
- Ability to change the used HTTP client either via optional construct arg or `setClient` method

### Changed
- GuzzleHttp dependency to ^7.4
- Moved `HTTP_CODES` const to dedicated `Luur\Amplitude\ErrorCodes` class

### Removed
- Logger in `Luur\Amplitude\Amplitude`

### Fixed
- Added correct namespace in tests
- Added missing return types

## [2.0.0] - 2020-12-22
### Changed
- GuzzleHttp dependency to ^7.0.1
- Code to PHP 7.4

## [1.0.0] - 2019-07-10
### Added
- Initial version

 
___
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
