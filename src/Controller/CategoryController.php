<?php

namespace App\Controller;

use App\Dto\CategoryDto;
use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Mapper\CategoryMapper;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryMapper $mapper,
        private EntityManagerInterface $em,
    ) {}
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
        ]);
    }

    #[Route('/category/add', name: 'app_category_add', methods: ['GET','POST'])]
    public function add(Request $request): Response
    {
        $categoryDto = new CategoryDto();
        $form = $this->createForm(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $this->mapper->toEntity($categoryDto);
            $this->em->persist($category);
            $this->em->flush();
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/category/{id}/edit", name: 'category_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Category $category): Response
    {
        $dto = $this->mapper->toDto($category);
        $form = $this->createForm(CategoryFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mapper->updateEntity($category, $dto);
            $this->em->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/category/{id}/delete', name: 'category_delete', methods: ['POST'])]
    public function delete(Category $category, EntityManagerInterface $em, Request $request): Response
    {
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('app_category');
    }
}
