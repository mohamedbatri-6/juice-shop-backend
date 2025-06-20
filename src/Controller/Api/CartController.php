<?php

namespace App\Controller\Api;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/api/cart')]
class CartController extends AbstractController
{
    #[Route('', name: 'api_cart_list', methods: ['GET'])]
    public function list(CartRepository $repo, Security $security): JsonResponse
    {
        $user = $security->getUser();
        $cart = $repo->findBy(['user' => $user]);

        return $this->json($cart, 200, [], ['groups' => 'cart:read']);
    }

    #[Route('/add', name: 'api_cart_add', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $em,
        ProductRepository $productRepo,
        Security $security
    ): JsonResponse {
        $user = $security->getUser();
        $data = json_decode($request->getContent(), true);

        $product = $productRepo->find($data['product_id']);
        if (!$product) {
            return $this->json(['error' => 'Produit introuvable'], 404);
        }

        $cartItem = new Cart();
        $cartItem->setUser($user);
        $cartItem->setProduct($product);
        $cartItem->setQuantity($data['quantity']);
        $cartItem->setCreatedAt(new \DateTimeImmutable());

        $em->persist($cartItem);
        $em->flush();

        return $this->json(['message' => 'Ajouté au panier !'], 201);
    }
}
