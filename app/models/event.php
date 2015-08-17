<?php
require_once dirname(dirname(__DIR__)) . '/config.php';
require CENTRIFUGE_ROOT . '/vendor/autoload.php';

use Symfony\Component\PropertyAccess\PropertyAccess;
use League\Url\Url;
use League\Url\UrlImmutable;


trait PropertyAccessTrait {

    private $data = array();
    // private $accessor = PropertyAccess::createPropertyAccessor(true);

    public function __get($name) {
        return $this->accessor->getValue($this->data, "[{$name}]");
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function toArray() {
        return array_filter($this->data);
    }
}


class UrlContext {
    use PropertyAccessTrait;

    function __construct(
        $host,
        $url,
        $path,
        $query
    ) {
        $this->host      = $host;
        $this->url       = $url;
        $this->path      = $path;
        $this->query     = $query;
    }

    public static function fromRequest(&$request) {
        $url = UrlImmutable::createFromServer($request->server());
        $host = self::hostFromRequest($request->server());
        $query = ($url->getQuery() !== null) ?  $url->getQuery() : false;
        return new UrlContext(
            $host,
            (string) $url->setQuery([]),
            (string) $url->getPath(),
            $query
        );
    }

    public static function hostFromRequest($server) {
        $url = Url::createFromServer($server);
        return ltrim ($url->setScheme(null)->getBaseUrl(), '/');
    }
}

class UserContext {
    use PropertyAccessTrait;

    function __construct(
        $ip        = false,
        $userAgent = false,
        $refer     = false
    ) {
        $this->ip        = $ip;
        $this->userAgent = $userAgent;
        $this->referrer  = $refer;
    }

    public static function fromRequest(&$request) {
        return new UserContext(
            $request->ip(),
            $request->server('HTTP_USER_AGENT', false),
            $request->server('HTTP_REFERER', false)
        );
    }
}

