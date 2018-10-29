# Console Runner

An extension for running console commands on background in Yii framework.  
This is a fork of `https://github.com/vova07/yii2-console-runner-extension`, so all 
credits go to it's author, we just forked it to add PHP 7 features and a few additional checks.

## Installation

Add the following to `require` section of your `composer.json`:

```
"twisted1919/yii2-console-runner-extension": "*"
```

Then do `composer install`.

## Usage

##### Imported class:

```php
use twisted1919\console\ConsoleRunner;
$cr = new ConsoleRunner(['file' => '@my/path/to/yii']);
$cr->run('controller/action param1 param2 ...');
```

##### Application component:

```php
// config.php
...
components [
    'consoleRunner' => [
        'class' => twisted1919\console\ConsoleRunner::class,
        'file' => '@my/path/to/yii' // or an absolute path to console file
    ]
]
...

// some-file.php
Yii::$app->consoleRunner->run('controller/action param1 param2 ...');
```

### Running Tests
```bash
$ phpunit
```
