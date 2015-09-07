<?php

namespace Flagship\Event;


use \Slim\Helper\Set;


abstract class BaseEvent {

    const NAME = "";
    const SEGMENT_NAME = "";
    const SEGMENT_METHOD = "";
    const AEROSPIKE_KEY = "";

    protected $id;
    public $context;
    public $properties;
    public $lander;

    // User Data
    protected $userId;
    protected $gaId;
    protected $cookie;

    public function __construct(
        $id,
        $userId,
        $cookie = null,
        $gaId = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->cookie = $cookie;
        $this->context = new Set();
        $this->properties = new Set();
        $this->gaId = $gaId;
    }


    /////////////
    // Getters //
    /////////////

    public function getId() {
        return $this->id;
    }

    public function getContext() {
        return $this->context;
    }

    public function getUserId() {
        return $this->userId;
    }


    public function getCookie() {
        return $this->cookie;
    }

    // public function getProperties() {
    //     return $this->properties;
    // }

    public function getGoogleId() {
        return $this->gaId;
    }

    public function getSegmentArray($integrationsOn = false) {
        $c = [
            'userId' => $this->getUserId(),
            'properties' => $this->properties->all(),
            'context' => $this->context->all()
        ];

        if ($integrationsOn) {
            $c['integrations'] = ['All' => true];
        }
        return $c;
    }

    /////////////
    // Setters //
    /////////////

    // public function setId($x) {
    //     $this->id = $x;
    // }

    public function setContext(EventContext $tc) {
        $tc->finalize();
        $user = array_intersect_key($tc['user'], array_flip(['ip', 'user_agent']));
        $camp = array_intersect_key($tc['campaign'], array_flip(['keyword', 'ad']));
        if (isset($tc['campaign']['utm'])) {
            $camp = array_merge($camp, $tc['campaign']['utm']);
        }

        $this->context->replace(array_merge($user, $camp));
    }

    // public function setUserId($x) {
    //     $this->userId = $x;
    // }

    // public function setProperties($x) {
    //     $this->properties = $x;
    // }

    public function setCookie($x) {
        $this->cookie = $x;
    }

    public function setGoogleId($x) {
        if(isset($x)) {
            $this->gaId = $x;
            $this->context->set("Google Analytics", ['clientId' => $this->gaId]);
        }
    }

    public function setLander($x) {
        $this->lander = $x;
        $this->properties->replace([
            'lander.id' => $this->lander->id,
            'title' => $this->lander->notes,
            'website' => $this->lander->website->name,
            'website.id' => $this->lander->website->id,
            'vertical' => $this->lander->offers[1]->product->vertical,
            'offer.source' => $this->lander->offers[1]->product->source
        ]);
    }
}

