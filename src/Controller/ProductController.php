<?php

namespace App\Controller;

use App\Dto\ProductDto;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductFormType;
use App\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    public function __construct(
        private ProductMapper $mapper,
        private EntityManagerInterface $em,
    ) {}

    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $this->mapper->toDtoList($productRepository->findAll());

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/add', name: 'app_product_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $dto = new ProductDto();
        $form = $this->createForm(ProductFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $this->mapper->toEntity($dto);
            $this->em->persist($product);
            $this->em->flush();

            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $this->mapper->toDto($product),
        ]);
    }

    #[Route('/category/{id}/products', name: 'app_products_by_category', methods: ['GET'])]
    public function byCategory(Category $category, ProductRepository $productRepository): Response
    {
        $products = $this->mapper->toDtoList(
            $productRepository->findBy(['category' => $category])
        );

        return $this->render('product/products_by_category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }
    #[Route('/product/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product): Response
    {
        $dto = $this->mapper->toDto($product);
        $form = $this->createForm(ProductFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mapper->updateEntity($product, $dto);
            $this->em->flush();

            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form,
            'product' => $dto,
        ]);
    }

    #[Route('/product/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Product $product): Response
    {
        $this->em->remove($product);
        $this->em->flush();

        return $this->redirectToRoute('app_product');
    }

}
