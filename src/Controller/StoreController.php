<?php

namespace App\Controller;

use App\Entity\Store\Brand;
use App\Entity\Store\Product;
use App\Repository\Store\BrandRepository;
use App\Repository\Store\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Store\ProductRepository;
use PhpParser\Builder\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\VarDumper\VarDumper;

class StoreController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em, 
        private ProductRepository $productRepository,
        private CommentRepository $commentRepository,
        private BrandRepository $brandRepository
        )
    {
        $this->em = $em;
        
    }

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
            'brandId' => null,
        ]);
    }



    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product')]
    public function productDetail(int $id, string $slug): Response
    {
        $product = $this->productRepository->find($id);
        $comments = $this->commentRepository->findBy(['product' => $product]);

        return $this->render('store/show.html.twig', [
            'slug' => $slug,
            'comments' => $comments,
            'product' => $product,
        ]);
    }

    #[Route('/store/products/brand/{brandId}', 
    name: 'store_list_products_by_brand',
    requirements: ['brandId' =>'\d+']
    )]
    public function listProductsByBrand(int $brandId): Response
    {
        $brand = $this->brandRepository->find($brandId);
        if($brand === null) {
            throw new NotFoundHttpException();
        }

        $products = $this->productRepository->findBy(['brand' => $brand]);

        return $this->render('store/index.html.twig', [
            'brand' => $brand,
            'brandId' => $brandId,
            'products' => $products,
        ]);

    }
    
    public function listAllBrands($brandId): Response
    {
        $brands = $this->brandRepository->findAll();

        return $this->render('store/_list_brands.html.twig', [
            'brands' => $brands,
            'brandId' => $brandId,
        ]);
    }
}
