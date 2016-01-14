# atoum/autoloop-extension

autoloop-extension allows you automaticly run your tests in atoum's loop mode when files are changes.

## Example

Here is an example : we can see that the test files are automaticly executed when the classes and test classes are changed.

![Demo](doc/demo.gif)


## Install it

Install extension using [composer](https://getcomposer.org):

```
composer require --dev atoum/autoloop-extension
```

Enable and configure the extension using atoum configuration file:

```php
<?php

// .atoum.php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$extension = new mageekguy\atoum\autoloop\extension($script);
$extension
    ->setWatchedFiles(array(__DIR__ . '/src'))
    ->addToRunner($runner)
;
```

Add composer autoloader in your `.bootstrap.atoum.php` file:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';
```

## Use it

When the extension is loaded a new option is available : 

```
--autoloop: Automaticly relaunch tests on file change (implies --loop)
```

So, instead of launch the loop mode like this (an having to press enter each time you want to run the tests) : 


```
./vendor/bin/atoum --loop
```

Just run the tests like this : 

```
./vendor/bin/atoum --autoloop
```

All your usual options are available. 

## Links

* [atoum](http://atoum.org)
* [atoum's documentation](http://docs.atoum.org)


## Licence

autoloop-extension is released under the MIT License. See the bundled LICENSE file for details.
