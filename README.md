# cataas-api-php
Cat as a service API wrapper for PHP.

### Install

$ composer require chimpook/cataas-api-php

#### To get the URL of a cat:
$url = $cataas->getUrl();

#### To download a cat:
$cataas->get('randomCat.png');

#### To get a cat and output it to STDOUT
$cataas->get();

### Usage variants from cataas.com:

$cataas->tag('cute')->html()->get();

$cataas->tag('cute')->get();

$cataas->gif()->get();

$cataas->says('Hello, human!')->get();

$cataas->says('Hello, human!')->size(22)->color('green')->get();

$cataas->type('sq')->get();

$cataas->filter('negative')->get();

$cataas->width(360)->get();

$cataas->height(240)->get();

$cataas->gif()->says('Hello!')->filter('sepia')->color('orange')->size(40)->type('or')->get();


$cataas->api()->cats()->tags('cute,eyes')->skip(0)->limit(10)->get();

$cataas->api()->tags()->get();

### Console oneliners

#### Get a cat and flip it upside down
php -r 'require "CataasApiPhp.php"; echo CataasApiPhp::factory()->get();' | cat | convert - -flip flipped_cat.png

#### Get a cat and frame it with a tomato frame
php -r 'require "CataasApiPhp.php"; echo CataasApiPhp::factory()->get();' | cat | convert - -bordercolor Tomato -border 10%x10% framed_cat.png

