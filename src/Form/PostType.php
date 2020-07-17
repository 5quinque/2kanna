<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Board;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Service\GetWordFilters;
use App\Service\BannedIP;
use App\Service\FindPosts;

class PostType extends AbstractType
{
    private $getWordFilters;
    private $bannedIP;
    private $findPosts;

    public function __construct(GetWordFilters $getWordFilters, BannedIP $bannedIP, FindPosts $findPosts)
    {
        $this->getWordFilters = $getWordFilters;
        $this->bannedIP = $bannedIP;
        $this->findPosts = $findPosts;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => ['placeholder' => 'Title', 'tabindex' => 1],
                'label' => false,
                'required' => false,
            ])
            ->add('board', HiddenEntityType::class, [
                'class' => Board::class,
            ])
            ->add('parent_post', HiddenEntityType::class, [
                'class' => Post::class,
                'required' => false
            ])
            ->add('message', null, [
                'attr' => ['placeholder' => 'Message', 'tabindex' => 2, 'rows' => 5],
                'label' => false,
                'required' => true
            ])
            ->add('imageFile', VichImageType::class, [
                'attr' => ['placeholder' => 'Choose Image'],
                'required' => false,
                'label' => false,
                'allow_delete' => true,
                'download_label' => 'hello',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'constraints' => array(
                new Assert\Callback(array($this, 'messageFilter')),
                new Assert\Callback(array($this, 'bannedFilter')),
                new Assert\Callback(array($this, 'cooldown'))
            )
        ]);
    }

    public function messageFilter(Post $post, ExecutionContextInterface $context)
    {
        $badWords = $this->getWordFilters->findAllFilters();
        foreach ($badWords as $word) {
            if (preg_match($word->getBadWord(), $post->getMessage())) {
                $context->buildViolation('Choose something more interesting to say.')
                ->atPath('message')
                ->addViolation();

                return false;
            }
        }
    }

    public function bannedFilter(Post $post, ExecutionContextInterface $context)
    {
        $banned = $this->bannedIP->isRequesterBanned();
        if ($banned) {
            $context->buildViolation("Your IP address is banned. You are unable to post until {$banned->getUnbanTime()->format('Y-m-d H:i:s')}")
                ->atPath('message')
                ->addViolation();
        }
    }

    public function cooldown(Post $post, ExecutionContextInterface $context)
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $userIP = $_SERVER['REMOTE_ADDR'];
        }

        $posts = $this->findPosts->isPosterHot($userIP);

        if (!empty($posts)) {
            $context->buildViolation("You're posting too frequently")
                ->atPath('message')
                ->addViolation();
        }
    }
}
