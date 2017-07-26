# Frame Validator
Library used to check urls and tell whether they can be embedded using iframe by any website, according to the server response headers.

## Installation

```
 composer require exfriend/frame-validator
```

## Usage

```
<?php

require 'vendor/autoload.php';
 
if ( \Exfriend\FrameValidator\Validator::make( 'https://example.com' )->supportsIframes() )
{
    echo 'frameable';
}
else
{
    echo 'unframeable';
}
 
```

### Overriding curl options

```
<?php
 
require 'vendor/autoload.php';
 
use Exfriend\FrameValidator\Validator;
 
$v = Validator::make( 'https://example.com' )->withCurlOptions( [
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
] );
 
echo $v->supportsIframes();
 
```

### Contributing

You are welcome!