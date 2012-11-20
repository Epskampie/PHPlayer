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

    private function getArtists() {
        $artists = array();
        $dir = FileHelper::getAbsolutePath('music');
        $files = scandir($dir);
        foreach ($files as $file) {
            if (!in_array($file, array('.', '..'))){
                if (is_dir($dir.'/'.$file)) {
                    $artist = new Artist();
                    $artists[] = $artist;
                    $artist->setName($file);

                    $this->getAlbums($artist);
                }
            }
        }

        return $artists;
    }

    private function getAlbums($artist) {
        $dir = $artist->getAbsolutePath();
        $files = scandir($dir);
        foreach ($files as $file) {
            if (!in_array($file, array('.', '..'))){
                if (is_dir($dir.'/'.$file)) {
                    $album = new Album();
                    $album->setName($file);
                    
                    $artist->addAlbum($album);

                    $this->getTracks($album);
                }
            }
        }
    }

    private function getTracks($album) {
        $dir = $album->getAbsolutePath();
        $files = scandir($dir);
        foreach ($files as $file) {
            if (!in_array($file, array('.', '..'))){
                if (is_file($dir.'/'.$file)) {
                    $track = new Track();
                    $track->setName($file);
                    
                    $album->addTrack($track);
                }
            }
        }
    }

    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction()
    {
        return array('artists' => $this->getArtists());
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
