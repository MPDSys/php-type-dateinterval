# DateInterval
[![Packagist Version](https://img.shields.io/packagist/v/mpd/type-dateinterval)](https://packagist.org/packages/mpd/type-dateinterval)
![GitHub License](https://img.shields.io/github/license/MPDsys/php-type-dateinterval)
![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/mpd/type-dateinterval/php)

Extension and drop-in replacement of DateInterval class adding ISO 8601 compatibility and comparators
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
echo($i->format("%d days") . "\n"); //"2 days"

$b = new DateInterval("PT48H");
$c = new DateInterval("P2D");
echo($b->isExactlyEqual($i)); // false
echo($c->isExactlyEqual($i)); // true

$date = new DateTime("1970-01-01 00:01:00");
echo($b->cmpRelative($c, $date)); // 0 (equal)
$i = new DateInterval("P2DT1S");
echo($c->cmpRelative($i, $date)); // -1 (c < i)
echo($i->cmpRelative($c, $date)); //  1 (i > c
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
    public cmpRelative(\DateInterval $b, ?DateTimeInterface $baseDate = null): int
    public isExactlyEqual(\DateInterval $b): bool 
    public static convertToDurationString(\DateInterval $i): string 
    public static createFromDateString(string $datetime): DateInterval|false
    public static compareRelativeToDate(\DateInterval $a, \DateInterval $b, ?DateTimeInterface $base=null): int
    public static areExactlyEqual(\DateInterval $a, \DateInterval $b): bool
}
```

## Notes
This class is based on php's native DateInterval class, can be used as drop in
replacement. but also inherits some recommendations like 
"properties should be considered read only". 

### isExactlyEqual / areExactlyEqual
Compare intervals by components. 24H != 1D, 24H == 24H, 1D == 1D

### cmpRelative / compareRelativeToDate
Intervals cant be easily compared. For reasons like  
1Y == 365,366D;  1M == 28,29,30,31D;  leap seconds    
But you can compare them exactly from given starting point. 
You can either specify your own or use implicit current time
which gives accurate enough comparison for a lot of cases.  
But yes, in some cases this still might not be kind of comparison you need.  

