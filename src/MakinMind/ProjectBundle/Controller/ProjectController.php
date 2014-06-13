<?php

namespace MakinMind\ProjectBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MakinMind\ProjectBundle\Entity\Project;
use MakinMind\ProjectBundle\Form\ProjectType;
use MakinMind\ProjectBundle\Entity\Contract;
use MakinMind\UserBundle\Entity\User;

use MakinMind\ProjectBundle\Entity\Request;
use MakinMind\ProjectBundle\Entity\ApplyTerms;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class ProjectController extends Controller
{
  /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function showProjectAction(Project $project)
    {
      $request = $this->get('request');
      if ($request->getMethod() == 'POST') {

          // On redirige vers la page de visualisation de l'article nouvellement créé
          //return $this->redirect($this->generateUrl('MakinMindProjectBundle_confidentiality', array('id' => $project->getId())));
      }

      $user = $this->container->get('security.context')->getToken()->getUser();

        $em = $this->get('doctrine.orm.entity_manager');
        $request = $em->getRepository('MakinMindProjectBundle:Request')->findBy(array('user' => $user->getId(), 'project' => $project->getId()));
         if(count($request)==0)
            $request = null;

        $formProject = $this->createFormBuilder($project);
        $formProject = $formProject
                ->add('shortDescription',        'textarea')
                ->add('longDescription',       'textarea')
                ->getForm();

        $applyTerms = $project->getApplyTerms();
        $formApplyTerms = $this->createFormBuilder($applyTerms);
        $formApplyTerms = $formApplyTerms
                ->add('pbStudies',          'integer')
                ->add('firstDiploma',       'text')
                ->add('specialization',     'text')
                ->add('numberWorkedYears',  'integer')
                ->add('hoursPerWeek',       'integer')
                ->add('paralleleContract',  'checkbox')
                ->getForm();


    return $this->render('MakinMindProjectBundle:Project:show_project.html.twig', array('project' => $project, 'request'=>$request, 'formProject'=>$formProject->createView(), 'formApplyTerms'=>$formApplyTerms->createView()));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function confidentialityAction(Project $project)
    {
    $request = $this->get('request');
    if ($request->getMethod() == 'POST') {
        if($request->request->get("contract_accpt"))
        {
          // On enregistre la signature du clause de confidentialité
            

          // On redirige vers la page de visualisation de l'article nouvellement créé
            return $this->redirect($this->generateUrl('MakinMindProjectBundle_contract', array('id' => $project->getId())));
        }
        else
        {
          return $this->redirect($this->generateUrl('MakinMindProjectBundle_project', array('id' => $project->getId())));
        }
      }

      //vérifier que la clause n'est pas déjà signé, sinon redirection direct vers la page d'après
      return $this->render('MakinMindProjectBundle:Project:confidentiality.html.twig', array('project' => $project));
    }


    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function contractAction(Project $project)
    { 
      $user = $this->container->get('security.context')->getToken()->getUser();
      
      $request = $this->get('request');
      if ($request->getMethod() == 'POST') {
        if($request->request->get("contract_accpt"))
        {
          $motivations = $request->request->get("motivations");
          $request = new request;
          $request->setProject($project);
          $request->setUser($user);
          $request->setDate(new \DateTime());
          $request->setMotivations($motivations);

          $em = $this->get('doctrine.orm.entity_manager');
          $em->persist($request);
          $em->flush();

          return $this->redirect(
                    $this->generateUrl(
                        'show_project',
                        array('id' => $project->getId(),
                              'url' => $project->getUrl()
                            )
                        )
                    );

        }
        //vérifier la clause de confidentialité signé 
        return $this->redirect(
                $this->generateUrl(
                    'project_contract', 
                    array('id' => $project->getId(), 
                          'url' => $project->getUrl()
                         )
                    )
                );

      // sinon  return $this->redirect($this->generateUrl('MakinMindProjectBundle_confidentiality', array('id' => $project->getId(), 'type'=>$type)));
      }
      return $this->render('MakinMindProjectBundle:Project:contract.html.twig', array('project' => $project));

    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectsAction()
    {
      $user = $this->container->get('security.context')->getToken()->getUser();

      $em = $this->get('doctrine.orm.entity_manager');
      $projects = $em->getRepository('MakinMindProjectBundle:Project')->findByOwner($user->getId());
      return $this->render('MakinMindProjectBundle:Project:owner_projects.html.twig', array('projects'=> $projects));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectDisplayAction(Project $project)
    {
      $user = $this->container->get('security.context')->getToken()->getUser();

      // test si le project lui appartient 
      $em = $this->get('doctrine.orm.entity_manager');

      return $this->render('MakinMindProjectBundle:Project:owner_project_description.html.twig', array('project'=> $project));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectContractAction(Project $project)
    {
      $user = $this->container->get('security.context')->getToken()->getUser();
      // test si le project lui appartient 
      $em = $this->get('doctrine.orm.entity_manager');

      return $this->render('MakinMindProjectBundle:Project:owner_project_contract.html.twig', array('project'=> $project));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectDevelopersListAction(Project $project)
    {
      $user = $this->container->get('security.context')->getToken()->getUser();
      $em = $this->get('doctrine.orm.entity_manager');

      $request = $this->get('request');
      if ($request->getMethod() == 'POST') {
        $newChiefId = (int) $request->request->get("projectChief");

        if ($newChiefId == -2)
          $query = $em->createQuery('UPDATE MakinMind\ProjectBundle\Entity\Contract c SET c.workerMission = 0 WHERE c.project = ?1');

        else {

          $contract = $em->getRepository('MakinMindProjectBundle:Contract')->find($newChiefId);
          if (!$contract)
            throw new \Exception('Cannot find contract #' . $newChiefId);

          if ($project->getId() == $contract->getProject()->getId()) {
            $contract->setWorkerMission(1);
            $em->persist($contract);
            $em->flush();

            $query = $em->createQuery('UPDATE MakinMind\ProjectBundle\Entity\Contract c SET c.workerMission = 0 WHERE c.project = ?1 AND c.worker <> ?2');
            $query->setParameter(2, $contract->getWorker()->getId());
          }
        }
        $query->setParameter(1, $project->getId());
        $query->execute();
      }

      $query = $em->createQuery('SELECT c FROM MakinMind\ProjectBundle\Entity\Contract c WHERE c.creaDate is not null AND c.signDate is not null AND c.project = ?1');
      $query->setParameter(1, $project);
      $contracts = $query->getResult();

      $query = $em->createQuery('SELECT c FROM MakinMind\ProjectBundle\Entity\Contract c WHERE c.workerMission = 1 AND c IN (?1)');
      $query->setParameter(1, $contracts);

      try {
        $projectChief = $query->getSingleResult();
      } catch (\Doctrine\Orm\NoResultException $e) {
        $projectChief = null;
      }
      
      return $this->render('MakinMindProjectBundle:Project:owner_project_list_developers.html.twig',
                array('project'=> $project,
                      'contracts'=>$contracts,
                      'projectChief' => $projectChief
                      )
            );
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectRetainedCandidatesListAction(Project $project)
    {
      $user = $this->container->get('security.context')->getToken()->getUser();
      // test si le project lui appartient 
      $em = $this->get('doctrine.orm.entity_manager');

      $query = $em->createQuery('SELECT c FROM MakinMind\ProjectBundle\Entity\Contract c WHERE c.creaDate is not null AND c.signDate is null AND c.project = ?1');
      $query->setParameter(1, $project);
      $contracts = $query->getResult();
      return $this->render('MakinMindProjectBundle:Project:owner_project_list_successful_candidates.html.twig', array('project'=> $project, 'contracts' => $contracts));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectCandidatesListAction(Project $project)
    {
      $user = $this->container->get('security.context')->getToken()->getUser();
        // test si le project lui appartient 
      $em = $this->get('doctrine.orm.entity_manager');

      $query = $em->createQuery('SELECT r FROM MakinMind\ProjectBundle\Entity\Request r JOIN r.user u1 WHERE u1 NOT IN (SELECT u2 FROM MakinMind\ProjectBundle\Entity\Contract c JOIN c.worker u2 WHERE c.project = ?1)');
      $query->setParameter(1, $project);
      $requests = $query->getResult();
      return $this->render('MakinMindProjectBundle:Project:owner_project_list_candidates.html.twig', array('project'=> $project, 'requests' => $requests));
    }
/*
    
    public function projectModifyAction(Project $project)
    {
      $user = $this->container->get('security.context')->getToken()->getUser();
      // test si le project lui appartient 
    $em = $this->get('doctrine.orm.entity_manager');

    return $this->render('MakinMindProjectBundle:Project:owner_project_modify_project.html.twig', array('project' => $project));
    }
*/
    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectStatusAction(Project $project)
    {
      $user = $this->container->get('security.context')->getToken()->getUser();
      // test si le project lui appartient 
      $em = $this->get('doctrine.orm.entity_manager');

      $request = $this->get('request');
      if ($request->getMethod() == 'POST') {
        if($project->getRecruitment()==0)
          $project->setRecruitment(1);
        else
          $project->setRecruitment(0);
        $em->flush();
    }

    return $this->render('MakinMindProjectBundle:Project:owner_project_status.html.twig', array('project'=> $project));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectsListAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery("SELECT p FROM MakinMind\ProjectBundle\Entity\Project p WHERE p.owner = ?1 OR p IN (SELECT proj FROM MakinMind\ProjectBundle\Entity\Partners part JOIN part.project proj WHERE part.partner = ?1)");
        $query->setParameter(1, $user);
        $myProjects = $query->getResult();

        if($myProjects)
        {
          $query = $em->createQuery("SELECT p FROM MakinMind\ProjectBundle\Entity\Project p WHERE p not in (?1)");
          $query->setParameter(1, $myProjects);
        }
        else
          $query = $em->createQuery("SELECT p FROM MakinMind\ProjectBundle\Entity\Project p");
        $otherProjects = $query->getResult();

      return $this->render('MakinMindProjectBundle:Project:list_projects.html.twig', 
          array('myProjects' => $myProjects,
                'otherProjects' => $otherProjects)
          );
    }


    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function myProjectsListAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');
        $projects = $em->getRepository('MakinMindProjectBundle:Project')->findByOwner($user->getId());

        return $this->render('MakinMindProjectBundle:Project:owner_projects.html.twig', array("projects" => $projects));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectModifyResumeAction(Project $project)
    {
      $user = $user = $this->container->get('security.context')->getToken()->getUser();

      if ($user != $project->getOwner())
        return new Reponse('Erreur');

      $formProject = $this->createFormBuilder($project);
      $formProject = $formProject
                ->add('shortDescription',        'textarea')
                ->add('longDescription',       'textarea')
                ->getForm();

      $request = $this->get('request');
      if ($request->getMethod() == 'POST') {
        $formProject->bindRequest($request);
         if ($formProject->isValid()) {
          $em = $this->get('doctrine.orm.entity_manager');
          $em->persist($project);
          $em->flush();
          $responseOfController = '<body><div><p id="resp_shortDesc">'. $project->getShortDescription() .'</p>';
          $responseOfController .= '<p id="resp_longDesc">'. $project->getLongDescription() .'</p></div></body>';
          return new Response($responseOfController);
         }
         else
          return new Response('Formulaire non valide');
      }
      else
        return new Response('Erreur');
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function projectModifyApplyTermsAction(Project $project)
    {
      $user = $user = $this->container->get('security.context')->getToken()->getUser();

      if ($user != $project->getOwner())
        return new Reponse('Erreur');

      $applyTerms = $project->getApplyTerms();
      $formApplyTerms = $this->createFormBuilder($applyTerms);
      $formApplyTerms = $formApplyTerms
                ->add('pbStudies',          'integer')
                ->add('firstDiploma',       'text')
                ->add('specialization',     'text')
                ->add('numberWorkedYears',  'integer')
                ->add('hoursPerWeek',       'integer')
                ->add('paralleleContract',  'checkbox')
                ->getForm();

      $request = $this->get('request');
      if ($request->getMethod() == 'POST') {
        $formApplyTerms->bindRequest($request);
        if ($formApplyTerms->isValid()) {
          $em = $this->get('doctrine.orm.entity_manager');
          $em->persist($applyTerms);
          $em->flush();

          $json = array(
            'pbStudies' => $applyTerms->getPbStudies(),
            'firstDiploma' => $applyTerms->getFirstDiploma(),
            'specialization' => $applyTerms->getSpecialization(),
            'numberWorkedYears' => $applyTerms->getNumberWorkedYears(),
            'hoursPerWeek' => $applyTerms->getHoursPerWeek(),
            'paralleleContract' => $applyTerms->getParalleleContract()
            );
          $jsonResponse = new Response(json_encode($json));
          $jsonResponse->headers->set('Content-Type', 'application/json');
          return $jsonResponse;
        }
        else
          return new Response('Formulaire non valide');
      }
      else
        return new Response('Erreur');
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function createProjectAction()
    {
        $project = new Project;
        $project->setApplyTerms(new ApplyTerms());
        $formProject = $this->createForm(new ProjectType, $project);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $formProject->bindRequest($request);
          //if ($formProject->isValid()) {
            $project->setRecruitment(0);
            $project->setOwner($this->container->get('security.context')->getToken()->getUser());
            $em = $this->get('doctrine.orm.entity_manager');
            //$resourceLogo = $em->getRepository('MakinMindResourceBundle:Resource')->findOneByUrl($fileName);
            //$project->setLogo($resourceLogo);
            $em->persist($project);
            $em->flush();
            
            // create default directories for project
            $dir = $this->get('kernel')->getRootDir();
            $dir = str_replace('/app', '/src/MakinMind/ProjectBundle/Resources/Projects', $dir);
            $dir .= '/P' . $project->getId();
            mkdir($dir, 0755);
            mkdir($dir.'/Resources', 0755);
            mkdir($dir . '/Src', 0755);


            return $this->redirect($this->generateUrl('projects'));
          //}
        }
       
        return $this->render('MakinMindProjectBundle:Project:createProject.html.twig', array(
          'formProject' => $formProject->createView()
        ));
    }
} 
