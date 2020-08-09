<?php

namespace App\Util;

use App\Entity\Board;
use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class BoardUtil
{
    private $boardRepository;
    private $postRepository;
    private $board;
    private $cache;

    public function __construct(
        BoardRepository $boardRepository,
        PostRepository $postRepository,
        TagAwareCacheInterface $boardsCache
    ) {
        $this->boardRepository = $boardRepository;
        $this->postRepository = $postRepository;

        $this->cache = $boardsCache;
    }

    public function setBoard(Board $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function boards()
    {
        return $this->cache->get('boardlist', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $item->tag('boards');

            return $this->boardRepository->findAll();
        });
    }

    public function boardPostCount(Board $board)
    {
        $this->setBoard($board);

        return $this->cache->get("{$board->getName()}_postcount", function (ItemInterface $item) {
            $bp = $this->postRepository->findLatest(1, $this->board);
            $item->tag('boards');
            $item->expiresAfter(3600);

            return $bp->getNumResults();
        });
    }

    public function boardPostCountAll()
    {
        $postCount = [];

        foreach ($this->boards() as $b) {
            $postCount[$b->getName()] = $this->boardPostCount($b);
        }

        return $postCount;
    }

    public function clearSetting(string $name)
    {
        $this->cache->delete($name);
    }
}
