<?php

namespace App\Util;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Banned;
use App\Entity\WordFilter;
use App\Entity\User;

class UserUtil
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function banIP(Banned $banned)
    {
        $this->em->persist($banned);
        $this->em->flush();

        return true;
    }

    public function addBadWord(WordFilter $wordFilter)
    {
        $this->em->persist($wordFilter);
        $this->em->flush();

        return true;
    }

    public function addUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();

        return true;
    }
}
