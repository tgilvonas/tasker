<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontPagesController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        dd('homepage');
    }

    #[Route('/kategorija/{slug}', name: 'category')]
    public function category(string $slug, Request $request): Response
    {
        dd('some-category ' . $slug);
    }
}
