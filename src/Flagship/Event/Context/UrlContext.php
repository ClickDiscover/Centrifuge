<?php

namespace Flagship\Event\Context;

use Flagship\Util\ImmutableProperties;
use Flagship\Util\ArrayConversions;
use Flagship\Util\ArrayConvertible;



class UrlContext {

    use ImmutableProperties;
    use ArrayConversions;
    protected $__keyMap = [];

    function __construct(
        $host = false,
        $url = false,
        $path = false,
        $query = false
    ) {
        $this->host      = $host;
        $this->url       = $url;
        $this->path      = $path;
        $this->query     = $query;
    }

    public static function fromRequest(&$request) {
        return new UrlContext(
            $request->getHost(),
            $request->getUrl(),
            $request->getPath(),
            $request->params()
        );
    }

}



