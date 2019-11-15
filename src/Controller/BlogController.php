<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\Type\ArticleFormType;
use App\Repository\{ArticleRepository, CategoryRepository};
use App\Service\QuoteGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController {

    public function list(
        ArticleRepository $articleRepository, 
        Request $request,
        QuoteGenerator $quoteGenerator
    ): Response {
        $onlyPublished = $request->get('onlyPublished', false);
        if ($onlyPublished) {
            $articles = $articleRepository->findBy(
                ['isPublished' => true], 
                ['id' => 'DESC'],
                10,
                0
            );
        } else {
            $articles = $articleRepository->findAll();
        }

        return $this->render('blog_list.html.twig', [
            'articles' => $articles,
            'quote' => $quoteGenerator->getQuote(true)    
        ]);
    }

    public function article($id, ArticleRepository $articleRepository): Response {
        $article = $articleRepository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article with id '.$id.' not found');
        }
        $category = $article->getCategory();
        $categoryName = empty($category) ? '' : $category->getName();
        return $this->render('article.html.twig', ['article' => $article]);
    }

    public function create(
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager, 
        Request $request
    ): Response {

        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', sprintf('Article created with id: %d and title: %s', 
                $article->getId(),
                $article->getTitle()));
            return $this->redirectToRoute('blog_article', ['id' => $article->getId()]);
        }

        return $this->render('blog_article_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function update(
        $id, 
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $article = $articleRepository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article with id '.$id.' not found');
        }
        $article->setTitle('título actualizado: '.time());
        $entityManager->flush();
        return new Response($article->getTitle());
    }

    public function delete(
        $id, 
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $article = $articleRepository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article with id '.$id.' not found');
        }
        $entityManager->remove($article);
        $entityManager->flush();
        return new Response('Deleted article with id: '.$id);
    }
}
