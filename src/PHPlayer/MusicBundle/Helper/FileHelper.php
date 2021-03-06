<?php

namespace PHPlayer\MusicBundle\Helper;

use \Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{

	const ROOT_DIR = 'music';

	/**
	 * Try to make string filesystem safe, so that there is no path specified (no stuff like /../.. )
	 * @param UploadedFile    $file 
	 * @return string         cleaned filename
	 */
	public static function cleanFileName(UploadedFile $file) {
		if ($file === null) {
			return 'filename';
		}
		return self::cleanFileNameString($file->getClientOriginalName());
	}

	/**
	 * Try to make string filesystem safe, so that there is no path specified (no stuff like /../.. )
	 * @param  string $dirty 
	 * @return string         cleaned filename
	 */
	public static function cleanFileNameString($dirty) {
		// Only allow a few characters
		$clean = "";
		$matches = null;
		$returnValue = preg_match_all('/[\w.0-9 _-]/u', $dirty, $matches);
		if ($returnValue) {
			$clean = implode('', $matches[0]);
		}

		// Fallback
		if (empty($clean)) {
			return 'filename';
		}

		return $clean;
	}

	public static function moveUpload(UploadedFile $file = null, $path) {
		if ($file === null) {
			return;
		}

		self::mkdir(self::getUploadRootDir().'/'.$path);
		
		$file->move(self::getUploadRootDir().'/'.dirname($path), $path);
	}

	public static function moveFiles($fromDir, $toDir) {
		self::mkdir($toDir.'/test.txt'); // keep test.txt so that mkdir works correct

		$files = scandir($fromDir);
		foreach ($files as $file) {
			if (is_file($fromDir.'/'.$file)){

				rename($fromDir.'/'.$file, $toDir.'/'.$file);

			}
		}
	}
	
	public static function removeUpload($path) {
		$file = FileHelper::getAbsolutePath($path);
		if (is_file($file)) {
            unlink($file);
        }
	}
	
	public static function getAbsolutePath($path)
    {
        return null === $path ? null : self::getUploadRootDir().'/'.$path;
    }

    public static function getWebPath($path)
    {
        return null === $path ? null : self::getUploadDir().'/'.$path;
    }

    public static function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.self::getUploadDir();
    }

    public static function getUploadDir()
    {
        return 'uploads';
    }

    /**
     * Create directory if it doesn't exist yet.
     * 
     * @param  string $file absolute path to file or directory
     */
    public static function mkdir($file) {
    	if (!$file) return;
    	
    	$dir = dirname($file);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public static function delTree($dir) {
    	if (!is_dir($dir)) return false;

		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
		}
		
		return rmdir($dir);
	}

	public static function stripExtension($filename) {
		$dotIndex = strripos($filename, '.');

    	if ($dotIndex !== false) {
    		return substr($filename, 0, $dotIndex);
    	} 

    	return $filename;
	}

	public static function getExtension($filename) {
		$dotIndex = strripos($filename, '.');

    	if ($dotIndex !== false) {
    		return substr($filename, $dotIndex + 1);
    	}

    	return null;
	}
}