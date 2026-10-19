<?php
require_once __DIR__ . '/../classes/CartClass.php';

class CartController
{
    private $cart;

    public function __construct()
    {
        $this->cart = new CartClass();
    }

    public function isInCart($productId, $ip)
    {
        return $this->cart->isInCart($productId, $ip);
    }

    public function addToCart($productId, $qty, $ip, $customerId = null)
    {
        return $this->cart->addToCart($productId, $qty, $ip, $customerId);
    }

    public function getCartItems($ip)
    {
        return $this->cart->getCartItems($ip);
    }

    public function getCartCount($ip)
    {
        return $this->cart->getCartCount($ip);
    }

    public function getCartTotal($ip)
    {
        return $this->cart->getCartTotal($ip);
    }

    public function removeFromCart($productId, $ip)
    {
        return $this->cart->removeFromCart($productId, $ip);
    }

    public function updateQty($productId, $qty, $ip)
    {
        return $this->cart->updateQty($productId, $qty, $ip);
    }

    public function updateQuantities($items, $ip)
    {
        return $this->cart->updateQuantities($items, $ip);
    }

    public function removeItems($productIds, $ip)
    {
        return $this->cart->removeItems($productIds, $ip);
    }
}
