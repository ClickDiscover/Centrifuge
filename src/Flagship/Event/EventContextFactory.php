<?php

namespace Flagship\Event;

use Flagship\Event\Context\UserContext;
use Flagship\Event\Context\UrlContext;
use Flagship\Event\Context\CampaignContext;

use League\Event\EventInterface;

class EventContextFactory {

    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function createFromRequest($request) {
        $c = $this->config;
        $url  = UrlContext::fromRequest($request);
        $user = UserContext::fromRequest($request);
        $camp = CampaignContext::fromRequest($request, $c['campaign.key'], $c['ad.key']);
        return new EventContext(array(
            'url' => $url,
            'user' => $user,
            'campaign' => $camp
        ));
    }
}

class EventContext extends \ArrayObject {
    // Init is array of Contexts.. they need an interface...
    public function __construct($init) {
        parent::__construct($init, \ArrayObject::ARRAY_AS_PROPS);
    }

    public function get($subcontext, $key, $default = null) {
        if (isset($this[$subcontext]) && isset($this[$subcontext][$key])) {
            return $this[$subcontext][$key];
        }
        return $default;
    }

    public function getMulti($sub, $keys) {
        $out = [];
        $s = $this[$sub];
        foreach ($keys as $k) {
            if (isset($s[$k])) {
                $out[$k] = $s[$k];
            }
        }
        return $out;
    }

    // Cleans up the contexts, ready for serialization
    public function finalize() {
        foreach ($this as $k => $v) {
            $this[$k] = $v->toCleanArray();
        }
    }
}
