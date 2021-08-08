<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
	/**
	 * @Route("/categories", name="get_categories")
	 */
	public function getCategories(CategoryRepository $categoryRepository): Response
	{
		$categoies = $categoryRepository->findAll();
		if (count($categoies) === 0) {
			return $this->render('category/index.html.twig', [
				'title' => "Category",
				'error' => "No category found."
			]);
		}
		return $this->render('category/index.html.twig', [
			'title' => "Category",
			'categories' => $categoies
		]);
	}

	/**
	 * @Route("/categories/{id}", name="get_category")
	 */
	public function getCategory(Category $category): Response
	{
		if (!$category) {
			return $this->render('category/index.html.twig', [
				'title' => "Category",
				'error' => "Category not found."
			]);
		}

		return $this->render('category/index.html.twig', [
			'title' => "Category",
			'category' => $category
		]);
	}


	/**
	 * @Route("addCategory", methods={"GET","POST"}, name="add_category")
	 */
	public function addProduct(Request $request)
	{
		try {
			$category = new Category;
			$form = $this->createForm(CategoryType::class, $category, [
				'method' => 'POST',
			]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($category);
				$entityManager->flush();

				return $this->redirectToRoute('get_categories');
			}

			return $this->render('category/add.html.twig', [
				'title' => "Add Category",
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
	 * @Route("updateCategory/{id}", methods={"GET","POST"}, name="update_category")
	 */
	public function updateProduct(Request $request, Category $category)
	{
		try {
			$form = $this->createForm(CategoryType::class, $category, [
				'method' => 'POST',
			]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($category);
				$entityManager->flush();

				return $this->redirectToRoute('get_categories');
			}

			return $this->render('category/update.html.twig', [
				'title' => "Update Category",
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
	 * @Route("deleteCategory/{id}", methods={"GET","POST"}, name="delete_category")
	 */
	public function deleteCategory(Category $category)
	{
		try {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($category);
			$entityManager->flush();

			return $this->redirectToRoute('get_categories');

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
