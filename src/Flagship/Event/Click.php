<?php
namespace Flagship\Event;



class Click extends BaseEvent {
    const NAME = "OFFER_CLICK";
    const SEGMENT_NAME = "Offer Click";
    const SEGMENT_METHOD = "track";
    const AEROSPIKE_KEY = "clicks";

    protected $stepId;

    public function setStepId($x) {
        $this->stepId = $x;
    }

    public function setLander($x) {
        parent::setLander($x);
        $offer = $this->lander->offers[$this->stepId];

        $this->properties->replace([
            'offer' => $offer->getName(),
            'offer.id' => $offer->product->id,
            'step.number' => $this->stepId,
        ]);

        if (isset($this->cookie)) {
            $tc = $this->cookie;
            $view = $tc->getLastVisitTime();
            $click = $tc->getLastOfferClickTime();
            if (isset($view) && isset($click)) {
                $this->properties->set('time_to_click', $click - $view);
            }
        }
    }

    public function getSegmentArray($ignored = false) {
        $s = parent::getSegmentArray(true);
        $s['name'] = self::SEGMENT_NAME;
        return $s;
    }
}
