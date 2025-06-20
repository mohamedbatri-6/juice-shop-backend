<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    #[Route('', name: 'api_products_list', methods: ['GET'])]
    public function list(ProductRepository $repo): JsonResponse
    {
        $products = $repo->findAll();
        return $this->json($products, 200, [], ['groups' => 'product:read']);
    }

    #[Route('', name: 'api_products_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em, Security $security): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $data = json_decode($request->getContent(), true);

        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);
        $product->setImageUrl($data['imageUrl']);
        $product->setCreatedAt(new \DateTimeImmutable());

        $em->persist($product);
        $em->flush();

        return $this->json(['message' => 'Produit ajouté !'], 201);
    }
}
