<?php

namespace Flagship\Storage;


class SegmentStorage {

    protected $writeKey;
    protected $jar;
    protected $log;

    public function __construct($config, CookieJar $jar, \Flagship\Util\Logger $log) {
        $segconf = $config['segment'];
        $options = isset($segconf['options']) ? $segconf['options'] : [];
        // $integrations = isset($segconf['options']) ? $segconf['options'] : [];
        $this->writeKey = $segconf['write.key'];
        \Segment::init($this->writeKey, $options);
        $this->jar = $jar;
        $this->log = $log;
    }


    public function offerClick($tracking, $lander) {
        if(!$this->identify($tracking)) {
            return false;
        }

        $stepNumber = $tracking['click.step_id'];
        $context = $this->buildContext($tracking);
        $properties =  $this->buildProperties($tracking, $lander);
        $properties['offer'] = $lander->offers[$stepNumber]->getName();
        $properties['step.number'] = $stepNumber;

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
        $properties['offer1'] = $lander->offers[1]->getName();
        $properties['offer2'] = $lander->offers[2]->getName();

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

    public function scriptTag() {
        $tag = <<<HTML
<script type="text/javascript">
  !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","group","track","ready","alias","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t){var e=document.createElement("script");e.type="text/javascript";e.async=!0;e.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};analytics.SNIPPET_VERSION="3.0.1";
  analytics.load("{$this->writeKey}");
  }}();
</script>
HTML;
        return $tag;
    }
}
