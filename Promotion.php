<?php
	
class Promotion
{
	protected $con;
	protected $promotion_id;
	
	public function __construct($promotion_id) { 
		global $con;
		$this->con = $con;
		$this->promotion_id = $promotion_id;
	}
	
	public function isClaimed($user_id)
	{
		// GET PROMOTION
		
		$sp_usage = "";
		$sp_main_type = "";
		$mysql_addon = "";
		
		$sql = mysqli_query($this->con, "SELECT sp_usage, sp_main_type FROM NEW_special_promotion WHERE sp_id = '" . $this->promotion_id . "' LIMIT 1");
		
		while($rows = mysqli_fetch_array($sql))
		{
			$sp_usage = $rows["sp_usage"];
			$sp_main_type = $rows["sp_main_type"];
		}
		
		if($sp_usage!="Daily" && $sp_usage!="First Time Only")
			return false;
		
		// GET DEPOSIT WITH PROMOTION
		
		$deposit_type = "deposit";
		
		if($sp_main_type=="withdrawal")
			$deposit_type = "withdrawal";
			
		if($sp_usage=="Daily")
			$mysql_addon .= " AND deposit_date_strtotime >= " . strtotime(date("Y-m-d"));
		
		$sql = mysqli_query($this->con, "SELECT d_id FROM SUPER_deposit WHERE deposit_user_id = '$user_id' AND deposit_sp_id = '" . $this->promotion_id . "' AND deposit_main_type = '{$deposit_type}' AND deposit_status = 'approved' $mysql_addon LIMIT 1");
		
		if(mysqli_num_rows($sql)>0)
		{
			return true;
		}
		
		return false;
	}
}

?>