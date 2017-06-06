<?php

class Folder
{
	
	public static function delete($dir)
	{
	//recursively deletes folder and all contents.
    if(!$dh = @opendir($dir))
    {
        return;
    }
		
    while (false !== ($obj = readdir($dh)))
    {
        if($obj == '.' || $obj == '..')
        {
            continue;
        }
        if (!@unlink($dir . '/' . $obj))
        {
            self::delete($dir.'/'.$obj, true);
        }
    }
    closedir($dh);
    @rmdir($dir);
   
    return;
	} 


}

?>