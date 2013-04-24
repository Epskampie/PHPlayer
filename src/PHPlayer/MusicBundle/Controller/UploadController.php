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
use PHPlayer\MusicBundle\Model\UploadedFile;

/**
 * @Route("/upload")
 */
class UploadController extends BaseController
{
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

    private function getArtist($artistName, $albumName) {
        $artistName = FileHelper::cleanFileNameString($artistName);
        $albumName = FileHelper::cleanFileNameString($albumName);

        $artist = new Artist();
        $artist->setName($artistName);

        $album = new Album();
        $album->setName($albumName);
        $artist->addAlbum($album);

        return $artist;
    }

    // ============= Actions =============

    /**
     * @Route("/index/{artistName}/{albumName}",
     *     defaults={"artistName" = "Artist", "albumName"="Album"})
     * @Template()
     */
    public function indexAction($artistName, $albumName)
    {
        $artistName = FileHelper::cleanFileNameString($artistName);
        $albumName = FileHelper::cleanFileNameString($albumName);

        return array(
            'artistName' => $artistName,
            'albumName' => $albumName
        );
    }

    /**
     * Upload a file.
     * 
     * @Route("/file", options={"expose"=true})
     * @Template()
     */
    public function fileAction()
    {
        $request = $this->getRequest();

        $uploadedFile = new UploadedFile();

        $form = $this->createFormBuilder($uploadedFile, array('csrf_protection' => false))
            ->setErrorBubbling(true)
            ->add('myfile', 'file')
            ->add('artist')
            ->add('album')
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                
                $file = $uploadedFile->myfile;
                $fileName = FileHelper::cleanFileName($file);
                if ($this->isImage($fileName)) {
                    $fileName = 'folder.jpg';
                }
                $artist = FileHelper::cleanFileNameString($uploadedFile->artist);
                $album = FileHelper::cleanFileNameString($uploadedFile->album);

                FileHelper::moveUpload($file, $this->getDir($artist, $album).'/'.$fileName);

                return new Response('success!');
            }
            return $this->json($this->getAllFormErrors($form), 400);
        }

        return array('form' => $form->createView());
    }

    private function getAllFormErrors($form, array &$errors = array()) {
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->getChildren() as $child) {
            $this->getAllFormErrors($child, $errors);
        }
        return $errors;
    }

    /**
     * @Route("/move/{oldArtist}/{oldAlbum}/{newArtist}/{newAlbum}", options={"expose"=true})
     * @Template()
     */
    public function moveFilesAction($oldArtist, $oldAlbum, $newArtist, $newAlbum)
    {
        $oldArtist = FileHelper::cleanFileNameString($oldArtist);
        $oldAlbum = FileHelper::cleanFileNameString($oldAlbum);
        $newArtist = FileHelper::cleanFileNameString($newArtist);
        $newAlbum = FileHelper::cleanFileNameString($newAlbum);

        FileHelper::moveFiles(
            $this->getAbsDir($oldArtist, $oldAlbum),
            $this->getAbsDir($newArtist, $newAlbum)
        );
        rmdir($this->getAbsDir($oldArtist, $oldAlbum));

        return new Response('ok');
    }

    /**
     * @Route("/list_files/{artist}/{album}", options={"expose"=true})
     * @Template()
     */
    public function listFilesAction($artist, $album)
    {
        $artist = FileHelper::cleanFileNameString($artist);
        $album = FileHelper::cleanFileNameString($album);

        $artist = $this->getArtist($artist, $album);

        return array('artists' => array($artist));
    }

    /**
     * @Route("/get_art/{artist}/{album}", options={"expose"=true})
     * @Template()
     */
    public function getArtUrlAction($artist, $album)
    {
        $artist = FileHelper::cleanFileNameString($artist);
        $album = FileHelper::cleanFileNameString($album);

        $albums = $this->getArtist($artist, $album)->getAlbums();

        return array('album' => $albums[0]);
    }

    /**
     * Guess artist & album based on id3 tags.
     * 
     * @Route("/guess_filenames/{artist}/{album}", options={"expose"=true})
     */
    public function guessFilenamesAction($artist, $album)
    {
        $artist = FileHelper::cleanFileNameString($artist);
        $album = FileHelper::cleanFileNameString($album);

        $albums = $this->getArtist($artist, $album)->getAlbums();
        $album = $albums[0];
        $tracks = $album->getTracks();

        $status = 'default';
        $newArtist = 'Artist';
        $newAlbum = 'Album';

        foreach ($tracks as $track) {
            $id3 = new Id3Info($track->getAbsolutePath());
            if ($id3->valid && $id3->artist && $id3->album) {
                $status = 'rename';
                $newArtist = $id3->artist;
                $newAlbum = $id3->album;
                break;
            }
        }

        return $this->json(array(
            'status' => $status,
            'newArtist' => $newArtist,
            'newAlbum' => $newAlbum
        ));
    }

    /**
     * @Route("/delete_file/{artist}/{album}/{track}", options={"expose"=true})
     */
    public function deleteFileAction($artist, $album, $track)
    {
        $artist = FileHelper::cleanFileNameString($artist);
        $album = FileHelper::cleanFileNameString($album);
        $track = FileHelper::cleanFileNameString($track);

        $dir = $this->getAbsDir($artist, $album);

        if (file_exists($dir.'/'.$track)) {
            unlink($dir.'/'.$track);
        }

        return $this->redirect($this->generateUrl('phplayer_music_upload_index', array(
            'artistName' => $artist,
            'albumName' => $album,
        )));
    }

    /**
     * @Route("/delete_album/{artist}/{album}", options={"expose"=true})
     */
    public function deleteAlbumAction($artist, $album)
    {
        $artist = FileHelper::cleanFileNameString($artist);
        $album = FileHelper::cleanFileNameString($album);

        $dir = $this->getAbsDir($artist, $album);

        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                if (is_file($dir.'/'.$file)) {
                    unlink($dir.'/'.$file);
                }
            }
            rmdir($dir);
        }

        return $this->redirect($this->generateUrl('phplayer_music_upload_index'));
    }

    /**
     * @Route("/rename_file/{artist}/{album}/{track}/{newTrackName}", options={"expose"=true})
     */
    public function renameFileAction($artist, $album, $track, $newTrackName)
    {
        $artist = FileHelper::cleanFileNameString($artist);
        $album = FileHelper::cleanFileNameString($album);
        $track = FileHelper::cleanFileNameString($track);
        $newTrackName = FileHelper::cleanFileNameString($newTrackName);

        $dir = $this->getAbsDir($artist, $album);

        if (file_exists($dir.'/'.$track) && !file_exists($dir.'/'.$newTrackName)) {
            rename($dir.'/'.$track, $dir.'/'.$newTrackName);

            return $this->json(array(
                'status' => 'ok',
            ));
        }

        return $this->json(array(
            'status' => 'error',
            'error' => 'file not found',
        ));
    }


}

class Id3Info { 
    public $valid = false;

    public $title;
    public $artist;
    public $album;
    public $year;
    public $comment;
    public $genre;

    public function __construct($file) { 
        if (file_exists($file)) {
            
            $id_start=filesize($file)-128; 
            $fp=fopen($file,"r"); 
            fseek($fp,$id_start); 
            $tag=fread($fp,3); 

            if ($tag == "TAG") { 
                $this->valid = true; 

                $this->title = trim(fread($fp,30));
                $this->artist = trim(fread($fp,30));
                $this->album = trim(fread($fp,30));
                $this->year = trim(fread($fp,4));
                $this->comment = trim(fread($fp,30));
                $this->genre = trim(fread($fp,1));
            }

            fclose($fp); 
        } 
    } 
}
