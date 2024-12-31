# DateInterval
![Packagist Version](https://img.shields.io/packagist/v/mpd/type-dateinterval)
![GitHub License](https://img.shields.io/github/license/MPDsys/php-type-dateinterval)
![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/mpd/type-dateinterval/php)

Extension of DateInterval class adding ISO 8601 compatibility, while maintaining interoperability
with php DateInterval
## Installation
 `composer require mpd/type-dateinterval`
 
## Usage
```php
require_once 'vendor/autoload.php';

use mpd\Type\DateInterval\DateInterval;

$i = new DateInterval("P2Y3M1DT8H4M3S");
echo($i->toDuration() . "\n"); //"P2Y3M1DT8H4M3S"

// see https://www.php.net/manual/en/dateinterval.createfromdatestring.php
$i = DateInterval::createFromDateString("+2 days");
echo($i->toDuration() . "\n"); //"P2D"

// see https://www.php.net/manual/en/dateinterval.format.php
$i->format("%d days"); //"2 days"
```


```php
class DateInterval {
    /* Properties */
    public int $y;
    public int $m;
    public int $d;
    public int $h;
    public int $i;
    public int $s;
    public float $f;
    public int $invert;
    public mixed $days;
    public bool $from_string;
    public string $date_string;
    /* Methods */
    public __construct(string $duration)
    public toDuration(): string
    public format(string $format): string
    public static convertToDurationString(\DateInterval $i): string 
    public static createFromDateString(string $datetime): DateInterval|false
}
```