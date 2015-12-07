<?php

class SearchModel_test extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->model('SearchModel');
		$this->obj = $this->CI->SearchModel;
	}
	
	public function test_get_namelist()
	{
		$type = '姓名';
		$key = '劉';
		$actual = $this->obj->get_namelist($type,$key);
		$expected = array('A0128418','劉文勝');
		$this->assertInternalType('array',$actual);
		$this->assertContains($expected, $actual);
		
		$type = '級別';
		$key = '99';
		$actual = $this->obj->get_namelist($type,$key);
		$expected = array('A9928400','賴昱佐');
		$this->assertInternalType('array',$actual);
		$this->assertContains($expected, $actual);
		
		$type = '縣市';
		$key = 'kaohsiung';
		$actual = $this->obj->get_namelist($type,$key);
		$expected = array('A0128418','劉文勝');
		$this->assertInternalType('array',$actual);
		$this->assertContains($expected, $actual);
	}
}

?>