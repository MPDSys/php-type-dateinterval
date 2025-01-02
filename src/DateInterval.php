<?php
namespace mpd\Type\DateInterval;

use DateTimeInterface;

/**
 * @inheritDoc
 */
class DateInterval extends \DateInterval
{
    /**
     * Create optimised creation (duration) string according to ISO 8601
     *
     * @return string
     */
    public function toDuration(): string {
        return self::convertToDurationString($this);
    }

    /**
     * Compares interval size relative to given starting date
     *
     * @param \DateInterval $b
     * @param ?DateTimeInterface $baseDate starting point or now when null
     * @return int -1 if < b | if equals b | 1 when
     */
    public function cmpRelative(\DateInterval $b, ?DateTimeInterface $baseDate = null): int {
        return self::compareRelativeToDate($this, $b, $baseDate);
    }

    /**
     * Check if all components are equal.
     *
     * Note 24H != 1D. 24H equals only to 24H.
     * For more benevolent comparison check cmpRelative
     *
     * @param \DateInterval $b
     * @return bool
     */
    public function isExactlyEqual(\DateInterval $b): bool {
        return self::AreExactlyEqual($this, $b);
    }

    /**
     * @inheritDoc
     */
    public static function createFromDateString($datetime): DateInterval|false {
        /* sadly this method in parent returns always self not child,
           so here comes this hack. improvements welcome */
        $par = parent::createFromDateString($datetime);
        $par = serialize($par);
        $par = preg_replace('/^O:\d+:"DateInterval"/', 'O:' . strlen(static::class) . ':"' . static::class . '"', $par);
        return unserialize($par);
    }

    /**
     * Create optimised creation (duration) string according to ISO 8601
     *
     * @param \DateInterval $i
     * @return string
     */
    public static function convertToDurationString(\DateInterval $i): string {
        $d = $i->invert?'-P':'P';
        if($i->y) $d .= $i->y . 'Y';
        if($i->m) $d .= $i->m . 'M';
        if($i->d) $d .= $i->d . 'D';
        $t = '';
        if($i->h) $t .= $i->h . 'H';
        if($i->i) $t .= $i->i . 'M';
        if($i->s) $t .= $i->s . 'S';

        if(!empty($t)) $d .= 'T' . $t;
        return $d;
    }

    /**
     * Compares two DateIntervals relative to given starting date or now
     *
     * @param \DateInterval $a
     * @param \DateInterval $b
     * @param ?DateTimeInterface $base Date To calculate from
     * @return int a<b => -1 | a=b => 0 | a > b => 1
     */
    public static function compareRelativeToDate(\DateInterval $a, \DateInterval $b, ?DateTimeInterface $base=null): int {
        if($base === null)
            $base = new \DateTimeImmutable();
        else // ensure immutability
            $base = \DateTimeImmutable::createFromInterface($base);
        $da = $base->add($a);
        $db = $base->add($b);
        return $da <=> $db;
    }

    /**
     * Check for equality of components.
     *
     * Note 24H != 1D . 24H equals only 24H and 1D to 1D
     * if you want 24H to equal 1D use compareRelativeToDate
     *
     * @param \DateInterval $a
     * @param \DateInterval $b
     * @return bool
     */
    public static function areExactlyEqual(\DateInterval $a, \DateInterval $b): bool {
        return
            $a->y == $b->y &&
            $a->m == $b->m &&
            $a->d == $b->d &&
            $a->h == $b->h &&
            $a->i == $b->i &&
            $a->s == $b->s &&
            $a->f == $b->f &&
            $a->invert == $b->invert;
    }
}
