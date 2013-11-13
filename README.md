# Helthe Monitor

Helthe Monitor is the offical PHP client for Helthe.

## Installation

Helthe Monitor requires that the cURL extension be installed.

### Using composer

You can install the Helthe monitor library using [composer](http://getcomposer.org) by adding the dependency to the library as shown below.

    {
      "require": {
          "helthe/monitor": "dev-master"
      }
    }

## Usage

    use Helthe\Monitor\Client;
    use Helthe\Monitor\ErrorHandler
    

    $client = new Client('http://api.helthe.co', 'your_project_api_key');
    ErrorHandler::register($client, -1);
    
    trigger_error('test');

## Bugs

For bugs or feature requests, please [create an issue](https://github.com/helthe/monitor/issues/new).
