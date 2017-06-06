<?php 

class RowImage
{
	
	private $_row;
	private $_catFolder;
	private $_rowFolder;
	private $_thumbFolder;
	private $_originalFolder;
	private $_webFolder;
	
	private $_thumbFile;
	private $_originalFile;
	private $_webFile;
	
	
	private $_imageTypeName;
		
	const IMAGE_ROOT = '/tandl/elearning/exemplars/images';
	
	const THUMB_WIDTH = 100;
	const THUMB_HEIGHT = 75;
	
	const THUMB_QUALITY = 80;
	
	const WEB_MAX_WIDTH = 1000;
	const WEB_MAX_HEIGHT = 700;
	
	const WEB_QUALITY = 80;
		
	private $_hasImage = false;
	
	private $_defaultThumb;
	
	private $_imageTypePrefOrder = array('png','gif','jpg','jpeg');
	
	public function __construct($row)
	{
		$this->_row = $row;
		$this->_catFolder = self::IMAGE_ROOT.'/'.get_class($this->getRow()->getTable());
		$this->_rowFolder = $this->_catFolder.'/'.$this->getRow()->getID();
		
		$this->_thumbFolder = $this->_rowFolder.'/thumb';
		$this->_originalFolder = $this->_rowFolder.'/original';
		$this->_webFolder = $this->_rowFolder.'/web';
		
		$this->_hasImage = $this->load();
		
		$this->_imageFolders = array($this->_originalFolder, $this->_webFolder, $this->_thumbFolder);
		
	}
	
	
	
	
	public function setupFolders()
	{
		//prep the folders required
		if(!is_dir($_SERVER['DOCUMENT_ROOT'].$this->_rowFolder))
		{
			if(!mkdir($_SERVER['DOCUMENT_ROOT'].$this->_rowFolder))
			  {
				 throw new Exception("mkdir(".$_SERVER['DOCUMENT_ROOT'].$this->_rowFolder.") call failed");
			  }
		}
		
		foreach($this->_imageFolders as $folder)
		{
			if(!is_dir($_SERVER['DOCUMENT_ROOT'].$folder))
			{
				try{
					mkdir($_SERVER['DOCUMENT_ROOT'].$folder);
				}
				catch (Exception $e) {
				    throw new Exception("mkdir(".$_SERVER['DOCUMENT_ROOT'].$folder.") call failed");
				}
			}
		}
		
		$this->load();
	}	
	
	
	public function hasImage()
	{
		return $this->_hasImage;
	}
	
	
	
	public function load()
	{
		$this->_defaultThumb = $this->loadDefaultThumb();
		
		if(!$this->loadOriginalFile())
		{
			return false;
		}
		
		$this->_originalFile = $this->loadOriginalFile();
		
		if(!$this->loadThumbFile() || $this->createWebFromOriginal($this->_originalFile))
		{
		$this->createThumbFromOriginal($this->_originalFile);
		$this->createWebFromOriginal($this->_originalFile);
		}
		
		$this->_thumbFile = $this->loadThumbFile();
		$this->_webFile = $this->loadWebFile();
		
		return true;
	}
	
	
	
	
	public function loadThumbFile()
	{
		$files = glob($_SERVER['DOCUMENT_ROOT'].$this->_thumbFolder."/*.*");
		return is_array($files) && count($files) ? $this->_thumbFolder.'/'.basename($files[0]) : false;
	}
	
	public function loadWebFile()
	{
		$files = glob($_SERVER['DOCUMENT_ROOT'].$this->_webFolder."/*.*");
		return is_array($files) && count($files) ? $this->_webFolder.'/'.basename($files[0]) : false;
	}
	
	public function loadOriginalFile()
	{
		$files = glob($_SERVER['DOCUMENT_ROOT'].$this->_originalFolder."/*.*");
		return is_array($files) && count($files) ? $this->_originalFolder.'/'.basename($files[0]) : false;
	}
	
	
	
	public function createThumbFromOriginal($original = null)
	{
		if(!$original)
		{
			$original = $this->_originalFile;
		}
		if(!is_file($original))
		{
			return false;
		}
		
		if(Image::makeThumb($original, $_SERVER['DOCUMENT_ROOT'].$this->_thumbFolder.'/'.basename($original), self::THUMB_WIDTH, self::THUMB_HEIGHT, self::THUMB_QUALITY))
		{
			return true;
		}
		
		//making the thumb must have failed :(
		return false;
		
	}
	
	
	public function createWebFromOriginal($original)
	{
		if(!$original)
		{
			$original = $this->_originalFile;
		}
		if(!is_file($original))
		{
			return false;
		}
		
		if(Image::makeConstrainedThumb($original, $_SERVER['DOCUMENT_ROOT'].$this->_webFolder.'/'.basename($original), self::WEB_MAX_WIDTH, self::WEB_MAX_HEIGHT, self::WEB_QUALITY))
		{
			return true;
		}
		
		//making the thumb must have failed :(
		return false;
		
	}
	
	
	
	public function createAllDerived($file)
	{
		if(!is_file($file)) return false;
		
		$this->createWebFromOriginal($file);
		$this->createThumbFromOriginal($file);
		
		return true;
	}
	
	
	
	
	
	public function save($srcPath, $srcName)
	{
		//takes a source image from wherever, make a thumb canvas and a web sized canvas, saves into folders.
		try {
			$this->setupFolders();
		  }
	    catch (Exception $e)
		{
		   echo "Caught exception from RowImage->setupFolders() :".$e->getMessage();
		}
		
		$oldFile = $_SERVER['DOCUMENT_ROOT'].$this->_originalFile;
		
		$this->_originalFile = $this->_originalFolder.'/'.basename($srcName);
		
	    move_uploaded_file($srcPath, $_SERVER['DOCUMENT_ROOT'].$this->_originalFile);
		//copy($srcPath, $_SERVER['DOCUMENT_ROOT'].$this->_originalFile);
		
		
		$this->createAllDerived($_SERVER['DOCUMENT_ROOT'].$this->_originalFile);
		
		if(is_file($oldFile) && is_file($_SERVER['DOCUMENT_ROOT'].$this->_originalFile) && $oldFile != $_SERVER['DOCUMENT_ROOT'].$this->_originalFile)
		{
			foreach($this->_imageFolders as $folder)
			{
				if(is_file($_SERVER['DOCUMENT_ROOT'].$folder.'/'.basename($oldFile))) unlink($_SERVER['DOCUMENT_ROOT'].$folder.'/'.basename($oldFile));
			}
		}
		
		$this->load();
		
		return $this;
	}
	
	
	
	
	public function delete()
	{
		Folder::delete($this->_rowFolder());
		return true;
	}
	
	
	
	
	
	
	public function getOriginal()
	{
		return $this->_originalFile;	
	}
	
	
	public function getWeb()
	{
		return is_file($_SERVER['DOCUMENT_ROOT'].$this->_webFile) ? $this->_webFile : $this->getOriginal();	
	}
	
	public function getThumb()
	{
		//return $this->_thumbFile;
		return is_file($_SERVER['DOCUMENT_ROOT'].$this->_thumbFile) ? $this->_thumbFile : $this->getDefaultThumb();	
	}
	
	
	
	
	
	public function loadDefaultThumb()
	{
		foreach($this->_imageTypePrefOrder as $ext)
		{
			$files = glob($_SERVER['DOCUMENT_ROOT'].$this->_catFolder."/default.{$ext}");
			if(@count($files))
			{
				return $this->_catFolder.'/'.basename($files[0]);
			}
		}
		
	}
	
	
	public function getDefaultThumb()
	{
		return $this->_defaultThumb;
	}
	
	
	
	
	public function getRow()
	{
	return $this->_row;
	}
	
	
	
	
	
	
	
}

?>