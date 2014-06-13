<?php

namespace MakinMind\EditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
	public static function afficherVar()
	{
		$var = "test heheheo";
	}

    public function indexAction()
    {
    	self::afficherVar();
    	echo $var;
        return $this->render('MakinMindEditorBundle:Default:index.html.twig');
    }
}
