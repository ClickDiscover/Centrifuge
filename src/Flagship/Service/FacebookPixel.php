<?php

namespace Flagship\Service;


class FacebookPixel {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetchForKeyword($keyword) {
        if (isset($keyword)) {
            $sql = "SELECT * FROM fb_pixels WHERE keyword = ?";
            $id = $this->db->fetch('fb_pixels', $keyword, $sql)['fb_id'];

            if (isset($id)) {
                return $this->pixel($id);
            }
        }

        return "";
    }

    public function pixel($id) {
        $tag = <<<HTML
<!-- Facebook Conversion Code for Mom -->
<script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();
window._fbq = window._fbq || [];
_fbq.push(['track', '{$id}', {value: '10.00', currency: 'USD'}]);
</script>
HTML;
        return $tag;

    }
}

