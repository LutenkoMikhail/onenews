<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TagController extends AbstractController
{
    /**
     * Show all tags
     * @return Response
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository('App:Tag');

        return $this->json(
            array_values($repository->findAll()),
            Response::HTTP_OK,
            [],
            ['groups' => ['default']]
        );

    }

    /**
     * Show one tag
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $repository = $this->getDoctrine()->getRepository('App:Tag');

        return $this->json(
            $repository->find($id),
            Response::HTTP_OK,
            [],
            ['groups' => ['default']]
        );
    }

    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag, ['method' => 'POST']);
//        $form->handleRequest($request);
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();
            return $this->json([
                    'message' => 'Create new tag'
                ]
            );
        }
        return $this->json([
                'error' => 'Error creating new tag'
            ]

        );
    }
}
