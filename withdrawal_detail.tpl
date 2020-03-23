
		<!-- right col (We are only adding the ID to make the widgets sortable)-->
		<section class='col-lg-6 connectedSortable ui-sortable'>
		    <!-- quick email widget -->
		  <div class='box box-primary'>
		    <div class='box-header'>
		      <i class='fa fa-user'></i>
		      <h3 class='box-title'>{$_LANG.withdrawal_details}</h3>
		      <!-- tools box -->
		      <!--<div class='pull-right box-tools'>
		        <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></button>
		      </div>-->
		      <!-- /. tools -->
		    </div>
		    <div class='box-body' style=''>
		   
		<form method='post' action='{$WEBSITE_HOME_BACKEND}/withdrawal/{$row.d_id}/'>
		<table class='table table-hover table-bordered'>
		<tr>
			<th colspan='2'>{$_LANG.update_status}</th>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.status}</td>
			<td>
				
				{if $row.deposit_is_complete==1 || $jobs_list.withdrawal_edit_completed_job==0}
				
					{*
					 * If withdrawal is completed, cannot edit status
					 *}
				
					{$_LANG.{$row.deposit_status}}
				
				{else}
				
					<select name='deposit_status' style='width:168px;' onchange='$("#withdrawal_view_update_1").click();'>
					
					{foreach from=$pn_status_array key=$pn_key item=$pn_value}
					
						{if $row.deposit_status==$pn_key}
							<option value='{$pn_key}' selected>{$pn_value}</option>
						{else}
							<option value='{$pn_key}'>{$pn_value}</option>
						{/if}
						
					{/foreach}
					
					</select>
					
				{/if}
				
			</td>
		</tr>
		
		{*
		 * Don't show if goods code is main wallet or permission is not permitted
		 *}
		
		{* $row.goods_code=="main_wallet" || *}
		
		{if $jobs_list.withdrawal_edit_completed_job==0}
		
			{*
			 * Don't show if goods code is main wallet or permission is not permitted
			 *}
		
		{else}
		
			<tr>
				<td>{$_LANG.completed}</td>
				<td>
					
					{if $row.deposit_status=="pending"}
					
						{*
						 * If deposit status is pending, do not show complete dropdown
						 *}
					
						-
						
					{else}
					
						<select name='deposit_is_complete' style='width:168px;' onchange='$("#withdrawal_view_update_1").click();'>
							<option value='1' {if $row.deposit_is_complete==1}selected{/if}>{$_LANG.yes}</option>				
							<option value='0' {if $row.deposit_is_complete==0}selected{/if}>{$_LANG.no}</option>				
						</select>
					
					{/if}
					
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type='hidden' name='action' value='update_withdrawal'>
					<input type='hidden' name='d_id' value='{$row.d_id}'>
					<input type='hidden' name='user_id' value='{$row.user_id}'>
					<input type='submit' value='Update' id='withdrawal_view_update_1'>
				</td>
			</tr>
		
		{/if}
		
		
		</table>
		</form>
		
		<br>   
		    
	    {*<form method='post' action='{$WEBSITE_HOME_BACKEND}/withdrawal/{$row.d_id}/'>*}
		<table class='table table-hover table-bordered'>
		<tr>
			<th colspan='2'>{$_LANG.withdrawal_details}</th>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.withdrawal_id}</td>
			<td>
				
				{$row.d_id_formatted}
				
			</td>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.withdrawal_user}</td>
			<td>
				
				{$row.user_name} 
				
				<a href='javascript:void(0);' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/user/{$row.user_id}/?lite=1")'><img src='{$WEBSITE_HOME_BACKEND}/images/user.png' style='width:16px;'></a>
				
			</td>
		</tr>
		<tr style='background:#faa;'>
			<td>{$_LANG.withdrawal_amount}</td>
			<td>
				
				{$currency}{$deposit_amount_formatted}
			
				{if $row.deposit_add_employee_id==0}
					
					<div style='float:right;'><img src='{$WEBSITE_HOME_BACKEND}/images/icon_globe.png' style='width:24px; margin-top:0px;'></div>
				
				{/if}
				
				{if $row.depsoit_amount_promo>0}
					
					<div style='border-radius:4px; margin-top:2px; margin-bottom:4px; background:#e56e6e; padding:6px;'>
						
						+{$currency}{$row.depsoit_amount_promo} ({$_LANG.promo})
						
						<div style='font-size:12px; opacity:0.8;'><i>{$_LANG.promo_d} {$row.sp_name}</i></div>
						
					</div>
					<div style='margin-bottom:0px;'></div>
					
				{/if}
				
				{if $row.deposit_amount_payback>0}
					<div style='border-radius:4px; background:#ffc587; padding:6px; margin-bottom:3px;'>-{$currency}{$row.deposit_amount_payback} ({$_LANG.payback})<div style='margin-top:-;font-size:12px; opacity:0.8;'></div></div>
				
				{/if}
				
				{if $row.deposit_amount>0}
					
					<textarea id='deposit_fast_copy_text_backend_{$row.d_id}' onclick='$(this).select(); document.execCommand("copy"); $(this).fadeOut(200).fadeIn(200);' style='width:108px;height:26px; margin-top:2px; font-size:8px;'>{$row.d_id_formatted}{$row.deposit_amount_final_formatted|indent:1:"\t"}{$row.deposit_time_formatted|indent:1:"\t"}</textarea>
					
				{/if}
				
				<div><textarea id='pending_deposit_success_textarea_{$d_id}' class='pending_deposit_success_textarea_{$d_id}' onclick='this.select(); document.execCommand("copy"); $(this).fadeOut(200).fadeIn(200);' style=' line-height: 8px; margin-top:2px; width:108px; max-width: 100%; height: 50px; margin-top: 2px; font-size:8px;'>{$row.success_message}</textarea></div>
				
				{$row.goods_name_long}
				
				{if $row.goods_code=="main_wallet"}
						
					<div style='margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$WEBSITE_HOME_BACKEND}/win-lose-logs/?user_name={$row.user_name2}", "_blank")' style='width:auto; font-size:10px;'>{$_LANG.game_log}</button></div>
				
				{/if}

				{if $row.goods_code!="main_wallet"}
					
					{if $row.deposit_ug_u!=""}
						<div style='margin-top:0px; font-size:12px; opacity:0.8;'><i>ID: {$row.deposit_ug_u}</i></div>
					{/if}
					
					<div style=''><textarea style='padding:5px; line-height: 28px; margin-top:0px; width:88px; max-width: 100%; height: 38px; margin-top: 2px; font-size:28px; overflow:hidden;' onclick='this.select(); document.execCommand("copy"); $(this).fadeOut(200).fadeIn(200); $("#set_score_{$row.d_id}").removeAttr("disabled"); $("#set_score_{$row.d_id}").click();'>{$row.deposit_point}</textarea></div>
					
					{if $row.shortcut_url_balance!=""}
						<div style='float:left; margin-right:4px; margin-bottom:4px;'><button id='set_score_{$row.d_id}' type='button' class=' btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_balance}","_blank");' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.set_score}</button></div>
					{/if}
					
					{if $row.shortcut_url_log_score!=""}
						
						<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_log_score}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.score_log}</button></div>
						
					{/if}
					
					{if $row.shortcut_url_log_game!=""}
						 
						<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_log_game}","_blank");' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.game_log}</button></div>
					
					{/if}
					
					{if $row.shortcut_url_edit!=""}
						 
						<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_edit}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.edit}</button></div>
					{/if}
					
					<div style='clear:both;'></div>
				
				{/if}
			
			</td>
		</tr>
		<tr>
			<td>{$_LANG.withdrawal_bank_from}</td>
			<td>
				
			{*{if $jobs_list.withdrawal_edit_job==1}
			
				<select name='deposit_bank' onchange='$("#withdrawal_view_update_2").click();' style=''>
				
				{foreach from=$payment_list item=pay_row}
					<option value='{$pay_row.pay_id}' {if $row.deposit_bank==$pay_row.pay_id}selected{/if}>{$pay_row.pay_name}</option>			
				{/foreach}
				
				</select>
					
			{else}
			
				{$row.bank_name}
			
			{/if}*}
			
			{$row.bank_name}
			
			</td>
		</tr>
		<tr>
			<td>{$_LANG.bank_name}</td>
			<td>{$row.pay_name2}</td>
		</tr>
		<tr>
			<td>{$_LANG.account_name}</td>
			<td>{$row.ub_account_name}</td>
		</tr>
		<tr>
			<td>{$_LANG.account_number}</td>
			<td>{$row.ub_account_number}</td>
		</tr>
		<tr>
			<td>{$_LANG.withdrawal_date}</td>
			<td>
			
			{*if($jobs_list["withdrawal_edit_job"]==1)
			{
				$selected_day = date("d",$deposit_date_strtotime);
				$selected_month = date("m",$deposit_date_strtotime);
				$selected_year = date("Y",$deposit_date_strtotime);
				$selected_hour = date("H",$deposit_date_strtotime);
				$selected_min = date("i",$deposit_date_strtotime);
				$selected_ampm = date("A",$deposit_date_strtotime);
				
				$edit_date_style = "";
				
				if($jobs_list["withdrawal_edit_date_job"]==0)
				{
					$edit_date_style = "display:none;";
					echo date("d-m-Y",$deposit_date_strtotime) . " ";
				}
				else
				{
				}
				
				<select name='deposit_day' style='$edit_date_style' required onchange='$("#withdrawal_view_update_2").click();'>
				";
				
				for($a=1; $a<=31; $a++)
				{
					$a = str_pad($a, 2, '0', STR_PAD_LEFT);
					
					if($selected_day==$a)
					<option value='$a' selected>$a</option>";				
					else
					<option value='$a'>$a</option>";
				}
				
				
				</select> 
				
				<select name='deposit_month' style='$edit_date_style' required onchange='$("#withdrawal_view_update_2").click();'>
				";
				
				for($a=1; $a<=12; $a++)
				{
					$a = str_pad($a, 2, '0', STR_PAD_LEFT);
					
					if($selected_month==$a)
					<option value='$a' selected>$a</option>";				
					else
					<option value='$a'>$a</option>";
				}
				
				
				</select> 
				
				<select name='deposit_year' style='$edit_date_style' required onchange='$("#withdrawal_view_update_2").click();'>
				";
				
				for($a=date("Y"); $a>=date("Y"); $a--)
				{
					if($selected_year==$a)
					<option value='$a' selected>$a</option>";				
					else
					<option value='$a'>$a</option>";
				}
				
				
				</select>
				
				<select name='deposit_hour' required onchange='$("#withdrawal_view_update_2").click();'>
				";
				
				for($a=1; $a<=23; $a++)
				{
					$a = str_pad($a, 2, '0', STR_PAD_LEFT);
					
					if($selected_hour==$a)
					<option value='$a' selected>$a</option>";				
					else
					<option value='$a'>$a</option>";
				}
				
				
				</select>
				
				<select name='deposit_min' required onchange='$("#withdrawal_view_update_2").click();'>
				";
				
				for($a=0; $a<=59; $a++)
				{
					$a = str_pad($a, 2, '0', STR_PAD_LEFT);
					
					if($selected_min==$a)
					<option value='$a' selected>$a</option>";				
					else
					<option value='$a'>$a</option>";
				}
				
				
				</select>
				
				<select name='deposit_am_pm' style='display:none;' onchange='$("#withdrawal_view_update_2").click();'>
				<option value=''></option>
				";
				
				
				</select>";
			}
			else
			{
				{$row.deposit_datetime_formatted}
			}*}
			
			{$row.deposit_datetime_formatted}
			
			</td>
		</tr>
		<tr>
			<td>{$_LANG.game}</td>
			<td><font color='red'><b>{$row.goods_name_long}</b></font></td>
		</tr>
		
		{if $row.deposit_reject_reason!=""}

			<tr>
				<td>{$_LANG.reject_reason}</td>
				<td><font color='red'><b>{$row.deposit_reject_reason}</b></font></td>
			</tr>"

		{/if}
		
		{*{if $jobs_list.withdrawal_edit_job==1}
			
			<tr>
				<td></td>
				<td>
					<input type='hidden' name='d_id' value='{$row.d_id}'>
					<input type='hidden' name='action' value='edit_withdrawal'>
					<input type='submit' value='Update' id='withdrawal_view_update_2'>
				</td>
			</tr>
		
		{/if}*}
		
		</table>
		{*</form>*}
		
		<br>
		
		<table class='table table-hover table-bordered'>
		<tr>
			<th colspan='2'>{$_LANG.action_details}</th>
		</tr>
		<tr>
			<td style='width:128px;'>Add</td>
			<td>
			
				{$row.employee_short_name}
			 
				{if $row.deposit_add_time>0}
					<div style="float:right;">{$row.deposit_add_time_formatted}</div>
				{/if}
			
			</td>
		</tr>
		<tr>
			<td style='width:128px;'>Approve</td>
			<td>
			
				{$row.employee_short_name_approve}
			 
				{if $row.deposit_approve_time>0}
					<div style="float:right;">{$row.deposit_approve_time_formatted}</div>
				{/if}
			
			</td>
		</tr>
		<tr>
			<td style='width:128px;'>Reject</td>
			<td>
			
				{$row.employee_short_name_reject}
				
				{if $row.deposit_reject_time>0}
					<div style="float:right;">{$row.deposit_reject_time_formatted}</div>
				{/if}
				
			</td>
		</tr>
		<tr>
			<td style='width:128px;'>Complete</td>
			<td>
				
				{$row.employee_short_name_complete}
			
				{if $row.deposit_complete_time>0}
					<div style="float:right;">{$row.deposit_complete_time_formatted}</div>
				{/if}
			
			</td>
		</tr>
		</table>
		
		    </div>
		  </div>
		</section>
		
		<!-- right col (We are only adding the ID to make the widgets sortable)-->
		
		
		<section class='col-lg-6 connectedSortable ui-sortable' style='display:;'>
		    <!-- quick email widget -->
		  <div class='box box-primary'>
		    <div class='box-header'>
		      <i class='fa fa-user'></i>
		      <h3 class='box-title'>{$_LANG.admin}</h3>
		      <!-- tools box -->
		      <!--<div class='pull-right box-tools'>
		        <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></button>
		      </div>-->
		      <!-- /. tools -->
		    </div>
		    <div class='box-body' style=''>
		
		<form method='post'>
		<table class='table table-hover table-bordered'>
		<tr>
			<th colspan='2'>{$_LANG.user_details}</th>
		</tr>
		<tr style='display:none;'>
			<td style='width:128px;'>{$_LANG.user_id}</td>
			<td>$user_id</td>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.username}</td>
			<td><a href='javascript:void(0);' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/user/{$row.user_id}/?lite=1")'>{$row.user_name}</a></td>
		</tr>
		<tr>
			<td>{$_LANG.name}</td>
			<td>{$row.name}</td>
		</tr>
		<tr>
			<td>{$_LANG.tel}</td>
			<td>{$row.tel}</td>
		</tr>
		</table>
		</form>
		
		    </div>
		  </div>
		</section>
		
		<!-- right col (We are only adding the ID to make the widgets sortable)-->
		<section class='col-lg-6 connectedSortable ui-sortable'>
		    <!-- quick email widget -->
		  <div class='box box-primary'>
		    <div class='box-header'>
		      <i class='fa fa-user'></i>
		      <h3 class='box-title'>{$_LANG.game_info}</h3>
		      <!-- tools box -->
		      <!--<div class='pull-right box-tools'>
		        <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></button>
		      </div>-->
		      <!-- /. tools -->
		    </div>
		    <div class='box-body' style=''>
		<form method='post'>
		
		{if $user_game_data}
		
			<form method='post'>
			<table class='table table-hover table-bordered'>
			<tr>
				<th style='width:158px;'>{$_LANG.game}</th>
				<th>{$_LANG.username}</th>
				<th style='display:none;'>{$_LANG.password}</th>
			</tr>
					
			{foreach from=$user_game_data item=$row}
			
				<tr style='{if $row.ug_is_active==0} background:#FAA; {/if}'>
					<td>{$row.goods_name_long}</td>
					<td>{$row.ug_u}</td>
				</tr>
				
			{/foreach}
			
			</table>
			</form>
			
		{/if}
		
		</form>
		    </div>
		  </div>
		</section>
		