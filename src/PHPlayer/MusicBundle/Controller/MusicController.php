<?php

namespace PHPlayer\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PHPlayer\MusicBundle\Helper\FileHelper;
use PHPlayer\MusicBundle\Model\Artist;
use PHPlayer\MusicBundle\Model\Album;
use PHPlayer\MusicBundle\Model\Track;

class MusicController extends BaseController
{

    private function getArtists($dir) {
        $artists = array();
        $files = scandir($dir);
        foreach ($files as $file) {
            if (!in_array($file, array('.', '..'))){
                if (is_dir($dir.'/'.$file)) {
                    $artist = new Artist();
                    $artists[] = $artist;
                    $artist->setName($file);

                }
            }
        }

        return $artists;
    }

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $dir = FileHelper::getAbsolutePath('music');

        if (file_exists($dir) && is_dir($dir)) {
            $artists = $this->getArtists($dir);
            $haveTracks = false;
            foreach ($artists as $artist) {
                if ($artist->hasTracks()) {
                    $haveTracks = true;
                    break;
                }
            }

            return array(
                'artists' => $artists,
                'haveTracks' => $haveTracks,
            );
        } else {
            return $this->redirect($this->generateUrl('phplayer_music_upload_index'));
        }
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
