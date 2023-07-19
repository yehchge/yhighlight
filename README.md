# yhighlight

highlight keywords


* [Installation](#installation)
* [Basic Usage](#basic-usage)

## Installation

``` bash
composer require "yehchge/yhighlight"
```

## Basic Usage


``` php
<?php declare(strict_types=1);

include "vendor/autoload.php";

use yehchge\yhighlight\highlight;

$hl = new highlight();

// default debug: false
// $hl->setDebug(true);

// default:
//    - <span class="hl">
//    - </span>
// $hl->setLeftHtml("<div style='background:yellow;'>");
// $hl->setRightHtml("</div>");

$title = "Do you know any funny jokes";
$keywords = array('fun', 'funny', 'joke');

$str = $hl->highlightKeywords($title, $keywords);

echo $str;
```

```html
<style>
.hl {
    color: black;
    background-color: yellow;
    font-weight: bold;
}
</style>
```
