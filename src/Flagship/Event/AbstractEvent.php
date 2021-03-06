<?php

namespace Flagship\Event;


use \Slim\Helper\Set;

use \Flagship\Container;
use \Flagship\Model\Lander;
use \Flagship\Model\User;
use \Flagship\Storage\LibratoStorage;
use \Flagship\Storage\SegmentStorage;
use \Flagship\Storage\AerospikeNamespace;
use Flagship\Util\Profiler\Profiling;



abstract class AbstractEvent implements EventInterface {

    use Profiling;

    const NAME           = "";
    const SEGMENT_NAME   = "";
    const SEGMENT_METHOD = "";
    const AEROSPIKE_KEY  = self::NAME . "s";
    const LIBRATO_KEY    = self::NAME . "s";

    const FILTER_CONTEXT_CAMPAIGN = ['ad', 'keyword']; // UTM dealt with seperatly
    const FILTER_CONTEXT_USER     = ['ip', 'user_agent'];
    const FILTER_CONTEXT_URL      = ['url', 'path'];

    protected $timestamp;
    protected $id;
    public $context;
    public $properties;
    public $lander;

    protected $eventContexts = [];

    // User Data
    protected $user;

    public function __construct(
        $id,
        User $user,
        EventContext $context
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->context = new Set();
        $this->properties = new Set();
        $this->timestamp = isset($timestamp) ? $timestamp : time();
        $this->setContext($context);
        $this->setProfilingClass(static::AEROSPIKE_KEY);
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
        return $this->user->getId();
    }

    public function getUser() {
        return $this->user;
    }

    public function getCookie() {
        return $this->user->getCookie();
    }

    public function getGoogleId() {
        return $this->user->getGoogleId();
    }

    /////////////
    // Setters //
    /////////////

    public function setContext(EventContext $tc) {
        $tc->finalize();
        $this->eventContexts['user'] = $tc['user'];
        $this->eventContexts['campaign'] = $tc['campaign'];
        $this->eventContexts['url'] = $tc['url'];

        $user = array_intersect_key($tc['user'], array_flip(static::FILTER_CONTEXT_USER));
        $camp = array_intersect_key($tc['campaign'], array_flip(static::FILTER_CONTEXT_CAMPAIGN));
        if (isset($tc['campaign']['utm'])) {
            $camp = array_merge($camp, $tc['campaign']['utm']);
        }

        if (count($camp) > 0) {
            $user['campaign'] = $camp;
        }
        $this->context->replace($user);
        $url = array_intersect_key($tc['url'], array_flip(static::FILTER_CONTEXT_URL));
        $this->properties->replace($url);
    }

    public function setLander(Lander $x) {
        $this->lander = $x;
        $this->properties->replace([
            'lander_id' => $this->lander->id,
            'title' => $this->lander->notes,
            'variants' => $this->lander->variants,
            'website' => $this->lander->website->name,
            'website_id' => $this->lander->website->id,
            'web_namespace' => $this->lander->website->namespace,
            'web_template' => $this->lander->website->templateFile,
            'country' => $this->lander->geo->name,
            'geo_id' => $this->lander->geo->id,
            'vertical' => $this->lander->offers[1]->product->vertical,
            'offer_source' => $this->lander->offers[1]->product->source
        ]);
    }

    //////////////////////
    // Storage Handlers //
    //////////////////////

    public function track(Container $centrifuge) {
        $this->toLibrato  ($centrifuge['librato.performance']);
        $this->toSegment  ($centrifuge['segment']);
        $this->toAerospike($centrifuge['aerospike']);
        $this->aerospikeSummary($centrifuge['aerospike.stats']);
        $this->getProfiler()->stop(static::NAME . '.createAndTrack');
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
        return $segment->$method($this);
    }

    public function toAerospike(AerospikeNamespace $db) {
        $record = [
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'ts' => $this->timestamp
        ];

        if (isset($this->gaId)) {
            $data['google_id'] = $this->gaId;
        }

        if (isset($this->eventContexts['campaign'])) {
            $data['campaign'] = $this->eventContexts['campaign'];
        }

        $data = array_merge($data, $this->properties->all());
        $record = array_merge($record, $data);
        $status = $db->putById(static::AEROSPIKE_KEY, $this->getId(), $record);
    }

    protected function aerospikeSummary(AerospikeNamespace $db) {
        $now = time();
        $interval = 60;
        $bucket = $now - ($now % $interval);
        $rec = $db->fetchById('globals', $bucket);

        if (empty($rec)) {
            $record = [
                'ts' => $bucket,
                static::AEROSPIKE_KEY => 1
            ];
            $rc = $db->putById('globals', $bucket, $record);
        } else {
            $record = $rec['bins'];
            $n = isset($record[static::AEROSPIKE_KEY]) ? $record[static::AEROSPIKE_KEY] : 0;
            $record[static::AEROSPIKE_KEY] = $n + 1;
            $rc = $db->putById('globals', $bucket, $record);
        }
    }

    /////////////
    // Helpers //
    /////////////

    protected function callCookieMethod($method, $arg) {
        $cookie = $this->getCookie();
        if (isset($cookie)) {
            $cookie->$method($arg);
        }
    }
}

