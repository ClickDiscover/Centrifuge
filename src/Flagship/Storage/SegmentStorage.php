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

    public function page($arr) {
        if(!$this->identify($arr)) {
            return false;
        }
        $arr['integrations'] = ['All' => true];
        \Segment::page($arr);
        return $arr;
    }

    public function track($arr) {
        if(!$this->identify($arr)) {
            return false;
        }
        $arr['integrations'] = ['All' => true];
        \Segment::track($arr);
        return $arr;
    }

    protected function identify($arr) {
        if (empty($arr['userId'])) {
            return false;
        }

        $userId = $arr['userId'];
        if (empty($_SESSION['_fp_segment'])) {
            \Segment::identify([
                'userId' => $userId
            ]);
            $_SESSION['_fp_segment'] = $userId;
        }
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
