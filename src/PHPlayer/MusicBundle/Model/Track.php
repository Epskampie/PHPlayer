<?

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