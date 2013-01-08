<?php

namespace PHPlayer\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PHPlayer\MusicBundle\Helper\FileHelper;
// use PHPlayer\MusicBundle\Model\Artist;
// use PHPlayer\MusicBundle\Model\Album;
// use PHPlayer\MusicBundle\Model\Track;
use PHPlayer\MusicBundle\Model\Directory;

class MusicController extends BaseController
{

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $rootDir = new Directory(FileHelper::ROOT_DIR);

        // if (count($rootDir->getChildren()) > 0) {
            return array(
                'rootDir' => $rootDir
            );
        // } else {
        //     return $this->redirect($this->generateUrl('phplayer_music_upload_index'));
        // }
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
