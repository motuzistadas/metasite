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
                   'label' => false,
                   'attr' => [
                   		'placeholder' => 'Name'
                   ]
               ])
               ->add('surname', TextType::class, [
                   'label'    => false,
                   'attr' => [
                   		'placeholder' => 'Surname'
                   ]
               ])
               ->add('email', EmailType::class, [
                   'label'    => false,
                   'attr' => [
                   		'placeholder' => 'Email'
                   ]
               ])
               ->add('mobile', TextType::class, [
                   'label'    => false,
                   'attr' => [
                   		'placeholder' => 'Mobile'
                   ]
               ])
			   ->add('submit', SubmitType::class, [
					'attr' => [
						'class' => 'button tm'
					],
					'label' => 'Send'
				]);
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
			'data_class' => Orders::class
		]);
	}

}