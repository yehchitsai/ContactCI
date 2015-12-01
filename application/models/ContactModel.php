<?php
class ContactModel extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_namelist($type,$key)
	{
		if($type=="姓名"){
			$str="SELECT * FROM `studata` WHERE `stu_name` LIKE '%$key%';";
		}
		else if($type=="級別"){
			$str="SELECT * FROM `studata` WHERE `stu_id` Like 'A$key%' OR `stu_id` LIKE 'T$key%';";
		}
		else if($type=="縣市"){
			$str="SELECT * FROM `fb` WHERE `current_location` LIKE '%$key%';";
		}		

		$result = $this->db->query($str);				
		$record=array();
		$uid=array();
		$stu_id=array();
				
		if($type=="姓名"||$type=="級別"){
			while($row=$result->unbuffered_row('array')){
				$row = array_values($row);
				array_push($record,array($row[0],$row[1]));
			}
		}
		else if($type=="縣市"){
			while($row=$result->unbuffered_row('array')){
				$row = array_values($row);
				array_push($uid,$row[0]);
			}
			foreach($uid as $key){
				$str = "SELECT * FROM `link` WHERE `uid` = '$key';";
				$result=$this->db->query($str);
				while($row=$result->unbuffered_row('array')){
					$row = array_values($row);
					array_push($stu_id,$row[0]);
				}
			}
			foreach($stu_id as $key){
				$str = "SELECT * FROM `studata` WHERE `stu_id` = '$key';";
				$result=$this->db->query($str);
				while($row=$result->unbuffered_row('array')){
					$row = array_values($row);
					array_push($record,array($row[0],$row[1]));
				}
			}
		}
		
		return $record;
	}
	
	public function get_detail($stu_id)
	{
		$json = array("studata"=>array(),
						"email"=>array(),
						"account"=>array(
							array(
								"uid"=>array(),
								"facebook"=>array(),
								"work"=>array(),
								"education"=>array()
							)
						)	
			);
			
		$query = 'SELECT * FROM `studata` WHERE `stu_id` = "'.$_POST["stu_id"].'"';
		$result = $this->db->query($query);
		$json["studata"]=array_values($result->unbuffered_row('array'));

		$query = 'SELECT `email` FROM `email` WHERE `stu_id` = "'.$_POST["stu_id"].'"';
		$result = $this->db->query($query);
		while($row=$result->unbuffered_row('array')){
			array_push($json['email'], array_values($row));
		}
							
		$query = 'SELECT `uid` FROM `link` WHERE `stu_id` = "'.$_POST["stu_id"].'"';
		$result = $this->db->query($query);
		$i=0;
		while($row=$result->unbuffered_row('array')){
			$json["account"][$i]["uid"] = array_values($row); //convert associative array to index array --$array = array_values($array);
			$i++;
		}
		
		for($j=0;$j<$i;$j++){
			$query = 'SELECT `fb_name`,`photo`,`current_location`,`hometown_location` FROM `fb` WHERE `uid` = "'.$json["account"][$j]["uid"][0].'"';
			$result = $this->db->query($query);
			while($row=$result->unbuffered_row('array')){
				$json["account"][$j]["facebook"] = array_values($row);
			}
			
			$query = 'SELECT `employer`,`position`,`start_date`,`end_date` FROM `work` WHERE `uid` = "'.$json["account"][$j]["uid"][0].'"';
			$result = $this->db->query($query);
			while($row=$result->unbuffered_row('array')){
				$json["account"][$j]["work"][] = array_values($row);
			}
			
			$query = 'SELECT `degree`,`school`,`year` FROM `education` WHERE `uid` = "'.$json["account"][$j]["uid"][0].'"';
			$result = $this->db->query($query);
			while($row=$result->unbuffered_row('array')){
				$json["account"][$j]["education"][] = array_values($row);
			}
		}
		
		return $json;
	}
}