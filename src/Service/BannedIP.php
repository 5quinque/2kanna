<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BannedRepository;

class BannedIP
{
    private $em;
    private $bannedRepository;

    public function __construct(EntityManagerInterface $em, BannedRepository $bannedRepository)
    {
        $this->em = $em;
        $this->bannedRepository = $bannedRepository;
    }

    public function printDebug(array $debug)
    {
        foreach ($debug as $d) {
            print "$d";
        }
    }

    public function isRequesterBanned()
    {
        $banned = $this->bannedRepository->findOneBy(["ipAddress" => HelperUtil::getIPAddress()]);

        return $banned;
    }

    public function findOldBans()
    {
        $debug = [];

        $oldBans = $this->bannedRepository->findByUnbanBeforeNow();
        foreach ($oldBans as $b) {
            $debug[] = "Deleting Banned IP Address: {$b->getIPAddress()}\n";

            $this->em->remove($b);
            $this->em->flush();
        }

        $this->printDebug($debug);
    }
}
