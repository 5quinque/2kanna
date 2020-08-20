<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                null,
                [
                    'attr' => ['placeholder' => 'Username'],
                    'label' => false,
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                'choices' => ['Admin' => 'ROLE_ADMIN', 'Moderator' => 'ROLE_MODERATOR', 'User' => 'ROLE_USER'],
                'required'   => true,
                'expanded' => false,
                'multiple' => true,
                'label' => false,
            ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
