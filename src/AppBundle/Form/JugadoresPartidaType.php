<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JugadoresPartidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jugador_1_apodo', TextType::class, [
                'attr' => [
                    'maxlength' => 10
                ],
                'label' => 'Apodo',
                'empty_data' => 'Jugador 1'
            ])
            ->add('jugador_2_apodo', TextType::class, [
                'attr' => [
                    'maxlength' => 10
                ],
                'label' => 'Apodo',
                'empty_data' => 'Jugador 2'
            ])
        ;
    }
}