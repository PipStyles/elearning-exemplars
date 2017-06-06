<?php


class Files
{
	protected $_files = array();
	
	protected $_localfolder;
	protected $_webfolder;
	
	public function __construct($webFolder)
	{
		$this->setWebFolder($webFolder);
		$this->setLocalFolder($this->getLocalFolderFromWeb($this->getWebFolder()));
		$this->setFiles($this->loadFiles());
		return $this;
	}
	
	public function __toArray()
	{
		return $this->getFiles();
	}
	
	
	
	public function setFiles(array $files)
	{
		$this->_files = $files;
	}
	
	
	public function getFiles()
	{
		return $this->_files;
	}
	
	public function Files()
	{
		return $this->getFiles();
	}
	
	
	public function getFileNum($num)
	{
		return isset($this->_files[$num]) ? $this->_files[$num] : false;
	}
	
	
	
	public function createFolders()
	{
		if(!is_dir($this->getLocalFolder()))
		{
			return @mkdir($this->getLocalFolder());
		}
	}
	
	
	
	public function setWebFolder($value)
	{
		$this->_webFolder = $value;
	}
	
	public function getWebFolder()
	{
		return $this->_webFolder;
	}
	
	public function setLocalFolder($value)
	{
		$this->_localFolder = $value;
	}
	
	public function getLocalFolderFromWeb($webFolder)
	{
		return rtrim($_SERVER['DOCUMENT_ROOT'],'/').$webFolder;
	}
	
	public function getLocalFolder()
	{
		return $this->_localFolder;
	}
	
	
	/*
	returns an array of File objects or blank array.
	*/
	public function loadFiles()
	{
		$files = array();
		
		$searchPath = $this->getLocalFolder()."/*.*";
		$filesArray = glob($searchPath);
		
		foreach($filesArray as $file)
		{
			$files[] = new File($file);
		}
		
		return $files;
	}
	
	
	public function getMb()
	{
		$total = 0;
		foreach($this->getFiles() as $file)
		{
			$total += $file->getSize();
		}
		
		return round($total/1024/1024, 2);		
	}
	
	
	function uploadFile($fileArray)
	{
		$this->createFolders();
		
		$newPath = $this->getLocalFolder().'/'.basename($fileArray['name']);
		
		return move_uploaded_file($fileArray['tmp_name'], $newPath);
	}
	
	
	public function deleteAll()
	{
		if(@count($this->getFiles()))
		{
			foreach($this->getFiles() as $file)
			{
				$file->delete();
			}
		}
		
		return rmdir($this->getLocalFolder());
		
	}
	
}


?>