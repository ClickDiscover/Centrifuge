<?php
namespace Flagship\Event;

use League\Url\Url;
use Flagship\Model\Lander;
use Flagship\Model\User;


class Click extends AbstractEvent {
    const NAME = "clicks";
    const SEGMENT_NAME = "Offer Click";
    const SEGMENT_METHOD = "click";

    protected $stepId;
    protected $viewId;

    public function __construct(
        $id,
        User $user,
        EventContext $context,
        $stepId
    ) {
        $this->setStepId($stepId);
        parent::__construct($id, $user, $context);
        $this->callCookieMethod('setLastOfferClickTime', time());
        $this->user->appendClick($this);
    }


    public function setStepId($x) {
        $this->stepId = $x;
    }

    // setStepId should be called before this
    public function setLander(Lander $x) {
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
