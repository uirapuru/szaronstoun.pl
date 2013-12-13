<?php

class Form_Login
        extends Zend_Form
{

    public function init()
    {
        /**
         * Email
         */
        $this->addElement('text', 'email', array(
            'required' => true,
            'label' => 'Nazwa użytkownika',
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('validator' => 'NotEmpty', 'breakChainOnFailure' => true),
                array('validator' => 'StringLength', 'breakChainOnFailure' => true, array('min' => 6, 'max' => 64)),
                array('validator' => 'EmailAddress', 'breakChainOnFailure' => true),
            ),
        ));

        /**
         * Password
         */
        $this->addElement('password', 'password', array(
            'required' => true,
            'label' => 'Hasło',
            'validators' => array(
                array('validator' => 'NotEmpty', 'breakChainOnFailure' => true),
                array('validator' => 'StringLength', 'breakChainOnFailure' => true, array('min' => 6, 'max' => 20)),
            ),
            'renderPassword' => true
        ));

        /**
         * Submit
         */
        $this->addElement('submit', 'Login', array(
            'label' => 'Logowanie'
        ));
    }
}

