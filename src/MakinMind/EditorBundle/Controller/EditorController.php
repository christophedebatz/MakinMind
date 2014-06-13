<?php

namespace MakinMind\EditorBundle\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MakinMind\ProjectBundle\Entity\Project;
use MakinMind\ProjectBundle\Entity\Contract;
use MakinMind\UserBundle\Entity\User;

use MakinMind\ProjectBundle\Entity\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use MakinMind\EditorBundle\Entity\Editor;
use MakinMind\EditorBundle\Entity\FileTreeManager;
use MakinMind\EditorBundle\Entity\File;


class EditorController extends Controller
{
	/**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
	public function editorAction(Project $project)
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		if($user != $project->getOwner())
		{
			$emTmp = $this->get('doctrine.orm.entity_manager');
			$contract = $emTmp->getRepository('MakinMindProjectBundle:Contract')->findOneBy(array("worker"=>$user->getId(), "project" => $project->getId()));
			if(is_null($contract))
				throw $this->createNotFoundException("Cette page n'hexiste pas !");
			else
			{
				if(is_null($contract->getSignDate()))
					throw $this->createNotFoundException("Vous n'avez pas signé le contrat ! ");
			}

		}

		$request = $this->getRequest();
		$data = '';
		if ($request->getMethod() == 'GET' || $request->getMethod() == 'POST') {
			$dir = $this->get('kernel')->getRootDir();
 			$dir = str_replace('/app', '/src/MakinMind/ProjectBundle/Resources/Projects', $dir);
 			$dir .= '/P'.$project->getId();
			$fichier = $request->query->get('fichier');

			$user = $this->container->get('security.context')->getToken()->getUser();
			$em = $this->get('doctrine.orm.entity_manager');
			$fileInDb = $em->getRepository('MakinMindEditorBundle:File')->findOneByName(str_replace('FOLDER_SEPARATOR', '/', $fichier));
			$editable = false;
			if($fileInDb)
			{
				$author = $fileInDb->getAuthor();
				$editable = false;
				if( $author == $user || $fileInDb->getPrivileges())
					$editable = true;
			}


			if(!empty($fichier))
			{
				
				$filename =$dir.str_replace('FOLDER_SEPARATOR', '/', $fichier);
				$newdata = $request->request->get("newd");
		    	if(!empty($newdata))
		    	{
					// open file 
					$fw = fopen($filename, 'w') or die('Could not open file');
					// write to file
					// added stripslashes to $newdata
					$fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
					// close file
					fclose($fw);
		    	}
				// open file
				$fh = fopen($filename, "r") or die("Could not open file!");
				// read file contents
				 

				if(filesize($filename)>0)
					$data = fread($fh, filesize($filename)) or die("Could not read file!");
				else
					$data="";
				// close file
				fclose($fh);
			}
	    }
		return $this->render('MakinMindEditorBundle:Editor:editor.html.twig', array('data'=>$data, 'project'=> $project, 'file'=> $fichier, 'editable'=> $editable, 'fileInDb' => $fileInDb));
	}

	/**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
	public function treeManagerAction(Project $project)
	{
		define("IN_PHP", true);
		$numProject = $project->getId();
		define ("DATABASE_PLATFORM", 0); // Don't edit
		define ("FILE_SYSTEM_PLATFORM", 1); // Don't edit
		define ("TARGET_PLATFORM", FILE_SYSTEM_PLATFORM);
		$dir = $this->get('kernel')->getRootDir();
	 	$dir = str_replace('/app', '/src/MakinMind/ProjectBundle/Resources/Projects', $dir);
		$dir .= '/P'.$numProject;
		define("FILE_ROOT", $dir); 
		define ("SUCCESS", 1);  // Don't edit
		define ("FAILED", -1);  // Don't edit
		define ("FAILED_FILE_WITH_SAME_NAME_EXIST", -2); // Don't edit, it is only used in FILE_SYSTEM_PLATFORM

		error_reporting(0); 
		require_once('TreeManager/includes/functions.php');
		$treeManager = new FileTreeManager(FILE_ROOT);

		$rootName = "Root";
		$pageName = $this->generateUrl('EditorBundle_structManage', array('id'=>$project->getId()) );
		$treeElements = $treeManager->getElementList(null, $pageName);
		$rootId = $treeManager->getRootId();
		return $this->render('MakinMindEditorBundle:Editor:tree.html.twig', array('project'=> $project, 'rootName'=>$rootName, 'treeElements'=>$treeElements, 'rootId'=>$rootId));
	}

	
	/**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
	public function manageStructureAction(Project $project)
	{
		if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) 
		{
			$action = $_REQUEST['action'];	
		}
		else 
		{
			die("FAILED");
		}
		define("IN_PHP", true);
		$dir = $this->get('kernel')->getRootDir();
		$dir = str_replace('/app', '/src/MakinMind/ProjectBundle/Resources/Projects', $dir);
		$dir .= '/P'.$project->getId();

		define("FILE_ROOT", $dir);
		require_once("TreeManager/common.php");

		$out = NULL;

		$request = $this->getRequest();
		switch($action)
		{
			case "insertElement":
			{	
				$ownerEl = $request->request->get('ownerEl');
				$slave = $request->request->get('slave');
				$name = $request->request->get('name');

				/**
				 * insert new element
				 */	
				if ( ( isset($name) === true && $name != NULL )  &&
				     ( isset($ownerEl) === true && $ownerEl != NULL )  &&
			         ( isset($slave ) === true && $slave != NULL )		   
				   )
				{				
					//$ownerEl = checkVariable($ownerEl);
					$slave = (int) checkVariable($slave);
					$name = checkVariable($name);			

					//insert in DB if element is a file
					if($slave)
					{
						$date = new \Datetime();
						$author = $this->container->get('security.context')->getToken()->getUser();
						if(strcmp(str_replace("FOLDER_SEPARATOR", "/", $ownerEl), "/") == 0)
							$fileName = str_replace("FOLDER_SEPARATOR", "/", $ownerEl) . $name;
						else
							$fileName = str_replace("FOLDER_SEPARATOR", "/", $ownerEl) . '/' . $name;

						$file = new File();
						$file->setProject($project);
						$file->setAuthor($author);
						$file->setPrivileges(0);
						$file->setTime($date);
						$file->setName($fileName);
						$em = $this->get('doctrine.orm.entity_manager');
	    				$em->persist($file);
	    				$em->flush();
					}
					$out = $treeManager->insertElement($name, $ownerEl, $slave);

				}
				else {
					$out = FAILED; 
				}
			}
			break;	
			case  "getElementList":  
			{
				$ownerEl = $request->query->get('ownerEl');

				/**
				 * getting element list
				 */
		        if( isset($ownerEl) == true && $ownerEl != NULL ) {  	
					$ownerEl = checkVariable($ownerEl); 
				}
				else {
					$ownerEl = 0;
				}
				$pageName = $this->generateUrl('EditorBundle_structManage', array('id'=>$project->getId()));
		  		$out = $treeManager->getElementList($ownerEl, $pageName);
		    }
			break;		
		    case "updateElementName":
		    {
				$ownerEl = $request->request->get('ownerEl');
				$elementId = $request->request->get('elementId');
				$name = $request->request->get('name');
		    	/**
		    	 * Changing element name
		    	 */
				if (isset($name) && !empty($name) &&
				    isset($elementId) && !empty($elementId) &&
				    isset($ownerEl ) && $ownerEl  != NULL)
				{			

					$name = checkVariable($name); 
					$elementId = checkVariable($elementId); 		
					$ownerEl = checkVariable($ownerEl); 

					$author = $this->container->get('security.context')->getToken()->getUser();
					$em = $this->get('doctrine.orm.entity_manager');
					
					$fileName = str_replace("FOLDER_SEPARATOR", "/", $elementId);
					if(strcmp($ownerEl, "FOLDER_SEPARATOR") == 0)
						$fileName = substr($fileName, strrpos($fileName, "/"), strlen($fileName));
					else
						$fileName = str_replace("FOLDER_SEPARATOR", "/", $ownerEl) . substr($fileName, strrpos($fileName, "/"), strlen($fileName));

					$fullPath = $this->get('kernel')->getRootDir();
	 				$fullPath = str_replace('/app', '/src/MakinMind/ProjectBundle/Resources/Projects', $fullPath);
					$fullPath .= '/P'.$project->getId();
					$fullPath .=  $fileName;

					if(!is_dir($fullPath))
					{
						if(strcmp($ownerEl, "FOLDER_SEPARATOR") == 0)
							$realElementName = substr($fileName, strrpos($fileName, "/"), strlen($fileName));
						else
						{	//echo  str_replace("FOLDER_SEPARATOR", "/", $ownerEl ) . '<br/>';
							$realElementName = str_replace("FOLDER_SEPARATOR", "/", $ownerEl ) . substr($fileName, strrpos($fileName, "/"), strlen($fileName));
						}
						//echo $realElementName;
						$file = $em->getRepository('MakinMindEditorBundle:File')->findOneBy(array('name' => $fileName, 'author' => $author->getId(), 'project'=>$project->getId()));
						if($file)
						{
							if(strcmp(str_replace("FOLDER_SEPARATOR", "/", $ownerEl) , '/') == 0) 
								$file->setName(str_replace("FOLDER_SEPARATOR", "/", $ownerEl) .  $name);
							else
								$file->setName(str_replace("FOLDER_SEPARATOR", "/", $ownerEl) . '/' . $name);

							$em->flush();
							$out = $treeManager->updateElementName($name, $elementId, $ownerEl);
						}
						else
							$out = FAILED;
					}
					else // if dir
					{
						if(strcmp($ownerEl, "FOLDER_SEPARATOR") == 0)
							$realElementName = substr($fileName, strrpos($fileName, "/"), strlen($fileName));
						else
							$realElementName = str_replace("FOLDER_SEPARATOR", "/", $ownerEl ) . substr($fileName, strrpos($fileName, "/"), strlen($fileName));
						//echo $realElementName;
						$query = $em->createQuery("SELECT f FROM MakinMind\EditorBundle\Entity\File f WHERE f.project = ?1 and f.author = ?2 and f.name LIKE ?3");
       					$query->setParameter(1, $project);
       					$query->setParameter(2, $author);
       					$query->setParameter(3, $realElementName.'%');
       					$files = $query->getResult();
       					if($files)
       					{
       						$owner = str_replace("FOLDER_SEPARATOR", "/", $ownerEl);
	       					foreach ($files as $file) {
	       						if(strcmp($owner , '/') == 0) 
	       							$newName = str_replace($fileName,  $owner. $name, $file->getName());
	       						else
	       							$newName = str_replace($fileName, $owner . '/'. $name, $file->getName());

	       						$file->setName($newName);
	       					}
	       					$em->flush();
	       					$out = $treeManager->updateElementName($name, $elementId, $ownerEl);
       					}
       					else
       						$out = FAILED;
					}

				}                         
				else {
					$out = FAILED;	
				}
		    }    
		    break;

			case "deleteElement":
			{
				$ownerEl = $request->request->get('ownerEl');
				$elementId = $request->request->get('elementId');
				/**
				 * deleting an element and elements under it if exists
				 */
				if (isset($elementId) && !empty($elementId) &&
				    isset($ownerEl) && $ownerEl != NULL)
				{
		        	$elementId =  checkVariable($elementId);	 
		        	$ownerEl = checkVariable($ownerEl); 			 
		        	$index = 0;
					
					
					// delete in DB
					$realElementId = str_replace("FOLDER_SEPARATOR", "/", $elementId);
					if(strcmp($ownerEl, "FOLDER_SEPARATOR") == 0)
						$realElementId = substr($realElementId, strrpos($realElementId, "/"), strlen($realElementId));
					else
						$realElementId = str_replace("FOLDER_SEPARATOR", "/", $ownerEl) . substr($realElementId, strrpos($realElementId, "/"), strlen($realElementId));

					$fileName = str_replace("FOLDER_SEPARATOR", "/", $realElementId);
					$fullPath = $this->get('kernel')->getRootDir();
	 				$fullPath = str_replace('/app', '/src/MakinMind/ProjectBundle/Resources/Projects', $fullPath);
					$fullPath .= '/P'.$project->getId();
					$fullPath .=  $fileName;

					$em = $this->get('doctrine.orm.entity_manager');
					$author = $this->container->get('security.context')->getToken()->getUser();

					// element is not a directory, remove it from database
					if(!is_dir($fullPath))
					{
						$file = $em->getRepository('MakinMindEditorBundle:File')->findOneBy(array('name' => $fileName, 'author' => $author->getId(), 'project'=>$project->getId()));
						if($file)
						{
							$em->remove($file);
							$em->flush();
							$out = $treeManager->deleteElement($elementId, $index, $ownerEl);
						}

					}
					else // if dir
					{
						$fileName = str_replace("FOLDER_SEPARATOR", "/", $elementId);
						$query = $em->createQuery("SELECT f FROM MakinMind\EditorBundle\Entity\File f WHERE f.project = ?1 and f.author = ?2 and f.name LIKE ?3");
       					$query->setParameter(1, $project);
       					$query->setParameter(2, $author);
       					$query->setParameter(3, $fileName.'%');
       					$files = $query->getResult();
       					if($files)
       					{
	       					foreach ($files as $file) {
	       						$em->remove($file);
	       					}
	       					$em->flush();
	       					$out = $treeManager->deleteElement($elementId, $index, $ownerEl);
       					}
       					else
       						$out = FAILED;
					}
			    }
		        else {
					$out = FAILED;	
				}
			}
			break;
			case "changeOrder":
			{	
				$elementId = $request->request->get('elementId');
				$destOwnerEl = $request->request->get('destOwnerEl');	
				$position = $request->request->get('position');
				$oldOwnerEl = $request->request->get('oldOwnerEl');	
				/**
				 * Change the order of an element
				 */
				if ((isset($elementId) && $elementId != NULL) &&
					(isset($destOwnerEl) && $destOwnerEl != NULL) &&
					(isset($position) && $position != NULL) &&
					(isset($oldOwnerEl) && $oldOwnerEl != NULL) 
					)			
				{			
					$oldOwnerEl = checkVariable($oldOwnerEl); // path without name of element
					$elementId = checkVariable($elementId); // element to change with full path
					$destOwnerEl = checkVariable($destOwnerEl); // path of destination
					$position = (int) checkVariable($position);
					
					$elementBis = str_replace("FOLDER_SEPARATOR", "/", $elementId);
					$realElementId = $oldOwnerEl . '/' . substr($elementBis, strrpos($elementBis, "/"), strlen($elementBis));
					$fileName = substr($realElementId, strrpos($realElementId, "/")+1);
					$fullPath = $this->get('kernel')->getRootDir();
	 				$fullPath = str_replace('/app', '/src/MakinMind/ProjectBundle/Resources/Projects', $fullPath);
					$fullPath .= '/P'.$project->getId();
					if(strcmp($oldOwnerEl, "FOLDER_SEPARATOR") == 0)
						$fullPath .=  '/' . $fileName;
					else
						$fullPath .=  str_replace("FOLDER_SEPARATOR", "/", $oldOwnerEl) . '/' . $fileName;

					$em = $this->get('doctrine.orm.entity_manager');
					$author = $this->container->get('security.context')->getToken()->getUser();
					if(!is_dir($fullPath))
					{
						if(strcmp($oldOwnerEl, "FOLDER_SEPARATOR") == 0)
							$fileInDb = '/' . $fileName;
						else
							$fileInDb = str_replace("FOLDER_SEPARATOR", "/", $oldOwnerEl) . '/' . $fileName;

						$file = $em->getRepository('MakinMindEditorBundle:File')->findOneBy(array('name' => $fileInDb, 'project'=> $project->getId()));
						if($file)
						{
							$destOwner = str_replace("FOLDER_SEPARATOR", "/", $destOwnerEl);
							if(strcmp($destOwner, "/") == 0)
								$newName = '/' . $fileName;
							else
								$newName = $destOwner . '/' . $fileName;

							$file->setName($newName); 
							$em->flush();
							$out = $treeManager->changeOrder($elementId, $oldOwnerEl, $destOwnerEl, $position);
						}
						else
						{
							//$out = $treeManager->changeOrder($elementId, $oldOwnerEl, $destOwnerEl, $position);
							$out = 0;
						}
							
					}
					else
					{
						if(strcmp($oldOwnerEl, "FOLDER_SEPARATOR") == 0)
							$dirInDb = '/' . $fileName;
						else
							$dirInDb = str_replace("FOLDER_SEPARATOR", "/", $oldOwnerEl) . '/' . $fileName;

						$query = $em->createQuery("SELECT f FROM MakinMind\EditorBundle\Entity\File f WHERE f.project = ?1 and f.author = ?2 and f.name LIKE ?3");
       					$query->setParameter(1, $project);
       					$query->setParameter(2, $author);
       					$query->setParameter(3, $dirInDb.'%');
       					$files = $query->getResult();
       					if($files)
       					{
       						foreach ($files as $file) {
       							$dest = str_replace("FOLDER_SEPARATOR", '/', $destOwnerEl);
       							if(strcmp($dest, "/") == 0)
       								$newName = str_replace($dirInDb, '/' . $fileName, $file->getName());
       							else
       								$newName = str_replace($dirInDb, $dest . '/' . $fileName, $file->getName());
       							$file->setName($newName);
       						}
       						$em->flush();
       					}
						$out = $treeManager->changeOrder($elementId, $oldOwnerEl, $destOwnerEl, $position);
					}
				}			
				else{		
					$out = FAILED;
				}			
			}
			break;		
		    default:
		    	/**
		    	 * if an unsupported action is requested, reply it with FAILED
		    	 */
		      	$out = FAILED;
			break;
		}
		return new Response($out);
	}

	/**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
	public function modifyFileAction(Project $project, $file)
	{
		$request = $this->getRequest();
		$newdata="";
		if($request->getMethod() == 'POST')
		{
			$newdata = $request->request->get('newdata');
			$dir = $this->get('kernel')->getRootDir();
			$dir = str_replace('/app', '/src/MakinMind/ProjectBundle/Resources/Projects', $dir);
			$dir .= '/P'.$project->getId();
			$filename =$dir.str_replace('FOLDER_SEPARATOR', '/', $file);
			// open file 
			$fw = fopen($filename, 'w') or die('Could not open file');
			// write to file
			// added stripslashes to $newdata
			$fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
			// close file
			fclose($fw);
		}

		return new Response($newdata);
	}

	/**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
	public function checkFileOwnerAction($fileParent, $file)
	{
		$fileParent = str_replace("FOLDER_SEPARATOR", "/", $fileParent);
		$file = str_replace("FOLDER_SEPARATOR", "/", $file);
		if(strcmp($fileParent, '/') == 0)
			$realElementId = substr($file, strrpos($file, "/"), strlen($file));
		else
			$realElementId = $fileParent . substr($file, strrpos($file, "/"), strlen($file));
		$em = $this->get('doctrine.orm.entity_manager');
		//$fileInDb = $em->getRepository('MakinMindEditorBundle:File')->findOneByName($realElementId);
		$query = $em->createQuery("SELECT f FROM MakinMind\EditorBundle\Entity\File f WHERE f.name LIKE ?1");
		$query->setParameter(1, $realElementId.'%');
		$files = $query->getResult();
		if($files)
		{
			$author = $this->container->get('security.context')->getToken()->getUser();
			foreach($files as $file){
				if($file->getAuthor() != $author && $file->getPrivileges() == 0)
					return new Response(0);
			}
		}
		return new Response(1);
	}

	/**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
	public function changePrivilegesAction(File $file)
	{
		$em = $this->get('doctrine.orm.entity_manager');
		$em->persist($file);
		if($file->getPrivileges() == 0)
			$file->setPrivileges(1);
		else
			$file->setPrivileges(0);
		$em->flush();
		return new Response($file->getPrivileges());
	}
}