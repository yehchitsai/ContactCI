<?php
/**
提供FACEBOOK帳號登入登出
查詢社團成員與其UID並寫入資料庫name_uid資料表
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
          $canvas_page = 'http://localhost/server/group_members.php';
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
		
		$query = 'DELETE FROM name_uid';
		$array_uid = mysql_query($query,$link);
		
        $user_profile = $facebook->api('/me','GET');
        echo "Name: " . $user_profile['name'];
		echo "<br>user_id:" . $user_profile['id'];
		
		
		//while($uid = mysql_fetch_row($array_uid)){
			
				
			
			$graph = '/261722443914328?fields=members';
			$ret_obj = $facebook->api('/261722443914328/members','GET');			
			
			
			foreach($ret_obj['data'] as $vaule){
				echo '<br>----------------<br>';
				
				$que='/'.$vaule['id'].'/picture';
				$pic = $facebook->api($que,'GET',array (
						'redirect' => false,
						'height' => '200',
						'type' => 'normal',
						'width' => '200',
					)
				);				
				
				echo $vaule['name'].'<br>';
				echo $vaule['id'].'<br>';
				echo $pic['data']['url'];
				
				ob_flush();
				flush();
				$query='INSERT INTO `name_uid`(`fbname`, `uid`,`photo`) VALUES ("'.$vaule['name'].'","'.$vaule['id'].'","'.$pic['data']['url'].'")';
				mysql_query($query);
				
			}

	
			/*
			if(array_key_exists('0', $ret_obj[0]['work'])){
			echo '<br>----------------------<br>';
			echo $uid[0];
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
				
					$query='INSERT INTO `name_uid`(`name`, `uid`) VALUES ("'.$uid[0].'","'.$vaule2.'")';
					
					echo '<br>'.$query;
					
					mysql_query($query);
					
				}					
			}
			*/
		//}
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