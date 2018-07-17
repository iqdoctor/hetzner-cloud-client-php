A PHP wrapper to be used with [Hetzner Cloud API](https://docs.hetzner.cloud).
==============

[![Build Status](https://travis-ci.com/arkste/hetzner-cloud-client-php.svg?branch=master)](https://travis-ci.com/arkste/hetzner-cloud-client-php)
[![StyleCI](https://styleci.io/repos/141315134/shield?style=flat)](https://styleci.io/repos/141315134)
[![Latest Stable Version](https://poser.pugx.org/arkste/hetzner-cloud-client-php/v/stable)](https://packagist.org/packages/arkste/hetzner-cloud-client-php)
[![Total Downloads](https://poser.pugx.org/arkste/hetzner-cloud-client-php/downloads)](https://packagist.org/packages/arkste/hetzner-cloud-client-php)

A simple wrapper for the Hetzner Cloud API in PHP.

## Features

* Light and fast thanks to lazy loading of API classes
* Extensively tested

## Requirements

* PHP >= 5.5.9
* A [HTTP client](https://packagist.org/providers/php-http/client-implementation)
* A [PSR-7 implementation](https://packagist.org/providers/psr/http-message-implementation)
* (optional) PHPUnit to run tests.

Installation
------------

via [composer](https://getcomposer.org)

```bash
composer require arkste/hetzner-cloud-client-php php-http/guzzle6-adapter
```

Why `php-http/guzzle6-adapter`? We are decoupled from any HTTP messaging client with help by [HTTPlug](http://httplug.io).

You can visit [HTTPlug for library users](http://docs.php-http.org/en/latest/httplug/users.html) to get more information about installing HTTPlug related packages.

## Basic usage of `hetzner-cloud-client-php` client

```php
<?php

use HetznerCloud\HttpClient\Message\ResponseMediator;

// This file is generated by Composer
require_once __DIR__ . '/vendor/autoload.php';

// Create Client and authenticate with your token
$client = \HetznerCloud\Client::create()
    ->authenticate('YOUR_API_TOKEN')
;

// Get all Servers
$servers = $client->servers()->all();

// Get additional information about the last Response
$lastResponse = $client->getLastResponse();
$pagination = ResponseMediator::getPagination($lastResponse);
$apiLimit = ResponseMediator::getApiLimit($lastResponse);
```

From `$client` object, you can access to all Hetzner Cloud.

## Example with Pager

to fetch all actions with pagination:

```php
<?php

// This file is generated by Composer
require_once __DIR__ . '/vendor/autoload.php';

// Create Client and authenticate with your token
$client = \HetznerCloud\Client::create()
    ->authenticate('YOUR_API_TOKEN')
;

// Create ResultPager with Client
$pager = new \HetznerCloud\ResultPager($client);

// Get all Actions
$actions = $pager->fetchAll($client->actions(), 'all');
```

## Versioning

|Version|Hetzner Cloud API Version|
|-------|-------------------------|
|1.x    | V1                      |

## Why no >= PHP 7.0 Features?

This Library aims to be as compatible as possible, thats why it only needs PHP >= 5.5.9 and depends on very few composer packages.

## License

`hetzner-cloud-client-php` is licensed under the MIT License - see the LICENSE file for details

## Credits

This Library is heavily inspired by [php-github-api](https://github.com/KnpLabs/php-github-api) & [php-gitlab-api](https://github.com/m4tthumphrey/php-gitlab-api).

Thanks to Hetzner for the high quality API and documentation.
