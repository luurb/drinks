<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route(
        '/{react}',
        name: 'app_default',
        methods: ['GET'],
        requirements: ['react' => '^(?!api).+'],
        defaults: ['react' => 'null']
    )]
    public function index(): Response
    {
        return $this->render('index.html.twig',);
    }
}
