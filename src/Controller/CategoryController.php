<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    public function list(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findWithArticleCount();
        return new Response('Categorías');
    }

    public function create(EntityManagerInterface $em): Response
    {
        $category = new Category();
        $category->setName('Category '.time());
        $em->persist($category);
        $em->flush();

        return new Response('Category created: '.$category->getId());
    }

    public function show($id, CategoryRepository $categoryRepository): Response {
        $category = $categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Category with id '.$id.' not found');
        }
        $articles = $category->getArticles();
        $titles = $articles->map(function(Article $article) {
            return $article->getTitle();
        });
        
        return new Response($category->getName());
    }
}