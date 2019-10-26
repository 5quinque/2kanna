<?php
namespace App\Service;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\BannedRepository;

class BannedIP
{
    private $om;
    private $bannedRepository;

    public function __construct(ObjectManager $objectManager, BannedRepository $bannedRepository)
    {
        $this->om = $objectManager;
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
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $userIP = $_SERVER['REMOTE_ADDR'];
        }

        $banned = $this->bannedRepository->findOneBy(["ipAddress" => $userIP]);

        return $banned;
    }

    public function findOldBans()
    {
        $debug = [];

        $oldBans = $this->bannedRepository->findByUnbanBeforeNow();
        foreach ($oldBans as $b) {
            $debug[] = "Deleting Banned IP Address: {$b->getIPAddress()}\n";

            $this->om->remove($b);
            $this->om->flush();
        }

        $this->printDebug($debug);
    }

}