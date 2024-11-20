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
use App\Entity\Order;
use App\Entity\OrderProduct;

#[Route('/order')]

class NewOrderController extends AbstractController
{

    #[Route('/', name: 'my_payment', methods: ['GET'])]
    public function goingPayment(): Response
    {
        return $this->render('payment/payment.html.twig', [
            
        ]);
    }
    
    #[Route("/", name: "payment_page", methods: ["POST", "GET"])]
    public function index(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        // dd($request);
        $data = json_decode($request->getContent(), true);

        if(empty($data["cart"])) {
            return $this->json(["error" => "Missing data in the application"], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error'=> 'User not founded'], Response::HTTP_NOT_FOUND);
        }

        $order = new Order();
        $order->setUserId($user);
        $order->setTotalAmount(5);

        //$order->setTotalAmount($data['cart']);
        $order->setDateCreated(new \DateTime());

        $entityManager->persist($order);
        $entityManager->flush();
        foreach ($data['cart'] as $productData) {
            $product = $productRepository->find(    $productData["id"]);
            
            if ($product) {
                $orderProduct = new OrderProduct();
                $orderProduct->setOrder($order);
                $orderProduct->setProduct($product);
                $orderProduct->setQuantity( $productData["quantity"]);
                $entityManager->persist($orderProduct);
            }
        }
        

        $entityManager->flush();
        $user->addOrder($order);

        return $this->json([
            'message' => 'Order created successfully',
            'order_id' => $order->getId()
        ], Response::HTTP_OK);
    
    }
}
