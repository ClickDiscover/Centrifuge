<?php

namespace Flagship\Event;

use \Flagship\Model\Lander;
use \Flagship\Model\User;
use Flagship\Util\Profiler\Profiling;


class EventBuilder {

    use Profiling;

    protected $id;
    protected $user;
    protected $context;
    protected $lander;
    protected $stepId;

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }

    public function setContext(EventContext $context) {
        $this->context = $context;
        return $this;
    }

    public function setLander($lander) {
        $this->lander = $lander;
        return $this;
    }

    public function setStepId($stepId) {
        $this->stepId = $stepId;
        return $this;
    }

    public function buildView() {
        if (
            empty($this->id)      ||
            empty($this->user)    ||
            empty($this->context)
        ) {
            throw new \InvalidArgumentException("EventBuilder::buildView is missing something");
        }

        $this->getProfiler()->start(View::AEROSPIKE_KEY . '.createAndTrack');
        $ev = new View(
            $this->id,
            $this->user,
            $this->context
        );
        $ev->setProfiler($this->getProfiler());

        if (isset($this->lander)) {
            $ev->setLander($this->lander);
        }
        return $ev;
    }

    public function buildClick() {
        if (
            empty($this->id)      ||
            empty($this->user)    ||
            empty($this->context) ||
            empty($this->stepId)
        ) {
            throw new \InvalidArgumentException("EventBuilder::buildClick is missing something: ". print_r(array_keys(get_object_vars($this)), 1));
        }

        $this->getProfiler()->start(Click::AEROSPIKE_KEY . '.createAndTrack');
        $ev = new Click(
            $this->id,
            $this->user,
            $this->context,
            $this->stepId
        );
        $ev->setProfiler($this->getProfiler());
        if (isset($this->lander)) {
            $ev->setLander($this->lander);
        }
        return $ev;
    }
}

