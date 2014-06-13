<?php

namespace MakinMind\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('MakinMindUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
