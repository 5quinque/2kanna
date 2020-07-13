<?php

namespace App\Form;

use App\Entity\Banned;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BannedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'ipAddress',
                null,
                [
                    'attr' => ['placeholder' => 'IP Address'],
                    'label' => false,
                ]
            )
            ->add(
                'reason',
                null,
                [
                    'attr' => ['placeholder' => 'Ban Reason'],
                    'label' => false,
                ]
            )
            ->add('banTime', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Ban starts',
                'data' => new \DateTime(),
            ])
            ->add('unbanTime', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Ban ends',
                'data' => new \DateTime('+3 day'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Banned::class,
        ]);
    }
}
