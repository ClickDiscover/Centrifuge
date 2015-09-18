<?php
namespace Flagship\Event;



class View extends BaseEvent {
    const NAME = "PAGE_VIEW";
    const SEGMENT_NAME = "Landing Pageview";
    const SEGMENT_METHOD = "page";
    const AEROSPIKE_KEY = "views";
    const LIBRATO_KEY = "views";


    public function setLander($x) {
        parent::setLander($x);
        $this->properties->replace([
            'offer1' => $this->lander->offers[1]->getName(),
            'offer1_id' => $this->lander->offers[1]->product->id,
            'offer2' => $this->lander->offers[2]->getName(),
            'offer2_id' => $this->lander->offers[2]->product->id
        ]);
    }
}
