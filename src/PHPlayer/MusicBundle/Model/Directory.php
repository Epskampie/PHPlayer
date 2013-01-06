<?

namespace PHPlayer\MusicBundle\Model;

use PHPlayer\MusicBundle\Helper\FileHelper;

class Directory {
	private $name;
	private $parent;
	private $level = 0;

	private $scanned = false;
	private $children = array();
	private $files = array();
	private $tracks = array();

	public function __construct($name) {
		$this->name = $name;
	}

	// ============ Utility ==========

	public function getAbsolutePath() {
    	if ($this->parent) {
    		return $this->parent->getAbsolutePath().'/'.$this->name;
    	} else {
    		return FileHelper::getAbsolutePath($this->name);
    	}
    }

    public function getWebPath() {
    	if ($this->parent) {
    		return $this->parent->getWebPath().'/'.$this->name;
    	} else {
    		return FileHelper::getWebPath($this->name);
    	}
    }

    public function hasArt() {
    	return 
    		file_exists($this->getAbsolutePath().'/'.'folder.jpg') ||
    		file_exists($this->getAbsolutePath().'/'.'Folder.jpg');
    }

    public function getArtWebPath() {
    	if (file_exists($this->getAbsolutePath().'/'.'folder.jpg')) {
    		return $this->getWebPath().'/'.'folder.jpg';
    	}
    	return $this->getWebPath().'/'.'Folder.jpg';
    }

    public function hasTracks() {
    	$this->scan();
    	return count($this->tracks) > 0;
    }

    public function scan() {
    	if ($this->scanned) return;
    	$this->scanned = true;

    	$dir = $this->getAbsolutePath();
    	if (is_dir($dir)) {
	        $files = scandir($dir);
	        foreach ($files as $file) {
	            if (!in_array($file, array('.', '..'))){
	                if (is_file($dir.'/'.$file)) {
	                    $newFile = new File($file);
	                    $newFile->setParent($this);
	                    $this->addFile($newFile);
	                } else if (is_dir($dir.'/'.$file)) {
	                	$newDirectory = new Directory($file);
	                	$newDirectory->setParent($this);
	                	$newDirectory->setLevel($this->level + 1);
	                	$this->addChild($newDirectory);
	                }
	            }
	        }
    	}
    }

    public function getArtistName() {
    	if ($this->level > 1) {
    		return $this->parent->getName();
    	}
    	$split = $this->splitName();
    	return $split['artist'];
    }

    public function getAlbumName() {
    	if ($this->level > 1) {
    		return $this->getName();
    	}
    	$split = $this->splitName();
    	return $split['album'];
    }

   	/**
   	 * Split name into artist and album
   	 */
    private function splitName() {
		$matches = null;
		$success = preg_match('#^(.+) - (.+)$#U', $this->name, $matches);
		if ($success) {
			return array(
				'artist' => $matches[1],
				'album' => $matches[2]
			);
		}

		return array(
			'artist' => 'Unknown artist',
			'album' => $this->name
		);
    }

	// ============ Accessors ==========

	public function getName() {
	    return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}

	public function addFile($file) {
		$this->files[] = $file;
		if ($file->isAudioFile()) {
			$this->tracks[] = $file;
		}
	}

	public function getParent() {
	    return $this->parent;
	}
	
	public function setParent($parent) {
		$this->parent = $parent;
	}

	public function getChildren() {
		$this->scan();
	    return $this->children;
	}
	
	public function setChildren($children) {
		$this->children = $children;
	}

	public function addChild($child) {
		$this->children[] = $child;
	}

	public function getTracks() {
		$this->scan();
	    return $this->tracks;
	}

	public function getFiles() {
		$this->scan();
	    return $this->files;
	}
	
	public function setFiles($files) {
		$this->files = $files;
	}

	public function getLevel() {
	    return $this->level;
	}
	
	public function setLevel($level) {
		$this->level = $level;
	}
	
}