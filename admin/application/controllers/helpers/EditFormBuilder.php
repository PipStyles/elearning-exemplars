<?php

class Helper_EditFormBuilder extends Zend_Controller_Action_Helper_Abstract
	{
	
	public $view;
	
	public function setView(Zend_View_Interface $view)
		{
		$this->view = $view;
		}
	
	
	public function refineFormBuilder()
		{
		//return $this->build();
		}
	
	
	public function build($row)
		{
		//takes a row and makes edit form
		
		$model = $row->getTable();
				
		$metaData = $model->getFields();
		$primary = $model->getPrimary();
			
		$form = new Zend_Form();
		$form->setAttrib("accept-charset","UTF-8");
		$form->setAttrib("id", "form-edit-form");
		$form->setAttrib("method", "post");
		$form->setAttrib("name", "editForm");
		//$form->setAttrib("enctype", "");
		
		
		$idString = "";
		if($row->getID())
		{
			$idString = '/id/'.$row->getID();
		}
		
		
		$form->setDecorators(array('FormElements','Form'));
		
		$form_fields = $model->getFieldsByContext('form');
		
		
		foreach($form_fields as $fieldName => $meta)
			{
			$div_wrap_classes = "";
			
			$type = $meta['DATA_TYPE'];
			$label = isset($meta['label']) ? $meta['label'] : $fieldName;
			$value = $row->$fieldName;
			
			switch(true)
				{
				
				case strstr($type, 'tinyint') && $meta['formType'] == 'radio' :
					//is boolean - do radio yes/no
					$div_wrap_classes = ' radio';
					
					$element = new Zend_Form_Element_Radio($fieldName);
					$options = array(1 => "yes" , 0 => "no");
					$element->addMultiOptions($options);
					$element->setAttrib('class', 'radio');
				break;
				
				case isset($meta['refTableClass']):
				
					//is a foreign keyed field
					$pClass = $meta['refTableClass'];
					$pTable = new $pClass();
					
					$pSelect = $pTable->select()->title()->notDeleted();
					
					$pSelect->order($pTable->getDefaultOrder());
					
					if(!isset($meta['showZero']) || !$meta['showZero'])
					{
						$zeroTerm = $pTable->getZeroTerm();
					}
					
					$pRowset = $pTable->fetchAll($pSelect);
					$element = new Zend_Form_Element_Select($fieldName);
					
					$multi = array();
					
					foreach($pRowset as $pRow)
					{
						$multi[$pRow->getID()] = htmlentities($pRow->getTitle(), ENT_QUOTES, 'UTF-8');
					}
					
					$element->addMultiOptions($multi);
					
					if(isset($meta['showZero']) && $meta['showZero'])
					{
						$zeroTerm = $pTable->getZeroTerm();
						$element->addMultiOptions(array(0 => $zeroTerm));
					}
					
					
					$element->setAttrib('class', 'select');
				break;
				
				
				case strstr($type, 'text') || $meta['formType'] == 'textarea':
					//is text - do a textarea
					$element = new Zend_Form_Element_Textarea($fieldName);
					$element->setAttrib('class', 'textarea');
				break;
				
				
				case strstr($type,"enum"):
					//is an enum - do radio boxes
					$div_wrap_classes .= ' radio';
					$element = new Zend_Form_Element_Radio($fieldName);
					$options = explode(",",str_replace(array("enum(", "'", ")"),'', $type));
					$opts = array();
					foreach($options as $option)
						{
						$opts[$option] = $option;
						}
					
					$element->addMultiOptions($opts);
					$element->setAttrib('class', 'radio');
					
				break;
								
				case $type == 'date':
					$element = new Zend_Form_Element_Text($fieldName);
					$element->setAttrib('class', 'text');
				break;
				
				case in_array('password', $meta['roles']):
					$element = new Zend_Form_Element_Password($fieldName);
					$element->setAttrib('class', 'text')
									->addValidator('StringLength', false, array(6))
        					->setRequired(true);
					
					if($row->getID())
					{
					//we're editing, so this is optional and is to reset the password
						$element->setRequired(false);
						$element->setLabel('Reset ');
					}
					else
					{
						
					}
				break;
				
				
				case $meta['formType'] == 'text' :
					$element = new Zend_Form_Element_Text($fieldName);
					$element->setAttrib('class', 'text');
				break;
				
				
				default:
					$element = new Zend_Form_Element_Text($fieldName);
					$element->setAttrib('class', 'text');
				break;
				
				}
			
			
			
			$element->setValue($value);
			$element->setLabel($element->getLabel().' '.$label);
			
			$div_wrap = array('tag' => 'div', 'class' => 'edit-field '.$div_wrap_classes);
			
			$element->setDecorators(array(
    			'ViewHelper', 'Description', 'Errors', 'Label',
				array(array('elementDiv' => 'HtmlTag'), $div_wrap)
				));
			
			$element->setDescription($meta['fieldHint']);
			
			$element->setAttrib('id', $fieldName);
			$element->removeFilter('HtmlEntities');
			
			$form->addElement($element);
			}
			
		
		//make the save button
		//*
		$element = new Zend_Form_Element_Submit('save');
		$element->setValue('save');
		$element->setAttrib('class','button save-button edit-save-button');
		$element->setAttrib('id','form-save-button');
		
		$element->setDecorators(array(
    			'ViewHelper', array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div'))
					));
		
		$form->addElement($element);
		//*/
		
		
		if($row->getDescendent() instanceof Model_DbTable_Row)
			{
				$descRow = $row->getDescendent();
				
				$descForm = $this->build($descRow);
				$descForm->addDecorator('Fieldset', array('legend'=>'Extended information: '. $descRow->getTable()->getSingular()));
				$descForm->removeDecorator('Form');
					
				//add desc form to the main form			
				$form->addSubForm($descForm, get_class($descRow->getTable()));
				
			}
		
		
		return $form;
		}
	
	
	

   
	
	
	}
?>