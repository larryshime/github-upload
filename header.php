<?php

$start_super_time = microtime(true);

session_start();

/**
 * Include configuration file, classes and functions
 */

include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
include_once(ROOT_PATH_BACKEND . "classes/ActionLog.php");
include_once(ROOT_PATH_BACKEND . "classes/Cache.php");
include_once(ROOT_PATH_BACKEND . "classes/Game.php");
include_once(ROOT_PATH_BACKEND . "classes/Wallet.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_common.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_dashboard.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_base.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_pagination.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_transactions.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_manage.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_report.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_users.php");
include_once(ROOT_PATH_BACKEND . "functions/fn_games.php");
include_once(ROOT_PATH_BACKEND . "libraries/smarty/Smarty.class.php");
include_once(ROOT_PATH_BACKEND . "public/authorization.php");
include_once(ROOT_PATH_BACKEND . "public/version.php");

/**
 * Include languages
 */

include_once(ROOT_PATH_BACKEND . "languages/" . LANGUAGE . "/header.php");
include_once(ROOT_PATH_BACKEND . "languages/" . LANGUAGE . "/index.php");
include_once(ROOT_PATH_BACKEND . "languages/" . LANGUAGE . "/settings.php");
include_once(ROOT_PATH_BACKEND . "languages/" . LANGUAGE . "/game_logs.php");
include_once(ROOT_PATH_BACKEND . "languages/" . LANGUAGE . "/transactions.php");
include_once(ROOT_PATH_BACKEND . "languages/" . LANGUAGE . "/users.php");
include_once(ROOT_PATH_BACKEND . "languages/global.php");

/**
 * Include Smarty (Templating Engine)
 */

$smarty = new Smarty;
$smarty->caching = false;
$smarty->cache_lifetime = 0;
$smarty->setTemplateDir(ROOT_PATH_BACKEND . "templates/".$shop_config_array["template"]);
$smarty->assign("WEBSITE_HOME_BACKEND", WEBSITE_HOME_BACKEND);
$smarty->assign("LANG", $_LANG);

/*$smarty->assign("Name", "Fred Irving Johnathan Bradley Peppergill", true);
$smarty->assign("FirstName", array("John", "Mary", "James", "Henry"));
$smarty->assign("LastName", array("Doe", "Smith", "Johnson", "Case"));
$smarty->assign("option_selected", "NE");
$smarty->display('header.tpl');*/

/**
 * Detect user device type
 */

if(!isset($_SESSION["deviceType"]))
{
	require_once ROOT_PATH_BACKEND . 'libraries/mobile_detect/MobileDetect.php';
	
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	$is_ipad = $detect->isiPad();
	$is_iphone = $detect->isiPhone();

	$_SESSION["deviceType"] = $deviceType;
}

if($_POST["action"]=="edit_language")
{
	$employee_id = $_SESSION["employee_id"];
	$language = $_POST["language"];
	
	$_SESSION['language'] = $language;
	
	mysql_query_smart("UPDATE SUPER_employees SET is_master_slave = 0, language = '$language' WHERE employee_id = '$employee_id' LIMIT 1");
}
else if($_GET["action"]=="edit_language")
{
	$employee_id = $_SESSION["employee_id"];
	$language = $_GET["language"];
	
	$_SESSION['language'] = $language;
	
	mysql_query_smart("UPDATE SUPER_employees SET is_master_slave = 0, language = '$language' WHERE employee_id = '$employee_id' LIMIT 1");
	
	exit;
}

$cat_array = array();
$cat_array["slot"] = "Slots";
$cat_array["casino"] = "Slots";
$cat_array["sports"] = "Sportbooks";

$favicon_link = "";

$super_name = $shop_config_array["backend_name"];
$super_name_short = $shop_config_array["backend_name_short"];

$error = "";

$basename = str_replace(".php","",basename($_SERVER['REQUEST_URI']));
$basename = explode("?",$basename);
$basename = $basename[0];
	
if($logged)
{
	if(!$permission_check)
	{
		echo "
		<script data-cfasync='false' type='text/javascript'>
		window.location.reload();
		</script>
		";
	}
}

$employee_id = $_SESSION["employee_id"];

$employee_short_name = $_SESSION["employee_short_name"];
$commission_type = $_SESSION["commission_type"];
$commission_amount = $_SESSION["commission_amount"];
$timenow = strtotime("now");

/**
 * lite is to display content without header and footer, use together with fastSectionDisplay javascript function
 */	

if($_GET["lite"]!=1)
{

?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="utf8mb4">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $super_name?></title>
    <?php echo $favicon_link ?>
    <meta content='True' name='HandheldFriendly' />
    <meta name='robots' content="noindex" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo WEBSITE_HOME_BACKEND ?>/css/style.css?<?php echo $javascript_version; ?>">
    <link rel="stylesheet" href="<?php echo WEBSITE_HOME_BACKEND ?>bootstrap/css/bootstrap.min.css?<?php echo $javascript_version; ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo WEBSITE_HOME_BACKEND ?>dist/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo WEBSITE_HOME_BACKEND ?>dist/css/AdminLTE.min.css?<?php echo $javascript_version; ?>">
    <link rel="stylesheet" href="<?php echo WEBSITE_HOME_BACKEND ?>dist/css/skins/skin-blue.min.css?<?php echo $javascript_version; ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css?<?php echo $javascript_version; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo WEBSITE_HOME_BACKEND ?>dist/css/lightbox.css?<?php echo $javascript_version; ?>">
	<script>
		window.backOfficePath = window.location.protocol + "//" + window.location.hostname + "<?php echo WEBSITE_HOME_BACKEND; ?>";
		window.backOfficePath2 = "<?php echo WEBSITE_HOME_BACKEND_FOR_RELOAD_THIRD_PARTY_API; ?>";
	</script>
	<script data-cfasync='false' type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js?<?php echo $javascript_version; ?>"></script>
	<script data-cfasync='false' type='text/javascript' src="<?php echo WEBSITE_HOME_BACKEND ?>js/main.js?<?php echo $javascript_version; ?>"></script>
	<script data-cfasync='false' type='text/javascript' src="<?php echo WEBSITE_HOME_BACKEND ?>js/index.js?<?php echo $javascript_version; ?>"></script>
	<script data-cfasync='false' type='text/javascript' src="<?php echo WEBSITE_HOME_BACKEND ?>js/reports.js?<?php echo $javascript_version; ?>"></script>
	<script data-cfasync='false' type='text/javascript' src="<?php echo WEBSITE_HOME_BACKEND ?>js/transactions.js?<?php echo $javascript_version; ?>"></script>
	<script data-cfasync='false' type='text/javascript' src="<?php echo WEBSITE_HOME_BACKEND ?>js/users.js?<?php echo $javascript_version; ?>"></script>
	
	<script src='<?php echo WEBSITE_HOME_BACKEND ?>js/chart.js2/Chart.js?<?php echo $javascript_version; ?>'></script>
	
	<!-- include libraries(jQuery, bootstrap) -->
	<!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">-->
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> -->
	<!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> -->
	
	<!-- include summernote css/js -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
	<script data-cfasync='false' type='text/javascript' src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js?<?php echo $javascript_version; ?>"></script>
	<script data-cfasync='false' type='text/javascript' src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js?<?php echo $javascript_version; ?>"></script>

	<script>
    function scrollToId(the_id)
    {
	    $("html, body, #fast_section_display_content").animate({ scrollTop: $("#"+the_id).offset().top - 100 }, 500);
	}
	
	setInterval(function(){
		$("#fast_section_display").css("width",$(".content").css("width"));	
		$("#fast_section_display").css("height",($(window).height() - $(".navbar-static-top").css("height").replace("px","") + "px"));	
	}, 250);
	
	</script>

	<style>
	.main-header { height:50px; }	
		
	.filter_section { margin-bottom:12px; }	
		
	b, strong { font-weight:600 }	
		
	input:focus {
	    outline: none !important;
	    /*border:2px solid #e56e6e;*/
	    box-shadow: 0 0 3px red;
	}	
	
	button:focus {
	    outline: none !important;
	    /*border:1px solid #e56e6e;*/
	    box-shadow: 0 0 3px red;
	}		
	
	select:focus {
	    outline: none !important;
	    /*border:1px solid #e56e6e;*/
	    box-shadow: 0 0 3px red;
	}		
	
	textarea:focus {
	    outline: none !important;
	    /*border:1px solid #e56e6e;*/
	    box-shadow: 0 0 3px red;
	}	
	
	.table input { color:#333;}
	.table-inner {}
	.table-inner th { background:white !important; color:#333 !important; font-weight:600;}
	.table-inner td { background:white !important; color:#333 !important; }
	h3 { font-weight: 600 }
		
	.rejected {opacity:0.5}
	.main-header {z-index:999999}
	.main-sidebar {z-index:99999}
	.box-header{padding-bottom:0 !important}
	
	<?php
	
	if($shop_config_array["is_frontend"]==1)
	{
		echo "
		.main-header .logo {background:#000 !important}
		.main-header .navbar {background:#222 !important}
		.main-header .user-header {background:#222 !important}
		.main-header-border {border:2px solid #222 !important}
		.box.box-primary {border-top-color: #222 !important}
		.sidebar-menu>li.active>a { border-left-color: #000 !important }
		.sidebar-menu>li:hover>a { border-left-color: #000 !important }
		#sidebar-toggle {background:#222 !important}
		#sidebar-toggle:hover {background:#222 !important}";
	}
	else if($shop_config_array["is_backup"]==1)
	{
		echo "
		.main-header .logo {background:#555299 !important}
		.main-header .navbar {background:#605ca8 !important}
		.main-header .user-header {background:#605ca8 !important}
		.main-header-border {border:2px solid #605ca8 !important}
		.box.box-primary {border-top-color: #605ca8 !important}
		.sidebar-menu>li.active>a { border-left-color: #605ca8 !important }
		.sidebar-menu>li:hover>a { border-left-color: #605ca8 !important }
		#sidebar-toggle {background:#605ca8 !important}
		#sidebar-toggle:hover {background:#555299 !important}";
	}
	else if($shop_config_array["is_backend"]==1)
	{
		echo "
		.main-header-border {border:2px solid #3c8dbc !important}";
	}
	
	echo "
	table.table th {background: #EEE; color:#333; font-weight:600;}
	.table-hover>tbody>tr:hover { background:#f5f5f5; opacity:0.85}
	.table-hover-td td:hover { background:#f5f5f5; opacity:0.85}
	.center {text-align:center}
	";
		
	?>	
		
	.td_infekted_active {opacity:1}
	.bank-table td{font-size:14px; padding:1px !important;}
	.set_score_success {background: #00a65a !important; color:white; !important;}
	</style>
	
	<style>
	@media screen and (max-width: 767px) {
	#fast_section_display {left:0 !important; width:100% !important; z-index:9999 !important;}
	}
		
	.noselect {
	-webkit-touch-callout: none; /* iOS Safari */
	-webkit-user-select: none; /* Safari */
	-khtml-user-select: none; /* Konqueror HTML */
	-moz-user-select: none; /* Firefox */
	-ms-user-select: none; /* Internet Explorer/Edge */
	user-select: none; /* Non-prefixed version, currently
	                      supported by Chrome and Opera */
	}
		
	.table {margin-bottom:0;}	
	
	.blink_me_once {
	  animation: blinker 0.8s linear;
	  animation-iteration-count: 3;
	}
		
	.blink_me {
	  animation: blinker 0.8s linear infinite;
	}
	
	.pagination{margin-top:0; margin-bottom:0;}
	.pagination .current{background:#ddd}
	
	.noselect {
		-webkit-user-select: none; /* Chrome/Safari */        
		-moz-user-select: none; /* Firefox */
		-ms-user-select: none; /* IE10+ */
		
		/* Rules below not implemented in browsers yet */
		-o-user-select: none;
		user-select: none;
	}
	
	@keyframes blinker {  
	  50% { opacity: 0.5; }
	}
	</style>
  </head>
  
<?php

if($logged==0)
{
	echo "
	
	<body id='body' onLoad='document.getElementById(\"username\").focus();'>";
	echo "
	
	<div style='top: 25%;left: 50%;position: fixed;height: 250px;width: 450px;margin-left: -225px;'>
	<center>
	
	<br><br>
		
	<form action='' method='post'>
	
	<table style='border:1px solid white;'>
	<tr><td style='border:1px solid white; padding:4px;'><input style='border:1px solid #CCC; padding:8px; width:168px;' name='username' id='input_username' type='password' required placeholder='Username'></td></tr>
	<tr><td style='border:1px solid white; padding:4px;'><input style='border:1px solid #CCC; padding:8px; width:168px;' name='password' type='password' id='input_password' required placeholder='Password'></td></tr>";
	
	if($shop_config_array["enable_verification_code"]==1)
	{
		echo "
		<tr style='opacity:1;'><td style='border:1px solid white; padding:4px;'><input style='border:1px solid #CCC; padding:8px; width:168px;' name='verification_code' type='' id='input_verification_code' class='captcha_code_input' placeholder='Verification Code'></td></tr>
		<tr style='opacity:1;'>
			<td style='border:1px solid white; padding:4px;'><img class='captcha_img' src='" . WEBSITE_HOME_BACKEND . "images/captcha.php' style='width:168px;'></td>
		</tr>";
	}
	
	echo "
	<tr><td style='border:1px solid white; padding:4px; padding-top:3px;'><input style='width:100%; font-size:12px; border:0; background-color:#333; color:white; padding:14px; border-radius:0;border-radius:0;-webkit-appearance: button;' type='submit' value='Login'><br><br><font color='red'>$error</font></td></tr>
	</table>
	
	</form>
	</center>
	</div>
	
	";
	
	exit;
}
	
	$filename = basename($_SERVER["SCRIPT_FILENAME"]);
	
	$is_sidebar_collapse = false;
	$body_extra_class = "";
	
	$is_sidebar_collapse = true;
	
	?>
  
  <body class="skin-blue fixed sidebar-mini sidebar-mini-expand-feature  <?php echo $body_extra_class; ?>">  

<?php
	
if(isset($_GET["success"]) && $_GET["success"]==1)
{
	alertMessage("success", $_GET["message"], $_GET["timer_manual"], $_GET["special_request"], $_GET["special_request_val1"], $_GET["special_request_val2"]);
}
else if(isset($_GET["error"]) && $_GET["error"]==1)
{
	alertMessage("error", $_GET["message"]);
}

?>
  
    <div class="wrapper" style="height: auto; min-height: 100%;">

      <!-- Main Header -->
      <header class="main-header">

	  <?php
	
	  if($_SESSION["deviceType"]!="phone")
	  {
		 echo '
        <!-- Logo -->
	        <a href="javascript:void()" class="logo" onclick="fastRefreshDashboard()">
	          <!-- mini logo for sidebar mini 50x50 pixels -->
	          <span class="logo-mini" style="text-shadow: 0 0 10px white;"><b style="font-weight:200;">' . $super_name_short . '</b></span>
	          <!-- logo for regular state and mobile devices -->
	          <span class="logo-lg" style="letter-spacing:3px; text-shadow: 0 0 10px white;">' . $super_name . '</span>
	        </a>'; 
	  }
		  
	  ?>
	  
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation" style="height:30px;">
	      <a href="javascript:void();" id='sidebar-toggle' class="sidebar-toggle" data-toggle="offcanvas" role="button" style='<?php if($deviceType=="computer"){ echo "display:none;"; } ?>'>
            <span class="sr-only">Toggle navigation</span>
          </a>
          
          <div id='fast_header_bob' style='position:relative; margin-top:11px; float:left; margin-left:15px; <?php if($_SESSION["deviceType"]!="computer"){ echo "display:none;"; } ?>'>
	          
	      
		  <?php
			  
		  if($jobs_list["user_add_fast_bank_detail_job"]==1)
		  {
			  echo "
		          <select id='fast_create_type' style='width:108px; float:left; margin-left:5px; display:none;'>
		          ";
		          
		          if($shop_config_array["language"]=="thai")
		          {
			          echo "
			          <option value='tel'>" . $_LANG["tel_num2"] . "</option>
			          <option value='line' selected>" . $_LANG["line"] . "</option>";
			      }
			      else
			      {
				      echo "
				      <option value='tel'>" . $_LANG["tel_num2"] . "</option>
			          <option value='wechat'>" . $_LANG["wechat"] . "</option>
			          <option value='telegram'>" . $_LANG["telegram"] . "</option>
			          <option value='line'>" . $_LANG["line"] . "</option>";
		          }
		          
		          echo "
		          </select>";
			  
			  echo "
			  <div class='main-header-border' id='fast_add_bank_detail' style='display:none;position: absolute; top: 37px; background: white; left: -15px; padding: 13px;'>";
			  
			  echo "
			  <table class='table table-bordered' style='background:white; width:450px;'>
				<tr>
					<td style='font-size:12px;'>" . $_LANG["tel_num"] . "</td>
					<td><input style='float:left; width:100%; font-size:12px;' id='fast_create_tel2' onblur='removeWeirdChar(\"fast_create_tel2\");reformatTel(\"fast_create_tel2\"); numberOnly(\"fast_create_tel2\");' onkeyup='removeWeirdChar(\"fast_create_tel2\");reformatTel(\"fast_create_tel2\"); numberOnly(\"fast_create_tel2\");' onclick='this.select(); document.execCommand(\"paste\");'></td>
				</tr>
				<tr>
					<td style='width:108px;font-size:12px; height:35px; '>" . $_LANG["bank_name"] . "</td>
					<td>
					";
					
					$ddd_select = "";
					$ddd_select_img = array();
					
					$m_sql = mysqli_query($con, "SELECT pay_id, pay_name, is_main FROM NEW_payment WHERE enabled = '1' ORDER BY pay_order ASC");
					
					while($m_rows = mysqli_fetch_array($m_sql))
					{
						$tmp_pay_id = $m_rows["pay_id"];
						$tmp_pay_name = $m_rows["pay_name"];
						$tmp_is_main = $m_rows["is_main"];
						
						$pay_name_array[$tmp_pay_id] = $tmp_pay_name;
					}
					
					
					foreach($pay_name_array as $pay_id => $pay_name)
					{
						if($bank_name==$pay_id)
							$ddd_select .= "<option value='$pay_id'>$pay_name</option>";				
						else
							$ddd_select .= "<option value='$pay_id'>$pay_name</option>";
							
						$ddd_select_img[$pay_id] = $pay_name;
					}
					
					echo "<div id='add_user_select_bank_big_img'></div>";
					
					foreach($ddd_select_img as $tmp_pay_id => $tmp_pay_name)
					{
						if(file_exists(ROOT_PATH_BACKEND."/images/banks/$tmp_pay_name.png"))
						{
							echo "<div style='float:left;'><img src='" . WEBSITE_HOME_BACKEND . "images/banks/$tmp_pay_name.png' onclick='
							
							if($(this).hasClass(\"add_deposit_bank_selected\"))
							{
								$(this).removeClass(\"add_deposit_bank_selected\"); 						
								$(\"#add_user_bank_name\").val(\"\"); 
							}
							else
							{
								$(\".add_user_deposit_bank_select\").removeClass(\"add_deposit_bank_selected\");
								$(this).addClass(\"add_deposit_bank_selected\");
								$(\"#add_user_bank_name\").val($tmp_pay_id);
							}
							
							/*displayBankImage(\"deposit\");*/
							
							' class='add_user_deposit_bank_select' style='opacity:0.3; cursor:pointer;border:2px solid transparent; width:110px; height:33px; padding:4px;'></div>";
						}
						else
						{
							echo "<div style='float:left;'><div onclick='
							
							if($(this).hasClass(\"add_deposit_bank_selected\"))
							{
								$(this).removeClass(\"add_deposit_bank_selected\"); 						
								$(\"#add_user_bank_name\").val(\"\"); 
							}
							else
							{
								$(\".add_user_deposit_bank_select\").removeClass(\"add_deposit_bank_selected\");
								$(this).addClass(\"add_deposit_bank_selected\");
								$(\"#add_user_bank_name\").val($tmp_pay_id);
							}
							
							/*displayBankImage(\"deposit\");*/
							
							' class='add_user_deposit_bank_select' style='opacity:0.3; cursor:pointer;border:2px solid transparent; width:110px; height:33px; text-align:center; padding:2px 4px;'>$tmp_pay_name</div>";
						}
					}
					
					echo "
					<!--<select id='fast_add_user_bank_name' style='font-size:12px; width:100%;' onchange='$(\"#add_user_bank_name\").val($(this).val())' required>
					<option value=''>-</option>
					$ddd_select
					</select>-->
					</td>
				</tr>
				<tr>
					<td style='font-size:12px; height:35px'>" . $_LANG["account_name"] . "</td>
					<td><input id='fast_add_user_bank_account_name' required onclick='this.select()' value='' style='width:100%;font-size:12px;' onkeyup='$(\"#add_user_bank_account_name\").val($(this).val())' onblur='$(\"#add_user_bank_account_name\").val($(this).val()); charOnly(\"fast_add_user_bank_account_name\");' onkeyup='$(\"#add_user_bank_account_name\").val($(this).val()); charOnly(\"fast_add_user_bank_account_name\");'></td>
				</tr>
				<tr>
					<td style='font-size:12px; height:35px'>" . $_LANG["account_number"] . "</td>
					<td><input required id='fast_add_user_bank_account_number' value='' onblur='removeWeirdChar(\"fast_add_user_bank_account_number_$user_id\"); $(\"#add_user_bank_account_number\").val($(this).val()); numberOnly(\"fast_add_user_bank_account_number_$user_id\");' onclick='this.select()' style='width:100%;font-size:12px;' onkeyup='$(\"#add_user_bank_account_number\").val($(this).val()); numberOnly(\"fast_add_user_bank_account_number\");'> <span id='check_duplicate_bank_account_number_fast_add_result'></span></td>
				</tr>
				<tr>
					<td></td>
					<td>";
					
				echo "
		          
		          <div style='text-align:center; width:100%; font-size: 12px; padding: 5px 18px; /* max-width: 128px; */ /* float: left; */ background: #4267B2 !important; color: white; border: 0; margin-left: 0; cursor: pointer; border-radius: 3px; float: left; font-weight:bold;' type='button' id='the_fast_create_account_button' value='" . $_LANG["fast_create_account"] . "' onclick='$(\"#add_user_tel\").val($(\"#fast_create_tel2\").val());  $(\"#add_name\").val(\"\");  $(\"#add_user_wechat\").val(\"\"); 
		          
		          if($(\"#fast_create_type\").val()==\"tel\")
		          {
			          /*$(\"#add_user_tel\").val($(\"#fast_create_tel\").val());*/
			      }
			      else if($(\"#fast_create_type\").val()==\"wechat\")
		          {
			          $(\"#add_user_wechat\").val($(\"#fast_create_tel\").val());
			      }
			      else if($(\"#fast_create_type\").val()==\"telegram\")
		          {
			          $(\"#add_user_telegram\").val($(\"#fast_create_tel\").val());
			      }
		          
		          if($(\"#fast_add_user_bank_account_name\").val()==\"\" && $(\"#fast_add_user_bank_account_number\").val()==\"\" && $(\"#add_user_bank_name\").val()==\"\")
		          {
			      
			      }
			      else if($(\"#fast_add_user_bank_account_name\").val()!=\"\" && $(\"#fast_add_user_bank_account_number\").val()!=\"\" && $(\"#add_user_bank_name\").val()!=\"\")
		          {
			      
			      }
			      else    
		          {
			          alert(\"" . $_LANG["select_bank"] . "\");
			          
			          return;
			      }
		          
		          if($(\"#fast_create_tel2\").val()==\"\")
		          {
			          alert(\"" . $_LANG["select_tel"] . "\");
			          
			          return;
			      }
			      
			       $(\"#button_user_add\").click();   
		           $(\"#add_user_submit\").click();
		          
		           
		           $(\"#close_reload_frame_button\").click();'>" . $_LANG["register"] . " <img src='" . WEBSITE_HOME_BACKEND . "images/bolt.png' style='width: 10px;margin-right: -2px;margin-left: 2px;'></div> <span class='add_user_tel_verification_result'></span>
		          ";	
					
				echo "</td>
				</tr>	
				</table>   
		      </div>";
			 
          
          }
           	  
			  ?> 
	          
	          <?php
		          
		      $is_front_end = false;
			      
		      if(isset($shop_config_array["is_frontend"]) && $shop_config_array["is_frontend"]==1)
		      	$is_front_end = true;
		      
		      if(isset($_SESSION["employee_username"]) && $_SESSION["employee_username"]!="beachman" && !$is_front_end && $jobs_list["user_add_job"]==1)
		      {   
			      $referral_array = array();
			      		      
			      $m_sql = mysqli_query($con, "SELECT r_id, r_name, r_code FROM NEW_referrals WHERE r_active = '1' ORDER BY r_order ASC");
			      
			      	$m_count = 0;
			      	$m_last_count = 0;
			      
					while($m_rows = mysqli_fetch_array($m_sql))
					{
					  $r_id = $m_rows["r_id"];
					  $r_name = $m_rows["r_name"];
					  $r_code = $m_rows["r_code"];
					  
					  $referral_array[$r_id]["r_name"] = $r_name;
					  $referral_array[$r_id]["r_code"] = $r_code;
					  $referral_array[$r_id]["r_count"] = $m_count;
					  
					  $m_last_count = $m_count;
					  
					  $m_count++;
					}
					
					echo "<div style='float:left; background:white; border-radius:3px; margin-right:5px;'>";
					
					foreach($referral_array as $tmp_r_id => $tmp_vv)
					{
						if($tmp_vv["r_code"])
						$tmp_r_code = strtolower($tmp_vv["r_code"]);
						
						if(file_exists(ROOT_PATH_BACKEND."/images/ic_{$tmp_r_code}.png"))
						{
							if($m_last_count!=$tmp_vv["r_count"])
								echo "<div class='ic_ic_ic_icon_parent' style='width:33px; padding:3px 6px; float:left; border-right:1px solid #DDD; cursor:pointer;' onclick='/*$(\".ic_ic_ic_icon_parent\").removeClass(\"add_deposit_bank_selected\");  $(this).addClass(\"add_deposit_bank_selected\");*/ $(\"#fast_create_referral\").val(\"$tmp_r_id\"); $(\".ic_ic_ic_icon\").css(\"opacity\",\"0.3\"); $(\"#ic_ic_{$tmp_r_id}\").css(\"opacity\",\"1\"); $(\"#add_register_referral_id\").val($(\"#fast_create_referral\").val()); $(\"#fast_add_bank_detail\").fadeIn(0);'>";
							else
								echo "<div class='ic_ic_ic_icon_parent' style='width:33px; padding:3px 6px; float:left;cursor:pointer;' onclick='/*$(\".ic_ic_ic_icon_parent\").removeClass(\"add_deposit_bank_selected\");  $(this).addClass(\"add_deposit_bank_selected\");*/ $(\"#fast_create_referral\").val(\"$tmp_r_id\"); $(\".ic_ic_ic_icon\").css(\"opacity\",\"0.3\"); $(\"#ic_ic_{$tmp_r_id}\").css(\"opacity\",\"1\");  $(\"#add_register_referral_id\").val($(\"#fast_create_referral\").val()); $(\"#fast_add_bank_detail\").fadeIn(0);'>";
							
							echo "<img id='ic_ic_{$tmp_r_id}' class='ic_ic_ic_icon' title='$tmp_vv[r_name]' src='" . WEBSITE_HOME_BACKEND . "images/ic_{$tmp_r_code}.png' style='width:100%; opacity:0.30;' onclick=''></div>";
						}
						else
							echo "<span id='ic_ic_{$tmp_r_id}' class='ic_ic_ic_icon' title='$tmp_vv[r_name]' style=' opacity:0.30;' onclick=''>$tmp_r_code</span>";
					}
			      
			      echo "</div>";
				
				if($jobs_list["user_add_fast_bank_detail_job"]!=1)
				{
					echo "    
			      <input style='float:left;' placeholder='" . $_LANG["tel_num"] . "' id='fast_create_tel' onblur='removeWeirdChar(\"fast_create_tel\");reformatTel(\"fast_create_tel\");' onclick='this.select(); document.execCommand(\"paste\");'>
			      ";
			      
			      echo "
		          <select id='fast_create_type' style='width:108px; float:left; margin-left:5px;'>
		          ";
		          
		          if($shop_config_array["language"]=="thai")
		          {
			          echo "
			          <option value='tel'>" . $_LANG["tel_num2"] . "</option>
			          <option value='line' selected>" . $_LANG["line"] . "</option>";
			      }
			      else
			      {
				      echo "
				      <option value='tel'>" . $_LANG["tel_num2"] . "</option>
			          <option value='wechat'>" . $_LANG["wechat"] . "</option>
			          <option value='telegram'>" . $_LANG["telegram"] . "</option>
			          <option value='line'>" . $_LANG["line"] . "</option>";
		          }
		          
		          echo "
		          </select>";
		          
		          echo "
		          
		          <div style='font-size: 12px; padding: 5px 18px; /* max-width: 128px; */ /* float: left; */ background: #4267B2 !important; color: white; border: 0; margin-left: 5px; cursor: pointer; border-radius: 3px; float: left; font-weight:bold;' type='button' id='the_fast_create_account_button' value='" . $_LANG["fast_create_account"] . "' onclick='$(\"#button_user_add\").click(); $(\"#add_name\").val(\"\"); $(\"#add_user_tel\").val(\"\"); $(\"#add_user_wechat\").val(\"\"); 
		          
		          if($(\"#fast_create_type\").val()==\"tel\")
		          {
			          $(\"#add_user_tel\").val($(\"#fast_create_tel\").val());
			      }
			      else if($(\"#fast_create_type\").val()==\"wechat\")
		          {
			          $(\"#add_user_wechat\").val($(\"#fast_create_tel\").val());
			      }
			      else if($(\"#fast_create_type\").val()==\"telegram\")
		          {
			          $(\"#add_user_telegram\").val($(\"#fast_create_tel\").val());
			      }
		          
		           $(\"#add_user_submit\").click(); $(\"#close_reload_frame_button\").click();'>" . $_LANG["register"] . " <img src='" . WEBSITE_HOME_BACKEND . "images/bolt.png' style='width: 10px;margin-right: -2px;margin-left: 2px;'></div> <span class='add_user_tel_verification_result'></span>
		          ";
			    }
			      
			      	echo "   
					<select id='fast_create_referral' style='display:none; float:left; margin-left:5px;' onchange='$(\"#add_register_referral_id\").val($(this).val())'>
					<option value=''>" . $_LANG["referral"] . "</option>
					";
					
					foreach($referral_array as $tmp_r_id => $tmp_vv)
					{
						echo "<option value='$tmp_r_id'>$tmp_vv[r_name]</option>";
					}
					
					echo "</select>";
					
			      
		          
		          
	          }
          
          ?>
          
          </div>
          
          <div style='float:left;'>
          <ul class="nav navbar-nav">
	          
	          <?php
		      
		      if($deviceType=="phone")
		      {
			      echo '<li><div class="logo-lg" style="letter-spacing:3px; text-shadow: 0 0 10px white; margin-top: 7px; margin-left: 28px; color: white;">' . $super_name . '</div></li>';
		      }
		         
		      ?>
		      
          </ul>
          </div>
          
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
	            
              <?php
	            
	          if($shop_config_array["system_language_available"])
	          {
		          $language_available = $shop_config_array["system_language_available"];
		          $language_available = explode(",",$language_available);
		          
		          if(count($language_available)>1)
		          {
			          foreach($language_available as $lang)
			          {
				          $lang = str_replace(" ","",$lang);
				          
				          if($_SESSION["language"]!=$lang)
					      {
						        echo "<li class='dropdown ship-now-menu'><img src='" . WEBSITE_HOME_BACKEND . "images/icon_{$lang}.png' style='width:25px; margin-right:12px; margin-top:12px;cursor:pointer;' onclick='changeLanguage(\"$lang\")'></li>";
						  }
			          }
		          }
	          }
              
              ?>
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!--<img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
                  <span class="hidden-xs"><?php echo $_SESSION['employee_name']; ?></span>
                  
                  <?php
	              
	              if($deviceType=="phone")
	              {
		              echo '<i class="fa fa-user"></i>';
	              }
	                  
	              ?>
                </a>
                <ul class="dropdown-menu" style="padding-top:0; border-top: 1px solid #FFF;">
                  <!-- User image -->
                  <li class="user-header" style="min-height:175px; height:auto; display:none;";>
                    <!--<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                      
                      
	                  
	                  <p>
                      <?php //echo $_SESSION['employee_name'] . " (ID: " . $_SESSION['employee_id'] .  ")"; ?>
                      <small></small>
                    </p>
                  </li>
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="javascript:void()" onclick="fastSectionDisplay('<?php echo WEBSITE_HOME_BACKEND ?>/profile/?lite=1',0)" class="btn btn-default btn-flat"><?php echo $_LANG["change_password"]; ?></a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo WEBSITE_HOME_BACKEND ?>logout/" class="btn btn-default btn-flat"><?php echo $_LANG["logout"]; ?></a>
                    </div>
                  </li>
                </ul>
                
              </li>
            </ul>
          </div>
	      
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div style='height:80px; padding:10px;'>
          <div class="user-panel sidebar-form"  style="height:70px; border:none;">
            <div class="pull-left">
              <!--<img src="img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
              
             
              
            </div>
            <div class="pull-left info">
              <p><?php echo $_SESSION['employee_name']; ?></p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $_LANG["online"] ?></a>
            </div>
          </div>
          </div>
          
          <!-- Sidebar Menu -->
          <ul class="sidebar-menu tree" data-widget="tree">
            <li class=" treeview">
              <a href="javascript:void()" onclick="fastRefreshDashboard()">
                <i class="fa fa-dashboard"></i> <span><b><?php echo $_LANG["dashboard"] ?></b></span></i>
              </a>
            </li>
            
            
                       
            <?php
	            
	        $menu_transactions_active = "";   
	        $menu_users_active = "";
	        $menu_apps_active = "";
	        $menu_settings_active = "";
	        $menu_balance_active = "active menu-open";
	            
	        $current_url = $_SERVER['REQUEST_URI'];
	        
	        if(
	        (preg_match('/manage-banks-blacklist/i', $current_url) || 
	        (preg_match('/user/i', $current_url)) && $_GET["lite"]!=1))
	        {
		        $menu_users_active = "active menu-open";
		        $menu_balance_active = "";
	        }
	        else if((preg_match('/manage-settings-android/i', $current_url) || 
	        preg_match('/manage-promotions-daily-login/i', $current_url) || 
	        preg_match('/manage-broadcast-android/i', $current_url) || 
	        preg_match('/manage-news-feed/i', $current_url) || 
	        preg_match('/manage-customer-service/i', $current_url) || 
	        preg_match('/manage-customer-service-android/i', $current_url) || 
	        preg_match('/manage-customer-service-ios/i', $current_url)
	        ) && $_GET["lite"]!=1)
	        {
		        $menu_apps_active = "active menu-open";
		        $menu_balance_active = "";
	        }
	        else if(preg_match('/manage-/i', $current_url) && $_GET["lite"]!=1)
	        {
		        $menu_settings_active = "active menu-open";
		        $menu_balance_active = "";
	        }
	        else if(preg_match('/manage-lobby-/i', $current_url) && $_GET["lite"]!=1)
	        {
		        $menu_lobby_active = "active menu-open";
		        $menu_balance_active = "";
	        }
	        else if((preg_match('/deposit\/(.+)/i', $current_url) ||
	        preg_match('/withdrawal\/(.+)/i', $current_url) ||
	        preg_match('/transfer\/(.+)/i', $current_url)) && $_GET["lite"]!=1)
	        {
		        $menu_balance_active = "";
	        }
	        else
	        {
		        $menu_transactions_active = "active menu-open";
	        }
	            
			if($jobs_list["product_credit_view_job"]==1)
			{
				$menu_transactions_active = "";
				
				$fast_refresh_addon = "$('.treeview-menu').css('display', 'none'); $('.treeview-menu').removeClass('menu-open'); $('.treeview').removeClass('active'); $('.treeview').removeClass('menu-open'); $('#menu_kiosk_credits').addClass('active'); $('#menu_kiosk_credits').addClass('menu-open'); $('#menu_ul_kiosk_credits').css('display','block');";
				
				echo "
				<li id='menu_kiosk_credits' class='treeview $menu_balance_active'>
	            	<a href='javascript:void();' onclick='fastLoadGoodsBalance()'>
	            		<i class='fa fa-gamepad'></i> <span><b>Kiosk Credits</b></span>
						<i class='fa fa-angle-down pull-right'></i>
	                </a>
	            	<ul id='menu_ul_kiosk_credits' class='treeview-menu'>
		            	<li><div id='fast_load_balance_result' style='font-size:14px; color:#8aa4af; padding:5px 15px 5px 15px'></li>
		            </ul>	
	            </li>";
			}
	        
	        if($_SESSION["deviceType"]=="phone")
				$fcd_mobile = "1";
			else
				$fcd_mobile = "0";
		        
	        if($_SESSION["employee_name"]=="beachman")
	        {    
		        echo '
		        <li class="active treeview">
	              <a href="javascript:void()">
	                <i class="fa fa-dashboard"></i> <span>' . $_LANG["numbers_list"] . '</span></i>
	              </a>
	              <ul class="treeview-menu">
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=last_10_days\',' . $fcd_mobile . ')">Recent Topup (<10 Days)</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=last_11_30_days\',' . $fcd_mobile . ')">Recent Topup (11-30 Days)</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=before_last_30_days\',' . $fcd_mobile . ')">Recent Topup (>30 Days)</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=all\',' . $fcd_mobile . ')">All Topup</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=all_never_topup\',' . $fcd_mobile . ')">All Never Topup</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=no_depo_last_10_days\',' . $fcd_mobile . ')">No Topup (10 Days)</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=no_depo_last_11_30_days\',' . $fcd_mobile . ')">No Topup (11-30 Days)</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=no_depo_before_last_30_days\',' . $fcd_mobile . ')">No Topup (>30 Days)</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=trx_more_than_30\',' . $fcd_mobile . ')">Transactions >= 30</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=android\',' . $fcd_mobile . ')">All Android</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=topup_not_android\',' . $fcd_mobile . ')">Topup Without Android App</a></li>
	              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'numbers.php?lite=1&type=4d_follow_up\',' . $fcd_mobile . ')">All 4D</a></li>
				  </ul>
	            </li>';
            }
	            
			if($jobs_list["deposit_view_job"]==1 || $jobs_list["deposit_add_job"]==1 || $jobs_list["withdrawal_add_job"]==1 || $jobs_list["withdrawal_view_job"]==1)
			{
				echo '
				<li class="treeview ' . $menu_transactions_active . '">
	              <a href="#" id="menu_transactions">
	                <i class="fa fa-line-chart"></i> <span><b>' . $_LANG["transactions"] . '</b></span></i>
	                <i class="fa fa-angle-down pull-right"></i>
	              </a>
	              <ul class="treeview-menu">';
	              
	              if($jobs_list["deposit_view_job"]==1)
	              {
		              echo'<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'deposit/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["deposits"] . '</a></li>';
		               echo'<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'deposit-unclaimed/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["deposits_unclaimed"] . '</a></li>';
	              }
	              
	              if($jobs_list["withdrawal_view_job"]==1)
	              {
		              echo'<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'withdrawal/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["withdrawals"] . '</a></li>';
	              }
	              
	              if($jobs_list["transfer_view_job"]==1)
	              {
		              echo'<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'transfer/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["transfers"] . '</a></li>';
	              }
	              
	              echo '
	              </ul>
	            </li>';
            }
            
            if($jobs_list["user_listing_view_job"]==1 || $jobs_list["game_logs_view_job"]==1)
            {
	            echo '
					<li class="treeview ' . $menu_transactions_active . '">
		              <a href="#" id="menu_transactions">
		                <i class="fa fa-table"></i> <span><b>' . $_LANG["reports"] . '</b></span></i>
		                <i class="fa fa-angle-down pull-right"></i>
		              </a>
		              <ul class="treeview-menu">';
		              
		              if($jobs_list["game_logs_view_job"]==1)
		              {
			          	  echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'outstanding-bets-logs/?lite=1\',' . $fcd_mobile . ')">Outstanding Bets</a></li>';
			          	  echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'win-lose-logs/?lite=1\',' . $fcd_mobile . ')">Win Lose Report</a></li>';
			          	  echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'wallet-transfer-report/?lite=1\',' . $fcd_mobile . ')">Wallet Transfer Report</a></li>';
		              }
		              
		              if($jobs_list["user_listing_view_job"]==1)
		              		echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'sales-report/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["sales_report"] . '</a></li>';
		              	
		              	if($jobs_list["user_listing_view_job"]==1)
		              		echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'sales-report-graph/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["sales_report"] . ' (Graph)</a></li>';
		              	
		              	if($jobs_list["report_view_job"]==1)
		              		echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'transactions-report/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["transactions_report"] . '</a></li>';
		              	
		              	
		        
	              echo '
	              </ul>
	            </li>';
            }
            
            if($jobs_list["user_view_job"]==1 || $jobs_list["user_add_job"]==1)
            {
	            echo'
				<li class="treeview ' . $menu_users_active . '">
	              <a href="#" id="menu_users">
	                <i class="fa fa-group"></i> <span><b>' . $_LANG["users"] . '</b></span></i>
	                <i class="fa fa-angle-down pull-right"></i>
	              </a>
	              <ul class="treeview-menu" style="">
	              	';
	              	
	              	if($jobs_list["user_view_job"]==1)
	              		echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'user/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["users"] . '</a></li>';
	              	
	              	if($jobs_list["user_follow_up_view_job"]==1 || $jobs_list["user_follow_up_4d_view_job"]==1)
	              		echo '<li><a href="javascript:void(0); " onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'user-follow-up/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["users_follow_up"] . '</a></li>';
	              	
	              	// if($jobs_list["user_follow_up2_view_job"]==1)
	              	//	echo '<li><a href="javascript:void(0); " onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'user-follow-up2/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["display_follow_up"] . ' 2</a></li>';
	              	
	              	if($jobs_list["bank_blacklist_job"]==1)
	              		echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'blacklist-user-bank/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["blacklist_bank"] . '</a></li>';
	              	
	              	/*if($jobs_list["report_full_view_job"]==1)
	              		echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'sales-report-full/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["display_report_full"] . '</a></li>';*/
	              	
	              	echo '
	              </ul>
	            </li>';
	        }
	        
			if(($jobs_list["app_customer_service_job"]==1 || 
	        $jobs_list["promotion_daily_login_add_job"]==1 || 
	        $jobs_list["broadcast_android_job"]==1 || 
	        $jobs_list["news_feed_job"]==1) && 
	        ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
            {
	            echo '<li class="treeview ' . $menu_apps_active . ' ">
	              <a href="#" id="menu_settings">
	                <i class="fa fa-rocket"></i> <span><b>' . $_LANG["app"] . '</b></span></i>
	                <i class="fa fa-angle-down pull-right"></i>
	              </a>
	              <ul class="treeview-menu" style="">';
	              
	            if($jobs_list["promotion_daily_login_add_job"]==1)
				  echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-promotions-daily-login/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_promo_daily_login"] . '</a></li>';
		            
	            if($jobs_list["news_feed_job"]==1)
	           	  echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-news-feed/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_news_feed"] . '</a></li>';
	            
				if($jobs_list["app_customer_service_job"]==1)
	           	  echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-customer-service-android/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_customer_service"] . '</a></li>';
				  
            	if($jobs_list["broadcast_android_job"]==1)
				  echo '<li><a href="javascript:void(0);" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-broadcast-android/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["broadcast_android"] . '</a></li>';
				  
            	echo '</ul>
            	</li>';
            }
            
            if($shop_config_array["is_frontend"]==1)
            	$menu_open = "display:block;";
            else
            	$menu_open = "";
            	
            if($jobs_list["lobby_add_job"]==1)
            {
	            echo '
				<li class="treeview ' . $menu_lobby_active . ' ">
	              <a href="#" id="menu_settings">
	                <i class="fa fa-wrench"></i> <span><b>' . $_LANG["lobby"] . '</b></span></i>
	                <i class="fa fa-angle-down pull-right"></i>
	              </a>
	              <ul class="treeview-menu " style="">
	              	';
	              	if($shop_config_array["is_frontend"]==1 && $jobs_list["banner_add_job"]==1)
	              	{
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-lobby-rtg/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_lobby"] . ' (RTG)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-lobby-simple-play/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_lobby"] . ' (Simple Play)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-lobby-playtech-slot/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_lobby"] . ' (Playtech Slot)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-lobby-playtech-casino/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_lobby"] . ' (Playtech Casino)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-lobby-micro-gaming-slot/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_lobby"] . ' (Micro Gaming Slot)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-lobby-micro-gaming-casino/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_lobby"] . ' (Micro Gaming Casino)</a></li>';
	              	}
	              echo '</ul>
	            </li>';
	        }
	            
	        //if($jobs_list["is_admin"]==1 || $shop_config_array["is_frontend"]==1)
	        {    
		        echo '
				<li class="treeview ' . $menu_settings_active . ' ">
	              <a href="#" id="menu_settings">
	                <i class="fa fa-wrench"></i> <span><b>' . $_LANG["settings"] . '</b></span></i>
	                <i class="fa fa-angle-down pull-right"></i>
	              </a>
	              <ul class="treeview-menu " style="' . $menu_open . '">
	              	';
	              	
	              	if($jobs_list["settings_job"]==1)
	              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-settings/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_settings"] . '</a></li>';
	              	
	              	/*if($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1)
	            	  	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-profile/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_profile"] . '</a></li>';*/
	              	
	              	if($jobs_list["contact_job"]==1)
	             	 	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-contacts/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_contacts"] . '</a></li>';
	              	
	              	if($jobs_list["app_job"]==1)
	              		echo '<li><a href="' . WEBSITE_HOME_BACKEND . 'manage-app/">' . $_LANG["manage_mobile_app"] . '</a></li>';
	              	
	              	if($shop_config_array["is_frontend"]==1 && $jobs_list["menu_add_job"]==1)
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-menus/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_menus"] . '</a></li>';
	              	
	              	if($shop_config_array["is_frontend"]==1 && $jobs_list["page_add_job"]==1)
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-pages/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_pages"] . '</a></li>';
	              	
	              	if($shop_config_array["is_frontend"]==1 && $jobs_list["template_add_job"]==1)
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-template-content/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_template_content"] . '</a></li>';
	              	
	              	if($shop_config_array["is_frontend"]==1 && $jobs_list["banner_add_job"]==1)
	              	{
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-banners-main/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_banners"] . ' (Main)</a></li>';
		            }
		            
		            if($shop_config_array["is_frontend"]==1 && $jobs_list["banner_promo_add_job"]==1)
	              	{  	
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-banners-promo/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_banners"] . ' (Promo)</a></li>';
		            }
		            
		            if($shop_config_array["is_frontend"]==1 && $jobs_list["banner_add_job"]==1)
	              	{
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-banners-slot/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_banners"] . ' (Slots)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-banners-casino/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_banners"] . ' (Casino)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-banners-sports/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_banners"] . ' (Sports)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-banners-fish/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_banners"] . ' (Fish)</a></li>';
		              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-banners-download/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_banners"] . ' (Download)</a></li>';
	              	}
	              	
	              	if(($jobs_list["product_edit_job"]==1 || $jobs_list["product_view_job"]==1) && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1 || $shop_config_array["is_frontend"]==1))
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-games/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_games"] . '</a></li>';
		            
		            if((($jobs_list["is_admin"]==1 || $jobs_list["bank_view_job"]==1 || $jobs_list["bank_edit_job"]==1) && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1)))
		            	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-banks/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_banks"] . '</a></li>';
		            
		            if(($jobs_list["promotion_add_job"]==1) && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
		            	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-promotions/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_promotions"] . '</a></li>';
		            
		            if(($jobs_list["promotion_add_job"]==1) && ($shop_config_array["is_frontend"]==1))
		            	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-promotions-frontend/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_promotions"] . '</a></li>';
		            
		            if($jobs_list["is_admin"]==1 && ($shop_config_array["is_frontend"]==1 || $shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
	              		echo '<!--<li><a href="' . WEBSITE_HOME_BACKEND . 'manage-banners/">' . $_LANG["manage_banners"] . '</a></li>-->';
		              	
		            if($jobs_list["is_admin"]==1 && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
	              		echo '<li style="display:none;"><a href="' . WEBSITE_HOME_BACKEND . 'manage-extra-messages/">Manage Extra Messages</a></li>';
		            
		            if($jobs_list["success_message_edit_job"]==1)
		            	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-success-messages/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_success_message"] . '</a></li>';
		            
		            if($jobs_list ["is_admin"]==1 && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
	              		echo '<li style="display:none"><a href="' . WEBSITE_HOME_BACKEND . 'manage-follow-up/">' . $_LANG["manage_follow_up"] . '</a></li>';
		            
		            if(($jobs_list["is_admin"]==1 || $jobs_list["facebook_pages_edit_job"]==1) && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-facebook-pages/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_facebook_pages"] . '</a></li>';
	              	
		            if(($jobs_list["free_4d_add_job"]==1) && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
		            	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-free-4d/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_free_4d"] . '</a></li>';
		            
		            if(($jobs_list["is_admin"]==1 || $jobs_list["referral_edit_job"]==1) && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-referrals/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_referrals"] . '</a></li>';
	              	
		            if($jobs_list["google_contact_job"]==1)
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-google-contacts/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_google_contact"] . '</a></li>';
	              	
	              	
	              	
		            if($jobs_list["sms_gateway_job"]==1 && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
	              	  	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-sms-gateway/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_sms_gateway"] . '</a></li>';
	              	
	              	
		            
		            if($jobs_list["is_admin"]==1 && $jobs_list["is_master"]==1)
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-companies/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_companies"] . '</a></li>';
	              	
	              	if($jobs_list["is_admin"]==1)
	              		echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'configurations/?lite=1\',' . $fcd_mobile . ')">Configuration</a></li>';
	              	
	              	if($_SESSION["employee_username"]=="master")
	              	  	echo '<li><a href="backup_status.php" target="_blank">' . $_LANG["backup_status"] . '</a></li>';
	              	
	              	echo '
	              </ul>
	            </li>';
			}
			
			if($jobs_list["is_admin"]==1 || $jobs_list["employees_add_job"]==1)
			{
				echo '
				<li class="treeview ' . $menu_settings_active . ' ">
	              <a href="#" id="menu_settings">
	                <i class="fa fa-street-view"></i> <span><b>' . $_LANG["employees"] . '</b></span></i>
	                <i class="fa fa-angle-down pull-right"></i>
	              </a>
	              <ul class="treeview-menu " style="margin-bottom:18px; ' . $menu_open . '">';
	              
	              	echo '<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-employees/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_employees"] . '</a></li>';
	              	
	              	if($jobs_list["is_admin"]==1)
	              	{
		              	echo '
		              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-permissions-group/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_permission_group"] . '</a></li>
		              	<li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-permissions/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_permissions"] . '</a></li>';
		            }
		            
		            if(($jobs_list["is_admin"]==1 || $jobs_list["shift_edit_job"]==1) && ($shop_config_array["is_backend"]==1 || $shop_config_array["is_backup"]==1))
	              		echo '  <li><a href="javascript:void()" onclick="fastSectionDisplay(\'' . WEBSITE_HOME_BACKEND . 'manage-shifts/?lite=1\',' . $fcd_mobile . ')">' . $_LANG["manage_shifts"] . '</a></li>';
		            
	              	echo '
	              </ul>
	            </li>';
	        }
		    
		    ?>
            
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

<!-- Control Sidebar -->
<div id='fast_section_display' style='z-index:88; position:fixed; top:0; left:0; width:100%; height:100%; background:white;display:none; padding:8px 0; overflow-y:scroll; -webkit-overflow-scrolling: touch;'>
	
<div style='width:100%; padding:0 12px;'>

<input id='close_reload_frame_button' type='button' value='✗' onclick='$("#fast_section_display").fadeOut(0); $("body").css("overflow","scroll");' style='margin-bottom: 8px; position: fixed; z-index: 999; right: 10px;'>
</div>

<div id='fast_section_display_content'></div>
</div>  
      
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">      
      
		<!-- Content Header (Page header) -->
		
		    <!-- Main content -->
		    <section class="content" style="min-height:1000px; padding-left:0; padding-right:0; position:relative; <?php echo $content_style; ?>">    
			  
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8mb4">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=1"/>
<title><?php echo $page_title ?></title>

<?php if($logged) { ?>
<style>
label {
display: inline-block;
width: 5em;
}
</style>
</head>
<?php } ?>

<?php 

echo "
<div id='content' style='width:100%;background-color:#FFF;'>
";

echo "<div id='search_result_content' style='background-color: white; width: 100%; height: 93%; position: fixed; top: 50px; left: 0; display:none; z-index:9000; overflow-y:scroll;'><div id='result' style='padding:15px;'></div></div>";

echo "<div onclick='displaySearchResult(0)' id='search_result_header' style='background-color: #3c8dbc; width: 100%; height: 50px; position: fixed; top: 0; left: 0; color:white; text-align: center; padding-top: 14px; z-index:11040; cursor:pointer; display:none;'>" . $_LANG["click_here_to_hide"] . "</div>";

echo "</div>";

?>

<style>
body {position:inherit;}
.tableresize td{font-size:13px !important}
.blacklist td{background:black !important; color:white !important; }
.blacklist td input{color:black !important; }
.blacklist td select{color:black !important; }
.blacklist td textarea{color:black !important; }
.pending_tr.pending td{background:#FFAAAA; }
.pending_tr.approved td{background:#FFF7BC; }
.pending_tr.rejected td{background:#FEFDD0; }
</style>

<script>

$("#control-sidebar-button").mouseenter(function(){$(".control-sidebar").addClass("control-sidebar-open");});
$(".control-sidebar-main").mouseenter(function(){$(".control-sidebar").addClass("control-sidebar-open");}).mouseleave(function(){$(".control-sidebar").removeClass("control-sidebar-open");})	

</script>

<?php
	
} // if lite != 1

?>