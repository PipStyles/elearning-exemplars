<?php

class Form_ManageImage extends Zend_Form
{

	public function init()
	{
	
	$upload = new Zend_Form_Element_File('imagefile');
		$upload->class = 'file';
		$upload->setLabel('upload image:')
						->setRequired(true)
						->addValidator('notEmpty')
						->removeDecorator('DtDdWrapper');	
		
	 $submit = new Zend_Form_Element_Submit('upload');
		$submit->class = 'submit button upload-button';
		$submit->removeDecorator('DtDdWrapper');	
		
		
		$maxSize = new Zend_Form_Element_Hidden('MAX_FILE_SIZE');
		$maxSize->setValue('6000000')
						->clearDecorators()
						->class = 'hidden';
		
		
		$this->addElements(array(
				$maxSize,
				$upload,
				$submit
		));
		
		
		$this->clearDecorators();
		
		$this->setDecorators(array(
				'FormElements',
				'Form',
				'Errors'
		));
			
	}



}

?>