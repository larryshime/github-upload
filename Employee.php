<?php
	
class Employee
{
	protected $con;
	
	public function __construct() { 
		global $con;
		$this->con = $con;
	}
	
	public function getList()
	{
		$employee_list = array();
		
		$sql = mysqli_query($this->con, "SELECT employee_id, employee_short_name, employee_username FROM SUPER_employees WHERE employee_username != 'beachman' AND employee_username != 'master' ORDER BY employee_short_name ASC");
	
		while($rows = mysqli_fetch_array($sql))
		{
			$employee_id = $rows["employee_id"];
			$employee_short_name = $rows["employee_short_name"];
			$employee_username = $rows["employee_username"];
			
			$employee_list[$employee_id]["employee_id"] = $employee_id;
			$employee_list[$employee_id]["employee_short_name"] = $employee_short_name;
			$employee_list[$employee_id]["employee_username"] = $employee_username;
		}
		
		return $employee_list;
	}
}