<?php

namespace App\Form;

use App\Entity\Board;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class BoardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                null,
                ['attr' => ['placeholder' => 'Board Name'],
                'label' => false,
            ]
            )
            ->add(
                'password',
                PasswordType::class,
                ['label' => false, 'attr' => ['placeholder' => 'Password']],
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Board::class,
            'constraints' => array(
                new Assert\Callback(array($this, 'boardNameValid')),
            ),
        ]);
    }

    public function boardNameValid(Board $board, ExecutionContextInterface $context)
    {
        if (!preg_match('/^\w+$/', $board->getName())) {
            $context->buildViolation("Illegal characters in board name")
                ->atPath('name')
                ->addViolation();
        }
    }
}
