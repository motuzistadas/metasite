<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Orders;


class OrderType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
			->add('name', TextType::class, [
                   'label' => 'Name',
               ])
               ->add('surname', TextType::class, [
                   'label'    => 'Surname',
               ])
               ->add('email', EmailType::class, [
                   'label'    => 'E-mail',
               ])
               ->add('mobile', TextType::class, [
                   'label'    => 'Mobile',
               ])
			   ->add('submit', SubmitType::class, [
					'attr' => [
						'class' => 'btn btn-success pull-right'
					],
					'label' => 'Order'
				]);
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
			'data_class' => Orders::class
		]);
	}

}