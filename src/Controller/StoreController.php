<?php

namespace App\Controller;

use App\Entity\Store\Product;
use App\Repository\Store\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Store\ProductRepository;

class StoreController extends AbstractController
{
    private $em;
    public function __construct(
        EntityManagerInterface $em, 
        private ProductRepository $productRepository,
        private CommentRepository $commentRepository
        )
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
        $products = $this->productRepository->findAll();
        foreach($products as $product) {
            $product->getImage();
        }

        return $this->render('store/index.html.twig', [
            'controller_name' => 'StoreController',
            'products' => $products,
        ]);
    }



    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product')]
    public function productDetail(Request $request, int $id, string $slug): Response
    {
        $product = $this->productRepository->find($id);
        $comments = $this->commentRepository->findBy(['product' => $product]);

        return $this->render('store/show.html.twig', [
            'slug' => $slug,
            'comments' => $comments,
            'product' => $product,
        ]);
    }


}
