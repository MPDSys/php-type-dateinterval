<?php
namespace mpd\Type\DateInterval;

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
}
