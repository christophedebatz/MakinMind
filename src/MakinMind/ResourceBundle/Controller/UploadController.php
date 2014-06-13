<?php

namespace MakinMind\ResourceBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use MakinMind\ResourceBundle\Entity\Resource;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use MakinMind\UserBundle\Entity\User;

class UploadController extends Controller
{
	/**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
	public function uploadAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		$listUploads = $em->getRepository('MakinMindResourceBundle:Resource')->findByOwner($user->getID());

		$resource = new Resource();

	    $form = $this->createFormBuilder($resource)->add('file', 'file')->getForm();

	    $request = $this->getRequest();
	  
	    if ($request->getMethod() == 'POST') {
        	$form->bindRequest($request);

	        if ($form->isValid()) {
	            $resource->setOwner($user);

			    $em->persist($resource);
			    $em->flush();
			    $this->get('session')->setFlash('info', 'Le fichier a bien été uploadé.');
	            return $this->redirect($this->generateUrl('resource_homepage'));
	        }
    	}

    	return $this->render('MakinMindResourceBundle:Upload:upload.html.twig', array('form' => $form->createView(), 'list_uploads' => $listUploads));
	}

	// public function displayUploadsAction()
	// {
	// 	$em = $this->getDoctrine()->getEntityManager();
	// 	$user = $this->container->get('security.context')->getToken()->getUser();
	// 	$listUploads = $em->getRepository('MakinMindResourceBundle:Resource')->findByOwner($user->getID());
	// 	return $this->render('MakinMindResourceBundle:Upload:personal_uploads.html.twig', array('list_uploads' => $listUploads));
	// }
}