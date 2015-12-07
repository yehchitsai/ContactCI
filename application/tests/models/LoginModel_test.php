<?php

class LoginModel_test extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->model('LoginModel');
		$this->obj = $this->CI->LoginModel;
	}
	
	public function test_get_permit()
	{
		$id = '10203510741249410';
		$actual = $this->obj->get_permit($id);
		$expected = true;
		$this->assertEquals($expected, $actual);
		
		$id = '10203510741249420';
		$actual = $this->obj->get_permit($id);
		$expected = false;
		$this->assertEquals($expected, $actual);
	}
}

?>