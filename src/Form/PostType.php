<?php

namespace App\Form;

use App\Entity\Board;
use App\Entity\Post;
use App\Service\BannedIP;
use App\Service\FindPosts;
use App\Service\GetWordFilters;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('board', HiddenEntityType::class, [
                'class' => Board::class,
            ])
            ->add('parent_post', HiddenEntityType::class, [
                'class' => Post::class,
                'required' => false,
            ])
            ->add('message', null, [
                'attr' => ['placeholder' => 'Message', 'tabindex' => 2, 'rows' => 5],
                'label' => false,
                'required' => true,
            ])
            ->add('imageFile', VichImageType::class, [
                'attr' => ['placeholder' => 'Choose File'],
                'required' => false,
                'label' => false,
                'allow_delete' => true,
                'download_label' => 'hello',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'constraints' => [
                new Assert\Callback([$this, 'messageFilter']),
                new Assert\Callback([$this, 'bannedFilter']),
                new Assert\Callback([$this, 'cooldown']),
            ],
        ]);
    }

    public function messageFilter(Post $post, ExecutionContextInterface $context)
    {
        $badWords = $this->getWordFilters->findAllFilters();
        foreach ($badWords as $word) {
            if (preg_match($word->getBadWord(), $post->getMessage())) {
                $context->buildViolation('Choose something more interesting to say.')
                    ->atPath('message')
                    ->addViolation()
                ;

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
                ->addViolation()
            ;
        }
    }

    public function cooldown(Post $post, ExecutionContextInterface $context)
    {
        $request = Request::createFromGlobals();
        $requesterIP = $request->getClientIp();

        // Local host doesn't need to cooldown
        if ($requesterIP === '127.0.0.1') {
            return;
        }

        $posts = $this->findPosts->isPosterHot($requesterIP);

        if (!empty($posts)) {
            $context->buildViolation("You're posting too frequently")
                ->atPath('message')
                ->addViolation()
            ;
        }
    }
}
