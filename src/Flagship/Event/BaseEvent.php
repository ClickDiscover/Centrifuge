<?php

namespace Flagship\Event;

use League\Event\EventInterface;

// abstract class BaseEvent implements EventInterface {
class BaseEvent {
    protected $id = false;
    protected $userId = false;
    protected $gaId = false;
    public $userContext;
    public $urlContext;

    public function __construct($request) {
        $this->urlContext = Context\UrlContext::fromRequest($request);
        $this->userContext = Context\UserContext::fromRequest($request);
    }

}
