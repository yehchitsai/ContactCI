<?php
	if(isset($_POST['stu'])){
		$link = mysql_connect('localhost','root','30678');
		mysql_select_db('database');
		mysql_query("SET NAMES 'utf8'");
			
		$json = array("student"=>array(),
					"url"=>"",
					"facebook"=>array(
						array(
							"uid"=>array(),"location"=>array(0=>"",1=>""),"work"=>array()
						)
					)					
		);
		
		$query = 'SELECT * FROM `notfb` WHERE `stu_no` = "'.$_POST["stu"].'"';
		$result = mysql_query($query);		
		$json["student"]=mysql_fetch_row($result);		
						
		$query = 'SELECT `UID`,`fb_name` FROM `uid` WHERE `stu_no` = "'.$_POST["stu"].'"';
		$result = mysql_query($query);
		$i=0;
		while($row=mysql_fetch_row($result)){
			$json["facebook"][$i]["uid"] = $row;
			$i++;
		}		
		for($j=0;$j<$i;$j++){	
						
			$query = 'SELECT `current_location`,`hometown_location` FROM `location` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
			$result = mysql_query($query);
			while($row=mysql_fetch_row($result)){
				$json["facebook"][$j]["location"]= $row;
			}
			
			$query = 'SELECT * FROM `work` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
			$result = mysql_query($query);
			while($row=mysql_fetch_row($result)){
				$json["facebook"][$j]["work"][]= $row;
			}
			
			$query = 'SELECT `photo` FROM `name_uid` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
			$result = mysql_query($query);
			$json["url"] = mysql_fetch_row($result);
		}
		/*
		$query = 'SELECT `work`.* FROM `100a`,`uid`,`work` WHERE `stuid` = "'.$_POST["stu"].'" && `uid`.`UID`=`work`.`uid` && `uid`.`stu_no` = `100a`.`stuid`';
		$result = mysql_query($query);
		while($row=mysql_fetch_row($result)){
			$json["facebook"][]=$row;
		}
		*/
		
		echo json_encode($json,JSON_UNESCAPED_UNICODE);
	}
?>

