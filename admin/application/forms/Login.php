<?php

class Form_Login extends Zend_Form
{

	public function init()
	{
	
	$username = new Zend_Form_Element_Text('username');
        $username->class = 'text';
        $username->setLabel('Username:')
									->setRequired(true)
									->addValidator('notEmpty')
									->addValidator('stringLength', true, array(7,9))
									->addValidator('alnum', true)
									->setDecorators(array(
                     array('ViewHelper',
                           array('helper' => 'formText')),
                     array('Label',
                           array('class' => 'label'))
                 ));
				
				
        $password = new Zend_Form_Element_Password('password');
        $password->class = 'text';
        $password->setLabel('Password:')
									->setRequired(true)
									->addValidator('notEmpty')
									->addValidator('stringLength', false, array(6,99))
									->addValidator('alnum', true)
                	->setDecorators(array(
                     array('ViewHelper',
                           array('helper' => 'formPassword')),
                     array('Label',
                           array('class' => 'label'))
                 ));

        $submit = new Zend_Form_Element_Submit('login');
        $submit->class = 'button login-button';
        $submit->setValue('Login')
               ->setDecorators(array(
                   array('ViewHelper',
                   array('helper' => 'formSubmit'))
               ));
				
        $this->addElements(array(
            $username,
            $password,
            $submit
        ));
				
        $this->setDecorators(array(
            'FormElements',
            'Fieldset',
            'Form',
						'Description',
						'Errors'
        ));
	
	}

}

?>