<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Board;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['placeholder' => 'Title', 'tabindex' => 1],
                'label' => false,
                'required' => false,
            ])
            ->add('board', HiddenEntityType::class, [
                'class' => Board::class,
            ])
            ->add('parent_post', HiddenEntityType::class, [
                'class' => Post::class,
            ])
            ->add('message', null, [
                'attr' => ['placeholder' => 'Message', 'tabindex' => 2, 'rows' => 5],
                'label' => false
            ])
            ->add('imageFile', VichImageType::class, ['required' => false]);


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $post = $event->getData();
            $form = $event->getForm();

            if (null === $post->getBoard()) {
                $form->add('board', EntityType::class, [
                    'class' => Board::class,
                    'choice_label' => 'name',
                    'label' => false
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
