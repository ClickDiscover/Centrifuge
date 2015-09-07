<?php

namespace Flagship\Event;


use \Slim\Helper\Set;

use \Flagship\Storage\LibratoStorage;
use \Flagship\Storage\SegmentStorage;

abstract class BaseEvent {

    const NAME = "";
    const SEGMENT_NAME = "";
    const SEGMENT_METHOD = "";
    const AEROSPIKE_KEY = "";
    const LIBRATO_KEY = "";

    const FILTER_CONTEXT_CAMPAIGN = ['ad', 'keyword']; // UTM dealt with seperatly
    const FILTER_CONTEXT_USER     = ['ip', 'user_agent'];
    const FILTER_CONTEXT_URL      = ['url', 'path'];

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

    public function getSegmentArray() {
        $c = [
            'userId' => $this->getUserId(),
            'properties' => $this->properties->all(),
            'context' => $this->context->all()
        ];
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
        $user = array_intersect_key($tc['user'], array_flip(static::FILTER_CONTEXT_USER));
        $camp = array_intersect_key($tc['campaign'], array_flip(static::FILTER_CONTEXT_CAMPAIGN));
        if (isset($tc['campaign']['utm'])) {
            $camp = array_merge($camp, $tc['campaign']['utm']);
        }

        $this->context->replace(array_merge($user, $camp));

        $url = array_intersect_key($tc['url'], array_flip(static::FILTER_CONTEXT_URL));
        $this->properties->replace($url);
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

    public function toLibrato(LibratoStorage $librato) {
        $librato->total(static::LIBRATO_KEY);
        $landerId = isset($this->lander) ? $this->lander->id : null;
        $keyword = $this->context->get('keyword');
        $ad = $this->context->get('ad');

        if (isset($landerId)) {
            $librato->breakout('lander', $landerId, static::LIBRATO_KEY);
        }
        if (isset($keyword)) {
            $librato->breakout('keyword', $keyword, static::LIBRATO_KEY);
        }
        if (isset($ad)) {
            $librato->breakout('ad', $ad, static::LIBRATO_KEY);
        }
    }

    public function toSegment(SegmentStorage $segment) {
        $method = static::SEGMENT_METHOD;
        echo static::SEGMENT_METHOD;
        return $segment->$method($this->getSegmentArray());
    }
}

