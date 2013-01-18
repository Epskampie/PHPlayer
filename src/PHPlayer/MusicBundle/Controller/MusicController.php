<?php

namespace PHPlayer\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PHPlayer\MusicBundle\Helper\FileHelper;
use PHPlayer\MusicBundle\Model\Directory;

class MusicController extends BaseController
{

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $response = new Response();

        // Set caching information
        $response->setPublic();
        $date = new \DateTime();
        $date->modify('+2 weeks');
        $response->setExpires($date);

        $rootDir = new Directory(FileHelper::ROOT_DIR);

        return $this->render(
            'MusicBundle:Music:index.html.twig',
            array(
                'rootDir' => $rootDir,
                'random' => rand(1,999),
            ),
            $response
        );
    }

    /**
     * @Route("/test")
     * @Template()
     */
    public function testAction()
    {
        return array();
    }
}
