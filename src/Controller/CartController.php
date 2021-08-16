<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session): Response
    {
        try {
            $products = $session->get('product', []);
            
            if (!$session->has('product')) {
                return $this->render('cart/index.html.twig', [
                    'controller_name' => 'CartController',
                    'title' => "Shopping Cart",
                    'error' => "The cart is empty"
                ]);
            }

            return $this->render('cart/index.html.twig', [
                'controller_name' => 'CartController',
                'title' => "Shopping Cart",
                'products' => $products
            ]);
        } catch (\Exception $e) {
            $error = ['ERROR' => $e->getMessage()];
            $json = json_encode($error);
            return new Response(
                $json,
                Response::HTTP_BAD_REQUEST,
                [
                    "content-type" => "application/json"
                ]
            );
        }
    }

    /**
	 * @Route("addToCart/{id}", methods={"GET","POST"}, name="add_to_cart")
	 */
	public function addToCart(SessionInterface $session, Product $product)
	{
		try {
			$session = new Session();
			$session->set('product', $product);

			$products = $session->get('product', []);

      return $this->redirect('cart');
		} catch (\Exception $e) {
			$error = ['ERROR' => $e->getMessage()];
			$json = json_encode($error);
			return new Response(
				$json,
				Response::HTTP_BAD_REQUEST,
				[
					"content-type" => "application/json"
				]
			);
		}
	}

    /**
	 * @Route("addToCart/{id}", methods={"GET","POST"}, name="add_to_cart")
	 */
	public function deleteItemFromCart(SessionInterface $session, Product $product)
	{
		try {
			$session->remove('product', $product);

			return $this->redirectToRoute('cart');
		} catch (\Exception $e) {
			$error = ['ERROR' => $e->getMessage()];
			$json = json_encode($error);
			return new Response(
				$json,
				Response::HTTP_BAD_REQUEST,
				[
					"content-type" => "application/json"
				]
			);
		}
	}
}
