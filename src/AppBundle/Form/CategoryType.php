<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Categories;


class CategoryType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
			->add('name', TextType::class, array('label' => 'New Category'))
			->add('submit', SubmitType::class, [
				'attr' => [
					'class' => 'btn btn-success pull-right'
				],
				'label' => 'Add Category'
			]);
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
			'data_class' => Categories::class
		]);
	}

}