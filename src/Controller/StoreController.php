<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    #[Route('/store', name: 'store')]
    public function index(): Response
    {
        return $this->render('store/index.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }

    #[Route('/store/product/{id}/details', name: 'store_show_product')]
    public function show(Request $request, $id): Response
    {
        return $this->render('store/show.html.twig', [
            'id' => $id,
            'slug' => $request->get('slug'),
            'clientIP' => $request->getClientIp(),
            'pageURL' => $request->getUri(),
        ]);
    }
}
