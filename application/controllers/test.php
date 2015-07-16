<?php
if (isset($_POST["key"])){
	
		$link_ID=mysql_connect("localhost","root","30678");
		$key=$_POST ["key"];
		$type=$_POST ["type"];
		mysql_select_db("database");
		
		if($type=="姓名"){
			$str="SELECT * FROM `100a` WHERE `Name` LIKE '%$key%';";
		}
		else if($type=="學號"){
			$str="SELECT * FROM `100a` WHERE `Stuid` LIKE '%$key%';";
		}
		else if($type=="專題老師"){
			$str="SELECT * FROM `project` WHERE `teacher` LIKE '%$key%';";
		}
		else if($type=="工作經歷"){
			$str="SELECT * FROM `work` WHERE `employer` LIKE '%$key%';";
		}		

		
		mysql_query("SET NAMES UTF8");
		$result=mysql_query($str,$link_ID);
		//print_r($result);
		
		$record=array();
		$uid=array();
		
		if($type=="姓名"||$type=="學號"){
			while($row=mysql_fetch_row($result)){
				array_push($record,array($row[1],$row[0]));
			}
		}
		else if($type=="專題老師"){
			while($row=mysql_fetch_row($result)){
				array_push($record,array($row[5],$row[6]));
			}
		}
		else if($type=="工作經歷"){
			while($row=mysql_fetch_row($result)){
				array_push($uid,$row[0]);
			}
			foreach($uid as $key){
				$str = "SELECT * FROM `uid` WHERE `UID` = '$key';";
				$result=mysql_query($str,$link_ID);
				while($row=mysql_fetch_row($result)){
					array_push($record,array($row[1],$row[0]));
				}
			}
		}
		
		mysql_close($link_ID);
		echo json_encode($record,JSON_UNESCAPED_UNICODE);	
	}	
?>