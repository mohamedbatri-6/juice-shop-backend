<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/api/orders')]
class OrderController extends AbstractController
{
    #[Route('', name: 'api_orders_list', methods: ['GET'])]
    public function list(Security $security): JsonResponse
    {
        $user = $security->getUser();
        $orders = $user->getOrders(); // nécessite un OneToMany dans User.php

        return $this->json($orders, 200, [], ['groups' => 'order:read']);
    }

    #[Route('/create', name: 'api_orders_create', methods: ['POST'])]
    public function create(Request $request, ProductRepository $productRepo, Security $security, EntityManagerInterface $em): JsonResponse
    {
        $user = $security->getUser();
        $data = json_decode($request->getContent(), true);

        $order = new Order();
        $order->setUser($user);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus('en attente');

        $totalPrice = 0;

        foreach ($data['products'] as $productId) {
            $product = $productRepo->find($productId);
            if ($product) {
                $order->addProduct($product);
                $totalPrice += $product->getPrice();
            }
        }

        $order->setTotalPrice($totalPrice);

        $em->persist($order);
        $em->flush();

        return $this->json(['message' => 'Commande enregistrée !'], 201);
    }
}
