<?php

class Game
{
	protected $con;
	
	public function __construct() { 
		global $con;
		$this->con = $con;
	}
	
	public function getList()
	{
		$game_list = array();
	
		$sql = mysqli_query($this->con, "SELECT goods_id, goods_name_long, is_on_sale FROM NEW_goods WHERE is_on_sale = 1 AND is_delete = 0");
	
		while($rows = mysqli_fetch_array($sql))
		{
			$goods_id = $rows["goods_id"];
			$goods_name_long = $rows["goods_name_long"];
			$is_on_sale = $rows["is_on_sale"];
			
			$game_list[$goods_id]["goods_id"] = $goods_id;
			$game_list[$goods_id]["goods_name_long"] = $goods_name_long;
			$game_list[$goods_id]["is_on_sale"] = $is_on_sale;
		}
		
		return $game_list;
	}
	
	public function addGameBalance($remarks, $employee_id, $goods_id, $d_id, $amount, $deposit_amount=0, $deposit_amount_promo=0, $deposit_amount_final=0, $deposit_amount_payback=0, $deposit_amount_void=0, $sp_id=0)
	{
		mysqli_query($this->con, "INSERT INTO NEW_goods_balance (gb_add_time, gb_goods_id, gb_d_id, gb_amount, gb_deposit_amount, gb_deposit_amount_promo, gb_deposit_amount_final, gb_deposit_amount_payback, gb_deposit_amount_void, gb_deposit_sp_id, gb_end_balance, gb_employee_id, gb_remarks) VALUES ('" . strtotime("now") . "', '$goods_id', '$d_id', '$amount', '$deposit_amount', '$deposit_amount_promo', '$deposit_amount_final', '$deposit_amount_payback', '$deposit_amount_void', '$sp_id', '-1', '$employee_id', '{$remarks}')");
		
		$this->updateGameBalance($goods_id);
	}
	
	public function deductGameBalance($remarks, $employee_id, $goods_id, $d_id, $amount, $deposit_amount=0, $deposit_amount_promo=0, $deposit_amount_final=0, $deposit_amount_payback=0, $deposit_amount_void=0, $sp_id=0)
	{
		mysqli_query($this->con, "INSERT INTO NEW_goods_balance (gb_add_time, gb_goods_id, gb_d_id, gb_amount, gb_deposit_amount, gb_deposit_amount_promo, gb_deposit_amount_final, gb_deposit_amount_payback, gb_deposit_amount_void, gb_deposit_sp_id, gb_end_balance, gb_employee_id, gb_remarks) VALUES ('" . strtotime("now") . "', '$goods_id', '$d_id', '-{$amount}', '$deposit_amount', '$deposit_amount_promo', '$deposit_amount_final', '$deposit_amount_payback', '$deposit_amount_void', '$sp_id', '-1', '$employee_id', '{$remarks}')");
		
		$this->updateGameBalance($goods_id);
	}
	
	public function updateGameBalance($goods_id)
	{
		$gb_end_balance = 0;
		
		// GET LATEST END BALANCE
		
		$sql = mysqli_query($this->con, "SELECT gb_end_balance FROM NEW_goods_balance WHERE gb_goods_id = '$goods_id' AND gb_end_balance != -1 ORDER BY gb_id DESC LIMIT 1");
		
		while($rows = mysqli_fetch_array($sql))
		{
			$gb_end_balance = $rows["gb_end_balance"];
		}
		
		// UPDATE END BALANCE
		
		$sql = mysqli_query($this->con, "SELECT gb_id, gb_amount, gb_end_balance FROM NEW_goods_balance WHERE gb_goods_id = '$goods_id' AND gb_end_balance = -1 ORDER BY gb_id ASC");
		
		if(mysqli_num_rows($sql)>0)
		{
			while($rows = mysqli_fetch_array($sql))
			{
				$gb_id = $rows["gb_id"];
				$gb_amount = $rows["gb_amount"];
				$gb_new_balance = $gb_end_balance + $gb_amount;
				
				mysqli_query($this->con, "UPDATE NEW_goods_balance SET gb_end_balance = {$gb_new_balance} WHERE gb_id = '$gb_id'");
			}
			
			mysqli_query($this->con, "UPDATE NEW_goods SET is_master_slave = 0, goods_credit = {$gb_new_balance} WHERE goods_id = '$goods_id'");
			
			clearCache("live_credit");
		}
	}
}
	
?>