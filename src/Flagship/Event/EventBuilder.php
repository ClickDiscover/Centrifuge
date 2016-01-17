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
        return $this->build("Flagship\Event\View");
    }

    public function buildClick() {
        $this->checkMissing(["stepId"]);
        $c = $this->build("Flagship\Event\Click");
        $c->setStepId($this->stepId);
        return $c;
    }

    protected function build($class) {
        $this->checkMissing([
            "id",
            "user",
            "context"
        ]);
        $this->getProfiler()->start($class::NAME . '.createAndTrack');
        $ev = new $class(
            $this->id,
            $this->user,
            $this->context
        );
        $ev->setProfiler($this->getProfiler());

        if (isset($this->stepId)) {
            $ev->setStepId($this->stepId);
        }

        if (isset($this->lander)) {
            $ev->setLander($this->lander);
        }

        return $ev;
    }


    private function checkMissing($props) {
        foreach ($props as $prop) {
            $var = $this->$prop;
            if (empty($var)) {
                $caller = debug_backtrace()[1]['function'];
                throw new \InvalidArgumentException("EventBuilder::{$caller} is missing {$prop}");
            }
        }
    }
}

