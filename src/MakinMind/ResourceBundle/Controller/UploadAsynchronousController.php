<?php

namespace MakinMind\ResourceBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File as File;

use MakinMind\ResourceBundle\Entity\Resource;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use MakinMind\UserBundle\Entity\User;

class UploadAsynchronousController extends Controller
{
	/**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
	public function uploadAction($idField = null)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		$listUploads = $em->getRepository('MakinMindResourceBundle:Resource')->findByOwner($user->getID());

		$resource = new Resource();
	  	
		$request = $this->getRequest();

	    if ($request->getMethod() == 'POST') {

	    	$file = new File($_FILES["file"]["tmp_name"]);
            $resource->setFile($file);
            $resource->setOwner($user);
            error_log($file.getMaxFilesize());
            var_dump($file.getMaxFilesize());
		    $em->persist($resource);
		    $em->flush();
		    
		    $result = array('url' => $resource->getWebUrl(), 'id_field' => $idField, 'mime_type' => $resource->getMimeType());

 			return new Response (json_encode($result));
		}

    	return $this->render('MakinMindResourceBundle:Upload:upload_asynchronous.html.twig', array('list_uploads' => $listUploads, 'id_field' => $idField));
	}
}