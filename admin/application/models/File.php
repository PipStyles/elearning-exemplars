<?php 

class File
{
	
	const ICON_PATH = '/tandl/elearning/images/app_icons/';
	
	protected $_file;
	protected $_path;
	protected $_type;
	
	protected $_iconMap = array();
	
	
	public function __construct($file)
	{
		$this->setFile($file);
		$this->setFolder(dirname($file));
		
		$this->setType($this->findType($file));
		
		$this->_iconMap = array(
			'word' => self::ICON_PATH.'page_word.png',
			'acrobat' => self::ICON_PATH.'page_white_acrobat.png',
			'audio' => self::ICON_PATH.'sound.png',
			'video' => self::ICON_PATH.'television.png',
			'file' => self::ICON_PATH.'page.png',
			'image' => self::ICON_PATH.'camera.png'
		);
		
		return $this;
	}
	
	
	public function setFile($path)
	{
		$this->_file = $path;
	}
	
	public function __toString()
	{
		return $this->getFile();
	}
	
	public function getFile()
	{
		return str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->_file);
	}
	
	public function getAbsolute()
	{
		return "http://".$_SERVER['HTTP_HOST'].$this->getFile();
	}
	
	
	
	public function getFileLocal()
	{
		return $this->_file;
	}
	
	
	
	public function getSize()
	{
		return filesize($this->getFileLocal());
	}
	
	public function getMb()
	{
		return round( ($this->getSize()/1024/1024) , 2);
	}
	
	
	public function setFolder($path)
	{
		$this->_folder = $path;
	}
	
	
	
	public function getName()
	{
		return basename($this->getFile());
	}
	
	
	public function getTitle()
	{
		$title = $this->stripExtension(str_replace('_', ' ', $this->_file));
		return basename($title);
	}
	
	
	
	public function stripExtension($file)
	{
		return File::s_stripExtension($file);
	}
	
	public static function s_stripExtension($file)
	{
		return substr($file, 0, strrpos($file, '.'));
	}
	
	
	
	public static function s_getExtension($file)
	{
		return substr($file, strrpos($file, '.')+1);
	}
	
	public function getExtension($file = null)
	{
		return self::s_getExtension($file);
	}
	
	
	
	public function setType($type)
	{
		$this->_type = $type;
	}
	
	public function getType()
	{
		return $this->_type;
	}
	
	
		
	public function findType($file)
	{
		$extension = $this->getExtension();
				
		$type = null;
		switch(true)
		{
			case strtolower($extension) == "doc" :
			$type = 'word';
			break;
			
			case strtolower($extension) == "pdf" :
			$type = 'acrobat';
			break;
			
			case in_array(strtolower($extension), array('jpeg','jpg','png','gif','bmp')):
			$type = 'image';
			break;
			
			case in_array(strtolower($extension), array('mp3','wav','wma')):
			$type = 'audio';
			break;
			
			case in_array(strtolower($extension), array('mp4','mpeg','mpg','avi','wmv')):
			$type = 'video';
			break;
			
			default :
			$type = 'file';
			break;
			
		}
		return $type;
	}
	
	
	public function getIcon()
	{
		return $this->_iconMap[$this->getType()];
	}
	
	
	public function delete()
	{
		return unlink($this->getFileLocal());		
	}
	
	
}


?>
