<?php

namespace Flagship\Storage;


class TrackingCookie {

    const KEY = '_fp';
    const VALUE_PREFIX = 'FP';

    protected $id;
    protected $creationTime;
    protected $visitCount;
    protected $lastVisitTime;
    protected $lastOfferClickTime; // Not a bad idea
    protected $visitId;

    // protected $cookieJar;

    public function __construct(
        // $cookieJar,
        $id,
        $creationTime,
        $visitCount,
        $lastVisitTime = null,
        $lastOfferClickTime = null
    ) {
        // $this->cookieJar = $cookieJar;
        $this->id = $id;
        $this->creationTime = $creationTime;
        $this->visitCount = $visitCount;
        $this->lastVisitTime = $lastVisitTime;
        $this->lastOfferClickTime = $lastOfferClickTime;
    }

    /////////////
    // Getters //
    /////////////

    public function getId() {
        return $this->id;
    }

    public function getVisitCount() {
        return $this->visitCount;
    }

    public function getVisitId() {
        return $this->visitId;
    }

    public function getCreationTime() {
        return $this->creationTime;
    }

    public function getLastVisitTime() {
        return $this->lastVisitTime;
    }

    public function getLastOfferClickTime() {
        return $this->lastOfferClickTime;
    }


    /////////////
    // Setters //
    /////////////


    private function setVisitCount($x) {
        $this->visitCount = $x;
        return $this;
    }

    public function incrementVisitCount() {
        // echo "VI";
        $this->setVisitCount($this->visitCount + 1);
        // var_dump($this->visitCount);
        return $this;
    }

    public function setVisitId($x) {
        $this->visitId = $x;
        return $this;
    }

    public function setLastVisitTime($x) {
        $this->lastVisitTime = $x;
        return $this;
    }

    public function setLastOfferClickTime($x) {
        $this->lastOfferClickTime = $x;
        return $this;
    }

    ///////////////////
    // Serialization //
    ///////////////////

    public function toArray() {
        return array(
            self::VALUE_PREFIX,
            $this->id,
            $this->creationTime,
            (string) $this->visitCount,
            $this->lastVisitTime,
            $this->lastOfferClickTime
        );
    }

    public function toAerospikeArray() {
        $rec = [
            'id' => $this->id,
            'creation.ts' => $this->creationTime,
            'visit.count' => $this->visitCount
        ];
        if (isset($this->lastVisitTime)) {
            $rec['visit.ts'] = $this->lastVisitTime;
        }
        if (isset($this->lastOfferClickTime)) {
            $rec['offer.ts'] = $this->lastOfferClickTime;
        }
        return $rec;
    }


    public function pretty() {
        $out = [];
        $out['Cookie Value'] = $this->toCookie();
        $out['Cookie Key'] = self::KEY;
        $out['ID'] = $this->id;
        $out['Created at'] = date("Y-m-d H:i:s", $this->creationTime);
        $out['Visit Counts'] = $this->visitCount;

        if (isset($this->lastVisitTime)) {
            $out['lastVisitTime'] = date("Y-m-d H:i:s", $this->lastVisitTime);
        }

        if (isset($this->lastOfferClickTime)) {
            $out['lastOfferClickTime'] = date("Y-m-d H:i:s", $this->lastOfferClickTime);
        }

        return $out;
    }

    public function toCookie() {
        return implode('.', array_filter($this->toArray()));
    }

    /////////////////////
    // Factory Methods //
    /////////////////////


    public static function getOrCreate($cookieValue, $id) {
        if (empty($cookieValue)) {
            return self::create($id);
        } else {
            return self::fromCookie($cookieValue);
        }
    }

    public static function create($id) {
        return new TrackingCookie($id, time(), 0);
    }

    public static function fromCookie($str) {
        $parts = explode('.', $str);
        if (count($parts) < 4 || $parts[0] !== self::VALUE_PREFIX) { // Could also do strlen($parts[0]) != self::LENGTH_VISITOR_ID
            return null;
        }
        array_shift($parts);

        $id = $parts[0];
        $creationTs = $parts[1];
        $count = (int) $parts[2];
        $t = new TrackingCookie($id, $creationTs, $count);

        if (isset($parts[3]) && $parts[3] !== "0") {
            $t->setLastVisitTime($parts[3]);
        }

        if (isset($parts[4])) {
            $t->setLastOfferClickTime($parts[4]);
        }

        return $t;
    }
}


