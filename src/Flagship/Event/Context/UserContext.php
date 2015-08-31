<?php

namespace Flagship\Event\Context;

use Flagship\Util\ImmutableProperties;
use Flagship\Util\ArrayConversions;
use Flagship\Util\ArrayConvertible;


class UserContext {

    use ImmutableProperties;
    use ArrayConversions;
    protected $__keyMap = [];

    function __construct(
        $ip        = false,
        $userAgent = false,
        $refer     = false
    ) {
        $this->ip        = $ip;
        $this->userAgent = $userAgent;
        $this->referrer  = $refer;
    }

    public static function fromRequest($request) {
        return new UserContext(
            $request->getIp(),
            $request->getUserAgent(),
            $request->getReferer()
        );
    }
}


