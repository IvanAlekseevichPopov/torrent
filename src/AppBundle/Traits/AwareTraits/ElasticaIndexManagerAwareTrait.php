<?php

namespace AppBundle\Traits\AwareTraits;

use FOS\ElasticaBundle\Index\IndexManager;

trait ElasticaIndexManagerAwareTrait
{
    /** @var IndexManager|null */
    private $indexManager;

    /**
     * Геттер IndexManager
     *
     * @return IndexManager|null
     */
    public function getIndexManager()
    {
        return $this->indexManager;
    }

    /**
     * Сеттер IndexManager
     *
     * @param IndexManager $indexManager
     *
     * @return $this
     */
    public function setIndexManager(IndexManager $indexManager)
    {
        $this->indexManager = $indexManager;

        return $this;
    }
}
