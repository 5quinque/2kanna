<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // [TODO] - Tidy up
        switch ($options['data']->getType()) {
            case 'text':
                $value = 'value';
                $type = TextType::class;
                $options = [
                    'attr' => ['placeholder' => 'value'],
                    'label' => false,
                    'required' => false,
                ];

                break;
            case 'checkbox':
                $value = 'value_bool';
                $type = CheckboxType::class;
                $options = [
                    'attr' => ['placeholder' => 'value'],
                    'label' => false,
                    'required' => false,
                ];

                break;
            case 'choice':
                $value = 'value';
                $type = ChoiceType::class;

                $choices = [];

                foreach ($builder->getData()->getSettingChoices() as $choice) {
                    $choices[$choice->getKey()] = $choice->getValue();
                }

                $options = [
                    'attr' => ['placeholder' => 'value'],
                    'label' => false,
                    'required' => false,
                    'choices' => $choices,
                ];

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
                $options
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
