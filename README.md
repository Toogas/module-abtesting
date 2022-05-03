# Magento 2 Module - Toogas AbTesting

    `toogas/module-abtesting`

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Module to create and measure A/B tests based on Page Builder CMS blocks

## Installation

### Type 1: Zip file

 - Unzip the zip file in `<magento_root>/app/code/Toogas`
 - Enable the module by running `php bin/magento module:enable Toogas_AbTesting`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Add the composer repository to the configuration by running `composer config repositories.toogas/module-abtesting vcs https://github.com/Toogas/module-abtesting.git`
 - Install the module composer by running `composer require toogas/module-abtesting:dev-master`
 - enable the module by running `php bin/magento module:enable Toogas_AbTesting`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

No configuration needed, just start creating A/B tests.


## Usage

Create A/B test under Marketing -> Toogas A/B tests section. Then you can add the test wherever you want via CMS widget.
