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
						"photo"=>"",
						"facebook"=>array(
							array(
								"uid"=>array(),
								"location"=>array(0=>"",1=>""),
								"work"=>array()
							)
						)					
			);
			
		$query = 'SELECT * FROM `studata` WHERE `stu_id` = "'.$_POST["stu_id"].'"';
		$result = $this->db->query($query);
	
		$json["studata"]=array_values($result->unbuffered_row('array'));	
							
		$query = 'SELECT `uid`,`fb_name` FROM `fb` WHERE `stu_id` = "'.$_POST["stu_id"].'"';
		$result = $this->db->query($query);
		$i=0;
		while($row=$result->unbuffered_row('array')){
			$json["facebook"][$i]["uid"] = array_values($row); //convert associative array to index array --$array = array_values($array);
			$i++;
		}		
		for($j=0;$j<$i;$j++){	
							
			$query = 'SELECT `current_location`,`hometown_location` FROM `fb` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
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
	
	public function get_permit($id)
	{
		$query = "SELECT `uid` FROM `fb` WHERE `uid` = '$id';";
		$result = $this->db->query($query);
		$row=$result->unbuffered_row('array');
		if($row > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}