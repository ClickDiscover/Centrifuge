<?php

namespace Flagship\Model;

use \Slim\Helper\Set;
use \Flagship\Event\EventContext;
use \Flagship\Event\Click;
use \Flagship\Event\View;

class User {

    const AEROSPIKE_KEY = 'users';

    protected $id;
    protected $segmentId;
    protected $googleId;
    protected $cookie;

    // Ids of events
    protected $views;
    protected $clicks;
    protected $viewCount;
    protected $clickCount;
    protected $email;
    protected $creationTime;

    public function __construct(
        $id,
        $cookie,
        $googleId = null
    ) {
        $this->id = $id;
        $this->cookie = $cookie;
        $this->googleId = $googleId;
    }

    /////////////
    // Getters //
    /////////////

    public function getId() {
        return $this->id;
    }

    public function getCookie() {
        return $this->cookie;
    }

    public function getGoogleId() {
        return $this->googleId;
    }

    public function getSegmentId() {
        return $this->segmentId;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getViewCount() {
        return $this->viewCount;
    }

    public function getClickCount() {
        return $this->clickCount;
    }

    // public function getVisitId() {
    //     return $this->cookie->getVisitId();
    // }

    public function getCreationTime() {
        return $this->creationTime;
    }

    // public function getLastVisitTime() {
    //     return $this->cookie->getLastVisitTime();
    // }

    // public function getLastOfferClickTime() {
    //     return $this->cookie->getLastOfferClickTime();
    // }

    /////////////
    // Setters //
    /////////////


    public function setGoogleId($x) {
        $this->googleId = $x;
        return $this;
    }

    public function setSegmentId($x) {
        $this->segmentId = $x;
        return $this;
    }

    public function setCreationTime($x) {
        $this->creationTime = $x;
        return $this;
    }

    public function setClicks($clicks) {
        $this->clicks = $clicks;
        $this->clickCount = count($this->clicks);
        return $this;
    }

    public function setViews($views) {
        $this->views = $views;
        $this->viewCount = count($this->views);
        return $this;
    }

    // Also need to scrub all context information from views/clicks
    // since that's user data.
    public function appendView(View $view) {
        if (isset($this->views)) {
            $this->views[] = $view->getId();
            $this->viewCount++;
        } else {
            $this->views = [$view->getId()];
            $this->viewCount = 1;
        }
    }

    public function appendClick(Click $click) {
        // Have map[Keyword, Click_count] for dirty camp tracking
        if (isset($this->clicks)) {
            $this->clicks[] = $click->getId();
            $this->clickCount++;
        } else {
            $this->clicks = [$click->getId()];
            $this->clickCount = 1;
        }
    }

    private function maybeSet($arr, $key, $myKey) {
        $val = $this->$myKey;
        if (isset($val)) {
            $arr[$key] = $val;
        }
    }

    public function toAerospike(\Aerospike $db) {
        $key = $db->initKey('test', self::AEROSPIKE_KEY, $this->id);

        $data = [
            'id' => $this->id
        ];

        if (isset($this->cookie)) {
            $data['cookie'] = $this->cookie->toAerospikeArray();
        }

        $ga = $this->getGoogleId();
        if (isset($ga)) {
            $data['google.id'] = $ga;
        }
        $seg = $this->getSegmentId();
        if (isset($seg)) {
            $data['segment.id'] = $seg;
        }

        $ct = $this->getCreationTime();
        if (isset($ct)) {
            $data['creation.time'] = $ct;
        }

        $views = $this->views;
        if (isset($views)) {
            $data['views'] = $views;
        }
        $clicks = $this->clicks;
        if (isset($clicks)) {
            $data['clicks'] = $clicks;
        }

        $status = $db->put($key, $data);
        return $status;
    }

    public static function fromAerospike(\Aerospike $db, $cookie, $ga = null) {
        $id = $cookie->getId();
        $user = new User($id, $cookie);
        $key = $db->initKey('test', self::AEROSPIKE_KEY, $id);
        $rc = $db->get($key, $rec);
        if ($rc == \Aerospike::OK) {
            $rec = $rec['bins'];

            if (isset($rec['google.id'])) {
                $user->setGoogleId($rec['google.id']);
            } elseif (isset($ga)) {
                $user->setGoogleId($ga);
            }

            if (isset($rec['segment.id'])) {
                $user->setSegmentId($rec['segment.id']);
            }

            if (isset($rec['creation.time'])) {
                $user->setCreationTime($rec['creation.time']);
            } else {
                $user->setCreationTime($cookie->getCreationTime());
            }

            if (isset($rec['views'])) {
                $user->setViews($rec['views']);
            }
            if (isset($rec['clicks'])) {
                $user->setClicks($rec['clicks']);
            }
        }
        // if ($rc == Aerospike::ERR_RECORD_NOT_FOUND)

        return $user;
    }
}

