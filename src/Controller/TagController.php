<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TagController extends AbstractController
{

    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository('App:Tag');

        return $this->json([
            'data'=>$tags = $repository->getAll()
        ]);
    }
    public function show($id): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'id' => $id,
        ]);
    }
}
