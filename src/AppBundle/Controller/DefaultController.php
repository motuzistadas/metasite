<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use AppBundle\Base\BaseController;
use AppBundle\Form\OrderType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;




class DefaultController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $order = new Orders();

        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Create the order
            $order->setOrderDate(new \DateTime("now"));
            $em->persist($order);
            $em->flush();

            $this->redirect('home');
        }

        return $this->render('default/index.html.twig', array(
        'form' => $form->createView()
        ));
    }
}
