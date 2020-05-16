<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Services\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller
 * @Route("api")
 */
class OrderController extends ApiController
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @Route("/orders",name="orders", methods={"GET"})
     * @param OrderRepository $orderRepository
     * @return mixed
     */
    public function getAllOrders(OrderRepository $orderRepository)
    {
        $data = $orderRepository->getUserOrders($this->getUser());
        return $this->response($data);
    }

    /**
     * @param OrderRepository $OrderRepository
     * @param $id
     * @return JsonResponse
     * @Route("/orders/{id}", name="orders_get", methods={"GET"})
     */
    public function getOrderById(OrderRepository $OrderRepository, $id)
    {
        $order = $OrderRepository->findOneBy(['user' => $this->getUser(), 'id' => $id]);
        if (!$order) {
            $data = [
                'status' => 404,
                'errors' => "Order not found",
            ];
            return $this->respondNotFound($data);
        }
        return $this->response($order);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param OrderRepository $orderRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/orders", name="orders_add", methods={"POST"})
     */
    public function addOrder(Request $request, EntityManagerInterface $entityManager, OrderRepository $orderRepository)
    {
        try {
            $request = $this->transformJsonBody($request);
            if (!$request || !$request->get('quantity') || !$request->request->get('address') || !$request->request->get('product_id')) {
                throw new \Exception();
            }

            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $request->get('product_id')]);
            if (!$product) {
                $data = [
                    'status' => 404,
                    'errors' => "Product not found",
                ];
                return $this->respondNotFound($data);
            }

            $order = new Order();
            $order->setOrderCode($this->orderService->generateOrderCode());
            $order->setQuantity($request->get('quantity'));
            $order->setAddress($request->get('address'));
            $order->setProduct($product);
            $order->setUser($this->getUser());
            $entityManager->persist($order);
            $entityManager->flush();

            $data = [
                'status'  => 201,
                'success' => "Order added successfully",
            ];
            return $this->respondCreated($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->respondValidationError($data);
        }
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param OrderRepository $OrderRepository
     * @param $id
     * @return JsonResponse
     * @Route("/orders/{id}", name="orders_put", methods={"PUT"})
     */
    public function updateOrder(Request $request, EntityManagerInterface $entityManager, OrderRepository $OrderRepository, $id)
    {
        try {
            $order = $OrderRepository->find($id);
            if (!$order) {
                throw new Exception();
            }

            // control shipping date before update
            if (!is_null($order->getShippingDate())) {
                $data = [
                    'status' => 422,
                    'errors' => "Order could not be updated because product has been shipped!",
                ];
                return $this->respondValidationError($data);
            }

            $request = $this->transformJsonBody($request);
            // check product
            if ($request->get('product_id')) {
                $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $request->get('product_id')]);
                if (!$product) {
                    $data = [
                        'status' => 404,
                        'errors' => "Product not found",
                    ];
                    return $this->respondNotFound($data);
                }
                $order->setProduct($product);
            }
            // check quantity
            if ($request->get('quantity')) {
                $order->setQuantity($request->get('quantity'));
            }
            // check address
            if ($request->get('address')) {
                $order->setAddress($request->get('address'));
            }
            $entityManager->flush();

            $data = [
                'status' => 200,
                'errors' => "Order updated successfully",
            ];
            return $this->response($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 404,
                'errors' => "Order:$id not found",
            ];
            return $this->respondNotFound($data);
        }

    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param OrderRepository $OrderRepository
     * @param $id
     * @return JsonResponse
     * @Route("/orders/{id}", name="orders_delete", methods={"DELETE"})
     */
    public function deleteOrder(EntityManagerInterface $entityManager, OrderRepository $OrderRepository, $id)
    {
        $order = $OrderRepository->find($id);
        if (!$order) {
            $data = [
                'status' => 404,
                'errors' => "Order not found",
            ];
            return $this->respondNotFound($data);
        }
        $entityManager->remove($order);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Order deleted successfully",
        ];
        return $this->response($data);
    }
}
