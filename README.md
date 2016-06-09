# atoum/autoloop-extension [![Build Status](https://travis-ci.org/atoum/autoloop-extension.svg?branch=master)](https://travis-ci.org/atoum/autoloop-extension)


The `autoloop` extension allows to automatically run tests in atoum's loop mode when files are modified.

## Example

In the following example, we can see that the test suites are automatically executed when the classes and test classes are modified.

![Demo](doc/demo.gif)

## Install it

Install the extension using [Composer](https://getcomposer.org):

```sh
$ composer require --dev atoum/autoloop-extension
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

Add Composer autoloader in the `.bootstrap.atoum.php` file:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';
```

## Using it

Thee new `--autoloop` option is available when the extension is correctly loaded:

```
--autoloop: Automatically relaunch tests on file modification (implies --loop)
```

So instead of running the loop mode (and having to press `enter` each time tests need to run) like this:

```sh
$ ./vendor/bin/atoum --loop
```

Just run:

```
$ ./vendor/bin/atoum --autoloop
```

All the usual options are available.

## Tips

### Notifications

This extension works well with with atoum's notification.

```php
//.atoum.php

$report = $script->addDefaultReport();

$notifier = new atoum\report\fields\runner\result\notifier\image\libnotify();

$notifier
    ->setSuccessImage('./vendor/atoum/atoum/resources/images/logo/success.png')
    ->setFailureImage('./vendor/atoum/atoum/resources/images/logo/failure.png')
;

$report
    ->addField($notifier)
;
```

With a setup like this, you will not even have to look at your console to see your test results. Here is what it looks like:

![Demo](doc/demo_notifications.gif)


## Links

* [atoum](http://atoum.org)
* [atoum's documentation](http://docs.atoum.org)


## Licence

`autoloop-extension` is released under the MIT License. See the bundled `LICENSE` file for details.
