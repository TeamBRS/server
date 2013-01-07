<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
		
	//facebook authentication info
	public $app_id = "100257963482709";
	public $app_secret = "5ffd4843d77d881f6e9a82c0309c51d7";

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create', 'update' and 'facebookconnect' actions
				'actions'=>array('create','update', 'facebookconnect', 'facebookin', 'facebookerror'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		//retrieve the user's name from the usertable 
		$gnn_user = User::model()->find('id=:id', array(':id'=>$id));
		
		//retrieve facebook user using their username
		$fb_user = FacebookUser::model()->find('user_id=:user_id', array(':user_id'=>$gnn_user->username));	

		//pull out the user's history
		$sql = "SELECT * from tbl_query_results WHERE user_id ='" .$gnn_user->username ."';";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam("category_id", $categoryId, PDO::PARAM_INT);
		$rows = $command->queryAll();
		
		$cats = array();
		
		foreach($rows as $row) {
			$cats[] = $row['business_cuisine'];
		}
		
		$a = $b = $c = $d = null;
		foreach($cats as $v) {
		  if(!isset($a) || $v > $a) {
			$d = $c;
			$c = $b;
			$b = $a;
			$a = $v;
		  }elseif(!isset($b) || $v > $b) {
			$d = $c;
			$c = $b;
			$b = $v;
		  }elseif(!isset($c) || $v > $c) {
			$d = $c;
			$c = $v;
		  }elseif(!isset($d) || $v > $d) {
			$d = $v;
		  }
		}

		$cats = array($a, $b, $c, $d);
		
		$previous = array();
		
		if($fb_user){
			//get previous rests
			$fql = "SELECT name, categories FROM page WHERE page_id IN (SELECT page_id from checkin WHERE author_uid =me())"; 
			$fb_url = "https://graph.facebook.com/fql?q=" .urlencode($fql) ."&access_token=" .$fb_user->auth_key;
			$fb_prev = json_decode(file_get_contents($fb_url),false, 512, JSON_BIGINT_AS_STRING);

			
			
			foreach($fb_prev->data as $prev) {
				//var_dump($prev); die();
				$flag=false;
				foreach($prev->categories as $ccc) {
					if($ccc->name == "Bar" || $ccc->name == "Restaurant") {
						$flag=true;
					}
				}
				if ($flag) {
					$previous[] = $prev->name;
				}
				$flag=false;
			}
		}	
		
		//display the profile page 
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'fb_user'=>$fb_user,
			'cats'=>$cats,
			'previous'=>$previous
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionFacebookConnect() 
	{
		//retrieve the user's id
		$gnn_user = User::model()->find('username=:username', array(':username'=>Yii::app()->user->id));
		
		//load user model
		$model=$this->loadModel($gnn_user->id);
		
		//generate return url
		$pageURL = 'http://';
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= "localhost". "/server/fb-collector.php";
		} else {
			$pageURL .= "localhost". "/server/fb-collector.php";
		}
		
		$my_url = $pageURL;
		
		//if no code has been returned initiate the facebook process
		if(empty($_REQUEST["code"])) {
			$_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
			$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
				. $this->app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
				. $_SESSION['state'] . 
				"&scope=user_activities,user_birthday,user_hometown,user_interests,user_likes,"
				."user_location,user_relationships,user_relationship_details,user_religion_politics,user_checkins,friends_checkins,user_status,friends_status,"
				."friends_activities,friends_interests,friends_relationships,friends_status,friends_photos,"
				."offline_access,read_friendlists,publish_checkins,publish_actions";

			$message = "<script> top.location.href='" . $dialog_url . "'</script>";
			$this->render('facebookout',array('model'=>$model,'message'=>$message,));
		}
	}
	
	public function actionFacebookIn() 
	{
		
		//generate reutrn url 
		$pageURL = 'http://';
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= "localhost". "/server/fb-collector.php";
		} else {
			$pageURL .= "localhost". "/server/fb-collector.php";
		}
		
		$my_url = $pageURL;
		
		//if all is well complete the authentication and retrieve a token
		if(!empty($_SESSION['state'])  && ($_SESSION['state'] === $_REQUEST['state'])) {
			$token_url = "https://graph.facebook.com/oauth/access_token?"
			. "client_id=" . $this->app_id . "&redirect_uri=" . urlencode($my_url)
			. "&client_secret=" . $this->app_secret . "&code=" . $_REQUEST['code'];
			
			$response = file_get_contents($token_url);
			
			//ensure that params is not defined
			$params = null;
			
			//parse response into a set of parmeters
			parse_str($response, $params);

			//access the individual's page from the opengraph
			$graph_url = "https://graph.facebook.com/me?access_token=" 
				. $params['access_token'];

				
			//retrieve the page and then parse it into an array 
			$user = json_decode(file_get_contents($graph_url));
			
			//pull out the person's username
			$facebook_name = $user->name;
			
			//create a new FacebookUser model with the details we now have
			$fb_user = new FacebookUser;
			$fb_user->user_id = Yii::app()->user->getId();
			$fb_user->auth_key = $params['access_token'];
			$fb_user->key_expiry = $params['expires'];
			$fb_user->facebook_id = $user->id;
			
			//dump the model to the database
			$fb_user->insert();
			
			//retrieve the user's name 
			$gnn_user = User::model()->find('username=:username', array(':username'=>$fb_user->user_id)); 
			
			//generate a welcome page 
			$this->render('facebookin',array( 'facebook_name'=>$facebook_name,'response'=>$fb_user,'gnn_user'=>$gnn_user));
		}else {
			$this->render('facebookerror',array());
		}
	}
	
	public function actionFacebookRemove($id) 
	{
		//TODO
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	function get_data($url) {
		  $ch = curl_init();
		  $timeout = 5;
		  curl_setopt($ch, CURLOPT_URL, $url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		  $data = curl_exec($ch);
		  curl_close($ch);
		  return $data;
	}
}
