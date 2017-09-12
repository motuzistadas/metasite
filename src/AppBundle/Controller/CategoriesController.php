<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categories;
use AppBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use AppBundle\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class CategoriesController extends Controller
{
    /**
     * @Route("/categories", name="categories")
     */
    public function categoriesAction(Request $request)
    {	

    	$em = $this->getDoctrine()->getManager();
    	$category = new Categories();

    	$form = $this->createForm(CategoryType::class, $category);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		// Create the category
    		$em->persist($category);
    		$em->flush();

    		return $this->redirectToRoute('categories');
    	}

    	$all_cats = $this->getDoctrine()
    	->getRepository('AppBundle:Categories')
        ->findAll();

        return $this->render('AppBundle:Categories:categories.html.twig', array(
            'form' => $form->createView(),
            'viewCatDets' => $all_cats
        ));
    }


    public function checkCsrfToken($key, $token)
    {
        if ($token !== $this->get('security.csrf.token_manager')->getToken($key)->getValue()) {
            throw new InvalidCsrfTokenException('Invalid CSRF token');
        }
    }

    /**
     * @Route("/delete/{id}/{token}", name="admin_groups_delete")
     * 
     */
    public function deleteAction(Request $request, $id, $token)
    {
        $this->checkCsrfToken('administration', $token);
        $manager = $this->getDoctrine()->getEntityManager();
        $entity = $manager->getRepository('AppBundle:Categories')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException();
        }

        $em = $this->get('doctrine')->getManager();
        $em->remove($entity);
        $em->flush();

        //$this->success('admin.groups.deleted', ['%id%' => $entity->getId()]);

        return $this->redirect($this->generateUrl('categories'));
    }

}
