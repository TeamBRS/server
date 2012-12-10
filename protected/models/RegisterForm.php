<?php

/**
 * RegisterForm class.
 * RegisterForm is the data structure for keeping
 * user registration form data. It is used by the 'register' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{

		//Determine user variables that need to be captured
		
        public $username;			//desired user name
        public $password;			//desired password
        public $location;			//location (auto)
        public $email; 				//email
        public $cusineposn[];		//positive cusine choices
        public $cusinenegn[];		//negative cusine choices
        public $cusinethreshold;	//minimum rating from fsa
        public $granularitylevel;	//level of desired detail (interpretation style?)
        public $socialpersonaliser;	//behavior based user modelling (wisdom of the crowds)

        private $_identity;

        /**
         * Declares the validation rules.
         * The rules state that username, password & email are required,
         * and username & email needs to be unique.
         */
        public function rules()
        {
                return array(
                        // username and password are required
                        // include cusine choices or we have nothing to go on.
                        array('username, password, email, cusineposn, cusinenegn', 'required'),
                        // make sure username and email are unique
            			array('username, email', 'unique'),
                );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
                return array(
                        'username'=>'Your username',
                        'password'=>'Your password',
                        'location'=>'Your location (optional)',
                        'email'=>'Your username',
                        'cusineposn[]'=>'Cusines you love',
                        'cusinenegn[]'=>'Cusines you\'d rather avoid..!',
                        'cusinethreshold'=>'Minimum establishment rating',
                        'granularitylevel'=>'How do you see things?',
                        'socialpersonaliser'=>'Want to see what your friends think?',
                );
        }
}