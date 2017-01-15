<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Torrent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Torrent controller.
 *
 * @Route("torrent")
 */
class TorrentController extends Controller
{
    /**
     * Lists all torrent entities.
     *
     * @Route("/", name="torrent_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $torrents = $em->getRepository('AppBundle:Torrent')->findAll();

        return $this->render('torrent/index.html.twig', array(
            'torrents' => $torrents,
        ));
    }

    /**
     * Creates a new torrent entity.
     *
     * @Route("/new", name="torrent_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $torrent = new Torrent();
        $form = $this->createForm('AppBundle\Form\TorrentType', $torrent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($torrent);
            $em->flush($torrent);

            return $this->redirectToRoute('torrent_show', array('id' => $torrent->getId()));
        }

        return $this->render('torrent/new.html.twig', array(
            'torrent' => $torrent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a torrent entity.
     *
     * @Route("/{id}", name="torrent_show")
     * @Method("GET")
     */
    public function showAction(Torrent $torrent)
    {
        $deleteForm = $this->createDeleteForm($torrent);

        return $this->render('torrent/show.html.twig', array(
            'torrent' => $torrent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing torrent entity.
     *
     * @Route("/{id}/edit", name="torrent_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Torrent $torrent)
    {
        $deleteForm = $this->createDeleteForm($torrent);
        $editForm = $this->createForm('AppBundle\Form\TorrentType', $torrent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('torrent_edit', array('id' => $torrent->getId()));
        }

        return $this->render('torrent/edit.html.twig', array(
            'torrent' => $torrent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a torrent entity.
     *
     * @Route("/{id}", name="torrent_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Torrent $torrent)
    {
        $form = $this->createDeleteForm($torrent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($torrent);
            $em->flush($torrent);
        }

        return $this->redirectToRoute('torrent_index');
    }

    /**
     * Creates a form to delete a torrent entity.
     *
     * @param Torrent $torrent The torrent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Torrent $torrent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('torrent_delete', array('id' => $torrent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
