<?php

namespace App\Service;

use App\Repository\BannedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class BannedIP
{
    private $em;
    private $bannedRepository;
    private $cache;
    private $ipAddress;
    private $hashedAddress;

    public function __construct(
        EntityManagerInterface $em,
        BannedRepository $bannedRepository,
        TagAwareCacheInterface $bansCache
    ) {
        $this->em = $em;
        $this->bannedRepository = $bannedRepository;
        $this->cache = $bansCache;
    }

    private function setIpAddress()
    {
        $request = Request::createFromGlobals();
        $this->ipAddress = $request->getClientIp();
        $this->hashedAddress = hash('sha256', $this->ipAddress);
    }

    public function printDebug(array $debug)
    {
        foreach ($debug as $d) {
            echo "{$d}";
        }
    }

    public function clearBanCache($key = null)
    {
        if ($key) {
            $this->cache->delete($key);
        } else {
            $this->cache->invalidateTags(['bans']);
        }
    }

    public function isRequesterBanned()
    {
        $this->setIpAddress();

        return $this->cache->get($this->hashedAddress, function (ItemInterface $item) {
            $item->tag('bans');

            /**
             * Following code is similar to IpUtils::checkIp, but returns the Banned Entity.
             */
            $method = substr_count($this->ipAddress, ':') > 1 ? 'checkIp6' : 'checkIp4';

            foreach ($this->bannedRepository->findAllArr() as $ip) {
                if (IpUtils::$method($this->ipAddress, $ip)) {
                    return $this->bannedRepository->findOneBy(['ipAddress' => $ip]);
                }
            }

            return false;
        });
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
