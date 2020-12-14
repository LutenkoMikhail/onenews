<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class NewsController extends AbstractController
{
    /**
     * Show all news
     * @return Response
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository('App:News');

        return $this->json(
            array_values($repository->findAll()),
            Response::HTTP_OK,
            [],
            ['groups' => ['default']]
        );
    }

    /**
     * Show one news
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $repository = $this->getDoctrine()->getRepository('App:News');

        return $this->json(
            $repository->find($id),
            Response::HTTP_OK,
            [],
            ['groups' => ['default']]
        );
    }

    public function new(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news, ['method' => 'POST']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $news = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->flush();

            return $this->json(
                $news,
                Response::HTTP_CREATED,
                [],
                ['groups' => ['default']]
            );
        }
        return $this->json([
                'error' => 'Wrong request'
            ]

        );
    }
    /**
     * Deleting a news
     * @param $id
     * @return Response
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $news = $entityManager->getRepository(News::class)->find($id);

        if (!$news) {
            $this->createNotFoundException();
        }
        $entityManager->remove($news);
        $entityManager->flush();
        return $this->json(
            $news,
            Response::HTTP_OK,
            [],
            ['groups' => ['default']]
        );
    }

}
