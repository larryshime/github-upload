<?php

class Payment
{
	protected $con;
	
	public function __construct() { 
		global $con;
		$this->con = $con;
	}
	
	public function getList()
	{
		$bank_list = array();
		
		$sql = mysqli_query($this->con, "SELECT pay_id, pay_name, is_main FROM NEW_payment WHERE enabled = '1' ORDER BY pay_order ASC");
		
		while($rows = mysqli_fetch_array($sql))
		{
			$pay_id = $rows["pay_id"];
			$pay_name = $rows["pay_name"];
			$is_main = $rows["is_main"];
			
			$bank_list[$pay_id]["pay_id"] = $pay_id;
			$bank_list[$pay_id]["pay_name"] = $pay_name;
			
			if(file_exists(ROOT_PATH_BACKEND."/images/banks/{$pay_name}.png"))
				$bank_list[$pay_id]["image_exist"] = true;
			else
				$bank_list[$pay_id]["image_exist"] = false;
		}
		
		return $bank_list;
	}
}
	
?>