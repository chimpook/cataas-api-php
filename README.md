# cataas-api-php
Cat as a service API wrapper for PHP.

### Installation

    $ composer require chimpook/cataas-api-php

#### Create an object of the API wrapper:
    <?php
    require __DIR__ . '/vendor/autoload.php';
    use CataasApiPhp\CataasApiPhp;
    $cataas = CataasApiPhp::factory();

#### To get the URL of a cat:
    $url = $cataas->getUrl();

#### To download a cat:
    $cataas->get('randomCat.png');

#### To get a cat and output it to STDOUT
    $cataas->get();

### Examples from the cataas.com:
    $cataas->tag('cute')->html()->get();
    $cataas->tag('eyes')->json()->get();
    $cataas->tag('cute')->get();
    $cataas->gif()->get();
    $cataas->says('Hello, a human!')->get();
    $cataas->says('Hello, a human!')->size(22)->color('green')->get();
    $cataas->type('sq')->get();
    $cataas->filter('negative')->get();
    $cataas->width(360)->get();
    $cataas->height(240)->get();
    $cataas->gif()->says('Hello!')->filter('sepia')->color('orange')->size(40)->type('or')->get();

    $cataas->api()->cats()->tags('cute,eyes')->skip(0)->limit(10)->get();
    $cataas->api()->tags()->get();

### Console oneliners

#### Get a cat and flip it upside down
    php -r 'require __DIR__ . "/vendor/autoload.php"; echo CataasApiPhp\CataasApiPhp::factory()->get();' | cat | convert - -flip flipped_cat.png

#### Get a cat and frame it with a tomato frame
    php -r 'require __DIR__ . "/vendor/autoload.php"; echo CataasApiPhp\CataasApiPhp::factory()->get();' | cat | convert - -bordercolor Tomato -border 10%x10% framed_cat.png
