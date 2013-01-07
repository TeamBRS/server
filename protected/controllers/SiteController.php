<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
    
        public function actionRegister()
        {
                $model=new RegisterForm;
                $newUser = new User;
                
                // if it is ajax validation request
                if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
                {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                }

                // collect user input data
                if(isset($_POST['registerForm']))
                {
                        $model->attributes=$_POST['registerForm'];
                        
                        if ($model->validate()) {
                                $newUser->username = $model->username;
                                $newUser->password = $model->password;
                                $newUser->email = $model->email;
                                        
                                if($newUser->save()) {
                                        $identity=new UserIdentity($newUser->username,$model->password);
                                        $identity->authenticate();
                                        Yii::app()->user->login($identity,0);
                                        //redirect the user to page he/she came from
                                        $this->redirect(Yii::app()->user->returnUrl);
                                }
                        }
                                
                }
                // display the register form
                $this->render('register',array('model'=>$model));
        }  

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	
	public function actionAjax()
	{
	
		if(count($_POST)==2) {
		
			$name = $_POST['name'];
			$location = $_POST['mk'];
			$mode = 'review';
			echo '<h1>'.$mode.'</h1>';
			
		} else if(count($_POST)==3) {
		
		    $name = $_POST['name'];
			$location = $_POST['mk'];
			$mode = 'twitter';
			echo '<h1>'.$mode.'</h1>';

		} else {
		
		    $name = $_POST['name'];
			$location = $_POST['mk'];
			$mode = 'default';
			echo '<h1>'.$mode.'</h1>';
		
		}
		
		//pull out the metrics from the database (cba for capitals)
		//$conn=Yii::app()->db;
		//$comm=$conn->createCommand($sql);		
		
		//not sure if the above returns an array (if so, all we care about is the last element)...
		
		$checkins=1;
		$visitors=1;
		$likes=1;
		
		$sociability = $checkins/$visitors;
		$popularity = $people;
		$likability = $checkins/$likes;
		
		$this->renderPartial('resultdetail', array('location'=>$location, 'name'=>$name, 'mode'=>$mode), false, true);
	}
	
	public function actionFSA()
	{
		if(Yii::app()->user->getId()===null)
            $this->redirect(array('site/login'));
	
		$model=new FSAModel;
		
		if(isset($_POST['FSAModel']))
		{
		
			$model->attributes=$_POST['FSAModel'];
			
			//Get attributes
			$loc = $model->location;
			$venue = $model->venue;
			
			//Reverse geocode and get results
			$latlong = explode(", ", $loc);
			$data = $model->ReverseGeocode($loc,$venue);
			
			//Refine results
			$data = $model->RefineResults();

			//Assign results
			$locmarkers = $model->locmarkers;
			$bname = $model->businessname;
			$btype = $model->businesstype;
			$baddr1 = $model->businessaddr1;
			$brate = $model->businessrating;
			$bcats = $model->businesscuisine;
			$cat = $model->cuisine;
			$br = $model->minrating;
			$bt = $model->venue;
			
			//Get array of previous queries
			$queries = $model->GetHistory();
				
			//Render results in result.php
			$this->render('result',array('results'=>$data, 
			'loc'=>$latlong, 'past'=>$queries,'markers'=>$locmarkers, 'bname'=>$bname,
			'btype'=>$btype,'baddr1'=>$baddr1,'brate'=>$brate, 'cuisine'=>$bcats, 'cco'=>$cat, 'br'=>$br, 'bt'=>$bt));
						
		} else {
	
			$this->render('fsa',array('model'=>$model));
			
		}
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}