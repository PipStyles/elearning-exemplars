<?php 

class Image extends File
{
	
	
	public static function makeThumb($sourcefile, $endfile, $thumbWidth, $thumbHeight, $quality)
	{
		//takes the sourcefile (path/to/image.jpg) and makes a thumbnail from it
		// and places it at endfile (path/to/thumb.jpg)
		// load image and get image size
		
		$img = self::getCanvasOfSameType($sourcefile);
		
		$set_w = $src_w = imagesx($img);
		$set_h = $src_h = imagesy($img);
		
		$thumb_x = $thumbWidth;
		$thumb_y = $thumbHeight;

		$offset_y = $offset_x = 0;

		$thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
		
		
		// 1 = square, >1 = landscape, <1 = portrait
		$dst_ratio = $thumbWidth/$thumbHeight;
		$src_ratio = $src_w/$src_h;
	  
		/*
		if($src_w > $src_h)
		  {
				$new_thumb_width = round( ($src_w/$src_h) * $thumbHeight);
				$ratio = $src_w/$new_thumb_width;
				$offset_x = round((($new_thumb_width - $src_w/2) * $ratio);
			}*/
		
		if($src_ratio > $dst_ratio)
		  {
			//source is MORE landscapy than thumb target - set the width and x offset only
			  $set_w = round( $src_w * $dst_ratio );
			  $offset_x = round( ($src_w/2) - ($set_w/2) );
			}
		else
		  {
			//source is LESS landscapy then thumb target - set the height and y offset only
			  $set_h = round( $src_h / $dst_ratio );
			  $offset_y = round(($src_h/2) - ($set_h/2));
			}
		
		
		imagecopyresampled($thumb, $img, 0, 0, $offset_x, $offset_y, $thumbWidth, $thumbHeight, $set_w, $set_h);
		
		// save thumbnail into a file - hopefully!
		
		if(!self::makeImageFile($thumb, $endfile, $quality)) return false;
		
		// release the memory
		imagedestroy($tmpimg);
		imagedestroy($img);
		
		return true;
		}
		
		
		
		
		
		public static function makeConstrainedThumb($sourcefile, $endfile, $maxWidth, $maxHeight, $quality)
		{
		//takes the sourcefile (path/to/image.jpg) and makes a constrained thmub or image from it
		//and places it at endfile (path/to/thumb.jpg)
		
		//load image and get image size
		$img = self::getCanvasOfSameType($sourcefile);
		$width = imagesx($img);
		$height = imagesy($img);
		
		if($width < $maxWidth && $height < $maxHeight)
		{
			 copy($sourcefile, $endfile);
			 return true;
		}
		
		//get number by which to mulitply to scale
		$scaleRatio = array($maxWidth/$width, $maxHeight/$height);
			
		//use the larger of the two discrpancies
		sort($scaleRatio);
		
		$destWidth = round($width*$scaleRatio[0]);
		$destHeight = round($height*$scaleRatio[0]);
		
		
		// create a new temporary image
		$tmpimg = imagecreatetruecolor($destWidth,$destHeight);
		
		// copy and resize old image into new image
		imagecopyresampled($tmpimg, $img, 0, 0, 0, 0, $destWidth, $destHeight, $width, $height);
		
		// save thumbnail into a file - hopefully!
		if(!self::makeImageFile($tmpimg, $endfile, $quality)) return false;
		
		// release the memory
		imagedestroy($tmpimg);
		imagedestroy($img);
		
		return true;
		
		
		}
		
		
		
		public static function getCanvasOfSameType($file)
		{
			$extension = strtolower(File::s_getExtension($file));
			
			$canvas = null;
			
			switch(true)
			{
				case in_array($extension, array('jpeg','jpg')):
					$canvas = imagecreatefromjpeg($file);
				break;
				
				case $extension == 'png':
					$canvas = imagecreatefrompng($file);
				break;
				
				case $extension == 'gif':
					$canvas = imagecreatefromgif($file);
				break;		
				
				default:
					$canvas = imagecreatefromjpeg($file);
				break;	
			}
			
			return $canvas;
			
		}
		
		
		
		
		public static function makeImageFile($srcimg, $endfile, $quality)
		{
			//makes image of the type specced by the extension of the END file, NOT the source.
			$origExtension = File::s_getExtension($endfile);
			$extension = strtolower($origExtension);
			
			
			switch(true)
			{
				case in_array($extension, array('jpeg','jpg')):
					imagejpeg($srcimg, $endfile, $quality);
				break;
				
				case $extension == 'png':
					imagepng($srcimg, $endfile, self::jpegQualityToPngQuality($quality));
				break;
				
				case $extension == 'gif':
				  imagegif($srcimg, File::s_stripExtension($endfile).".gif");
					//imagepng($srcimg, File::s_stripExtension($endfile).".png", self::jpegQualityToPngQuality($quality));
				break;
				
				default:
					imagejpeg($srcimg, File::s_stripExtension($endfile).".jpg" , $quality);
				break;
			}
		}
		
		
		
		
		public static function makeImageOfSameType($srcimg, $endfile, $quality)
		{
			//makes image of the type specced by the extension of the END file, NOT the source.
			$extension = strtolower(File::getExtension($endfile));
			
			switch(true)
			{
				case in_array($extension, array('jpeg','jpg')):
					imagejpeg($srcimg, $endfile, $quality);
				break;
				
				case $extension == 'png':					
					imagepng($srcimg, $endfile, self::jpegQualityToPngQuality($quality));
				break;
				
				case $extension == 'gif':
					imagegif($srcimg, $endfile);
				break;
				
				default:
					imagejpeg($srcimg, $endfile, $quality);
				break;
			}
		}
		
		
		
		public static function jpegQualityToPngQuality($quality)
		{
			//takes 0 > 100 and converts into reversed 0 > 9
			return ceil(((0 - $quality) + 100)/10);
		}


}

?>