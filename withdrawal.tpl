
	<div style='margin-left:15px; margin-bottom:15px;'>
	
		<form>
			
			<input type='button' value='{$_LANG.search}' onclick='searchDeposit("withdrawal")'> &nbsp;
			
			<input autocomplete='off' class='search_input' id='search_user_id' placeholder='{$_LANG.user_id}' name='user_id' value='{$post_user_id}' style='display:none; width:78px;' onkeyup='searchDepositEnter("withdrawal",event)' onclick='$(this).select()'> 			
			
			{*
			 * Search by transaction id
			 *}
			
			<input class='search_input search_withdrawal_d_id' id='search_withdrawal_d_id' placeholder='{$_LANG.trx_id}' name='d_id' value='{$post_d_id}' style='width:48px;' onkeyup='reformatTRXID("search_withdrawal_d_id"); searchAutoClearUnnecessary("search_withdrawal_d_id");searchDepositEnter("withdrawal",event)' onclick='$(this).select()'>	
			
			{*
			 * Search by username
			 *}
			
			<input autocomplete='off' class='search_input search_withdrawal_user_name' id='search_withdrawal_user_name' placeholder='{$_LANG.username}' name='user_name' value='{$post_user_name}' style='width:78px;' onkeyup='searchAutoClearUnnecessary("search_withdrawal_user_name"); searchDepositEnter("withdrawal",event)' onclick='$(this).select()'>		
			
			{*
			 * Search by name
			 *}
			
			<input autocomplete='off' class='search_input search_withdrawal_name' id='search_withdrawal_name' placeholder='{$_LANG.name}' name='name' value='{$post_name}' style='width:78px;' onkeyup='searchAutoClearUnnecessary("search_withdrawal_name");searchDepositEnter("withdrawal",event)' onclick='$(this).select()'>
			
			{*
			 * Search by remark
			 *}
			 
			<input autocomplete='off' class='search_input search_withdrawal_remark' id='search_withdrawal_remark' placeholder='{$_LANG.remarks}' name='name' value='{$post_remark}' style='width:78px;' onkeyup='searchAutoClearUnnecessary("search_withdrawal_remark"); searchDepositEnter("withdrawal",event)' onclick='$(this).select()'>
			
			&nbsp; {$_LANG.filter} 
			
			{*
			 * Search by date
			 *}
			
			<input class='search_input' id='search_from_date' type='date' value='{$post_from_date}' style='width:128px;' onkeyup='searchDepositEnter("withdrawal",event)'> 
			
			<input class='search_input' id='search_to_date' type='date' value='{$post_to_date}' style='width:128px;' onkeyup='searchDepositEnter("withdrawal",event)'> 
			
			{*
			 * Search by bank
			 *}
			
			<select class='search_input' id='search_withdrawal_bank' name='deposit_bank' onchange='searchDeposit("withdrawal")' style='width:58px;'>
				
				<option value=''>{$_LANG.bank}</option>
				
				{foreach from=$payment_list item=$row}
					
					<option value='{$row.pay_id}' {if $post_deposit_bank==$row.pay_id}selected{/if}>{$row.pay_name}</option>
				
				{/foreach}
			
			</select>
			
			{*
			 * Search by game
			 *}
			
			 <select class='search_input' id='search_withdrawal_goods_id' name='deposit_goodsid' onchange='searchDeposit("withdrawal")' style='width:68px;'>
				<option value=''>{$_LANG.game}</option>
				{foreach from=$game_list item=$row}
					{if $row.goods_name_long!=""}
						<option value='{$row.goods_id}' {if $row.goods_id==$post_deposit_goods_id}selected{/if}>{$row.goods_name_long}</option>
					{/if}
				{/foreach}
			</select>
			
			{*
			 * Search by promotion
			 *}
			
			<select class='search_input' id='search_withdrawal_sp_id' name='deposit_bank' onchange='searchDeposit("withdrawal")' style='width:68px; display:none;'>
				<option value=''>{$_LANG.promo}</option>
				{foreach from=$promotion_list item=$row}
					<option value='$sp_id' {if $row.sp_id==$post_deposit_sp_id}selected{/if}>{$row.sp_name}</option>
				{/foreach}
			</select>
			
			{*
			 * Search by employee
			 *}
			
			 <select class='search_input' id='search_withdrawal_add_employee_id' name='deposit_add_employee_id' onchange='searchDeposit("withdrawal")' style='width:68px;'>
				<option value=''>{$_LANG.admin}</option>
				{foreach from=$employee_list item=$row}
					{if $row.employee_short_name!=""}
						<option value='{$row.employee_id}' {if $post_deposit_add_employee_id==$row.employee_id}selected{/if}>{$row.employee_short_name}</option>
					{/if}
				{/foreach}
			</select>
			
			{*
			 * Search by special type
			 *}
			
			<select class='search_input' id='search_withdrawal_add_type' name='deposit_add_type' onchange='searchDeposit("withdrawal")' style='width:68px;'>
				<option value=''>-</option>
				<option value='void' {if $post_type=="void"}selected{/if}>Void</option>
			</select>
			
			&nbsp;
			
			{*
			 * Clear Button
			 *}
			
			<input type='button' value='{$_LANG.clear}' onclick='$(".search_input").val("");searchDeposit("withdrawal");'>
		
		</form>
		
		<form>
			
			{*
			 * Search by today, yesterday, this week, last week, this month and last month
			 *}
			
			<div style='margin-top:8px;'>
			
			<input type='button' value='{$_LANG.today}' onclick='$("#search_from_date").val("{$today_date_formatted}"); $("#search_to_date").val("{$today_date_formatted}");searchDeposit("withdrawal");'> &nbsp;
			
			<input type='button' value='{$_LANG.yesterday}' onclick='$("#search_from_date").val("{$yesterday_date_formatted}"); $("#search_to_date").val("{$yesterday_date_formatted}");searchDeposit("withdrawal");'> &nbsp;
			
			<input type='button' value='{$_LANG.this_week}' onclick='$("#search_from_date").val("{$this_week_from_date_formatted}"); $("#search_to_date").val("{$this_week_to_date_formatted}");searchDeposit("withdrawal");'> &nbsp;
			
			<input type='button' value='{$_LANG.last_week}' onclick='$("#search_from_date").val("{$last_week_from_date_formatted}"); $("#search_to_date").val("{$last_week_to_date_formatted}");searchDeposit("withdrawal");'> &nbsp;
			
			<input type='button' value='{$_LANG.this_month}' onclick='$("#search_from_date").val("{$this_month_from_date_formatted}"); $("#search_to_date").val("{$this_month_to_date_formatted}");searchDeposit("withdrawal");'> &nbsp;
			 
			<input type='button' value='{$_LANG.last_month}' onclick='$("#search_from_date").val("{$last_month_from_date_formatted}"); $("#search_to_date").val("{$last_month_to_date_formatted}");searchDeposit("withdrawal");'> &nbsp; 
			
			{*
			 * Search by left or right navigation button
			 *}
			
			<input id='user_listing_left' type='button' value='<' onclick='$("#search_from_date").val("{$left_from_date_button_value}"); $("#search_to_date").val("{$left_to_date_button_value}");searchDeposit("withdrawal")'> &nbsp;
			
			<input id='user_listing_right' type='button' value='>' onclick='$("#search_from_date").val("{$right_from_date_button_value}"); $("#search_to_date").val("{$right_to_date_button_value}");searchDeposit("withdrawal")'> &nbsp;
			
			</div> 
		
		</form>
	
	</div>	
	
	<section class='col-lg-12 connectedSortable ui-sortable'>
	    <!-- quick email widget -->
	  <div class='box box-primary'>
	    <div class='box-header'>
	      <i class='fa fa-money'></i>
	      <h3 class='box-title'>{$_LANG.view_withdrawals}</h3>
	      <!-- tools box -->
	      <!--<div class='pull-right box-tools'>
	        <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></button>
	      </div>-->
	      <!-- /. tools -->
	      
	      <div style='float:right;'>{$pagination}</div>
	    </div>
	    <div class='box-body' style=''>
	
		<div style=' overflow-x:scroll; width:100%;'>
		<table class='table table-bordered table-hover'>
		<tr>
			<th class='center' style='width:38px;'>#</th>
			<th class='center'>{$_LANG.reference}</th>
			<th class='center'>{$_LANG.date}</th>
			<th class='center'>{$_LANG.user2}</th>
			<th class='center'>{$_LANG.bank}</th>
			<th class='center'>{$_LANG.bank_customer}</th>
			<th class='center'>{$_LANG.amount}</th>
			<th class='center'>{$_LANG.amount} ({$_LANG.promo})</th>
			<!--<th>{$_LANG.tip_amount}</th>-->
			<th class='center'>{$_LANG.amount} ({$_LANG.void})</th>
			<th class='center'>{$_LANG.remarks}</th>
			<th class='center'>{$_LANG.game}</th>
			<th class='center'>{$_LANG.admin}</th>
		</tr>
		
		{foreach from=$withdrawal_data item=$row}
		
			<tr style='background: #FAA; {if $row.deposit_status=="rejected"}opacity:0.5;{/if}'>
				<td class='center'>{$row.count}</td>
				<td class='center'><a href='javascript:void(0);' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/withdrawal/{$row.d_id}/?lite=1")'>{$row.d_id_formatted}</a>
				
				<!--<br>
				
				<textarea id='deposit_fast_copy_text_backend_{$row.d_id}' onclick='$(this).select(); document.execCommand("copy"); $(this).fadeOut(200).fadeIn(200);' style='width:50px;height:26px; margin-top:2px; font-size:8px;'>{$row.d_id_formatted}{$row.deposit_amount_final|indent:1:"\t"}{$row.deposit_time_formatted|indent:1:"\t"}</textarea>-->
				
				<div style='margin-top:-2px; font-size:8px; color:#555;'></div>
				
				</td>
				<td class='center'>
					
					{$row.deposit_time_formatted}
				
					{if $row.deposit_add_employee_id==0}
						<center><img src='{$WEBSITE_HOME_BACKEND}/images/icon_globe.png' style='width:16px; margin-top:0;'></center>
					{/if}
				
				</td>
				<td class='center'>
					
					<a href='javascript:void(0);' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/user/{$row.deposit_user_id}/?lite=1")'>{$row.user_name}</a>
					
					{if $row.user_name2!=""}
						<div>({$row.user_name2})</div>
					{/if}
				
					<div style='font-size:10px; margin-top:0px;'>{$row.name}</div>
					
					{if $row.is_blacklist==1}
						<div class='label label-danger'>{$_LANG.blacklisted}</div>
					{/if}
					
					{if $row.is_iphone==1}
						<img src='{$WEBSITE_HOME_BACKEND}/images/ic_ios.png' style='width:14px;' title=''> &nbsp;
					{/if}
					
					{if $row.is_android==1}
						<img src='{$WEBSITE_HOME_BACKEND}/images/ic_android.png' style='width:16px;' title='Version {$row.android_app_version}'>
						
						{if $row.android_app_version>0}
							<span style='font-size:11px;'> {$row.android_app_version}</span>
						{/if}
						
						&nbsp;
					{/if}
				
				</td>
				<td class='center'>
				
					{if $row.image_exist==1}
						<img src='{$WEBSITE_HOME_BACKEND}/images/banks/{$row.pay_name}.png' style='width:88px;'>
					{else}
						{$row.pay_name}
					{/if}
				
				</td>
				<td class='center'>
				
					{if $row.deposit_user_bank>0}
						{$row.pay_name2}<br>
						{$row.ub_account_name}<br>
						{$row.ub_account_number}
					{/if}
				
				</td>
				<td class='center'>
					
					{$row.deposit_amount_formatted}
				
					{if $row.shortcut_url_log_game!=""}
					
						{if $row.goods_code=="main_wallet"}
							<center><div style=''><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$WEBSITE_HOME_BACKEND}/win-lose-logs/?user_name={$row.user_name2}","_blank")' style='width:auto; font-size:10px;'>{$_LANG.game_log}</button></div></center>
						{else}
							<center><div style=''><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_log_game}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.game_log}</button></div></center>
						{/if}
					
					{/if}
					
					{if $row.shortcut_url_log_score!=""}
					
						<center><div style='margin-top:5px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_log_score}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.score_log}</button></div></center>
					
					{/if}
				
				</td>
				<td style='text-align:center; {if $row.deposit_amount_promo>0 }background:#e56e6e;{/if}'>
				
					{if $row.deposit_amount_promo>0}
				
						{$row.deposit_amount_promo}
				
					{else}
					
						0.00
					
					{/if}
					
				</td>
				
				{if $row.deposit_amount_tip_formatted>0}
				
					<td style='background:#91e2d8; text-align:center;'>{$row.deposit_amount_tip_formatted}</td>
				
				{elseif $row.deposit_amount_void_formatted>0}
				
					<td style='background:#e2b8f9; text-align:center;'>{$row.deposit_amount_void_formatted}</td>
				
				{else}
				
					<td class='center'>0.00</td>
					
				{/if}
				
				<td style='text-align:center; {if $row.deposit_amount_payback > 0}background:#ffc587{/if}'>
				
					{if $row.deposit_amount_payback>0}
	
						<div style='margin-top:0;font-size:14px; opacity:0.8;max-width:128px; '><i><textarea style='width:100px; height:50px;'>Payback {$row.deposit_amount_payback}</textarea></i></div>
						
					{/if}
					
					{if $row.deposit_amount_void_explain!=""}
					
						<div style='margin-top:0;font-size:14px; opacity:0.8;max-width:128px; '><i><textarea style='width:100px; height:50px;'>{$row.deposit_amount_void_explain}</textarea></i></div>
					
					{/if}
				
				</td>
				<td class='center'>
					
					{$row.goods_name_long}
				
					<div style='margin-top:0; font-size:12px; opacity:0.8;'><i>{$row.deposit_ug_u}</i></div>
				
				</td>
				<td class='center'>
					
					{$row.employee_short_name}
				
					<br>
				
					{if $row.deposit_status=="pending"}
                    	<span class='label label-warning'>{$_LANG.pending}</span>
                    {elseif $row.deposit_status=="approved"}
                    	<span class='label label-success'>{$_LANG.approved}</span>
                    {elseif $row.deposit_status=="rejected"}
                    	<span class='label label-danger'>{$_LANG.rejected}</span>
                    {elseif $row.deposit_status=="completed"}
                    	<span class='label bg-blue'>{$_LANG.completed}</span>
                    {/if}
				
					{if $row.deposit_reject_reason!=""}
						<div style='line-height:16px; margin-top:3px;'><i style='color:red; font-size:13px;'>({$row.deposit_reject_reason})</i></div>
					{/if}
				
				</td>
			</tr>
			
		{/foreach}
		
	
	</table>
	</div>
	
	
	<div style='margin-top:5px;'></div>
	
	<div style='float:right;'>{$pagination}</div>
	
	    </div>
	  </div>
	</section>
	