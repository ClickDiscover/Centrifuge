<?php

namespace Flagship\Storage;

use Flagship\Event\EventInterface;
use Flagship\Event\View;
use Flagship\Event\Click;
use Flagship\Model\User;
use Flagship\Util\FunctionQueueInterface;
use Flagship\Util\FunctionQueueTrait;


class SegmentStorage implements FunctionQueueInterface {

    use FunctionQueueTrait;

    protected $writeKey;
    protected $jar;
    protected $log;

    public function __construct($config, CookieJar $jar, \Flagship\Util\Logger $log) {
        $segconf = $config['segment'];
        $options = isset($segconf['options']) ? $segconf['options'] : [];
        // $integrations = isset($segconf['options']) ? $segconf['options'] : [];
        $this->writeKey = $segconf['write.key'];
        $this->setProfilingClass('Segment');
        \Segment::init($this->writeKey, $options);
        $this->jar = $jar;
        $this->log = $log;
    }

    public function page(View $view) {
        if(!$this->identify($view)) {
            return false;
        }
        $context = $this->buildContext($view);
        $properties = $view->properties->all();
        $integrations = ['All' => true];

        $arr = [
            'userId' => $view->getUserId(),
            'name' => $view::SEGMENT_NAME,
            'context' => $context,
            'properties' => $properties,
            'integrations' => $integrations
        ];
        $this->enqueue(function () use ($arr) {
            \Segment::page($arr);
        }, 'page', $view->getId());
        return $arr;
    }

    public function click(Click $click) {
        if(!$this->identify($click)) {
            return false;
        }
        $context = $this->buildContext($click);
        $properties = $click->properties->all();
        $integrations = ['All' => true];

        $arr = [
            'userId' => $click->getUserId(),
            'event' => $click::SEGMENT_NAME,
            'context' => $context,
            'properties' => $properties,
            'integrations' => $integrations
        ];
        $this->enqueue(function () use ($arr) {
            \Segment::track($arr);
        }, 'click', $click->getId());
        return $arr;
    }

    protected function identify(EventInterface $ev) {
        $user = $ev->getUser();
        $userId = $user->getId();
        if (empty($userId)) {
            return false;
        }

        $identifiedBefore = $user->getSegmentId();
        if (empty($identifiedBefore)) {
            $user->setSegmentId($user->getId());
        }

        if (empty($_SESSION['_fp_segment'])) {
            $traits = [];
            $tc = $user->getCookie();
            if (isset($tc)) {
                $traits['visits'] = $tc->getVisitCount();
                $traits['createdAt'] = date("Y-m-d H:i:s", $tc->getCreationTime());
                $lvt = $tc->getLastVisitTime();
                if (isset($lvt)) {
                    $traits['lastVisitTime'] = date("Y-m-d H:i:s", $tc->getLastVisitTime());
                }
                $loct = $tc->getLastOfferClickTime();
                if (isset($loct)) {
                    $traits['lastOfferClickTime'] = date("Y-m-d H:i:s", $tc->getLastOfferClickTime());
                }
            }

            $context = $this->buildContext($ev);
            $this->enqueue(function () use ($user, $context, $traits) {
                \Segment::identify([
                    'userId' => $user->getId(),
                    'context' => $context,
                    'traits' => $traits
                ]);
                $_SESSION['_fp_segment'] = $user->getId();
            }, 'identify', $user->getId());
        }
        return true;
    }

    protected function buildContext(EventInterface $ev) {
        $context = $ev->context->all();
        $ga = $ev->getGoogleId();
        if (isset($ga)) {
            $context['integrations'] = [
                'Google Analytics' => ['clientId' => $ga]
            ];
        }
        return $context;
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
