<?php

namespace App;

use OmiseCharge as Charge;

class OmiseCharge {
    protected $charge;
    
    public function __construct($charge) {
        $this->charge = $charge;
        
        return $this;
    }
    
    public static function retrieve(string $id) {
        return new self(Charge::retrieve($id, self::getPublicKey(), self::getSecretKey()));
    }
    
    /**
     * Charge credit card by token
     * @param float  $amount Amount in THB e.g. 100.25
     * @param string $token Omise Card Token
     * @param        $order_id Order ID
     * @return OmiseCharge
     */
    public static function chargeCard(float $amount, string $token, $order_id) {
        return new self(Charge::create(array(
            'amount' => $amount * 100,
            'currency' => 'thb',
            'card' => $token,
            'metadata' => 'BTQ-ORDER-'.$order_id
        ), self::getPublicKey(), self::getSecretKey()));
    }
    
    /**
     * Get charge status.
     * Value can one be one of failed, expired, pending, reversed or successful.
     * @return string
     */
    public function getStatus(): string {
        return $this->charge['status'];
    }
    
    protected function isPaid(): bool {
        return $this->charge['paid'];
    }
    
    public function isSuccess() {
        return $this->isPaid() AND ($this->getStatus() == 'successful');
    }
    
    public function getErrorMessage() {
        return $this->charge['failure_message'] ?? false;
    }
    
    public function isVoided() {
        return $this->charge['reversed'] OR $this->charge['voided'];
    }
    
    
    /**
     * Get 3-D Secure authentication / Offsite Payment (Online Banking) redirect uri
     * @return bool|string
     */
    public function getAuthorizeUri() {
        return $this->charge['authorize_uri'] ?? false;
    }
    
    /**
     * Get summary of this charge in array for storage
     * @return array
     */
    public function export(): array {
        return [
            'method' => 'CREDITCARD',
            'charge_id' => $this->charge['id'],
            'status' => $this->charge['status'],
            'authorized' => $this->charge['authorized'],
            'paid' => $this->charge['paid'],
            'voided' => $this->isVoided(),
            'transaction_id' => $this->charge['transaction'],
            'card_country' => $this->charge['card']['country'],
            'card_bank' => $this->charge['card']['bank'],
            'card_last_digits' => $this->charge['card']['last_digits'],
            'card_brand' => $this->charge['card']['brand'],
            'card_holder' => $this->charge['card']['name'],
            'failure_code' => $this->charge['failure_code'],
            'livemode' => $this->charge['livemode'],
            'created' => $this->charge['created']
        ];
    }
    
    public static function getPublicKey(): string {
        return env('OMISE_PUBLIC_KEY');
    }
    
    protected static function getSecretKey(): string {
        return env('OMISE_SECRET_KEY');
    }
    
}