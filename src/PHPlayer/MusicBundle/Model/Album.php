<?

namespace PHPlayer\MusicBundle\Model;

use PHPlayer\MusicBundle\Helper\FileHelper;

class Album {
	private $artist;
	private $name;
	private $tracks = array();

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
    	return count($this->tracks) > 0;
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
	    return $this->tracks;
	}
	
	public function setTracks($tracks) {
		$this->tracks = $tracks;
	}

	public function addTrack($track) {
		$this->tracks[] = $track;
		$track->setAlbum($this);
	}

}