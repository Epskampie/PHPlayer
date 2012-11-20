<?

namespace PHPlayer\MusicBundle\Model;

use PHPlayer\MusicBundle\Helper\FileHelper;

class Artist {
	private $PARENT_DIR = 'music';

	private $name;
	private $albums = array();

	// ============ Utility ==========

	public function getAbsolutePath($identifier = null)
    {
        return FileHelper::getAbsolutePath($this->PARENT_DIR.'/'.$this->name);
    }

    public function getWebPath($identifier = null)
    {
        return FileHelper::getWebPath($this->PARENT_DIR.'/'.$this->name);
    }
	// ============ Accessors ==========

	public function getName() {
	    return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}

	public function getAlbums() {
	    return $this->albums;
	}
	
	public function setAlbums($albums) {
		$this->albums = $albums;
	}

	public function addAlbum($album) {
		$this->albums[] = $album;
		$album->setArtist($this);
	}

}