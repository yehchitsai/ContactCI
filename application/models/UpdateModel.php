<?php

class UpdateModel extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function update_members($data)
	{
		$query = 'DELETE FROM fb';
		$this->db->query($query);
	
		foreach($data['graphObject']['members'] as $value){
			$query='INSERT INTO `fb`(`uid`, `fb_name`,`photo`) VALUES ("'.$value['id'].'","'.$value['name'].'","'.$value['picture']['url'].'")';
			$this->db->query($query);
		}
		$this->load->view('web', $data);
	}
	
	public function update_info($AcessToken)
	{
		return $AcessToken;
	}
}

?>