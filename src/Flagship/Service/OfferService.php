<?php

namespace Flagship\Service;

use Flagship\Model\OfferLink;
use Flagship\Service\AdexOfferService;
use Flagship\Service\NetworkOfferService;

class OfferService {

    const NETWORK_TYPE = 'network';
    const ADEX_TYPE = 'adexchange';

    protected $network;
    protected $adex;
    protected $urlFor;

    public function __construct(NetworkOfferService $network, AdexOfferService $adex, $urlForCallback = false) {
        $this->network = $network;
        $this->adex = $adex;
        $this->urlFor = $urlForCallback;
    }

    public function setUrlFor($closure) {
        $this->urlFor = $closure;
    }

    public function fetch($type, $paramId = null, $product1Id = null, $product2Id = null) {
        $offers = [];
        if ($type === self::NETWORK_TYPE) {
            $offers[] = $this->network->fetch($product1Id);
            if (isset($product2Id)) {
                $offers[] = $this->network->fetch($product2Id);
            }
        } elseif ($type === self::ADEX_TYPE) {
            $offers = $this->adex->fetch($paramId);
        }

        $steps = [];
        foreach ($offers as $i => $o) {
            $num = $i + 1;
            $steps[$num] = $this->createOfferLink($o, $num);
        }

        // count($offers) represents 1 or 2 step lander..
        return $steps;
    }

    protected function createOfferLink($offer, $num)  {
        $offer = new OfferLink($offer, $num);
        if($this->urlFor) {
            $func = $this->urlFor;
            $clickUrl = $func('click', array(
                'stepId' => $offer->getStepNumber()
            ));
            $offer->setUrl($clickUrl);
        }
        return $offer;
    }
}
