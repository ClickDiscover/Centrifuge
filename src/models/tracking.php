<?php
require_once dirname(dirname(__DIR__)) . '/config.php';

class Tracking
{

    public $enable = array();
    public $html = array();

    public function __construct($enable)
    {
        $this->initTracking();
        $this->enable = $enable;
    }

    public static function fromPGArray($arr) {
        $tags = explode(',', trim($arr, '[]'));
        $tags = array_map(function ($x) {
            return preg_replace('/("|\'|\s+)/', '', $x);;
        }, $tags);
        return new Tracking($tags);
    }

    public function getEnabled() {
        return $this->enable;
    }

    public function getTrackingHTML() {
        $out = "";
        foreach ($this->enable as $e) {
            $out .= $this->html[$e];
        }
        return $out;
    }

    protected function initTracking() {
        $this->html['googleAnalytics'] = <<<HTML
<script type="text/javascript">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-64695438-1', 'auto');
  ga('send', 'pageview');
</script>
HTML;

        $this->html['perfectAudience'] = <<<HTML
<script type="text/javascript">
  (function() {
    window._pa = window._pa || {};
    var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;
    pa.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + "//tag.marinsm.com/serve/55976935923b8a9f2000001d.js";
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(pa, s);
  })();
</script>
HTML;

        $segmentKey = SEGMENT_KEY;
        $this->html['segment'] = <<<HTML
<script type="text/javascript">
  !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","group","track","ready","alias","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t){var e=document.createElement("script");e.type="text/javascript";e.async=!0;e.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};analytics.SNIPPET_VERSION="3.0.1";
  analytics.load("$segmentKey");
  }}();
</script>
HTML;
    }
}
