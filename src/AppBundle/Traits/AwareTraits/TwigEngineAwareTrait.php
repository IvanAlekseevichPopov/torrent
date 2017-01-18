<?php

declare(strict_types = 1);

namespace AppBundle\Traits\AwareTraits;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 *
 *
 * @author Попов Иван
 */
trait TwigEngineAwareTrait
{
    /** @var EngineInterface */
    private $twigEngine;

    /**
     * Сеттер шаблонизатора
     *
     * @param EngineInterface $twigEngine
     *
     * @return $this
     */
    public function setTwigEngine(EngineInterface $twigEngine)
    {
        $this->twigEngine = $twigEngine;

        return $this;
    }

    /**
     * Геттер шаблонизатора
     *
     * @return EngineInterface
     */
    public function getTwigEngine(): EngineInterface
    {
        return $this->twigEngine;
    }
}
