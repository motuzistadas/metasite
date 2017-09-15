<?php

namespace AppBundle\Controller;

use AppBundle\Base\BaseController;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Orders;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;

class ListController extends BaseController
{
    /**
     * @Route("/list", name="list")
     * @Template()
     */
    public function ListAction(Request $request)
    {
    	$filter = $request->query->get('filter');

        $qb = $this
           ->getDoctrine()->getManager()
           ->createQueryBuilder()
           ->select('g')
           ->from(Orders::class, 'g')
           
        ;

        if ($filter) {
            $qb
               ->where('g.name LIKE :criteria OR g.surname LIKE :criteria OR g.email LIKE :criteria')
               ->setParameter('criteria', '%'.$filter.'%')
            ;
        }

        return [
            'orderBy' => $this->orderBy($qb, Orders::class, 'g.orderDate', 'DESC'),
            'pager'   => $this->getPager($qb),
        ];
    
    }

    public function checkCsrfToken($key, $token)
    {
        if ($token !== $this->get('security.csrf.token_manager')->getToken($key)->getValue()) {
            throw new InvalidCsrfTokenException('Invalid CSRF token');
        }
    }

    /**
     * @Route("/delete_order/{id}/{token}", name="admin_order_delete")
     * 
     */
    public function deleteAction(Request $request, $id, $token)
    {
        $this->checkCsrfToken('administration', $token);
        $manager = $this->getDoctrine()->getEntityManager();
        $entity = $manager->getRepository('AppBundle:Orders')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException();
        }

        $em = $this->get('doctrine')->getManager();
        $em->remove($entity);
        $em->flush();

       // $this->success('admin.groups.deleted', ['%id%' => $entity->getId()]);

        return $this->redirect($this->generateUrl('list'));
    }


}
