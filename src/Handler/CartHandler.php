<?php

namespace App\Handler;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Strategies\cart\CartInterface;

class CartHandler
{
    private CartInterface $strategy;

    // handle() sets the strategy and returns the current cart
    public function handle(Cart $cart, CartInterface $strategy): Cart
    {
        $this->strategy = $strategy;
        return $this->strategy->getCart() ?? $cart;
    }

    public function add(CartItem $item): Cart
    {
        $cart = $this->strategy->getCart();
        return $this->strategy->add($item, $cart);
    }

    public function remove(CartItem $item): Cart
    {
        $cart = $this->strategy->getCart();
        return $this->strategy->remove($item, $cart);
    }

    public function clear(): Cart
    {
        $cart = $this->strategy->getCart();
        return $this->strategy->clearCart((string) $cart->getId());
    }
}
