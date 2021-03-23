<?php
namespace App\Controller;

use App\Entity\Details;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QueryController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index(): Response
    {
        $details = $this->getDoctrine()
            ->getRepository(Details::class)
            ->findAll();

        return $this->render('index.html.twig', [
            'details' => $details,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details_show")
     */
    public function show(int $id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great product: '.$product->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
         return $this->render('index.html.twig', ['product' => $product]);
    }
}
