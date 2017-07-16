<?php

declare(strict_types = 1);

namespace AppBundle\Manager;

use AppBundle\Entity\Torrent;
use AppBundle\Repository\TorrentRepository;

class TorrentManager extends AppManagerAbstract
{
    const LATEST_TORRENT_LIST_LIMIT = 10;

    /**
     * Возвращает список последних добавленных торрентов
     *
     * @return array|null
     */
    public function getLatestTorrentsList(): ?array
    {
        return
            $this->getRepository()->findBy(
                [],
                ['createdAt' => 'DESC'],
                self::LATEST_TORRENT_LIST_LIMIT
            );
    }

    /**
     * @return TorrentRepository
     */
    public function getRepository(): TorrentRepository
    {
        return $this->getEntityManager()->getRepository(Torrent::class);
    }
}
