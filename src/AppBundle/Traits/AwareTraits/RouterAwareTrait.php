<?php

declare(strict_types = 1);

namespace AppBundle\Traits\AwareTraits;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 *
 *
 * @author Попов Иван
 */
trait RouterAwareTrait
{
    /** @var Router */
    private $router;

    /**
     * Сеттер роутера
     *
     * @param Router $router
     *
     * @return $this
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Геттер роутера
     *
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }
}
