# Helthe Monitor [![Build Status](https://secure.travis-ci.org/helthe/Monitor.png?branch=master)](http://travis-ci.org/helthe/Monitor) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/helthe/Monitor/badges/quality-score.png?s=f09511b62eaeaafcc8c1d8cdb8a7149384a2bd94)](https://scrutinizer-ci.com/g/helthe/Monitor/)

Helthe Monitor is the offical PHP client for Helthe.

## Installation

Helthe Monitor requires that the cURL extension be installed.

### Using composer

Add the following in your `composer.json`:

```json
{
  "require": {
      "helthe/monitor": "dev-master"
  }
}
```

## Usage

```php
use Helthe\Monitor\Monitor


Monitor::enable(array(
    'api_key'     => 'your_project_api_key',
    'application' => array(
        'root_directory' => __DIR__
    )
));
```

## Bugs

For bugs or feature requests, please [create an issue](https://github.com/helthe/monitor/issues/new).
