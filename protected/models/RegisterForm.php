<?php

/**
 * registerForm class.
 * registerForm is the data structure for keeping
 * user registration form data. It is used by the 'register' action of 'SiteController'.
 */
class registerForm extends CFormModel
{
        public $username;
        public $password;
        public $email;

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
                        array('username, password, email', 'required')
                        // make sure username and email are unique
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
                        'email'=>'E-mail',
                );
        }
}