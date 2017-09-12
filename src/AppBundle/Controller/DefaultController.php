<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Subscriber;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DefaultController extends Controller
{
    
        // replace this example code with whatever you need
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {

        #function return last inserted subscription ID + 1
        function getLastId($file){
            
            $f = fopen($file, "r");
            if(fgets($f) !== false){

                $line = '';
                $cursor = -1;

                fseek($f, $cursor, SEEK_END);
                $char = fgetc($f);

                while ($char === "\n" || $char === "\r") {
                    fseek($f, $cursor--, SEEK_END);
                    $char = fgetc($f);
                }

                while ($char !== false && $char !== "\n" && $char !== "\r") {
                    $line = $char . $line;
                    fseek($f, $cursor--, SEEK_END);
                    $char = fgetc($f);
                }
                $lastId = explode(",", $line)[0];
                return $lastId+1; 
            fclose($f); 
            }else{
                return "1"; #return number 1 if file empty
            }
         }


        // create a subscription 
        $subscription = new Subscriber();
    
        $form = $this->createFormBuilder($subscription)
            ->add('name', TextType::class, array('label' => 'Name'))
            ->add('email', TextType::class, array('label' => 'E-mail'))
            ->add('groupId', EntityType::class, array(
                'class' => 'AppBundle:Categories',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Select categories',
                'required' => true
            ))
            ->add('save', SubmitType::class, array('label' => 'Subscribe'))
            ->getForm();

             $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $csvFile = getcwd()."\subscriptions.csv";
            $fp = fopen($csvFile, "r");
            $subId = getLastId($csvFile);
         

            $name = $form['name']->getData();
            $email = $form['email']->getData();
            $groups = $form['groupId']->getData();
            $groupIds  = array();
            foreach ($groups as $entity) {
                $groupIds[] = $entity->getId();
            }
            $regDate = date("Y-m-d H:i:s");
            $groupIds = implode(" ", $groupIds); //make groupsIds array to string

            $list = array($subId, $name, $email, $groupIds, $regDate);

            
            $fp = fopen($csvFile, "a");
            fputcsv(
                $fp, // The file pointer
                $list, // The fields
                ','
            );      
            fseek($fp, -1, SEEK_CUR);
            fwrite($fp, "\r\n");
            fclose($fp); 
        }

        return $this->render('default/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
