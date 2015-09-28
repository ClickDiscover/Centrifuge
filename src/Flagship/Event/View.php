<?php
namespace Flagship\Event;

use Flagship\Model\User;
use Flagship\Model\Lander;


class View extends AbstractEvent {
    const NAME = "view";
    const SEGMENT_NAME = "Landing Pageview";
    const SEGMENT_METHOD = "page";


    public function __construct(
        $id,
        User $user,
        EventContext $context
    ) {
        parent::__construct($id, $user, $context);
        $this->callCookieMethod('setLastVisitTime', time());
        $this->user->appendView($this);
    }

    public function setLander(Lander $x) {
        parent::setLander($x);
        $this->properties->replace([
            'offer1' => $this->lander->offers[1]->getName(),
            'offer1_id' => $this->lander->offers[1]->product->id,
            'offer2' => $this->lander->offers[2]->getName(),
            'offer2_id' => $this->lander->offers[2]->product->id
        ]);
    }
}
