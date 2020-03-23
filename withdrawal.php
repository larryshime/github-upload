<?php

include_once("../header.php");

if($_POST["action"]=="edit_withdrawal")
{	
	$d_id = mysqli_real_escape_string($con, $_POST["d_id"]);
	$deposit_bank = mysqli_real_escape_string($con, $_POST["deposit_bank"]);
	$deposit_day = mysqli_real_escape_string($con, $_POST["deposit_day"]);
	$deposit_month = mysqli_real_escape_string($con, $_POST["deposit_month"]);
	$deposit_year = mysqli_real_escape_string($con, $_POST["deposit_year"]);
	$deposit_hour = mysqli_real_escape_string($con, $_POST["deposit_hour"]);
	$deposit_min = mysqli_real_escape_string($con, $_POST["deposit_min"]);
	$deposit_am_pm = mysqli_real_escape_string($con, $_POST["deposit_am_pm"]);
	$deposit_sp_id = mysqli_real_escape_string($con, $_POST["deposit_sp_id"]);
	$deposit_ug_u = mysqli_real_escape_string($con, $_POST["deposit_ug_u"]);
	
	if($jobs_list["withdrawal_edit_job"]==1)
	{
		editWithdrawalDetail($d_id, $deposit_bank, $deposit_day, $deposit_month, $deposit_year, $deposit_hour, $deposit_min, $deposit_am_pm, $deposit_sp_id, $deposit_ug_u);
	}
}
else if($_POST["action"]=="update_withdrawal")
{
	$d_id = mysqli_real_escape_string($con, $_POST["d_id"]);
	$deposit_status = mysqli_real_escape_string($con, $_POST["deposit_status"]);
	$deposit_bank = mysqli_real_escape_string($con, $_POST["deposit_bank"]);
	$deposit_is_complete = mysqli_real_escape_string($con, $_POST["deposit_is_complete"]);
	
	if($jobs_list["withdrawal_edit_job"]==1)
	{
		updateWithdrawalDetail($d_id, $deposit_status, $deposit_is_complete, $deposit_sp_id);
	}
}

if($_GET["action"]=="display_withdrawal")
{
	$d_id = mysqli_real_escape_string($con, $_GET["id"]);
	
	$pn_status_array = array();
	$pn_status_array["pending"] = $_LANG["pending"];
	$pn_status_array["approved"] = $_LANG["approved"];
	$pn_status_array["rejected"] = $_LANG["rejected"];
	
	$withdrawal_data = array();
	
	$sql = mysqli_query($con, "SELECT shortcut_url_domain, shortcut_url_balance, shortcut_url_log_score, shortcut_url_log_game, shortcut_url_edit, d.deposit_ug_u, d.deposit_amount_promo, g.goods_url_admin, u.user_name2, u.bank_account_name, u.bank_account_number, d.deposit_reject_reason, goods_code, deposit_add_time, deposit_approve_time, deposit_reject_time, deposit_complete_time, e_a.employee_short_name AS employee_short_name_approve, e_r.employee_short_name AS employee_short_name_reject, e_c.employee_short_name AS employee_short_name_complete, d.deposit_add_employee_id, sp.sp_main_type, sp.sp_promo_amount_max, sp.sp_turnover_multiply_max, sp.sp_name, sp.sp_promo_type, sp.sp_promo_amount, sp.sp_restriction, sp.sp_message_after_topup, sp.sp_minimum_deposit, sp.sp_turnover_multiply, sp.sp_giveaway_max, sp.sp_usage, p2.pay_name AS pay_name2, ub_account_name, ub_account_number, d.deposit_is_complete, d.deposit_user_id, d.d_id, g.goods_name_long, u.bank_name, d.deposit_amount, d.deposit_amount_payback, d.deposit_bank, d.deposit_time, d.deposit_date_strtotime, d.deposit_add_time, d.deposit_status, g.goods_name_long, d.deposit_file, p.pay_code_backend, p.pay_name, e.employee_short_name, u.language, u.name, u.user_id, u.user_name, u.tel FROM SUPER_deposit AS d 
	LEFT JOIN NEW_goods AS g ON g.goods_id = d.deposit_goods_id 
	LEFT JOIN NEW_users AS u ON u.user_id = d.deposit_user_id 
	LEFT JOIN NEW_payment AS p ON p.pay_id = d.deposit_bank 
	LEFT JOIN NEW_users_banks AS ub ON ub.ub_id = d.deposit_user_bank 
	LEFT JOIN NEW_payment AS p2 ON p2.pay_id = ub.ub_bank  
	LEFT JOIN SUPER_employees AS e ON e.employee_id = d.deposit_add_employee_id 
	LEFT JOIN NEW_special_promotion AS sp ON sp.sp_id = d.deposit_sp_id 
	LEFT JOIN SUPER_employees AS e_a ON e_a.employee_id = d.deposit_approve_employee_id 
	LEFT JOIN SUPER_employees AS e_r ON e_r.employee_id = d.deposit_reject_employee_id 
	LEFT JOIN SUPER_employees AS e_c ON e_c.employee_id = d.deposit_complete_employee_id 
	WHERE d.deposit_main_type = 'withdrawal' AND d_id = '$d_id'");
	
	while($rows = mysqli_fetch_array($sql))
	{
		$d_id_ori = $rows["d_id"];
		$d_id = $rows["d_id"];
		$deposit_amount = $rows["deposit_amount"];
		$deposit_amount_promo = $rows["deposit_amount_promo"];
		$deposit_amount_tip = $rows["deposit_amount_tip"];
		$deposit_amount_payback = $rows["deposit_amount_payback"];
		$deposit_amount_void = $rows["deposit_amount_void"];
		$deposit_ug_u = $rows["deposit_ug_u"];
		$deposit_user_id = $rows["deposit_user_id"];
		$language = $rows["language"];
		$pay_name2 = $rows["pay_name2"];
		$bank_account_name = $rows["ub_account_name"];
		$bank_account_number = $rows["ub_account_number"];
		$user_name2 = $rows["user_name2"];
		
		$shortcut_url_domain = $rows["shortcut_url_domain"];
		$goods_url_admin = $rows["goods_url_admin"];
		$shortcut_url_edit=  $rows["shortcut_url_edit"];
		$shortcut_url_balance=  $rows["shortcut_url_balance"];
		$shortcut_url_log_game=  $rows["shortcut_url_log_game"];
		$shortcut_url_log_score=  $rows["shortcut_url_log_score"];
		
		/*
		 * Generate withdrawal point to deduct from kiosk
		 */
		
		$withdrawal_point = $deposit_amount+$deposit_amount_void;
		
		/*
		 * Generate kiosk url, bind with game username and point
		 */
		
		$shortcut_url_balance = str_replace("[*USERID*]","$deposit_ug_u",$shortcut_url_balance);
		
		if(preg_match('/\?/i', $shortcut_url_balance))
		{
			$shortcut_url_balance .= "&setscore=1&username={$deposit_ug_u}&score=-{$withdrawal_point}";
		}
		
		$shortcut_url_log_score = str_replace("[*USERID*]","$deposit_ug_u",$shortcut_url_log_score);
						
		if(preg_match('/\?/i', $shortcut_url_log_score))
		{
			$shortcut_url_log_score .= "&username={$deposit_ug_u}";
		}
		
		$shortcut_url_log_game = str_replace("[*USERID*]","$deposit_ug_u",$shortcut_url_log_game);
		
		if(preg_match('/\?/i', $shortcut_url_log_game))
		{
			$shortcut_url_log_game .= "&username={$deposit_ug_u}";
		}
		
		$shortcut_url_edit = str_replace("[*USERID*]","$deposit_ug_u",$shortcut_url_edit);
		
		/*
		 * Generate final amount
		 */
		 
		if($point_conversion>0)
		{
			$deposit_amount_final = -(($deposit_amount-$deposit_amount_tip+$promo_amount_ori-$deposit_amount_payback) * $point_conversion);
		}
		else
		{
			$deposit_amount_final = -($deposit_amount-$deposit_amount_tip+$promo_amount_ori-$deposit_amount_payback);
		}
				
		$withdrawal_data["d_id"] = $d_id;
		$withdrawal_data["d_id_formatted"] = "W" . str_pad($d_id,5,"0",STR_PAD_LEFT) . strtoupper($rows["pay_code_backend"]);;
		$withdrawal_data["goods_name_long"] = $rows["goods_name_long"];
		$withdrawal_data["goods_code"] = $rows["goods_code"];
		$withdrawal_data["bank_name"] = $rows["pay_name"];
		$withdrawal_data["deposit_amount_payback"] = $rows["deposit_amount_payback"];
		$withdrawal_data["deposit_amount"] = $rows["deposit_amount"];
		$withdrawal_data["deposit_amount_formatted"] = number_format($rows["deposit_amount"], 2);
		$withdrawal_data["deposit_amount_promo"] = $rows["deposit_amount_promo"];
		$withdrawal_data["deposit_amount_promo_formatted"] = number_format($rows["deposit_amount_promo"], 2);
		$withdrawal_data["deposit_amount_final"] = $deposit_amount_final;
		$withdrawal_data["deposit_amount_final_formatted"] = number_format($deposit_amount_final, 2);
		$withdrawal_data["deposit_point"] = -($withdrawal_point);
		$withdrawal_data["deposit_bank"] = $rows["deposit_bank"];
		$withdrawal_data["deposit_time"] = $rows["deposit_time"];
		$withdrawal_data["deposit_date_strtotime"] = $rows["deposit_date_strtotime"];
		$withdrawal_data["deposit_datetime_formatted"] = date("d-m-Y, h:iA",$rows["deposit_date_strtotime"]);
		$withdrawal_data["deposit_time_formatted"] = date("h:iA",$rows["deposit_date_strtotime"]);
		$withdrawal_data["deposit_add_time"] = $rows["deposit_add_time"];
		$withdrawal_data["deposit_status"] = $rows["deposit_status"];
		$withdrawal_data["deposit_add_employee_id"] = $rows["deposit_add_employee_id"];
		$withdrawal_data["deposit_reject_reason"] = $rows["deposit_reject_reason"];
		
		$withdrawal_data["deposit_ug_u"] = $rows["deposit_ug_u"];
		$withdrawal_data["goods_url_admin"] = $rows["goods_url_admin"];
		$withdrawal_data["shortcut_u"] = $rows["shortcut_u"];
		$withdrawal_data["shortcut_p"] = $rows["shortcut_p"];
		
		$withdrawal_data["shortcut_url_balance"] = "{$shortcut_url_domain}{$shortcut_url_balance}";
		$withdrawal_data["shortcut_url_log_score"] = "{$shortcut_url_domain}{$shortcut_url_log_score}";
		$withdrawal_data["shortcut_url_log_game"] = "{$shortcut_url_domain}{$shortcut_url_log_game}";
		$withdrawal_data["shortcut_url_edit"] = "{$shortcut_url_domain}{$shortcut_url_edit}";
		$withdrawal_data["goods_url_admin"] = "{$shortcut_url_domain}{$goods_url_admin}";
		
		$withdrawal_data["sp_name"] = $rows["sp_name"];
		$withdrawal_data["sp_main_type"] = $rows["sp_main_type"];
		$withdrawal_data["sp_promo_type"] = $rows["sp_promo_type"];
		$withdrawal_data["sp_promo_amount"] = $rows["sp_promo_amount"];
		$withdrawal_data["sp_promo_amount_max"] = $rows["sp_promo_amount_max"];
		$withdrawal_data["sp_minimum_deposit"] = $rows["sp_minimum_deposit"];
		$withdrawal_data["sp_turnover_multiply"] = $rows["sp_turnover_multiply"];
		$withdrawal_data["sp_turnover_multiply_max"] = $rows["sp_turnover_multiply_max"];
		$withdrawal_data["sp_giveaway_max"] = $rows["sp_giveaway_max"];
		$withdrawal_data["sp_usage"] = $rows["sp_usage"];
		$withdrawal_data["sp_message_after_topup"] = $rows["sp_message_after_topup"];
		$withdrawal_data["sp_restriction"] = $rows["sp_restriction"];
		
		$withdrawal_data["pay_name"] = $rows["pay_name"];
		$withdrawal_data["bank_account_name"] = $rows["bank_account_name"];
		$withdrawal_data["bank_account_number"] = $rows["bank_account_number"];
		
		$withdrawal_data["pay_code_backend"] = strtoupper($rows["pay_code_backend"]);
		
		$withdrawal_data["goods_name_long"] = $rows["goods_name_long"];
		$withdrawal_data["deposit_file"] = $rows["deposit_file"];
		$withdrawal_data["employee_short_name"] = $rows["employee_short_name"];
		$withdrawal_data["deposit_is_complete"] = $rows["deposit_is_complete"];
		$withdrawal_data["user_id"] = $rows["user_id"];
		$withdrawal_data["user_name"] = $rows["user_name"];
		$withdrawal_data["user_name2"] = $rows["user_name2"];
		$withdrawal_data["tel"] = $rows["tel"];
		$withdrawal_data["tel_hidden"] = starTel($tel);
		$withdrawal_data["name"] = $rows["name"];
		
		$withdrawal_data["pay_name2"] = $rows["pay_name2"];
		$withdrawal_data["ub_account_name"] = $rows["ub_account_name"];
		$withdrawal_data["ub_account_number"] = $rows["ub_account_number"];
		
		$withdrawal_data["deposit_add_time"] = $rows["deposit_add_time"];
		$withdrawal_data["deposit_add_time_formatted"] = date("d-m-Y, h:iA", $rows["deposit_add_time"]);
		$withdrawal_data["deposit_approve_time"] = $rows["deposit_approve_time"];
		$withdrawal_data["deposit_approve_time_formatted"] = date("d-m-Y, h:iA", $rows["deposit_approve_time"]);
		$withdrawal_data["deposit_reject_time"] = $rows["deposit_reject_time"];
		$withdrawal_data["deposit_reject_time_formatted"] = date("d-m-Y, h:iA", $rows["deposit_reject_time"]);
		$withdrawal_data["deposit_complete_time"] = $rows["deposit_complete_time"];
		$withdrawal_data["deposit_complete_time_formatted"] = date("d-m-Y, h:iA", $rows["deposit_complete_time"]);
		$withdrawal_data["employee_short_name_add"] = ucwords($rows["employee_short_name_add"]);
		$withdrawal_data["employee_short_name_approve"] = ucwords($rows["employee_short_name_approve"]);
		$withdrawal_data["employee_short_name_reject"] = ucwords($rows["employee_short_name_reject"]);
		$withdrawal_data["employee_short_name_complete"] = ucwords($rows["employee_short_name_complete"]);
		
		$withdrawal = new Deposit();
		
		$withdrawal_success_message = $withdrawal->getWithdrawalSuccessMessage($language, $deposit_amount, $deposit_bonus_amount, $deposit_amount_promo, $deposit_amount_tip, $deposit_amount_payback, $point_conversion, $pay_name2, $bank_account_name, $bank_account_number);
		
		$withdrawal_data["success_message"] = "$withdrawal_success_message";
	}
	
	/*
	 * Get details from database
	 */
	
	$payment_list = array();
	
	$sql = mysqli_query($con, "SELECT pay_id, pay_name FROM NEW_payment WHERE is_main_withdrawal = 1 AND enabled = 1");
	
	while($k_rows = mysqli_fetch_array($k_sql))
	{
		$pay_id = $k_rows["pay_id"];
		$pay_name = $k_rows["pay_name"];
		
		$payment_list[$pay_id] = $pay_name;
	}
	
	/*
	 * Get User Game List
	 */
	 
	$user = new User($deposit_user_id);
	$user_game_data = $user->getUserAllGameAccounts();
	
	$smarty->assign("row", $withdrawal_data);
	$smarty->assign("user_game_data", $user_game_data);
	$smarty->assign("payment_list", $payment_list);
	$smarty->assign("pn_status_array", $pn_status_array);

	$smarty->display("transactions/withdrawal_detail.tpl");
	
	exit;
}
else
{
	/*
	 * Retrieve GET values
	 */
	
	$post_d_id = mysqli_real_escape_string($con, $_GET["d_id"]);
	$post_user_id = mysqli_real_escape_string($con, $_GET["user_id"]);
	$post_user_name = mysqli_real_escape_string($con, $_GET["user_name"]);
	$post_name = mysqli_real_escape_string($con, $_GET["name"]);
	$post_remark = mysqli_real_escape_string($con, $_GET["remark"]);
	$post_type = mysqli_real_escape_string($con, $_GET["type"]);
	$post_from_date = mysqli_real_escape_string($con, $_GET["from_date"]);
	$post_to_date = mysqli_real_escape_string($con, $_GET["to_date"]);
	$post_deposit_bank = mysqli_real_escape_string($con, $_GET["deposit_bank"]);
	$post_deposit_goods_id = mysqli_real_escape_string($con, $_GET["deposit_goods_id"]);
	$post_deposit_sp_id = mysqli_real_escape_string($con, $_GET["deposit_sp_id"]);
	$post_deposit_add_employee_id = mysqli_real_escape_string($con, $_GET["deposit_add_employee_id"]);
	
	/*
	 * Format date
	 */
	
	if($post_from_date!="")
	{
		$post_from_date = date("Y-m-d", strtotime($post_from_date));
	}
	else
	{
		$post_from_date = date("Y-m-d");
	}
	
	if($post_to_date!="")
	{
		$post_to_date = date("Y-m-d", strtotime($post_to_date));
	}
	else
	{
		$post_to_date = date("Y-m-d");
	}
	
	/*
	 * Get details from database
	 */
	
	$payment = new Payment();
	$payment_list = $payment->getList();
	
	$game = new Game();
	$game_list = $game->getList();
	
	$promotion = new Promotion();
	$promotion_list = $promotion->getList();
	
	$employee = new Employee();
	$employee_list = $employee->getList();
	
	/*
	 * Check search is by day, week or month
	 */
	 
	$search_by_day = false;
	$search_by_week = false;
	$search_by_month = false;
	
	if($from_date==$to_date) // If it is search by day
	{
		$search_by_day = true;
	}
	else if(abs(strtotime($from_date) - strtotime($to_date))<=518400) // If it is search by week
	{
		$search_by_week = true;
	}
	else if(abs(strtotime($from_date) - strtotime($to_date))<=2678400) // If it is search by month
	{
		$search_by_month = true;
	}
	
	/*
	 * Set values for left and right navigation
	 */
	 
	if($search_by_week)
	{
		$left_from_date_button_value = date("Y-m-d", (strtotime($post_from_date) - (86400*7)));
		$left_to_date_button_value = date("Y-m-d", (strtotime($post_to_date) - (86400*7)));
		$right_from_date_button_value = date("Y-m-d", (strtotime($post_from_date) + (86400*7)));
		$right_to_date_button_value = date("Y-m-d", (strtotime($post_to_date) + (86400*7)));
	}
	else if($search_by_month)
	{
		$left_from_date_button_value = date("Y-m-", strtotime("$post_from_date last month")) . "01";
		$left_to_date_button_value = date("Y-m-", strtotime("$post_from_date last month")) . cal_days_in_month(CAL_GREGORIAN,date("m",strtotime($left_from_date_button_value)),date("Y",strtotime($left_from_date_button_value)));
		$right_from_date_button_value = date("Y-m-", strtotime("$post_from_date next month")) . "01";
		$right_to_date_button_value = date("Y-m-", strtotime("$post_from_date next month")) . cal_days_in_month(CAL_GREGORIAN,date("m",strtotime($right_from_date_button_value)),date("Y",strtotime($right_from_date_button_value)));
	}
	else
	{
		$left_from_date_button_value = date("Y-m-d", strtotime($post_from_date) - 86400);
		$left_to_date_button_value = date("Y-m-d", strtotime($post_from_date) - 86400);
		$right_from_date_button_value = date("Y-m-d", strtotime($post_from_date) + 86400);
		$right_to_date_button_value = date("Y-m-d", strtotime($post_from_date) + 86400);
	} 
	
	/*
	 * Get withdrawal list from database
	 */
	 
	$mysql_addon = "";
	
	if($post_d_id!="")
		$mysql_addon .= "w.d_id = '{$post_d_id}' AND ";
	
	if($post_user_id!="")
		$mysql_addon .= "u.user_id = '{$post_user_id}' AND ";
	
	if($post_username!="")
		$mysql_addon .= "u.user_name LIKE '%{$post_username}%' AND ";
	
	if($post_name!="")
		$mysql_addon .= "u.name LIKE '%{$post_name}%' AND ";
	
	if($post_deposit_goods_id!="")
		$mysql_addon .= "w.deposit_goods_id = '{$post_deposit_goods_id}' AND ";
	
	if($post_deposit_bank!="")
		$mysql_addon .= "w.deposit_bank = '{$post_deposit_bank}' AND ";
	
	if($post_deposit_sp_id!="")
		$mysql_addon .= "w.deposit_sp_id = '{$post_deposit_sp_id}' AND ";
	
	if($post_deposit_add_employee_id!="")
		$mysql_addon .= "w.deposit_add_employee_id = '{$post_deposit_add_employee_id}' AND ";
	
	if($post_remark!="")
		$mysql_addon .= "w.deposit_amount_void_explain LIKE '%{$post_remark}%' AND ";
	
	if($post_type=="void")
		$mysql_addon .= "w.deposit_amount_void <> 0 AND ";
	
	if($post_d_id=="" && $post_user_name=="" && $post_name=="" && $post_remark=="")
	{
		if($post_from_date!="")
			$mysql_addon .= "w.deposit_date_strtotime >= ". strtotime($post_from_date) . " AND ";
	
		if($post_to_date!="")
			$mysql_addon .= "w.deposit_date_strtotime < ". (strtotime($post_to_date) + 86400) . " AND ";
	}
	
	/*
	 * Create pagination
	 */
	
	$pagination_addon = "";

	if($post_d_id!="")
		$pagination_addon .= "d_id={$post_d_id}&";

	if($post_user_id!="")
		$pagination_addon .= "user_id={$post_user_id}&";

	if($user_name!="")
		$pagination_addon .= "user_name={$user_name}&";

	if($post_remark!="")
		$pagination_addon .= "remark={$post_remark}&";

	if($post_name!="")
		$pagination_addon .= "name={$post_name}&";

	if($post_deposit_goods_id!="")
		$pagination_addon .= "deposit_goods_id={$post_deposit_goods_id}&";

	if($post_deposit_bank!="")
		$pagination_addon .= "deposit_bank={$post_deposit_bank}&";

	if($deposit_sp_id!="")
		$pagination_addon .= "deposit_sp_id={$deposit_sp_id}&";

	if($deposit_add_employee_id!="")
		$pagination_addon .= "deposit_add_employee_id={$deposit_add_employee_id}&";

	if($post_from_date!="")
		$pagination_addon .= "from_date=".date("d-m-Y",strtotime($post_from_date)) . "&";

	if($post_to_date!="")
		$pagination_addon .= "to_date=".date("d-m-Y",strtotime($post_to_date)) . "&";
	
	if(isset($_GET["type"]) && $_GET["type"]=="void")
		$pagination_addon .= "type=void&";

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 25;
	$startpoint = ($page * $limit) - $limit;
	
	if($_POST["action"]=="search_deposit")
	{
		$deposit_date_from = $_POST["deposit_date_from"];
		$deposit_date_to = $_POST["deposit_date_to"];
		
		$pagination = pagination("SUPER_deposit AS w 
		LEFT JOIN NEW_users AS u ON u.user_id = w.deposit_user_id 
		WHERE $mysql_addon w.deposit_main_type = 'withdrawal' AND deposit_date_strtotime >= '" . $deposit_date_from . "' AND deposit_date_strtotime < '" . $deposit_date_to . "'",$limit,$page,"?$pagination_addon",1,WEBSITE_HOME_BACKEND . "/withdrawal/");
	}
	else
	{
		$pagination = pagination("SUPER_deposit AS w 
		LEFT JOIN NEW_users AS u ON u.user_id = w.deposit_user_id 
		WHERE $mysql_addon w.deposit_main_type = 'withdrawal'",$limit,$page,"?$pagination_addon",1,WEBSITE_HOME_BACKEND . "/withdrawal/");
	}
	
	if($_POST["action"]=="search_deposit")
	{
		$deposit_date_from = $_POST["deposit_date_from"];
		$deposit_date_to = $_POST["deposit_date_to"];
		
		$sql = mysqli_query($con, "SELECT u.total_dep_amount, u.total_wit_amount, g.goods_code, u.user_name2, w.deposit_amount_promo, w.deposit_reject_reason, w.deposit_amount_payback, w.deposit_add_employee_id, sp.sp_main_type, sp.sp_turnover_multiply, sp.sp_turnover_multiply_max, sp.sp_name, sp.sp_giveaway_min, sp.sp_giveaway_max, sp.sp_restriction, sp.sp_main_type, sp.sp_promo_type, sp_promo_amount, sp_promo_type, sp_promo_amount, w.deposit_sp_id, android_app_version, is_android, is_iphone, p2.pay_name as pay_name2, ub_account_name, ub_account_number, deposit_user_bank, g.shortcut_url_domain, g.shortcut_url_log_game, p.pay_code_backend, p.pay_name, w.deposit_ug_u, w.deposit_date_strtotime, w.d_id, w.deposit_user_id, w.deposit_amount, w.deposit_amount_tip, w.deposit_amount_void, w.deposit_amount_void_explain, w.deposit_status, w.deposit_user_id, u.user_name, g.goods_name_long, u.is_blacklist, e.employee_short_name AS add_e_name, e.employee_short_name FROM SUPER_deposit AS w 
		LEFT JOIN NEW_payment AS p ON p.pay_id = w.deposit_bank 
		LEFT JOIN NEW_goods AS g ON g.goods_id =  w.deposit_goods_id 
		LEFT JOIN NEW_users AS u ON u.user_id = w.deposit_user_id 
		LEFT JOIN SUPER_employees AS e ON e.employee_id = w.deposit_employee_id 
		LEFT JOIN NEW_users_banks AS ub ON ub.ub_id = w.deposit_user_bank 
		LEFT JOIN NEW_payment AS p2 ON p2.pay_id = ub.ub_bank 
		LEFT JOIN NEW_special_promotion AS sp ON sp.sp_id = w.deposit_sp_id 
		WHERE $mysql_addon w.deposit_main_type = 'withdrawal' AND deposit_date_strtotime >= '" . $deposit_date_from . "' AND deposit_date_strtotime < '" . $deposit_date_to . "' ORDER BY deposit_add_time DESC LIMIT $startpoint, $limit");
	}
	else
	{
		$sql = mysqli_query($con, "SELECT u.total_dep_amount, u.total_wit_amount, g.goods_code, u.user_name2, w.deposit_amount_promo, w.deposit_reject_reason, w.deposit_amount_payback, w.deposit_add_employee_id, sp.sp_main_type, sp.sp_turnover_multiply, sp.sp_turnover_multiply_max, sp.sp_name, sp.sp_giveaway_min, sp.sp_giveaway_max, sp.sp_restriction, sp.sp_main_type, sp.sp_promo_type, sp_promo_amount, sp_promo_type, sp_promo_amount, w.deposit_sp_id, android_app_version, is_android, is_iphone, p2.pay_name as pay_name2, ub_account_name, ub_account_number, deposit_user_bank, g.shortcut_url_log_game, g.shortcut_url_domain, g.shortcut_url_log_score, p.pay_code_backend, p.pay_name, w.deposit_ug_u, w.deposit_date_strtotime, w.d_id, w.deposit_user_id, w.deposit_amount, w.deposit_amount_tip, w.deposit_amount_void, w.deposit_amount_void_explain, w.deposit_status, w.deposit_user_id, u.user_name, g.goods_name_long, u.is_blacklist, e.employee_short_name AS add_e_name, e.employee_short_name FROM SUPER_deposit AS w 
		LEFT JOIN NEW_payment AS p ON p.pay_id = w.deposit_bank 
		LEFT JOIN NEW_goods AS g ON g.goods_id =  w.deposit_goods_id 
		LEFT JOIN NEW_users AS u ON u.user_id = w.deposit_user_id 
		LEFT JOIN SUPER_employees AS e ON e.employee_id = w.deposit_add_employee_id 
		LEFT JOIN NEW_users_banks AS ub ON ub.ub_id = w.deposit_user_bank 
		LEFT JOIN NEW_payment AS p2 ON p2.pay_id = ub.ub_bank  
		LEFT JOIN NEW_special_promotion AS sp ON sp.sp_id = w.deposit_sp_id 
		WHERE $mysql_addon w.deposit_main_type = 'withdrawal' ORDER BY deposit_date_strtotime DESC LIMIT $startpoint, $limit");
	}
	
	$withdrawal_data = array();
	
	$count = 1;
	
	while($rows = mysqli_fetch_array($sql))
	{
		$d_id = $rows["d_id"];
		$pay_name = $rows["pay_name"];
		$deposit_ug_u = $rows["deposit_ug_u"];
		$shortcut_url_domain = $rows["shortcut_url_domain"];
		$shortcut_url_log_score = $rows["shortcut_url_log_score"];
		$shortcut_url_log_game = $rows["shortcut_url_log_game"];
		
		if($shortcut_url_log_game!="")
		{
			$shortcut_url_log_game = str_replace("[*USERID*]","$deposit_ug_u",$shortcut_url_log_game);
		}
		
		if($shortcut_url_log_score!="")
		{
			$shortcut_url_log_score = str_replace("[*USERID*]","$deposit_ug_u",$shortcut_url_log_score);
			
			if(preg_match('/\?/i', $shortcut_url_log_score))
			{
				$shortcut_url_log_score .= "&username={$deposit_ug_u}";
			}
		}
			
		$shortcut_url_log_score = "{$shortcut_url_domain}{$shortcut_url_log_score}";
		$shortcut_url_log_game = "{$shortcut_url_domain}{$shortcut_url_log_game}";
		
		$withdrawal_data[$d_id]["count"] = $count;
		$withdrawal_data[$d_id]["d_id"] = $d_id;
		$withdrawal_data[$d_id]["d_id_formatted"] = "W" . str_pad($d_id,5,"0",STR_PAD_LEFT) . strtoupper($rows["pay_code_backend"]);
		$withdrawal_data[$d_id]["goods_code"] = $rows["goods_code"];
		$withdrawal_data[$d_id]["user_name2"] = $rows["user_name2"];
		$withdrawal_data[$d_id]["sp_promo_amount"] = $rows["sp_promo_amount"];
		$withdrawal_data[$d_id]["sp_promo_type"] = $rows["sp_promo_type"]; 
		$withdrawal_data[$d_id]["deposit_date_strtotime"] = $rows["deposit_date_strtotime"];
		$withdrawal_data[$d_id]["deposit_date_time_formatted"] = date("d-m-Y, h:iA", $rows["deposit_date_strtotime"]);
		$withdrawal_data[$d_id]["deposit_time_formatted"] = date("h:iA", $rows["deposit_date_strtotime"]);
		$withdrawal_data[$d_id]["deposit_add_employee_id"] = $rows["deposit_add_employee_id"];
		$withdrawal_data[$d_id]["deposit_ug_u"] = $rows["deposit_ug_u"];
		$withdrawal_data[$d_id]["deposit_reject_reason"] = $rows["deposit_reject_reason"];
		$withdrawal_data[$d_id]["deposit_id"] = $rows["deposit_id"];
		$withdrawal_data[$d_id]["deposit_user_id"] = $rows["deposit_user_id"];
		$withdrawal_data[$d_id]["deposit_sp_id"] = $rows["deposit_sp_id"];
		$withdrawal_data[$d_id]["deposit_amount_payback"] = $rows["deposit_amount_payback"];
		$withdrawal_data[$d_id]["deposit_amount"] = $rows["deposit_amount"];
		$withdrawal_data[$d_id]["deposit_amount_formatted"] = number_format($rows["deposit_amount"], 2);
		$withdrawal_data[$d_id]["deposit_amount_promo"] = $rows["deposit_amount_promo"];
		$withdrawal_data[$d_id]["deposit_amount_promo_formatted"] = $rows["deposit_amount_promo"];
		$withdrawal_data[$d_id]["deposit_amount_tip"] = $rows["deposit_amount_tip"];
		$withdrawal_data[$d_id]["deposit_amount_tip_formatted"] = number_format($rows["deposit_amount_tip"], 2);
		$withdrawal_data[$d_id]["deposit_amount_void"] = $rows["deposit_amount_void"];
		$withdrawal_data[$d_id]["deposit_amount_void_formatted"] = number_format($rows["deposit_amount_void"], 2);
		$withdrawal_data[$d_id]["deposit_amount_void_explain"] = $rows["deposit_amount_void_explain"];
		$withdrawal_data[$d_id]["deposit_amount_final"] = -($rows["deposit_amount"]-$rows["deposit_amount_tip"]+$rows["promo_amount_ori"]);
		$withdrawal_data[$d_id]["deposit_status"] = $rows["deposit_status"];
		$withdrawal_data[$d_id]["shortcut_url_domain"] = $rows["shortcut_url_domain"];
		$withdrawal_data[$d_id]["shortcut_url_log_game"] = $shortcut_url_log_game;
		$withdrawal_data[$d_id]["shortcut_url_log_score"] = $shortcut_url_log_score;
		$withdrawal_data[$d_id]["name"] = $rows["name"];
		$withdrawal_data[$d_id]["pay_name"] = $rows["pay_name"];
		$withdrawal_data[$d_id]["pay_code_backend"] = $rows["pay_code_backend"];
		$withdrawal_data[$d_id]["deposit_user_id"] = $rows["deposit_user_id"];
		$withdrawal_data[$d_id]["user_name"] = $rows["user_name"];
		$withdrawal_data[$d_id]["goods_name_long"] = $rows["goods_name_long"];
		$withdrawal_data[$d_id]["is_blacklist"] = $rows["is_blacklist"];
		$withdrawal_data[$d_id]["is_android"] = $rows["is_android"];
		$withdrawal_data[$d_id]["is_iphone"] = $rows["is_iphone"];
		$withdrawal_data[$d_id]["android_app_version"] = $rows["android_app_version"];
		$withdrawal_data[$d_id]["deposit_user_bank"] = $rows["deposit_user_bank"];
		$withdrawal_data[$d_id]["pay_name2"] = $rows["pay_name2"];
		$withdrawal_data[$d_id]["employee_short_name"] = $rows["employee_short_name"];
		$withdrawal_data[$d_id]["ub_account_name"] = $rows["ub_account_name"];
		$withdrawal_data[$d_id]["ub_account_number"] = $rows["ub_account_number"];
		
		$withdrawal_data[$d_id]["difference"] = number_format($rows["total_dep_amount"] - $rows["total_wit_amount"], 2);
		
		$withdrawal_data[$d_id]["sp_name"] = $rows["sp_name"];
		$withdrawal_data[$d_id]["sp_main_type"] = $rows["sp_main_type"];
		$withdrawal_data[$d_id]["sp_promo_type"] = $rows["sp_promo_type"];
		$withdrawal_data[$d_id]["sp_promo_amount"] = $rows["sp_promo_amount"];
		$withdrawal_data[$d_id]["sp_promo_amount_max"] = $rows["sp_promo_amount_max"];
		$withdrawal_data[$d_id]["sp_turnover_multiply"] = $rows["sp_turnover_multiply"];
		$withdrawal_data[$d_id]["sp_turnover_multiply_max"] = $rows["sp_turnover_multiply_max"];
		$withdrawal_data[$d_id]["sp_restriction"] = $rows["sp_restriction"];
		$withdrawal_data[$d_id]["sp_giveaway_min"] = $rows["sp_giveaway_min"];
		$withdrawal_data[$d_id]["sp_giveaway_max"] = $rows["sp_giveaway_max"];
		
		if(file_exists(ROOT_PATH_BACKEND . "/images/banks/{$pay_name}.png"))
		{
			$withdrawal_data[$d_id]["image_exist"] = 1;
		}
		else
		{
			$withdrawal_data[$d_id]["image_exist"] = 0;
		}
		
		$count++;
	}
	
	/*
	 * Assign variables in Smarty
	 */
	
	$smarty->assign("post_d_id", $post_d_id);
	$smarty->assign("post_user_id", $post_user_id);
	$smarty->assign("post_user_name", $post_user_name);
	$smarty->assign("post_name", $post_name);
	$smarty->assign("post_deposit_goods_id", $post_deposit_goods_id);
	$smarty->assign("post_deposit_sp_id", $post_deposit_sp_id);
	$smarty->assign("post_type", $post_type);
	$smarty->assign("post_remark", $post_remark);
	$smarty->assign("post_from_date", $post_from_date);
	$smarty->assign("post_to_date", $post_to_date);
	$smarty->assign("post_deposit_bank", $post_deposit_bank);
	$smarty->assign("post_deposit_goods_id", $post_deposit_goods_id);
	$smarty->assign("post_deposit_sp_id", $post_deposit_sp_id);
	$smarty->assign("post_deposit_add_employee_id", $post_deposit_add_employee_id);
	
	$smarty->assign("promotion_list", $promotion_list);
	$smarty->assign("payment_list", $payment_list);
	$smarty->assign("game_list", $game_list);
	$smarty->assign("employee_list", $employee_list);
	
	$smarty->assign("today_date_formatted", date("Y-m-d"));
	$smarty->assign("yesterday_date_formatted", date("Y-m-d", strtotime("-1 day")));
	
	$smarty->assign("this_week_from_date_formatted", date("Y-m-d", date("Y-m-d", strtotime("this week monday"))));
	$smarty->assign("this_week_to_date_formatted", date("Y-m-d", strtotime("this week sunday")));
	
	$smarty->assign("last_week_from_date_formatted", date("Y-m-d", strtotime("last week monday")));
	$smarty->assign("last_week_to_date_formatted", date("Y-m-d", strtotime("last week sunday")));
	
	$smarty->assign("this_month_from_date_formatted", date("Y-m-01"));
	$smarty->assign("this_month_to_date_formatted", date("Y-m-d",strtotime(date("Y-m-01", strtotime("+1 month")))-86400));
	
	$smarty->assign("last_month_from_date_formatted", date("Y-m-01", strtotime("-1 month")));
	$smarty->assign("last_month_to_date_formatted", date("Y-m-d",strtotime(date("Y-m-01"))-86400));
	
	$smarty->assign("left_from_date_button_value", $left_from_date_button_value);
	$smarty->assign("left_to_date_button_value", $left_to_date_button_value);
	$smarty->assign("right_from_date_button_value", $right_from_date_button_value);
	$smarty->assign("right_to_date_button_value", $right_to_date_button_value);
	
	$smarty->assign("pagination", $pagination);
	
	$smarty->assign("withdrawal_data", $withdrawal_data);
	
	$smarty->display("transactions/withdrawal.tpl");

}

include_once('../footer.php');

?>