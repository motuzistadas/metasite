<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{
    /**
     * @Route("/register")
     * @param Request $request
     * @param userPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param UserPasswordEncoderInterface $encoder
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = new User();

    	$form = $this->createForm(UserType::class, $user);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		// Create the user
    		$user->setPassword($encoder->encodePassword($user, $user->getPassword()));
    		$user->setRegDate(new \DateTime("now"));
    		$em->persist($user);
    		$em->flush();

    		return $this->redirectToRoute('login');
    	}

        return $this->render('AppBundle:Register:register.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
