<?php

namespace App\Strategies\cart;

use App\Entity\Cart;
use App\Entity\CartItem;

interface CartInterface
{
    public function add(CartItem $item, Cart $cart): Cart;
    public function remove(CartItem $item, Cart $cart): Cart;
    public function getCart(): Cart;
    public function clearCart(string $identifier): Cart;
}
