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
		else if($type=="學號"){
			$str="SELECT * FROM `studata` WHERE `stu_id` LIKE '%$key%';";
		}
		else if($type=="工作經歷"){
			$str="SELECT * FROM `work` WHERE `employer` LIKE '%$key%';";
		}		

		$result = $this->db->query($str);				
		$record=array();
		$uid=array();
				
		if($type=="姓名"||$type=="學號"){
			while($row=$result->unbuffered_row('array')){
				$row = array_values($row);
				array_push($record,array($row[0],$row[1]));
			}
		}
		else if($type=="工作經歷"){
			while($row=$result->unbuffered_row('array')){
				$row = array_values($row);
				array_push($uid,$row[0]);
			}
			foreach($uid as $key){
				$str = "SELECT * FROM `link` WHERE `uid` = '$key';";
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
						"photo"=>"",
						"facebook"=>array(
							array(
								"uid"=>array(),
								"location"=>array(0=>"",1=>""),
								"work"=>array()
							)
						)					
			);
			
		$query = 'SELECT `stu_id`, `stu_name`, `email` FROM `studata` WHERE `stu_id` = "'.$_POST["stu_id"].'"';
		$result = $this->db->query($query);
	
		$json["studata"]=array_values($result->unbuffered_row('array'));	
							
		$query = 'SELECT `uid`,`fb_name` FROM `link` WHERE `stu_id` = "'.$_POST["stu_id"].'"';
		$result = $this->db->query($query);
		$i=0;
		while($row=$result->unbuffered_row('array')){
			$json["facebook"][$i]["uid"] = array_values($row); //convert associative array to index array --$array = array_values($array);
			$i++;
		}		
		for($j=0;$j<$i;$j++){	
							
			$query = 'SELECT `current_location`,`hometown_location` FROM `location` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
			$result = $this->db->query($query);
			while($row=$result->unbuffered_row('array')){
				$json["facebook"][$j]["location"]= array_values($row); 
			}
				
			$query = 'SELECT * FROM `work` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
			$result = $this->db->query($query);
			while($row=$result->unbuffered_row('array')){
				$json["facebook"][$j]["work"][]= array_values($row);
			}
				
			$query = 'SELECT `photo` FROM `fb` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
			$result = $this->db->query($query);
			$json["photo"] = array_values($result->unbuffered_row('array'));
		}
		
		return $json;
	}
}