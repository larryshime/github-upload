<?php

include_once("../header.php");
	
if($_POST["action"]=="update_deposit_unclaimed")
{
	$d_id_array = $_POST["d_id"];
	$deposit_status_array = $_POST["deposit_status"];
	
	for($a=0; $a<=count($d_id_array); $a++)
	{
		$d_id = $d_id_array[$a];
		$deposit_status = $deposit_status_array[$a];
		
		$k_sql = mysqli_query($con, "SELECT d_id, deposit_status, user_name FROM SUPER_deposit AS d 
		LEFT JOIN NEW_users AS u ON u.user_id = d.deposit_user_id 
		WHERE deposit_uc_id = '$d_id'");
		
		if(mysqli_num_rows($k_sql)==0)
		{
			mysql_query_smart("UPDATE SUPER_deposit SET is_master_slave = 0, deposit_status = '$deposit_status' WHERE d_id = '$d_id' AND deposit_main_type = 'deposit_unclaim'");
		}
	}
}
else if($_POST["action"]=="add_unclaimed_deposit")
{
	$deposit_day_array = $_POST["deposit_day"];
	$deposit_month_array = $_POST["deposit_month"];
	$deposit_year_array = $_POST["deposit_year"];
	$deposit_hour_array = $_POST["deposit_hour"];
	$deposit_min_array = $_POST["deposit_min"];
	$deposit_am_pm_array = $_POST["deposit_am_pm"];
	$deposit_bank_array = $_POST["deposit_bank"];
	$deposit_amount_array = $_POST["deposit_amount"];
	
	for($a=0; $a<=count($deposit_day_array); $a++)
	{
		$deposit_day = $deposit_day_array[$a];
		$deposit_month = $deposit_month_array[$a];
		$deposit_year = $deposit_year_array[$a];
		$deposit_hour = $deposit_hour_array[$a];
		$deposit_min = $deposit_min_array[$a];
		$deposit_am_pm = $deposit_am_pm_array[$a];
		$deposit_bank = $deposit_bank_array[$a];
		$deposit_amount = $deposit_amount_array[$a];
		
		$deposit_date = "{$deposit_day}-{$deposit_month}-{$deposit_year}";
		$deposit_time = "{$deposit_hour}:{$deposit_min}{$deposit_am_pm}";
		$deposit_strtotime = strtotime("$deposit_date $deposit_time");
		
		if($deposit_amount>0)
		{
			mysql_query_smart("INSERT INTO SUPER_deposit (deposit_main_type, deposit_status, deposit_add_employee_id, deposit_add_time, deposit_amount, deposit_bank, deposit_date, deposit_time, deposit_date_strtotime) VALUES('deposit_unclaim', 'pending', '" . $_SESSION["employee_id"] . "', '" . strtotime("now"). "', '$deposit_amount', '$deposit_bank', '$deposit_date', '$deposit_time', '$deposit_strtotime')");
			
			//echo "INSERT INTO SUPER_deposit (deposit_main_type, deposit_status, deposit_add_employee_id, deposit_add_time, deposit_amount, deposit_bank, deposit_date, deposit_time, deposit_date_strtotime) VALUES('deposit_unclaim', 'pending', '" . $_SESSION["employee_id"] . "', '" . strtotime("now"). "', '$deposit_amount', '$deposit_bank', '$deposit_date', '$deposit_time', '$deposit_strtotime')";
			
			echo "
			<div style='color:green; margin-bottom:5px;'>" . $_LANG["unclaim_deposit_add"] . " (" . CURRENCY . "$deposit_amount, $deposit_date)</div>
			";
		}
	}
}

/*
 * Get payment list
 */
 
$payment_list = array();

$sql = mysqli_query($con, "SELECT pay_id, pay_name FROM NEW_payment WHERE is_main = 1");
				
while($rows = mysqli_fetch_array($sql))
{
	$pay_id = $rows["pay_id"];
	$pay_name = $rows["pay_name"];
	
	$payment_list[$pay_id] = $pay_name;
}

/*
 * Get values
 */
 
$get_from_date = mysqli_real_escape_string($con, $_GET["from_date"]);
$get_deposit_bank = mysqli_real_escape_string($con, $_GET["deposit_bank"]);
$get_deposit_amount = mysqli_real_escape_string($con, $_GET["deposit_amount"]);

if($get_from_date!="")
{
	$get_from_date = date("Y-m-d", strtotime($get_from_date));
}
else
{
	$get_from_date = date("Y-m-d");
}

$status_array = array();
$status_array["pending"] = $_LANG["pending"];
$status_array["approved"] = $_LANG["approved"];
$status_array["rejected"] = $_LANG["rejected"];

$from_date_strtotime = strtotime($get_from_date);
$to_date_strtotime = $from_date_strtotime + 86400;

$mysql_addon = "";

if($get_deposit_bank!="")
{
	$mysql_addon .= "d.deposit_bank = '{$get_deposit_bank}' AND ";
}

if($get_deposit_amount!="")
{
	$mysql_addon .= "d.deposit_amount = '{$get_deposit_amount}' AND ";
}

$deposit_data = array();
	
$sql = mysqli_query($con, "SELECT d_id, deposit_date_strtotime, deposit_date, deposit_time, deposit_bank, deposit_amount, deposit_status, pay_name, user_name FROM SUPER_deposit AS d 
LEFT JOIN NEW_users AS u ON u.user_id = d.deposit_user_id 
LEFT JOIN NEW_payment AS p ON p.pay_id = d.deposit_bank 
WHERE $mysql_addon deposit_main_type = 'deposit_unclaim' AND deposit_date_strtotime >= $from_date_strtotime AND deposit_date_strtotime < $to_date_strtotime");

while($rows = mysqli_fetch_array($sql))
{
	$d_id = $rows["d_id"];
	$pay_name = $rows["pay_name"];
	$user_name = $rows["user_name"];
	
	$deposit_data[$d_id]["d_id"] = $rows["d_id"];
	$deposit_data[$d_id]["d_id_formatted"] = "D".str_pad($d_id,5,"0",STR_PAD_LEFT);;
	$deposit_data[$d_id]["deposit_date"] = $rows["deposit_date"];
	$deposit_data[$d_id]["deposit_date_day"] = date("d",strtotime($deposit_date));
	$deposit_data[$d_id]["deposit_date_month"] = date("m",strtotime($deposit_date));
	$deposit_data[$d_id]["deposit_date_year"] = date("Y",strtotime($deposit_date));
	$deposit_data[$d_id]["deposit_time"] = $rows["deposit_time"];
	$deposit_data[$d_id]["deposit_bank"] = $rows["deposit_bank"];
	$deposit_data[$d_id]["deposit_amount"] = $rows["deposit_amount"];
	$deposit_data[$d_id]["deposit_amount_formatted"] = number_format($rows["deposit_amount"], 2);
	$deposit_data[$d_id]["deposit_status"] = $rows["deposit_status"];
	$deposit_data[$d_id]["deposit_status_formatted"] = ucwords($rows["deposit_status"]);
	$deposit_data[$d_id]["pay_name"] = $rows["pay_name"];
	$deposit_data[$d_id]["deposit_date_strtotime"] = $rows["deposit_date_strtotime"];
	$deposit_data[$d_id]["deposit_time_formatted"] = date("h:iA", $rows["deposit_date_strtotime"]);
	
	if(file_exists(ROOT_PATH_BACKEND . "/images/banks/{$pay_name}.png"))
	{
		$deposit_data[$d_id]["image_exist"] = 1;
	}
	else
	{
		$deposit_data[$d_id]["image_exist"] = 0;
	}
	
	/*
	 * Search linked deposit
	 */
	
	$link_sql = mysqli_query($con, "SELECT d_id, deposit_status, deposit_user_id, user_name FROM SUPER_deposit AS d 
	LEFT JOIN NEW_users AS u ON u.user_id = d.deposit_user_id 
	WHERE deposit_uc_id = '$d_id'");
	
	while($link_rows = mysqli_fetch_array($link_sql))
	{
		$deposit_data[$d_id]["link_deposit"]["d_id"] = $link_rows["d_id"];
		$deposit_data[$d_id]["link_deposit"]["d_id_formatted"] = "D".str_pad($link_rows["d_id"],5,"0",STR_PAD_LEFT);
		$deposit_data[$d_id]["link_deposit"]["deposit_user_id"] = $link_rows["deposit_user_id"];
		$deposit_data[$d_id]["link_deposit"]["user_name"] = $link_rows["user_name"];
	}

}

/*
 * Generate arrays for days, months, hours and minutes
 */
 
$day_array = array();
$month_array = array();
$year_array = array();
$hour_array = array(); 
$min_array = array(); 
 
for($a=1; $a<=31; $a++)
{
	$a = str_pad($a, 2, '0', STR_PAD_LEFT);	
	$day_array[$a] = $a; 
}
 
for($a=1; $a<=12; $a++)
{
	$a = str_pad($a, 2, '0', STR_PAD_LEFT);
	$month_array[$a] = $a; 
}
 
for($a=date("Y"); $a>=date("Y")-1; $a--)
{
	$year_array[$a] = $a; 
}

for($a=1; $a<=23; $a++)
{
	$a = str_pad($a, 2, '0', STR_PAD_LEFT);
	$hour_array[$a] = $a; 
}

for($a=1; $a<=59; $a++)
{
	$a = str_pad($a, 2, '0', STR_PAD_LEFT);
	$minute_array[$a] = $a; 
}
 
$smarty->assign("get_from_date", $get_from_date);
$smarty->assign("get_from_date_day", date("d",strtotime($get_from_date)));
$smarty->assign("get_from_date_month", date("m",strtotime($get_from_date)));
$smarty->assign("get_from_date_year", date("Y",strtotime($get_from_date)));

$smarty->assign("get_from_date_minus_1_day", date("Y-m-d", strtotime("{$get_from_date} -1 day")));
$smarty->assign("get_from_date_plus_1_day", date("Y-m-d", strtotime("{$get_from_date} +1 day")));
$smarty->assign("get_from_date_minus_1_day", date("Y-m-d", strtotime("{$get_from_date} -1 day")));

$smarty->assign("get_deposit_bank", $get_deposit_bank);
$smarty->assign("get_deposit_amount", $get_deposit_amount);

$smarty->assign("current_hour", date("H", strtotime("now")));
$smarty->assign("current_minute", date("i", strtotime("now")));
$smarty->assign("date_today", date("Y-m-d", strtotime("now")));
$smarty->assign("date_yesterday", date("Y-m-d", strtotime("-1 day")));

$smarty->assign("day_array", $day_array);
$smarty->assign("month_array", $month_array);
$smarty->assign("year_array", $year_array);
$smarty->assign("hour_array", $hour_array);
$smarty->assign("minute_array", $minute_array);

$smarty->assign("status_array", $status_array);
$smarty->assign("payment_list", $payment_list);
$smarty->assign("deposit_data", $deposit_data);

$smarty->display("transactions/deposit_unclaimed.tpl");

include_once('../footer.php');

?>