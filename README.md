# WordPress Auto Classloader

---

Extends WordPress to autoload functionality. This can be very helpful in Plugin OOP Development.

## Setup

Copy **autoload.php** into you root WordPres directory and add the following line into **wp-settings.php** after the **wp-includes definition**

``` php
require (ABSPATH . 'autoload.php');
```