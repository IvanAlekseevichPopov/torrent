<?php

namespace AppBundle\Controller\SonataAdmin\Custom;

use Sonata\AdminBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Test controller for sonata admin panels
 *
 * @author Popov Ivan <ivan.alekseevich.popov@gmail.com>
 */
class TestCustomSonataController extends CoreController
{

    /**
     * @Route(
     *     "/admin/test",
     *     name="taasdfadsgahsfdhasdfhafhd"
     * )
     *
     * @return Response
     */
    public function testAction()
    {
        dump('asdf');

        return $this->render('@App/SonataAdmin/test_custom_sonata_template.html.twig', [
            'test' => 'test1'
        ]);
    }
}
