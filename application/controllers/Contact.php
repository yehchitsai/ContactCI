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
	 
	function __construct()
	{
		parent::__construct();
//		$this->cors_headers();
		$this->load->model('ContactModel');	
	}
	
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
	
	public function detail()
	{		
		$this->cors_headers();
		if(isset($_POST['stu_id'])){
			$stu_id=$_POST ['stu_id'];
			$json=$this->ContactModel->get_detail($stu_id);
			echo json_encode($json,JSON_UNESCAPED_UNICODE);
		}
	}
	
	public function namelist()
	{	
		$this->cors_headers();
		if (isset($_POST["key"])){
			
			$type=$_POST ["type"];
			$key=$_POST ["key"];
				
				
			$record=$this->ContactModel->get_namelist($type,$key);			
			echo json_encode($record,JSON_UNESCAPED_UNICODE);
		}
	}
	
	public function permit()
	{
		$this->cors_headers();
//		if (isset($_POST["id"])){
		if ($this->input->post_get('id', TRUE)!=NULL){
			
			$id=$this->input->post_get('id', TRUE);
			$result=$this->ContactModel->get_permit($id);			
			echo $result;
		}
	}
	
	public function update()
	{
		$this->cors_headers();
		if ($this->input->post_get('AccessToken', TRUE)!=NULL){
			
			$AccessToken=$this->input->post_get('AccessToken', TRUE);
			$result=$this->ContactModel->update_info($AccessToken);			
			echo $result;
		}
	}
}
