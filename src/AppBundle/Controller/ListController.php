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

class ListController extends Controller
{
    /**
     * @Route("/list", name="list")
     */
    public function ListAction(Request $request)
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
				return $lastId+1; #id for new subscription
		 	fclose($f); 
		 	}else{
		 		return "0"; #return number 0 if file empty
		 	}
		 }

		



		# Read from file function and generate full list array 
			$csvFile = getcwd()."\subscriptions.csv";
			$fp = fopen($csvFile, "r");
    		if(fgets($fp) !== false){
    			$csv = array();
				$file = fopen($csvFile, 'r');

				while (($result = fgetcsv($file)) !== false)
				{
					if (array(null) !== $result) {
					$result[3]= explode(" ", $result[3]); #add string categories to array
					foreach ($result[3] as $key => $value) {
						$category = $this->getDoctrine()
				        ->getRepository('AppBundle:Categories')
				        ->find($result[3][$key]);
				        if($category !== null){
							$result[3][$key] = $category->getName();
				        }else{
				        	$result[3][$key] = null;
				        }
					}
				    $csv[] = $result;
					}
				}
				
				fclose($file);
    		}else{
    			$csv = null;
    		}

    	#save all data to array in  $csv variable 
		

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

        return $this->render('AppBundle:List:list.html.twig', array(
            'form' => $form->createView(),
            'list' => $csv
        ));
    }

    public function checkCsrfToken($key, $token)
    {
        if ($token !== $this->get('security.csrf.token_manager')->getToken($key)->getValue()) {
            throw new InvalidCsrfTokenException('Invalid CSRF token');
        }
    }


    /**
     * @Route("/delete_sub/{id}/{token}", name="admin_subscriber_delete")
     * 
     */
    public function deleteAction(Request $request, $id, $token)
    {
        $this->checkCsrfToken('administration', $token);
        
        $csvFile = getcwd()."\subscriptions.csv";
		$csvFileTemp = getcwd()."\subscriptions_temp.csv";
		//$id = 2;
  
		$fptemp = fopen($csvFileTemp, "a+");
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
		    while (($data = fgetcsv($handle)) !== FALSE) {
			    if ($id != $data[0] ){
			        //$list = $data;
			        fputcsv($fptemp, $data);
			    }
			}
		}
		fclose($handle);
		fclose($fptemp);
		unlink($csvFile);
		rename($csvFileTemp,'subscriptions.csv');

		return $this->redirect($this->generateUrl('list'));
	}



	/**
     * @Route("/edit_sub/{id}/{token}", name="admin_subscriber_edit")
     * 
     */
    public function editAction(Request $request, $id, $token)
    {
        $this->checkCsrfToken('administration', $token);
        
       #not finished

		return $this->redirect($this->generateUrl('list'));
	}
}
