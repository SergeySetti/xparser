## Xparser
Universal site parser for Laravel 5

[![Latest Stable Version](https://poser.pugx.org/sergeysetti/xparser/v/stable)](https://packagist.org/packages/sergeysetti/xparser)
[![Latest Unstable Version](https://poser.pugx.org/sergeysetti/xparser/v/unstable)](https://packagist.org/packages/sergeysetti/xparser)
[![Total Downloads](https://poser.pugx.org/sergeysetti/xparser/downloads)](https://packagist.org/packages/sergeysetti/xparser)
[![License](https://poser.pugx.org/sergeysetti/xparser/license)](https://packagist.org/packages/sergeysetti/xparser)


### Install

Require this package with composer using the following command:

```bash
composer require sergeysetti/xparser
```

After updating composer, add the service provider to the `providers` array in `config/app.php`

```php
Xparser\XparserServiceProvider::class,
```

Configure your app's database and run 
```bash
php artisan migrate
```

#### Usage

You need to declare at least one Client and one Type. Client is a main properties container for your parsing job. Types are used to detect necessary pages and grab its data.

##### Client example 

```php
use Xparser\Xparser;

class Client extends Xparser
{
    /*
     * Provide the main url for website destination
     * */
    protected $siteUrl = 'http://example.com';

    /*
     * Declare an URL patterns, which will be recognized as useful.
     * */
    protected $urlsToCrawl = [
        '\/?page=[a-zA-Z0-9_-]+',
        '\/?post=[a-z0-9-_]+',
        '\/?category=[a-z-_]+',
    ];

    /*
     * Register your types for the Client
     * */
    public function registerTypes()
    {
        return [
            TypeExample::class,
        ];
    }
}
```

##### Type example 

In your "Type" classes you must specify which page to look for. Type mapped to the page by url schemas. For example, the page with URL schema `\/?post=[a-z0-9-_]+` must be mapped to the `PageClient`, which contains corresponded rule. 

One interesting mandatory in Types is `fields()` - here we do our man job: detect the data fields, take it and post-process if it is necessary. The of this job collected in internal property `$data`

```php
use Xparser\Xparser;

class PostType extends AbstractType
{

    /*
     * Describe the patterns, which URLs will 
     * be mapped to the Type. From this and only pages 
     * we will grab the data
     * 
     * */
    public function urlPatterns()
    {
        return [
            '\/?post=[a-z0-9-_]+'
        ];
    }

    /*
     * Here you always have access to the page HTML through
     * `$this->html()` call. Grab the page data ane way you want.
     * It can be QueryPath library, Symfony DomCrawler or 
     * pure regular expressions. Later grabbed results 
     * can be accessed by the same fields names 
     * by calling `$this->data()` in Type's 'save()' method.
     * 
     * */
    public function fields()
    {
        return collect([
            'title' => function () {
                return QueryPath::qp($this->html(), 'h2')
                                ->text();
            },
            'content' => function () {
                return QueryPath::qp($this->html(), '#content')
                                ->text();
            },
        ]);
    }

    /*
     * Final steps goes here. By calling `$this->data()` we take 
     * collected data and saves it any way we can. Method `data()`
     * return the collection, in whith fields names named corresponding
     * to `fields()` method.
     * 
     * */
    public function save()
    {
        AnyModel::create(
            $this->data()->toArray()
        );
    }

}
```
You can describe any number of Types. Just don't forget do register them in yor Client class. 

After preparation is done, create your Client's instance:

```php
$parser = Xparser::create($client);
```
And run the parsing iteration
```php
dispatch($parser);
```
In first iteration parser grabs useful URLs from main page and saves them in database. In next iterations it takes last processed one from this URLs collection, process it and so on.