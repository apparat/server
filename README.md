# apparat/server

Purpose of this module:

1. Object access & retrieval
2. Object search
3. Object filtering
4. Object API
    * `/2016/01/13/123-article/123-1` returns the article payload
    * `/2016/01/13/123-article/123-1/meta` returns the article meta data as YAML / JSON object
    * Could serve different formats depending on the Accept header

## Documentation

Please find the [project documentation](doc/index.md) in the `doc` directory. I recommend [reading it](http://apparat-server.readthedocs.io/) via *Read the Docs*.

## Installation

This library requires PHP 5.6 or later. I recommend using the latest available version of PHP as a matter of principle. It has no userland dependencies.

## Quality

[![Build Status](https://secure.travis-ci.org/apparat/server.svg)](https://travis-ci.org/apparat/server)
[![Coverage Status](https://coveralls.io/repos/apparat/server/badge.svg?branch=master&service=github)](https://coveralls.io/github/apparat/server?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/apparat/server/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/apparat/server/?branch=master)
[![Code Climate](https://codeclimate.com/github/apparat/server/badges/gpa.svg)](https://codeclimate.com/github/apparat/server)
[![Documentation Status](https://readthedocs.org/projects/apparat-server/badge/?version=latest)](http://apparat-server.readthedocs.io/en/latest/?badge=latest)

To run the unit tests at the command line, issue `composer install` and then `phpunit` at the package root. This requires [Composer](http://getcomposer.org/) to be available as `composer`, and [PHPUnit](http://phpunit.de/manual/) to be available as `phpunit`.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
