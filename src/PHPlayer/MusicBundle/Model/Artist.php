<?

namespace PHPlayer\MusicBundle\Model;

use PHPlayer\MusicBundle\Helper\FileHelper;

class Artist {
	private $PARENT_DIR = 'music';

	private $name;
	private $albums = NULL;

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
		if ($this->albums === NULL) {

			$this->albums = array();

			$dir = $this->getAbsolutePath();
			$files = scandir($dir);
			foreach ($files as $file) {
			    if (!in_array($file, array('.', '..'))){
			        if (is_dir($dir.'/'.$file)) {
			            $album = new Album();
			            $album->setName($file);
			            
			            $this->addAlbum($album);
			        }
			    }
			}
		}
	    return $this->albums;
	}
	
	public function setAlbums($albums) {
		$this->albums = $albums;
		foreach ($this->albums as $album) {
			$album->setArtist($this);
		}
	}

	public function addAlbum($album) {
		$this->albums[] = $album;
		$album->setArtist($this);
	}

}