<?php

namespace Flagship\Event;

use \Flagship\Container;
use \Flagship\Model\Lander;
use \Flagship\Model\User;
use \Flagship\Storage\LibratoStorage;
use \Flagship\Storage\SegmentStorage;
use \Flagship\Storage\AerospikeNamespace;


interface EventInterface {
    public function getId();
    public function getContext();
    public function getUserId();
    public function getUser();
    public function getCookie();
    public function getGoogleId();
    public function setContext(EventContext $tc);
    public function setLander(Lander $x);
    public function track(Container $centrifuge);
    public function toLibrato(LibratoStorage $librato);
    public function toSegment(SegmentStorage $segment);
    public function toAerospike(AerospikeNamespace $db);
}
