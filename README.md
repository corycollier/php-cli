# php-cli
Cli Tools for PHP

[![Build Status](https://travis-ci.org/corycollier/php-cli.svg?branch=master)](https://travis-ci.org/corycollier/php-cli)

## Parser Usage
Using the Parser can be done like this:
```php
use PhpCli\Parser;
$parser = new PhpCli\Parser($argv, $argc);
print_r($parser->getArgs());
```

From the command line, arguments can be passed in like this:
```Shell
php script.php --arg=value arg2=value2
```

## Output Usage
Using the output class can be done like this;
```php
use PhpCli\Output;
$output = new PhpCli\Output;
$output->write("hello world", array(
  'color' => 'red',
));
```
