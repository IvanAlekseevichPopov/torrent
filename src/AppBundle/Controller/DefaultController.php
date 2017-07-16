<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/symfony", name="homepage")
     */
    public function indexAction()
    {
        $routesCollection = $this->get('router')->getRouteCollection();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'routes' => $routesCollection
        ]);
    }

    /**
     * Список последних торрентов на главной
     *
     * @Route("/", name="torrent_list")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function indexMainAction(Request $request)
    {
        $torrents = $this->get('app.manager.torrent_manager')->getLatestTorrentsList();
        dump($torrents);

        return $this->render('torrent/list.twig', array(
            'torrents' => $torrents,
        ));
    }
}
