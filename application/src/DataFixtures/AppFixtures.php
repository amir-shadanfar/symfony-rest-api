<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Services\OrderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $orderService;

    public function __construct(UserPasswordEncoderInterface $encoder,OrderService $orderService)
    {
        $this->encoder = $encoder;
        $this->orderService = $orderService;
    }

    public function load(ObjectManager $manager)
    {
        // seed users
        for ($i = 1; $i < 4; $i++) {
            $user = new User('username_' . $i);
            $user->setName('User ' . $i);
            $user->setPassword($this->encoder->encodePassword($user, 'password_' . $i));
            $manager->persist($user);
        }
        $manager->flush();

        // seed products
        for ($i = 1; $i < 11; $i++) {
            $user = new Product();
            $user->setName('Product_' . $i);
            $user->setBrand('Brand_' . $i);
            $manager->persist($user);
        }
        $manager->flush();

        // seed some orders for test
        $users = $manager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $products = $manager->getRepository(Product::class)->findAll();
            for ($i = 0; $i < 6; $i++) {
                $order = new Order();
                $order->setAddress('Address_' . $i);
                $order->setOrderCode($this->orderService->generateOrderCode());
                $order->setQuantity(rand());

                $dateTime = [new \DateTime(), null];
                $order->setShippingDate($dateTime[array_rand($dateTime)]);

                $order->setUser($user);
                $product = next($products);
                $order->setProduct($product);
                $manager->persist($order);
            }
        }
        $manager->flush();
    }
}
