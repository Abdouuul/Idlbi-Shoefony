<?php

namespace App\Controller;

use App\Entity\Store\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // #[Route('/store', name: 'store')]
    // public function index(): Response
    // {
    //     return $this->render('store/index.html.twig', [
    //         'controller_name' => 'StoreController',
    //     ]);
    // }

    #[Route('/store', name: 'store')]
    public function productShowList(): Response
    {
        $products = $this->em->getRepository(Product::class)->findAll();

        return $this->render('store/index.html.twig', [
            'controller_name' => 'StoreController',
            'products' => $products,
        ]);
    }



    #[Route('/store/product/{id}/details', name: 'store_show_product')]
    public function productDetail(Request $request, $id): Response
    {
        return $this->render('store/show.html.twig', [
            'id' => $id,
            'slug' => $request->get('slug'),
            'clientIP' => $request->getClientIp(),
            'pageURL' => $request->getUri(),
        ]);
    }


}
