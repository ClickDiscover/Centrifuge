<?php

namespace Flagship\Middleware;

use Slim\Slim;
use Slim\Middleware;
use Slim\Route;

use Flagship\Container;
use Flagship\Util\Profiler\Profiling;


class ScriptMiddleware extends Middleware {

    use Profiling;

    protected $scripts = [];

    protected $siteDomain;
    protected $piwikHost;
    protected $countlyHost;

    protected $insertTag = '</body>';

    public function __construct() {
        // $this->setProfiler($container['profiler']);
        // $this->setProfilingClass('ScriptMiddleware');
        $this->piwikHost = '52.26.98.211';
        $this->addScript($this->piwik());
    }

    public function addScript($s) {
        $this->scripts[] = $s;
    }

    public function call () {
        $this->next->call();

        $res = $this->app->response;
        if(!$res->isSuccessful()) {
            return false;
        }

        if (stripos($res->header('Content-Type'), 'html') !== false) {
            $newHtml = $this->insertScriptTags($res->body());
            $res->body($newHtml);
        }
    }

    protected function insertScriptTags($html) {
        $pos = mb_strripos($html, $this->insertTag);
        $tags = $this->buildScriptTags();
        if ($pos === false) {
            $html .= $tags;
        } else {
            $html = mb_substr($html, 0, $pos) . $tags . mb_substr($html, $pos);
        }
        return $html;
    }

    protected function buildScriptTags() {
        return implode("\n", $this->scripts);
    }

    public function countly() {
        $countly = <<<HTML
<script type="text/javascript" src="/static/admin/countly.js"></script>
<script type="text/javascript">
Countly.init({
    app_key: "560c189025e4082b0029df50",
    url: "http://192.168.99.100:32780", //or none for default countly cloud
    debug:true
})
console.log("Countly");
Countly.begin_session();
//add any events you want like pageView
// Countly.add_event({
    // "key": "pageView",
        // "segmentation": {
        // "url": window.location.pathname
    // }
// });
Countly.track_pageview();
window.onunload = function(){ Countly.end_session(); };
</script>
HTML;
        return $countly;
    }

    public function piwik() {

        $host = $this->piwikHost;
        $piwik = <<<HTML
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//$host/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//$host/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
HTML;
        return $piwik;
    }

}
