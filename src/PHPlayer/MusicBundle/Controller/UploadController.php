<?php

namespace PHPlayer\MusicBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use PHPlayer\MusicBundle\Helper\FileHelper;
use PHPlayer\MusicBundle\Model\Artist;
use PHPlayer\MusicBundle\Model\Album;
use PHPlayer\MusicBundle\Model\Track;

/**
 * @Route("/upload")
 */
class UploadController extends BaseController
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }


    /**
     * @Route("/file")
     * @Template()
     */
    public function fileAction()
    {
    	$request = $this->getRequest();

    	$form = $this->createFormBuilder(null, array('csrf_protection' => false))
            ->add('myfile', 'file')
            ->add('artist')
            ->add('album')
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
            	
            	$file = $form['myfile']->getData();
            	$fileName = FileHelper::cleanFileName($file);
            	if ($this->isImage($fileName)) {
            		$fileName = 'folder.jpg';
            	}
            	$artist = FileHelper::cleanFileNameString($form['artist']->getData());
            	$album = FileHelper::cleanFileNameString($form['album']->getData());

            	FileHelper::moveUpload($file, $this->getDir($artist, $album).'/'.$fileName);

                return new Response('success!');
            }
            return new Response('not valid');
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/move/{oldArtist}/{oldAlbum}/{newArtist}/{newAlbum}")
     * @Template()
     */
    public function moveFilesAction($oldArtist, $oldAlbum, $newArtist, $newAlbum)
    {
    	FileHelper::moveFiles(
    		$this->getAbsDir($oldArtist, $oldAlbum),
    		$this->getAbsDir($newArtist, $newAlbum)
		);

		return new Response('ok');
    }

    /**
     * @Route("/files/{artist}/{album}")
     * @Template()
     */
    public function filesAction($artist, $album)
    {
    	$results = array();

    	$dir = $this->getAbsDir($artist, $album);
    	foreach (scandir($dir) as $file) {
    		if (is_file($dir.'/'.$file)) {
    			$results[] = $file;
    		}
    	}

    	return array('files' => $results);
    }

    private function getDir($artist, $album)
    {
    	$artist = FileHelper::cleanFileNameString($artist);
    	$album = FileHelper::cleanFileNameString($album);
    	return 'music/'.$artist.'/'.$album;
    }

    private function getAbsDir($artist, $album) {
    	return FileHelper::getAbsolutePath($this->getDir($artist, $album));
    }

    private function isImage($fileName) {
    	foreach (array('png', 'gif', 'jpg') as $ext) {
    		if (stripos($fileName, $ext) !== false) {
    			return true;
    		}
    	}
    	return false;
    }

}
