# Helthe Monitor

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
