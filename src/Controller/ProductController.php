<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
	/**
	 * @Route("/products", name="get_products", methods={"GET"})
	 */
	public function getProducts(ProductRepository $productRepository, CategoryRepository $category): Response
	{
		$categories = $category->findAll();
		$products = $productRepository->findAll();
		if (count($products) === 0) {
			return $this->render('product/index.html.twig', [
				'title' => "Shopping",
				'categories' => $categories,
				'error' => "No product found."
			]);
		}

		return $this->render('product/index.html.twig', [
			'title' => "Shopping",
			'categories' => $categories,
			'products' => $products
		]);
	}

	/**
	 * @Route("/manageProducts", name="manage_products", methods={"GET"})
	 */
	public function manageProducts(ProductRepository $productRepository): Response
	{
		$products = $productRepository->findAll();
		if (count($products) === 0) {
			return $this->render('product/manage.html.twig', [
				'title' => "Manage Products",
				'error' => "No product found."
			]);
		}

		return $this->render('product/manage.html.twig', [
			'title' => "Manage Products",
			'products' => $products
		]);
	}

	/**
	 * @Route("/products/{id}", name="get_product", methods={"GET","POST"})
	 */
	public function getProduct(Product $product): Response
	{
		if (!$product) {
			return $this->render('product/view.html.twig', [
				'title' => "Manage Products",
				'error' => "No product found."
			]);
		}

		return $this->render('product/view.html.twig', [
			'title' => "View Product",
			'product' => $product
		]);

	}

	/**
	 * @Route("addProduct", methods={"GET","POST"}, name="add_product")
	 */
	public function addProduct(Request $request)
	{
		try {
			$product = new Product;
			$form = $this->createForm(ProductType::class, $product, [
				'method' => 'POST',
			]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($product);
				$entityManager->flush();

				return $this->redirectToRoute('manage_products');
			}

			return $this->render('product/add.html.twig', [
				'title' => "Add Product",
				'form' => $form->createView()
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
	 * @Route("updateProduct/{id}", methods={"GET","POST"}, name="update_product")
	 */
	public function updateProduct(Request $request, Product $product)
	{
		try {
			
			// $product = $productRepository->find($id);

			$form = $this->createForm(ProductType::class, $product, [
				'method' => 'POST',
			]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($product);
				$entityManager->flush();

				return $this->redirectToRoute('manage_products');
			}

			return $this->render('product/update.html.twig', [
				'title' => "Update Product",
				'form' => $form->createView()
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
	 * @Route("deleteProduct/{id}", methods={"GET","POST"}, name="delete_product")
	 */
	public function deleteProduct(Product $product)
	{
		try {
			
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($product);
			$entityManager->flush();

			return $this->redirectToRoute('manage_products');
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
