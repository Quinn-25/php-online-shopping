<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function index(): Response
  {
    return $this->render('main/index.html.twig', [
      'controller_name' => 'MainController',
      'title' => "Home Page"
    ]);
  }

  /**
	 * @Route("logout", methods={"GET","POST"}, name="logout")
	 */
	public function logout(SessionInterface $session)
	{
		try {

			$session->invalidate();

			return $this->redirectToRoute('home');
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
