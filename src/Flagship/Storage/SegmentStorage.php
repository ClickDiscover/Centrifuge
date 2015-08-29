<?php

namespace Flagship\Storage;


class SegmentStorage {

    protected $jar;
    protected $log;

    public function __construct($writeKey, CookieJar $jar, \Flagship\Util\Logger $log) {
        \Segment::init($writeKey);
        $this->jar = $jar;
        $this->log = $log;
    }



    public function landingPage($tracking, $lander) {
        $this->identify($tracking);

        $tc = $tracking['context'];
        $user = array_intersect_key($tc['user'], array_flip(['ip', 'user_agent']));
        $camp = array_intersect_key($tc['campaign'], array_flip(['keyword', 'ad']));

        if (isset($tc['campaign']['utm'])) {
            $camp = array_merge($camp, $tc['campaign']['utm']);
        }
        $context = array_merge($user, $camp);

        $properties = array(
            'lander_id' => $lander->id,
            'title' => $lander->notes,
            'website' => $lander->website->name,
        );
        $properties['offer_source'] = $lander->offers[1]->product->source;
        $properties['offer1'] = $lander->offers[1]->getName();
        $properties['offer2'] = $lander->offers[2]->getName();

        $url = array_intersect_key($tc['url'], array_flip(['url', 'path']));
        $properties = array_merge($properties, $url);

        $pg = array(
            'userId' => $tracking['flagship.id'],
            'name' => 'Landing Pageview',
            'properties' => $properties,
            'context' => $context
       );
       \Segment::page($pg);

       return $pg;
    }

    protected function identify($tracking) {
        if (empty($tracking['flagship.id'])) {
            // Could return anon id.
            return false;
        }

        $userId = $tracking['flagship.id'];
        if (empty($_SESSION['_fp_segment']) || !$_SESSION['_fp_segment'] ) {
            if (empty($this->jar->getCookie('_fp_segment'))) {
                Segment::identify([
                    'userId' => $userId
                ]);
                $_SESSION['_fp_segment'] = true;
                $this->jar->setCookie('_fp_segment', $userId, CookieJar::MONTHS);
            }

            $segmentId = $this->jar->getCookie('_fp_segment');
            if (isset($segmentId) && $segmentId != $userId) {
                $this->log->warn('Segment ID differs from User ID', [$userId, $segmentId, $tracking]);
            }
        }
    }
}
