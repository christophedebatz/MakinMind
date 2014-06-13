<?php

namespace MakinMind\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('MakinMindPortalBundle:Default:index.html.twig');
    }



    public function aboutUsAction()
    {
        return $this->render('MakinMindPortalBundle:About:index.html.twig');
    }

    public function lastProjectsAction () {
        $em = $this->get('doctrine.orm.entity_manager');
        $qb = $em->createQueryBuilder();
        $query = $qb->add('select', 'p')
                    ->add('from', 'MakinMind\ProjectBundle\Entity\Project p')
                    ->setFirstResult(0)
                    ->setMaxResults(5)
                    ->getQuery();
        $lastProjects = $query->getResult();

        return $this->render('MakinMindPortalBundle:Default:last-projects.html.twig', array('lastProjects' => $lastProjects));
    }
}

?>
