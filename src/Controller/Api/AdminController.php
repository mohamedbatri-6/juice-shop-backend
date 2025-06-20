<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin')]
class AdminController extends AbstractController
{
    #[Route('/users', name: 'admin_users_list', methods: ['GET'])]
    public function listUsers(UserRepository $repo): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $repo->findAll();

        return $this->json($users, 200, [], ['groups' => 'admin:read']);
    }

    #[Route('/product/delete/{id}', name: 'admin_product_delete', methods: ['DELETE'])]
    public function deleteProduct(int $id, ProductRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = $repo->find($id);

        if (!$product) {
            return $this->json(['error' => 'Produit introuvable'], 404);
        }

        $em->remove($product);
        $em->flush();

        return $this->json(['message' => 'Produit supprimé !']);
    }
}
