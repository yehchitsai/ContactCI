<?php

class LoginModel extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
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

?>