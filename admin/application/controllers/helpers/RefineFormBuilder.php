<?php

class Helper_RefineFormBuilder extends Zend_Controller_Action_Helper_Abstract
	{
	
	public $view;
	
	public function setView(Zend_View_Interface $view)
		{
		$this->view = $view;
		}
	
	
	public function refineFormBuilder()
		{
		
		}
	
	
	public function build($model, $request, $user = null)
		{
		//takes an instance of Custom_Db_Table and makes a refine form for it - duh!
		
		$metaData = $model->getFields();
		$primary = $model->getPrimary();
			
		$form = new Zend_Form();
		$form->setAttrib("accept-charset","UTF-8");
		$form->setAttrib("id","form-refine-form");
		
		$form->setAttrib("method","get");
		
		$form->setAttrib("name","refineForm");
		
		$form->setAttrib("action",'/list/show/cat/'.get_class($model).'/refine/1');
		
		$form->setDecorators(array('FormElements','Form'));
		
		$refine_fields = $model->getFieldsByContext('refine');
		
		foreach($model->getFieldsByContext('refine') as $fieldName => $meta)
			{
			
			$type = $meta['DATA_TYPE'];
			
			$label = isset($meta['label']) ? $meta['label'] : $fieldName;
			$value = $request->$fieldName ? $request->$fieldName : null;
			
			$div_wrap_classes = "refine-field";
			
			switch(true)
				{
				
				case strstr($type, 'tinyint') && $meta['formType'] == 'radio' :
				//is boolean - do radio yes/no
				$div_wrap_classes .= ' radio';
				
				$element = new Zend_Form_Element_Radio($fieldName);
				$options = array(1 => "yes" , 0 => "no");
				$element->addMultiOptions($options);
				$element->setAttrib('class', 'radio');
				break;
				
				case isset($meta['refTableClass']):
				//is a foreign keyed field
				$pClass = $meta['refTableClass'];
				$pTable = new $pClass();
				
				$pSelect = $pTable->select();
				$pSelect->setIntegrityCheck(false);
				
				$pSelect->from($pTable->info('name'), array('*', 'primary_id' => $pTable->getPrimary()));
				$pSelect->joinLeft($model->info('name'), "`{$model->info('name')}`.`{$fieldName}` = `{$pTable->info('name')}`.`{$pTable->getPrimary()}`");
				
				if($order = $pTable->getDefaultOrder())
					{
					$pSelect->order($order);
					}
				
				$pSelect->where("`{$model->info('name')}`.`{$model->getPrimary()}` IS NOT NULL")
								->having("`primary_id` != '0'");
				
				
				if(!$user)
				{
					if($pTable->canHide())
					{
						$visField = $pTable->getVisField();
						$pSelect->where("`{$pTable->info('name')}`.`{$visField}` = 'show'");	
					}
					if($model->canHide())
					{
						$visField = $model->getVisField();
						$pSelect->where("`{$model->info('name')}`.`{$visField}` = 'show'");	
					}
									
				}
				
				
				
				
				$parentRowset = $pTable->fetchAll($pSelect);
				$element = new Zend_Form_Element_Select($fieldName);
				
				$element->addMultiOptions(array(''=>''));
				
				$multi = array();
				$thisName = null;
				foreach($parentRowset as $row)
				{
					if($row->getTitle() == $thisName)
					{
						$thisName = $row->getTitle();
						continue; 
					}
					
					$multi[$row->primary_id] = $row->getTitle();
					$thisName = $row->getTitle();
				}
				
				$element->addMultiOptions($multi);
				
				$element->addMultiOptions(array(0 => 'unspecified'));
				
				$element->setAttrib('class', 'select');
				break;
				
				
				case strstr($type, 'text') && $meta['formType'] == 'textarea':
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
				
				default:
				$element = new Zend_Form_Element_Text($fieldName);
				$element->setAttrib('class', 'text');
				break;
				
				}
			
			$element->setValue($value);
			$element->setLabel($label);
			//$element->setDescription($meta['fieldHint']);
			$element->setAttrib('id', $fieldName);
			$element->removeFilter('HtmlEntities');
			
			$div_wrap = array('tag' => 'div');
			if(isset($request->$fieldName) && $request->$fieldName != '')
				{
				$div_wrap = array('tag' => 'div', 'class'=> $div_wrap_classes.' active');
				}
			else
				{
					$div_wrap = array('tag' => 'div', 'class'=> $div_wrap_classes.' inactive');
				}
			
			$element->setDecorators(array(
    			'ViewHelper',
				'Description',
    			'Errors',
    			array('Label'),
				array(array('elementDiv' => 'HtmlTag'), $div_wrap)
				));
				
			$form->addElement($element);
			}
			
		
		//is the order set? do as action instead!!!
		if($request->order)
			{
			$element = new Zend_Form_Element_Hidden('order');
			$element->setValue($request->order);
			//$form->addElement($element);
			}
		
		//make the refine button
		$element = new Zend_Form_Element_Submit('refine');
		$element->setValue('refine');
		$element->setAttrib('class','button refine-button');
		$element->setAttrib('id','refine-button');
		
		$element->setDecorators(array(
    			'ViewHelper', array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class'=>'div-refine-button'))
					));
		
		$form->addElement($element);
		
		return $form;
		}
	
	
	

   
	
	
	}
?>