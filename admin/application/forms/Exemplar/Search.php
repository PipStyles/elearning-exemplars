<?php

class Form_Exemplar_Search extends Zend_Form
{
	
	
	public function init()
	{
		$this->setView(new Zend_View());
		
		//$exemplar = new Exemplar();
		
		$fields['text'] = new Zend_Form_Element_Text('text');
		$fields['text']->class = 'text';
		
		//school field
		$fields['school'] = new Zend_Form_Element_Select('school');
		$school = new School();
		$titleFields = $school->getNameFields();
		$schoolSelect = $school->select()->from($school, array('key' => $school->getPrimary(), 'value'=>$titleFields[0]))
											 ->order($school->getDefaultOrder().' '.$school->getDefaultOrderDirection());
		$schools = $school->fetchAll($schoolSelect);
		$fields['school']->addMultiOptions(array('0'=>'select School...'))->addMultiOptions($schools->toArray())->setLabel('School');
		$fields['school']->setAttrib('onChange', 'setupDisciplineField()');
		
		//discipline field
		$fields['discipline'] = new Zend_Form_Element_Select('discipline');
		$fields['discipline']->addMultiOptions(array('select School first'));
		$fields['discipline']->class = 'unavailable';
		$fields['discipline']->setLabel('Subject');
		
		//theme field
		$fields['theme'] = new Zend_Form_Element_Select('theme');
		$theme = new Theme();
			$titleFields = $theme->getNameFields();
			$themeSelect = $theme->select()
								 ->from($theme, array('key' => $theme->getPrimary(), 'value'=>$titleFields[0]))
								 ->order($theme->getDefaultOrder().' '.$theme->getDefaultOrderDirection());
			
			$themes = $theme->fetchAll($themeSelect);
		$fields['theme']->addMultiOptions(array('0'=>'select Theme...'))->addMultiOptions($themes->toArray())->setLabel('Theme');
		
		
		$fields['serviceArea'] = new Zend_Form_Element_Select('serviceArea');
		$service = new ServiceArea();
			$titleFields = $service->getNameFields();
			$serviceSelect = $service->select()
									 ->from($service, array('key' => $service->getPrimary(), 'value'=>$titleFields[0]))
									 ->order($service->getDefaultOrder().' '.$service->getDefaultOrderDirection());
			
			$services = $service->fetchAll($serviceSelect);
		$fields['serviceArea']->addMultiOptions(array('0'=>'select Service area...'))->addMultiOptions($services->toArray())->setLabel('Service');
		
		
		$match = new Zend_Form_Element_Checkbox('matchany');
		$match->setLabel('Match any? ');
		$fields['match'] = $match;
		
		
		$fields['submit'] = new Zend_Form_Element_Submit('submit');
		$fields['submit']->class = 'button search-button';
		$fields['submit']->setLabel('search');
		
		
		foreach($fields as $name=>$element)
		{
			$element->setDecorators(array('ViewHelper', 'Label'));
			$this->addElement($element);
		}
		
		$this->getElement('submit')->setDecorators(array('ViewHelper'));
		
		$this->setDecorators(array(
				'FormElements',
				'Form'));
		
	}
	
	
	
	
	public function populate($defaults)
	{
		foreach ($this->getElements() as $name => $element)
		{
        	if(!array_key_exists($name, $defaults)) continue;
			
			if($defaults[$name] != 0 && $defaults[$name] != '')
			{
				$element->class .= ' active';
			}			
        }
		
		parent::populate($defaults);
	}
	
	

}
?>