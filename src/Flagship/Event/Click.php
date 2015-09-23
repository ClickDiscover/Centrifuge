<?php
namespace Flagship\Event;

use League\Url\Url;
use Flagship\Model\Lander;
use Flagship\Model\User;


class Click extends BaseEvent {
    const NAME = "OFFER_CLICK";
    const SEGMENT_NAME = "Offer Click";
    const SEGMENT_METHOD = "click";
    const AEROSPIKE_KEY = "clicks";
    const LIBRATO_KEY = "clicks";

    protected $stepId;
    protected $viewId;

    public function __construct(
        $id,
        User $user,
        EventContext $context,
        Lander $lander,
        $stepId
    ) {
        $this->setStepId($stepId);
        parent::__construct($id, $user, $context, $lander);
        $this->callCookieMethod('setLastOfferClickTime', time());
        $this->user->appendClick($this);
    }


    public function setStepId($x) {
        $this->stepId = $x;
    }

    public function setLander($x) {
        parent::setLander($x);
        $offer = $this->lander->offers[$this->stepId];
        $this->viewId = $x->id;

        $this->properties->replace([
            'offer'       => $offer->getName(),
            'offer_id'    => $offer->product->id,
            'step_number' => $this->stepId
        ]);

        if (isset($this->cookie)) {
            $tc = $this->cookie;
            $view = $tc->getLastVisitTime();
            $click = $tc->getLastOfferClickTime();
            if (isset($view) && isset($click)) {
                $td = $click - $view;
                if ($td > 0) {
                    $this->properties->set('time_to_click', $td);
                }
            }
        }
    }
}
