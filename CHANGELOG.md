# MODX MenuBuilder - Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.0](https://github.com/LippertComponents/MenuBuilder/compare/v0.2.0...v0.2.1) - 2019-08-12
### Added
- Added 3rd param to MenuBuilder->getMenuAsNestedArray($start_id, $depth, $set_index=true)  
@param bool $set_index ~ if true will set the indexes from the path
    
## [0.2.0](https://github.com/LippertComponents/MenuBuilder/compare/v0.1.2...v0.2.0) - 2019-08-12
### Added
- Add getMenuAsNestedArray and getMenuAsJSON method to the MenuBuilder class, can use to get a complete menu as a single array/json

## [0.1.2](https://github.com/LippertComponents/MenuBuilder/compare/v0.1.1...v0.1.2) - 2019-05-08
### Changed

- Added option to the menuBuilder snippet: viewHiddenFromTree, see Readme for details

## [0.1.1](https://github.com/LippertComponents/MenuBuilder/compare/v0.1.0...v0.1.1) - 2019-03-25
### Changed

- An individual item/resource now uses as default menutitle and then falls back to pagetitle if menutitle is empty.

## [0.1.0](https://github.com/LippertComponents/MenuBuilder/releases/tag/v0.1.0) - 2018-10-18

### Changed

- Converted from a traditional MODX build style extra to a [Orchestrator](https://github.com/LippertComponents/Orchestrator) and Composer build style 
