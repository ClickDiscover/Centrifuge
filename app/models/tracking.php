<?php

class Tracking
{

    protected $enableGoogleAnalytics;
    protected $enablePerfectAudience;
    protected $enable = array();
    protected $html = array();

    public function __construct($enableGA = true, $enablePA = true)
    {
        $this->enable['googleAnalytics'] = $enableGA;
        $this->enable['perfectAudience'] = $enablePA;
        $this->initTracking();
    }

    public static function fromPGArray($arr) {
        $tags = explode(',', trim($arr, '[]'));
        $ga = in_array('googleAnalytics', $tags);
        $pa = in_array('perfectAudience', $tags);
        return new Tracking($ga, $pa);
    }

    public function getTrackingHTML() {
        $out = "";
        $enabled = array_keys(array_filter($this->enable));
        foreach ($enabled as $e) {
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
    }
}
