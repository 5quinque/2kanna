<?php

namespace App\Service;

use App\Repository\BannedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;

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
            echo "{$d}";
        }
    }

    public function isRequesterBanned()
    {
        $request = Request::createFromGlobals();

        /**
         * Following code is similar to IpUtils::checkIp, but returns the Banned Entity.
         */
        $requestIp = $request->getClientIp();

        $method = substr_count($requestIp, ':') > 1 ? 'checkIp6' : 'checkIp4';

        foreach ($this->bannedRepository->findAllArr() as $ip) {
            if (IpUtils::$method($requestIp, $ip)) {
                return $this->bannedRepository->findOneBy(['ipAddress' => $ip]);
            }
        }

        return false;
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
