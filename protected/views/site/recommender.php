<?php
 
function recommenderData($location, $tweets) {

	$headerinfo = "<h4>Based on your query and past searches...</h4>";

	//get average cuisine type from searches
	
	$user_id = Yii::app()->user->id;
	$sql = "SELECT cuisinepref FROM tbl_query_history WHERE userid='".$user_id."';";
		
	$conn=Yii::app()->db;
	$comm=$conn->createCommand($sql);
	$queryhistory = $comm->queryAll();
	
	$av = array_sum($queryhistory)/count($queryhistory);
	
	//get most common restaurants in query_results
	
	$user_id = Yii::app()->user->id;
	$sql = "SELECT business_name FROM tbl_query_results WHERE user_id='".$user_id."';";
		
	$conn=Yii::app()->db;
	$comm=$conn->createCommand($sql);
	$rests = $comm->queryAll();
	
	//get most common rating from user_query table
	$user_id = Yii::app()->user->id;
	$sql = "SELECT minrating FROM tbl_query_history WHERE userid='".$user_id."';";
		
	$conn=Yii::app()->db;
	$comm=$conn->createCommand($sql);
	$rates = $comm->queryAll();
	
	//get most common cuisine type from query_restuls table
	
	$user_id = Yii::app()->user->id;
	$sql = "SELECT business_cuisine, COUNT(*) FROM tbl_query_results WHERE user_id='".$user_id."'
		    GROUP BY business_cuisine
  		    ORDER BY COUNT(*) DESC;";
		
	$conn=Yii::app()->db;
	$comm=$conn->createCommand($sql);
	$commcu = $comm->queryAll();
	
	//takes rating list index so add 1
	$avrating = (array_sum($rates)/count($rates)) + 1;
	
	//get facebook information
	
	//recommendation based on averaging over queries compared to current query
	$recstring = "<h4>Based on</h4>Preference for ".$av." cuisine.
	<h4>Queried locations</h4>".$rests[0]["business_name"].", ".$rests[1]["business_name"]." and ".$rests[2]["business_name"]
	."<h4>Tweets in the area</h4>".$tweets[0].$tweets[1].
	"<h4>Your average hygiene rating preference</h4>".$avrating." stars.".
	"<h4>Average business cuisine's in the area </h4>".$commcu[0]["business_cuisine"];
	
	//select ideal place to go based on averaged statistics on queries
	
	$user_id = Yii::app()->user->id;
	$sql = "SELECT business_name FROM tbl_query_results WHERE user_id='".$user_id."'AND business_cuisine='".$commcu[0]["business_cuisine"]."' AND business_rating >= '".$avrating."';";
		
	$conn=Yii::app()->db;
	$comm=$conn->createCommand($sql);
	$rts = $comm->queryAll();
	
	$gostring = "<h4>Top ranked place to go</h4>".$rts[0]["business_name"];
	
	return $recstring.$gostring;
	

}
?>
