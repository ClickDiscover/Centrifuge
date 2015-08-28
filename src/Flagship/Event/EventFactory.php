<?php

namespace Flagship\Event;

use Flagship\Event\Context\UserContext;
use Flagship\Event\Context\UrlContext;
use Flagship\Event\Context\CampaignContext;

use League\Event\EventInterface;

class EventFactory {

    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function createFromRequest($request) {
        $c = $this->config;
        $url  = UrlContext::fromRequest($request);
        $user = UserContext::fromRequest($request);
        $camp = CampaignContext::fromRequest($request, $c['campaign.key'], $c['ad.key']);
        return new Event(array(
            'url' => $url,
            'user' => $user,
            'campaign' => $camp
        ));
    }
}

class Event extends \ArrayObject {
    // protected $id = false;
    // protected $userId = false;
    // protected $gaId = false;

    // Init is array of Contexts.. they need an interface...
    public function __construct($init) {
        parent::__construct($init, \ArrayObject::ARRAY_AS_PROPS);
    }

    public function toArray() {
        $clean = [];
        foreach ($this as $k => $v) {
            $clean[$k] = $v->toCleanArray();
        }
        return $clean;
    }
}
