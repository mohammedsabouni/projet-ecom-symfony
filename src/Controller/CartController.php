<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Handler\CartHandler;
use App\Repository\ProductRepository;
use App\Strategies\cart\impl\SessionCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    //TODO: add total logic
    public function __construct(private SessionCart $sessionCart)
    {
    }

    // GET /cart
    #[Route('', name: 'show')]
    public function show(): Response
    {
        $cart = $this->sessionCart->getCart();

        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    // POST /cart/add/{id}
    #[Route('/add/{id}', name: 'add', methods: ['POST'])]
    public function add(int $id, Request $request, ProductRepository $productRepository): Response
    {
        if (!$this->isCsrfTokenValid('cart_add', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $quantity = max(1, (int) $request->request->get('quantity', 1));

        $item = new CartItem();
        $item->setProduct($product);
        $item->setQuantity($quantity);
        $item->setPrice($product->getSellPrice());

        $cart = $this->sessionCart->getCart();
        $this->sessionCart->add($item, $cart);

        $this->addFlash('success', $product->getName() . ' added to cart!');

        return $this->redirectToRoute('cart_show');
    }

    // POST /cart/remove/{id}
    #[Route('/remove/{id}', name: 'remove', methods: ['POST'])]
    public function remove(int $id, Request $request, ProductRepository $productRepository): Response
    {
        if (!$this->isCsrfTokenValid('cart_remove', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $item = new CartItem();
        $item->setProduct($product);

        $cart = $this->sessionCart->getCart();
        $this->sessionCart->remove($item, $cart);

        $this->addFlash('success', $product->getName() . ' removed from cart!');

        return $this->redirectToRoute('cart_show');
    }

    // POST /cart/clear
    #[Route('/clear', name: 'clear', methods: ['POST'])]
    public function clear(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('cart_clear', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->sessionCart->clearCart('session');

        $this->addFlash('success', 'Cart cleared!');

        return $this->redirectToRoute('cart_show');
    }
}
