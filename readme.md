## The Laravel View Control Package

...

### Requirements

-   PHP 5.6+
-   Laravel 5.2+
-   Wa72\HtmlPageDom
-   Symfony\Components\DomCrawler
-   Symfony\Components\CssSelector


### Install

Require this package with composer using the following command:

```bash
composer require rephole/laravel-view-control
```

After updating composer, add the service provider to the `providers` array in `config/app.php`

```php
Rephole\ViewControl\ViewControlServiceProvider::class,
```

### License

The Laravel View Control Package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
