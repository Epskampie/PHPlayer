<?php 

namespace PHPlayer\MusicBundle\Model;

use PHPlayer\MusicBundle\Helper\FileHelper;

class File {
	private $name;	
	private $parent;

	public function __construct($name) {
		$this->name = $name;
	}

	// ============ Utility ==========

	public function getAbsolutePath() {
        return $this->parent->getAbsolutePath().'/'.$this->name;
    }

    public function getWebPath() {
        return $this->parent->getWebPath().'/'.$this->name;
    }

    public function getExtension() {
    	return strtolower(FileHelper::getExtension($this->name));
    }

    public function isAudioFile() {
    	return in_array($this->getExtension(), array('mp3', 'ogg', 'wav'));
    }

    public function getViewName() {
    	return FileHelper::stripExtension($this->name);
    }

	// ============ Accessors ==========
	public function getName() {
	    return $this->name;
	}
	
	public function getParent() {
	    return $this->parent;
	}
	
	public function setParent($parent) {
		$this->parent = $parent;
	}

}