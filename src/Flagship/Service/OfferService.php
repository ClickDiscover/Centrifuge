<?php

namespace Flagship\Service;


class OfferService {

    const NETWORK_TYPE = 'network';
    const ADEX_TYPE = 'adexchange';

    protected $network;
    protected $adex;

    public function __construct($network, $adex) {
        $this->network = $network;
        $this->adex = $adex;
    }

    public function fetch($type, $paramId = null, $product1Id = null, $product2Id = null) {
        $offers = [];
        if ($type === self::NETWORK_TYPE) {
            $offers[] = $this->network->fetch($product1Id);
            if (isset($product2Id)) {
                $offers[] = $this->network->fetch($product2Id);
            }
        } elseif ($type === self::ADEX_TYPE) {
            $offers[] = $this->adex->fetch($paramId);
        }
        return $offers;
    }
}
