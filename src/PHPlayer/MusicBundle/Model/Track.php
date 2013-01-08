<?php

namespace PHPlayer\MusicBundle\Model;

use PHPlayer\MusicBundle\Helper\FileHelper;

class Track {

	private $album;
	private $name;

	// ============ Utility ==========

	public function getAbsolutePath($identifier = null)
    {
        return $this->getAlbum()->getAbsolutePath().'/'.$this->name;
    }

    public function getWebPath($identifier = null)
    {
        return $this->getAlbum()->getWebPath().'/'.$this->name;
    }

    public function getViewName() {
    	$result = $this->name;

    	$dotIndex = strripos($result, '.');

    	if ($dotIndex !== false) {
    		$result = substr($result, 0, $dotIndex);
    	} 

    	return $result;
    }

    public function isAudioFile() {
    	foreach (array('mp3', 'ogg', 'wav') as $ext) {
    		if (stripos($this->name, $ext) !== false) {
    			return true;
    		}
    	}
    	return false;
    }

	// ============ Accessors ==========

	public function getAlbum() {
	    return $this->album;
	}
	
	public function setAlbum($album) {
		$this->album = $album;
	}

	public function getName() {
	    return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}


}