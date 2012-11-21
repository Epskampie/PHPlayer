<?

namespace PHPlayer\MusicBundle\Model;

use PHPlayer\MusicBundle\Helper\FileHelper;

class Album {
	private $artist;
	private $name;
	private $tracks = NULL;

	// ============ Utility ==========

	public function getAbsolutePath($identifier = null)
    {
        return $this->getArtist()->getAbsolutePath().'/'.$this->name;
    }

    public function getWebPath($identifier = null)
    {
        return $this->getArtist()->getWebPath().'/'.$this->name;
    }

    public function hasArt()
    {
    	return file_exists($this->getAbsolutePath().'/'.'folder.jpg');
    }

    public function getArtWebPath()
    {
    	return $this->getWebPath().'/'.'folder.jpg';
    }

    public function hasTracks() 
    {
    	foreach ($this->getTracks() as $track) {
    		if ($track->isAudioFile()) return true;
    	}
    	return false;
    }

	// ============ Accessors ==========
	public function getName() {
	    return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}

	public function getArtist() {
	    return $this->artist;
	}
	
	public function setArtist($artist) {
		$this->artist = $artist;
	}

	public function getTracks() {
		if ($this->tracks === NULL) {
			$this->tracks = array();
			$dir = $this->getAbsolutePath();
	        $files = scandir($dir);
	        foreach ($files as $file) {
	            if (!in_array($file, array('.', '..'))){
	                if (is_file($dir.'/'.$file)) {
	                    $track = new Track();
	                    $track->setName($file);
	                    
	                    $this->addTrack($track);
	                }
	            }
	        }
		}

	    return $this->tracks;
	}
	
	public function setTracks($tracks) {
		$this->tracks = $tracks;
	}

	public function addTrack($track) {
		if ($this->tracks === NULL) {
			$this->tracks = array();
		}
		$this->tracks[] = $track;
		$track->setAlbum($this);
	}

}