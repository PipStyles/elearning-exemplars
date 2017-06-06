<?php

class View_Helper_ArtefactRender extends Zend_View_Helper_Abstract
{
	
	private $artefact;
	private $descRow;
	
	public function ArtefactRender($artefact)
	{
		
		if(!$artefact->getTable() instanceof Artefact) return null;
		
		$this->artefact = $artefact;
		$this->descRow = $artefact->getDescendent();
		
		$out = '';
		
		$type = strtolower($this->artefact->getDisplayString('artefactType'));
		
		switch(true)
		{
			case $type == 'image':
				$out .= $this->imageRender();
			break;
			
			case strstr($type, 'sound'):
				$out .= $this->soundRecordingRender();
			break;
			
			case $type == 'url':
				$out .= $this->urlRender();
			break;
			
			case $type == 'document':
				$out .= $this->documentRender();
			break;
			
			case $type == 'video':
				$out .= $this->videoRender();
			break;
			
			case strstr($type, 'screencast'):
				$out .= $this->screencastRender();
			break;
			
			default:
				return null;
			break;
			
		}
		
		$out .= strlen($this->artefact->artefactShortDescription) ? '<p>'.$this->artefact->artefactShortDescription.'</p>' : '';
				
		return $out;
	}
	
	
	
	private function getThumbHtml($file = null)
	{
		
		$altString = $this->artefact->getTitle();
		if($file)
		{
			$altString = $file->getTitle();
		}
		
		return "<img class=\"artefact-thumb\" alt=\"{$altString}\" src=\"{$this->artefact->getImage()->getThumb()}\" />";
	}
	
	
	
	private function imageRender()
	{
		$out = "<a href=\"{$this->artefact->getImage()->getOriginal()}\" target=\"_blank\" >{$this->getThumbHtml()}</a>";
		return $out;
	}
	
	
	
	private function soundRecordingRender()
	{
		$out = "<a href=\"{$this->descRow->soundrecUrl}\" target=\"_blank\" >{$this->getThumbHtml()}</a>";
		return $out;
	}
	
	
	private function documentRender()
	{
		$files = $this->descRow->getFiles();
		
		if(!count($files) > 0) return null;
		
		$file = $files->getFileNum(0);
		
		$out = '<h4 class="heading" >'.$this->artefact->getTitle().'</h4>';
		
		$out .= "<p><a href=\"{$file->getAbsolute()}\" target=\"_blank\" >
		<p>".$this->getThumbHtml($file)."</p></a></p>\n";
		
		return $out;
	}
	
	
	
	
	private function urlRender()
	{
		$thumb = $this->artefact->getImage()->hasImage() ? '<p>'.$this->getThumbHtml().'</p>' : '';
		
		$out = "<a href=\"{$this->descRow->url}\" target=\"_blank\" ><h4>{$this->descRow->getTitle()}</h4>{$thumb}</a>";
		
		return $out;
	}
	
	
	
	
	public function screencastRender()
	{
		if(!strlen($this->descRow->url))
		{
			return;
		}
		
		$out = '<h4 class="heading">'.$this->artefact->getTitle().'</h4>';
		$out .= "<p><a href=\"{$this->descRow->url}\" target=\"_blank\" >{$this->getThumbHtml()}</a></p>";
		
		return $out;
	}
	
	
	
	
	
	
	private function videoRender()
	{
		$out = '<h4 class="heading" >'.$this->artefact->getTitle().'</h4>';
			
		switch(strtolower($this->descRow->getDisplayString('videoType')))
		{
			case 'vls' :
				$out .=  $this->vlsVideoRender();
			break;
			
			case 'streaming server' :
				$out .= $this->streamingVideoRender();
			break;
			
			case 'external' :
				$out .= strlen($this->descRow->videoUrl) ? "<a href=\"{$this->descRow->videoUrl}\" title=\"See this video on the Video Library Service\" target=\"_blank\" >{$this->getThumbHtml()}</a>" : '';
			break;
			
		}
		
		return $out;
	}
	
	
	
	
	private function streamingVideoRender()
	{
		if(!strlen($this->descRow->videoUrl) > 5) return null;
		$out = '';
		
		$out .= "<p><a href=\"{$this->descRow->videoUrl}\" title=\"{$this->artefact->getTitle()}\" >{$this->getThumbHtml()}</a></p>";
		
		return $out;
	
	}
	
	
	
	
	private function vlsVideoRender()
	{
		if(!strlen($this->descRow->videoUrl) > 0 && !intval($this->descRow->videoVLS_id) > 0) return null;
		
		$out =  "<a href=\"http://stream.manchester.ac.uk/Play.aspx?VideoId={$this->descRow->videoVLS_id}\" title=\"See this video on the Video Library Service\" target=\"_blank\" >{$this->getThumbHtml()}</a>";
		
		return $out;
	
	}
	
	

}

?>