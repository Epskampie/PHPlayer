<?php

namespace PHPlayer\MusicBundle\Helper;

use \Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
	/**
	 * Clean filename of usafe characters
	 * @param UploadedFile $file 
	 */
	public static function cleanFileName(UploadedFile $file) {
		if ($file === null) {
			return 'filename';
		}
		return self::cleanFileNameString($file->getClientOriginalName());
	}

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
		self::mkdir($toDir.'/test.txt');

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
     * Create directory if it dosn't extist yet.
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
}