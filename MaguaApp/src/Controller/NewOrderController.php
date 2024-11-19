<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class NewOrderController extends AbstractController
{
    
    #[Route("/", name: "payment_page", methods: ["POST", "GET"])]
    public function index(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        // dd($request);
        $data = json_decode($request->getContent(), true);

        if(empty($data["cart"]) || empty($data["user"])) {
            return $this->json(["error" => "Missing data in the application"], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository('App\Entity\User')->find($data['user']['id']);
        if (!$user) {
            return $this->json(['error'=> 'User not founded'], Response::HTTP_NOT_FOUND);
        }

        $order = new Order();
        $order->setUser($user);
        $order->setTotal($data['cart']['total']);
        $order->setDate(new \DateTime());

        $entityManager->persist($order);
        $entityManager->flush();

        foreach ($data['cart']['product'] as $productData) {
            $product = $productRepository->find($productData['product']['id']);
            if ($product) {
                $orderProduct = new OrderProduct();
                $orderProduct->setOrder($order);
                $orderProduct->setProduct($product);
                $orderProduct->setQuantity($productData['quantity']);

                $entityManager->persist($orderProduct);
            }
        }
        

        $entityManager->flush();

        return $this->json([
            'message' => 'Order created successfully',
            'order_id' => $order->getId()
        ], Response::HTTP_OK);
    
    }
}
