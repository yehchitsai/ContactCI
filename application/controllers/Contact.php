<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
    function cors_headers() //Cross-origin resource sharing
    {
	header('Access-Control-Allow-Origin: *');
//	header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }
	
	public function test2()
	{		
		$this->cors_headers();
		if(isset($_POST['stu'])){
/*
			$link = mysql_connect('localhost','root','30678');
			mysql_select_db('database');
			mysql_query("SET NAMES 'utf8'");
*/				
			$json = array("student"=>array(),
						"url"=>"",
						"facebook"=>array(
							array(
								"uid"=>array(),
								"location"=>array(0=>"",1=>""),
								"work"=>array()
							)
						)					
			);
			
			$query = 'SELECT * FROM `notfb` WHERE `stu_no` = "'.$_POST["stu"].'"';
			$result = $this->db->query($query);
//			$result = mysql_query($query);		
			$json["student"]=array_values($result->unbuffered_row('array'));
//			$json["student"]=mysql_fetch_row($result);		
							
			$query = 'SELECT `UID`,`fb_name` FROM `uid` WHERE `stu_no` = "'.$_POST["stu"].'"';
			$result = $this->db->query($query);
//			$result = mysql_query($query);
			$i=0;
			while($row=$result->unbuffered_row('array')){
//			while($row=mysql_fetch_row($result)){
				$json["facebook"][$i]["uid"] = array_values($row); //convert associative array to index array --$array = array_values($array);
				$i++;
			}		
			for($j=0;$j<$i;$j++){	
							
				$query = 'SELECT `current_location`,`hometown_location` FROM `location` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
				$result = $this->db->query($query);
//				$result = mysql_query($query);
				while($row=$result->unbuffered_row('array')){
//				while($row=mysql_fetch_row($result)){
					$json["facebook"][$j]["location"]= array_values($row); 
				}
				
				$query = 'SELECT * FROM `work` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
				$result = $this->db->query($query);
//				$result = mysql_query($query);
				while($row=$result->unbuffered_row('array')){
//				while($row=mysql_fetch_row($result)){
					$json["facebook"][$j]["work"][]= array_values($row);
				}
				
				$query = 'SELECT `photo` FROM `name_uid` WHERE `uid` = "'.$json["facebook"][$j]["uid"][0].'"';
				$result = $this->db->query($query);
//				$result = mysql_query($query);
				$json["url"] = array_values($result->unbuffered_row('array'));
//				$json["url"] = mysql_fetch_row($result);
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
	}
	public function test()
	{	
		$this->cors_headers();
		if (isset($_POST["key"])){
			
//				$link_ID=mysql_connect("localhost","root","30678");
				$key=$_POST ["key"];
				$type=$_POST ["type"];
//				mysql_select_db("database");
				
				if($type=="姓名"){
					$str="SELECT * FROM `100a` WHERE `name` LIKE '%$key%';";
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

				$result = $this->db->query($str);
//				mysql_query("SET NAMES UTF8");
//				$result=mysql_query($str,$link_ID);
				//print_r($result);
				
				$record=array();
				$uid=array();
				
				if($type=="姓名"||$type=="學號"){
//					while($row=mysql_fetch_row($result)){
					while($row=$result->unbuffered_row('array')){
						//echo json_encode(var_dump($row),JSON_UNESCAPED_UNICODE);
						$row = array_values($row);
						array_push($record,array($row[1],$row[0]));
					}
				}
				else if($type=="專題老師"){
//					while($row=mysql_fetch_row($result)){
					while($row=$result->unbuffered_row('array')){
						$row = array_values($row);
						array_push($record,array($row[5],$row[6]));
					}
				}
				else if($type=="工作經歷"){
//					while($row=mysql_fetch_row($result)){
					while($row=$result->unbuffered_row('array')){
						$row = array_values($row);
						array_push($uid,$row[0]);
					}
					foreach($uid as $key){
						$str = "SELECT * FROM `uid` WHERE `UID` = '$key';";
//						$result=mysql_query($str,$link_ID);
						$result=$this->db->query($str);
//						while($row=mysql_fetch_row($result)){
						while($row=$result->unbuffered_row('array')){
							$row = array_values($row);
							array_push($record,array($row[1],$row[0]));
						}
					}
				}
				
//				mysql_close($link_ID);
				echo json_encode($record,JSON_UNESCAPED_UNICODE);
		}
	}
}
