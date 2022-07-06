<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    #[Route(
        '/{react}',
        name: 'app_default',
        methods: ['GET'],
        requirements: ['react' => '^(?!api).+'],
        defaults: ['react' => 'null']
    )]
    public function index(SerializerInterface $serializer): Response
    {
        return $this->render('index.html.twig', [
            'user' => $serializer->serialize($this->getUser(), 'jsonld')
        ]);
    }
}
