# Solarium PHP Solr client library


## What is Solarium?

Solarium is a PHP Solr client library that accurately model Solr concepts. Where many other Solr libraries only handle
the communication with Solr, Solarium also relieves you of handling all the complex Solr query parameters using a
well documented API.

Please see the docs for a more detailed description.
## Installation

**TL;DR**
```bash
composer require solarium/solarium php-http/curl-client guzzlehttp/psr7
```

This library does not have a dependency on Guzzle or any other library that sends HTTP requests. We use the awesome
HTTPlug to achieve the decoupling. We want you to choose what library to use for sending HTTP requests. Consult this list
of packages that support [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation)
find clients to use. For more information about virtual packages please refer to
[HTTPlug](http://docs.php-http.org/en/latest/httplug/users.html).

Example:
```bash
composer require php-http/curl-client
```

You do also need to install a PSR-7 implementation and a factory to create PSR-7 messages (PSR-17 whenever that is
released). You could use Guzzles PSR-7 implementation and factories from php-http:

```bash
composer require guzzlehttp/psr7
```

Now you may install the library by running the following:

```bash
composer require solarium/solarium
```
## More information

* Docs
  http://solarium.readthedocs.io/en/stable/

* Issue tracker   
  http://github.com/solariumphp/solarium/issues

* Contributors    
  https://github.com/solariumphp/solarium/contributors

* License   
  See the COPYING file or view online:  
  https://github.com/solariumphp/solarium/blob/master/COPYING

## Continuous Integration status

* 4.x branch (master) [![Develop build status](https://secure.travis-ci.org/solariumphp/solarium.png?branch=master)](http://travis-ci.org/solariumphp/solarium) [![Coverage Status](https://coveralls.io/repos/solariumphp/solarium/badge.png?branch=master)](https://coveralls.io/r/solariumphp/solarium?branch=master)
* 3.x branch [![Develop build status](https://secure.travis-ci.org/solariumphp/solarium.png?branch=3.x)](http://travis-ci.org/solariumphp/solarium) [![Coverage Status](https://coveralls.io/repos/solariumphp/solarium/badge.png?branch=3.x)](https://coveralls.io/r/solariumphp/solarium?branch=3.x)
* [![SensioLabsInsight](https://insight.sensiolabs.com/projects/292e29f7-10a9-4685-b9ac-37925ebef9ae/small.png)](https://insight.sensiolabs.com/projects/292e29f7-10a9-4685-b9ac-37925ebef9ae)
* [![Total Downloads](https://poser.pugx.org/solarium/solarium/downloads.svg)](https://packagist.org/packages/solarium/solarium)

