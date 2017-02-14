<?php
// src/HtlSpendenportalBundle/Form/RegistrationType.php

namespace Htl\SpendenportalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('firstname');

        $builder->add('lastname');

        $builder->add('age');

        $builder->add('street');

        $builder->add('zipcode');

        $builder->add('housenumberDoornumber');


        $builder->add('isDonator',ChoiceType::class,
        array('choices' => array(
            'Spender' => '1',
            'Empfänger' => '0'),
            'choices_as_values' => true,'multiple'=>false,'expanded'=>true));

        $builder->add('fileUpload');

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}