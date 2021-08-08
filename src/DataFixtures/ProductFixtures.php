<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cate = new Category;
        $cate->Category("Toy", "This is a Toy category");

        $manager->persist($cate);

        $product = new Product;
        $product->setName("Lego Car");
        $product->setPrice(12.99);
        $product->setDescription("This is a lego car");
        $product->setQuantity(10);
        $product->setCategory($cate->getId());

        $manager->persist($product);

        $manager->flush();
    }
}
