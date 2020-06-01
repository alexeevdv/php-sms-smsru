# php-sms-smsru

[![Build Status](https://travis-ci.org/alexeevdv/php-sms-smsru.svg?branch=master)](https://travis-ci.org/alexeevdv/php-sms-smsru) 
[![codecov](https://codecov.io/gh/alexeevdv/php-sms-smsru/branch/master/graph/badge.svg)](https://codecov.io/gh/alexeevdv/php-sms-smsru)
![PHP 7.1](https://img.shields.io/badge/PHP-7.1-green.svg) 
![PHP 7.2](https://img.shields.io/badge/PHP-7.2-green.svg)
![PHP 7.3](https://img.shields.io/badge/PHP-7.3-green.svg)
![PHP 7.4](https://img.shields.io/badge/PHP-7.4-green.svg)

PHP package for sending SMS messages via sms.ru provider

## Usage

```php
use alexeevdv\sms\SmsRu\Exception\Exception as SmsRuException;
use alexeevdv\sms\SmsRu\PhoneNumber;
use alexeevdv\sms\SmsRu\Provider;

$httpClient = new Psr18CompatibleHttpClient();
$requestFactory = new Psr17CompatibleRequestFactory();

$provider = new Provider('Your API key', $httpClient, $requestFactory);
try {
   $messageId = $provider->sendMessage(new PhoneNumber('+1-234-567-89-10'), 'Hello!');
} catch (SmsRuException $e) {
   // Message is not sent
}
```
