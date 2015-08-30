<?php

namespace Flagship\Storage;


class SegmentStorage {

    protected $jar;
    protected $log;

    public function __construct($config, CookieJar $jar, \Flagship\Util\Logger $log) {
        $segconf = $config['segment'];
        $options = isset($segconf['options']) ? $segconf['options'] : [];
        // $integrations = isset($segconf['options']) ? $segconf['options'] : [];
        \Segment::init($segconf['write.key'], $options);
        $this->jar = $jar;
        $this->log = $log;
    }


    public function offerClick($tracking, $lander) {
        if(!$this->identify($tracking)) {
            return false;
        }

        $context = $this->buildContext($tracking);
        $properties =  $this->buildProperties($tracking, $lander);

        if (isset($tracking['cookie'])) {
            $tc = $tracking['cookie'];
            $view = $tc->getLastVisitTime();
            $click = $tc->getLastOfferClickTime();
            if (isset($view) && isset($click)) {
                $properties['time_to_click'] = $click - $view;
            }
        }

        $pg = array(
            'userId' => $tracking['flagship.id'],
            'event' => 'Offer Click',
            'properties' => $properties,
            'context' => $context
       );
       \Segment::track($pg);
    }

    public function landingPage($tracking, $lander) {
        if(!$this->identify($tracking)) {
            return false;
        }

        $context = $this->buildContext($tracking);
        $properties =  $this->buildProperties($tracking, $lander);

        $pg = array(
            'userId' => $tracking['flagship.id'],
            'name' => 'Landing Pageview',
            'properties' => $properties,
            'integrations' => ['All' => true],
            'context' => $context
       );
        \Segment::page($pg);
        return $pg;
    }

    protected function buildProperties($tracking, $lander) {
        $properties = array(
            'lander_id' => $lander->id,
            'title' => $lander->notes,
            'website' => $lander->website->name,
        );
        $properties['offer_source'] = $lander->offers[1]->product->source;
        $properties['offer1'] = $lander->offers[1]->getName();
        $properties['offer2'] = $lander->offers[2]->getName();

        $url = array_intersect_key($tracking['context']['url'], array_flip(['url', 'path']));
        return array_merge($properties, $url);
    }

    protected function buildContext($tracking) {
        $tc = $tracking['context'];
        $user = array_intersect_key($tc['user'], array_flip(['ip', 'user_agent']));
        $camp = array_intersect_key($tc['campaign'], array_flip(['keyword', 'ad']));

        if (isset($tc['campaign']['utm'])) {
            $camp = array_merge($camp, $tc['campaign']['utm']);
        }

        if (isset($tracking['google.id'])) {
            $user['Google Analytics'] = array('clientId' => $tracking['google.id']);
        }

        return array_merge($user, $camp);
    }

    protected function identify($tracking) {
        if (empty($tracking['flagship.id'])) {
            // Could return anon id.
            return false;
        }

        $userId = $tracking['flagship.id'];
        if (empty($_SESSION['_fp_segment'])) {
            \Segment::identify([
                'userId' => $userId
            ]);
            $_SESSION['_fp_segment'] = $userId;
            // $this->jar->setCookie('_fp_segment', $userId, CookieJar::MONTHS);
        }

        // $segmentId = $this->jar->getCookie('_fp_segment');
        // if (isset($segmentId) && $segmentId != $userId) {
        //     $this->log->warn('Segment ID differs from User ID', [$userId, $segmentId, $tracking]);
        // }
        return true;
    }
}
