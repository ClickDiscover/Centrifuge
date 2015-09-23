<?php

namespace Flagship\Event;

use \Flagship\Model\Lander;
use \Flagship\Model\User;


class EventBuilder {

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

    public function setLander(Lander $lander) {
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
            empty($this->context) ||
            empty($this->lander)
        ) {
            throw new \InvalidArgumentException("EventBuilder::buildView is missing something");
        }

        return new View(
            $this->id,
            $this->user,
            $this->context,
            $this->lander
        );
    }

    public function buildClick() {
        if (
            empty($this->id)      ||
            empty($this->user)    ||
            empty($this->context) ||
            empty($this->lander)  ||
            empty($this->stepId)
        ) {
            throw new \InvalidArgumentException("EventBuilder::buildClick is missing something: ". print_r(array_keys(get_object_vars($this)), 1));
        }

        return new Click(
            $this->id,
            $this->user,
            $this->context,
            $this->lander,
            $this->stepId
        );
    }
}

