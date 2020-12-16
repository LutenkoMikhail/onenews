<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Serializer\Normalizer\SymfonyFormErrorNormalizer;
use App\Util\ErrorFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * Created a new Tag
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag, ['method' => Request::METHOD_POST]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->json(
                $tag,
                Response::HTTP_CREATED,
                [],
                ['groups' => ['default']]
            );
        }

        $encoders = [ new JsonEncoder()];
        $normalizers = [new SymfonyFormErrorNormalizer(new ErrorFactory())];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent =json_decode( $serializer->serialize($form, 'json'), true);

        return $this->json(
            $jsonContent,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            [],
            []
        );
    }

    /**
     * Deleting a tag
     * @param Request $request
     * @return Response
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tag = $entityManager->getRepository(Tag::class)->find($id);

        if (!$tag) {
            $this->createNotFoundException();
        }
        $entityManager->remove($tag);
        $entityManager->flush();
        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [],
            ['groups' => ['default']]
        );
    }

    /**
     * Tag update
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tag = $entityManager->getRepository(Tag::class)->find($id);

        if (!$tag) {
            $this->createNotFoundException();
        }

        $form = $this->createForm(TagType::class, $tag, ['method' => Request::METHOD_PATCH]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();
            $entityManager->persist($tag);
            $entityManager->flush();
            return $this->json(
                $tag,
                Response::HTTP_OK,
                [],
                ['groups' => ['default']]
            );
        }
        return $this->json([
                'error' => 'Wrong request'
            ]
        );
    }
}

