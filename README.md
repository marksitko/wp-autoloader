# WordPress Auto ClassLoader

Extends WordPress to autoload functionality. This can be very helpful in Plugin OOP Development.

## Setup

Copy `autoload.php` into you WordPres root directory and add the following line into `wp-settings.php` after the `wp-includes definition`

``` php
require (ABSPATH . 'autoload.php');
```

## Example of Plugin Development Usage

Add to your bootstrap Pluginfile the following line directly after defining your namespace.

```php
use db\wp\loader\ClassLoader;
```

Then add your namespace and source directory to the ClassLoader like this:

```php
ClassLoader::instance()->add(__NAMESPACE__, __DIR__ . '/src');
```

Now it is possible to load classes without require or include the files. Just specify the namespace and the ClassName you want to import.

Please take care to define your namespaces like your folder, filename and ClassName structure in your `/src` directory.