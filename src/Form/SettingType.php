<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['data']->getType()) {
            case 'text':
                $value = 'value';
                $type = TextType::class;

                break;
            case 'checkbox':
                $value = 'value_bool';
                $type = CheckboxType::class;

                break;
        }

        $builder
            ->add(
                'name',
                null,
                [
                    'attr' => ['readonly' => 'true'],
                    'label' => false,
                ]
            )
            ->add(
                $value,
                $type,
                [
                    'attr' => ['placeholder' => 'value'],
                    'label' => false,
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
