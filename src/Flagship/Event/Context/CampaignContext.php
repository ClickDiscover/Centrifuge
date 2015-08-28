<?php

namespace Flagship\Event\Context;

use Flagship\Util\Logger;
use Flagship\Util\ImmutableProperties;
use Flagship\Util\ArrayConversions;
use Flagship\Util\ArrayConvertible;


class CampaignContext {

    use ImmutableProperties;
    use ArrayConversions;
    protected $__keyMap = [];

    function __construct(
        $ad      = false,
        $keyword = false,
        $utm     = false
    ) {
        $this->ad      = $ad;
        $this->keyword = $keyword;
        $this->utm      = $utm;
    }

    public static function fromRequest($request, $campaignKey, $adKey) {
        $keyword = false;
        $ad = false;
        $utm = [];

        // Everything to lowercase for sanity
        $qs = $request->params();
        // $log = Logger::getInstance();
        // $log->info('test', $qs);
        foreach ($qs as $key => $value) {
            if (strtolower($key) === $campaignKey) {
                $keyword = $value;
            }

            if (strtolower($key) === $adKey) {
                $ad = $value;
            }

            if (substr($key, 0, 4) === 'utm_') {
                $utm[$key] = $value;
            }
        }

        $utm = (count($utm) > 0) ? $utm : false;
        return new CampaignContext($keyword, $ad, $utm);
    }
}

