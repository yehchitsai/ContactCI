<?php

class UpdateModel extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	public function write_monitor_data($monitor_data)
	{
		$query = "INSERT INTO `monitor_data` VALUES ($monitor_data[0],$monitor_data[1],$monitor_data[2],$monitor_data[3],$monitor_data[4],$monitor_data[5],$monitor_data[6],$monitor_data[7])";
		$result = $this->db->query($query);
		return $result;
	}
	*/
	
	/*
	public function get_monitor_last()
	{
		$query = "SELECT * FROM `monitor_data` ORDER BY `time` DESC LIMIT 1";
		$result = $this->db->query($query);
		$data = array_values($result->unbuffered_row('array'));
		return $data;
	}
	*/
	
	/*
	public function get_linechart($type)
	{
		if($type == "1")
		{
			$query = "SELECT `login`,`time` FROM `monitor_data` ORDER BY `time` DESC LIMIT 5";
		}
		else if($type == "2")
		{
			$query = "SELECT `search`,`time` FROM `monitor_data` ORDER BY `time` DESC LIMIT 5";
		}
		else if($type == "3")
		{
			$query = "SELECT `namelist`,`time` FROM `monitor_data` ORDER BY `time` DESC LIMIT 5";
		}
		else if($type == "4")
		{
			$query = "SELECT `render`,`time` FROM `monitor_data` ORDER BY `time` DESC LIMIT 5";
		}
		$result = $this->db->query($query);
		$data = array();
		while($row=$result->unbuffered_row('array')){
			array_push($data, array_values($row));
		}
		return $data;
	}
	*/
}

?>