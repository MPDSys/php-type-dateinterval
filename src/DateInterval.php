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
        $d = $this->invert?'-P':'P';
        if($this->y) $d .= $this->y . 'Y';
        if($this->m) $d .= $this->m . 'M';
        if($this->d) $d .= $this->d . 'D';
        $t = '';
        if($this->h) $t .= $this->h . 'H';
        if($this->i) $t .= $this->i . 'M';
        if($this->s) $t .= $this->s . 'S';

        if(!empty($t)) $d .= 'T' . $t;
        return $d;
    }
}
