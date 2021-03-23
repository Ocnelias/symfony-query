<?php

namespace App\Controller;

use App\Entity\Details;
use App\Entity\Query;
use App\Form\QueryType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
     * @Route("/create")
     */
    public function create(Request $request): Response
    {
        $query = new Details();
        $query->setFirstName('');

        $form = $this->createFormBuilder($query)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('phone', TextType::class)
            ->add('query', QueryType::class)
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($query);
            $entityManager->flush();
            $this->addFlash('success', 'Query Created!');
            return $this->redirect('/');
        }

        return $this->render('create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete")
     */
    public function delete(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $query         = $entityManager->getRepository(Query::class)->find($request->request->get('id'));
        if (!$query) {
            throw $this->createNotFoundException(
                'Not found'
            );
        }
        $entityManager->remove($query);
        $entityManager->flush();
        $this->addFlash('success', 'Query deleted!');
        return $this->redirect('/');
    }
}
