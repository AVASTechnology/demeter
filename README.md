Demeter
======
[![PHP Version Require](http://poser.pugx.org/avastechnology/demeter/require/php)](https://packagist.org/packages/avastechnology/demeter)
[![Latest Release](http://poser.pugx.org/avastechnology/demeter/v)](https://packagist.org/packages/avastechnology/demeter)
[![License](http://poser.pugx.org/avastechnology/demeter/license)](https://packagist.org/packages/avastechnology/demeter)

**Demeter is OOP approach for structural code generation.**

The Demeter library provides an Object-Oriented approach to building the structural units required
for code generation. The goal is to make it easy to generate/modify source code.

Since this library is built primarily for developer tasks, it really should not be used on production
sites, rather it should be included as part of the development package set.

**This is a work in progress and only feature complete as has been needed!**

## Installation
Include this library:
```bash
~ composer require avastechnology/demeter --dev
```

## CSS
### Parse a CSS file into components

```php
use AVASTech\Demeter\CSS\Parser;

$parser = new Parser();

$styleSheet = $parser->parse(file_get_contents(__DIR__ . '/test.css'));
```

### Reformat a CSS file into a pretty-print version
```php
use AVASTech\Demeter\CSS\Parser;
use AVASTech\Demeter\CSS\Formats\Factory;

$parser = new Parser();

$styleSheet = $parser->parse(file_get_contents(__DIR__ . '/compact.css'));

file_put_contents(
  __DIR__ . '/pretty.css',
  $styleSheet->render((new Factory())->compactStyleSheet())
);
```

### Reformat a CSS file into a compact version
```php
use AVASTech\Demeter\CSS\Parser;
use AVASTech\Demeter\CSS\Formats\Factory;

$parser = new Parser();

$styleSheet = $parser->parse(file_get_contents(__DIR__ . '/pretty.css'));

file_put_contents(
  __DIR__ . '/compact.css',
  $styleSheet->render((new Factory())->compactStyleSheet())
);
```

