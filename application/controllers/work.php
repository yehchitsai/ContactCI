<?php
/**
提供FACEBOOK帳號登入登出
連結資料庫獲取社團成員UID
使用FQL查詢社團成員資料
寫入資料庫
**/
  require_once('src/facebook.php');
  
  //APP資訊
  $config = array(
    'appId' => '533439160077459',
    'secret' => '54707dc4c09ee382bd22ec774b3ce495',  
  );

  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
?>
<html>
  <head>
	<meta http-equiv="refresh" content = "43200">
  </head>
  <body>

  <?php
  
	set_time_limit(0);
	
		//登入方法
      function render_login($facebook) {
          $canvas_page = 'http://localhost/server/example.php';
          // HERE YOU ASK THE USER TO ACCEPT YOUR APP AND SPECIFY THE PERMISSIONS NEEDED BY
          $login_url = $facebook->getLoginUrl(array('scope'=>'friends_work_history,email,user_photos,friends_photos', 'redirect_uri'=>$canvas_page));
          echo 'Please <a href="' . $login_url . '">login.</a>';
      }
	
	if(isset($_GET['action']) && $_GET['action'] === 'logout'){
        $facebook->destroySession(); //登出
    }
	
	$link = mysql_connect('localhost','root','30678');
	mysql_select_db('database');
	mysql_query("SET NAMES 'utf8'");
	
	
	//$uid = mysql_fetch_row($array_uid);
	
	
    if($user_id) {
      try {
		
		
        $user_profile = $facebook->api('/me','GET');
        echo "Name: " . $user_profile['name'];
		echo "<br>user_id:" . $user_profile['id'];
		
		$query = 'DELETE FROM work';
		mysql_query($query);
		$query = 'SELECT `uid` FROM `name_uid`';
		$array_uid = mysql_query($query,$link);
		$num_rows = mysql_num_rows($array_uid);
		$i=0;
		
		while($uid = mysql_fetch_row($array_uid)){
			
			$i = $i+1;	
			echo '<br>'.$i.'/'.$num_rows;
			
			$fql = 'SELECT work FROM user WHERE uid ='.$uid[0];
			$ret_obj = $facebook->api(array(
									   'method' => 'fql.query',
									   'query' => $fql,
									 ));

			// FQL queries return the results in an array, so we have
			//  to get the user's name from the first element in the array.
			
			if(array_key_exists('0', $ret_obj[0]['work'])){
			echo '<br>----------------------';
			
			echo '<br>'.$uid[0];			
			$vaule2 = '';
			$vaule3 = '';
			$vaule4 = '';
			$vaule5 = '';
				foreach($ret_obj[0]['work'] as $vaule){
				
					
					
					echo '<br>公司:'.$vaule['employer']['name'];
					$vaule2 = $vaule['employer']['name'];
					if(array_key_exists('position', $vaule)){
						echo '<br>職位:'.$vaule['position']['name'];
						$vaule3 = $vaule['position']['name'];
					}
					if(array_key_exists('start_date', $vaule)){
						echo '<br>開始時間:'.$vaule['start_date'];
						$vaule4 = $vaule['start_date'];
					}
					if(array_key_exists('end_date', $vaule)){
						echo '<br>結束時間:'.$vaule['end_date'];
						$vaule5 = $vaule['end_date'];
					}					
					
					$query='INSERT INTO `work`(`uid`, `employer`, `position`, `start_date`, `end_date`) VALUES ("'.$uid[0].'","'.$vaule2.'","'.$vaule3.'","'.$vaule4.'","'.$vaule5.'")';
					
					//echo '<br>'.$query;
					
					mysql_query($query);
				}					
			}
			ob_flush();
			flush();
		}
		mysql_close();
		echo '<br><a href="?action=logout">Logout</a>';
		

      } catch(FacebookApiException $e) {
        render_login($facebook);
        echo "1";
        error_log($e->getType());
        error_log($e->getMessage());
      }
    } else {
       render_login($facebook);
       echo "2";
    }
  ?>

  </body>
</html>