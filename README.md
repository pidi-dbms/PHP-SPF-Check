# PHP-SPF-Check
[![Build Status](https://travis-ci.org/Mika56/PHP-SPF-Check.svg?branch=master)](https://travis-ci.org/Mika56/PHP-SPF-Check)

Simple library to check an IP address against a domain's [SPF](http://www.openspf.org/) record

## Installation
This library is available through Composer.
Run `composer require mika56/spfcheck` or add this to your composer.json:
```json
{
  "require": {
    "mika56/spfcheck": "dev-master"
  }
}
```

## Usage
Create a new instance of SPFCheck. The constructor requires a DNSRecordGetterInterface to be passed. Currently, only DNSRecordGetter exists, which uses PHP's DNS functions to get data.
```php
<?php
use Mika56\SPFCheck\SPFCheck;
use Mika56\SPFCheck\DNSRecordGetter;

$checker = new SPFCheck(new DNSRecordGetter());
var_dump($checker->isIPAllowed('127.0.0.1', 'test.com'));
```

Return value is one of `SPFCheck::RESULT_PASS`, `SPFCheck::RESULT_FAIL`, `SPFCheck::RESULT_SOFTFAIL`, `SPFCheck::RESULT_NEUTRAL`, `SPFCheck::RESULT_NONE`, `SPFCheck::RESULT_PERMERROR`, `SPFCheck::RESULT_TEMPERROR`

## Missing features
A few features are missing from this library at the moment. Here's a partial list of those features:
* [Section 3.2 of RFC7208](https://tools.ietf.org/html/rfc7208#section-3.2) on having multiple SPF records on a domain
* [Section 3.3 of RFC7208](https://tools.ietf.org/html/rfc7208#section-3.2) on having multiple strings in a single SPF record
* [Section 6.2 of RFC7208](https://tools.ietf.org/html/rfc7208#section-6.2) on the `exp` modifier
* [Section 7 of RFC7208](https://tools.ietf.org/html/rfc7208#section-7) on macros