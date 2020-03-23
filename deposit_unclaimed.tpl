<div style='margin-left:15px; margin-right:15px;'>
	
	<div class='filter_section'>
	
		{$_LANG.filter}&nbsp;
		
		<input class='search_input' id='search_from_date' type='date' value='{$get_from_date}' style='width:128px;' onkeyup='searchDepositEnter("deposit-unclaimed",event)'>
		
		<select class='search_input' id='search_deposit-unclaimed_bank' name='deposit_bank' onchange='searchDeposit("deposit-unclaimed")' style='width:88px;'>
			<option value=''>{$_LANG.bank}</option>
			{foreach from=$payment_list key=$pay_id item=$pay_name}
				<option value='{$pay_id}' {if $get_deposit_bank==$pay_id}selected{/if}>{$pay_name}</option>
			{/foreach}
		</select>
	
	 &nbsp;
		<input type='button' value='{$_LANG.today}' onclick='$("#search_from_date").val("{$date_today}"); $("#search_to_date").val("{$date_today}");searchDeposit("deposit-unclaimed");'> &nbsp;
		<input type='button' value='{$_LANG.yesterday}' onclick='$("#search_from_date").val("{$date_yesterday}"); $("#search_to_date").val("{$date_yesterday}");searchDeposit("deposit-unclaimed");'> &nbsp;
		<input type='button' value='<' onclick='$("#search_from_date").val("{$get_from_date_minus_1_day}"); $("#search_to_date").val("{$get_from_date_minus_1_day}");searchDeposit("deposit-unclaimed");'> &nbsp;
		<input type='button' value='>' onclick='$("#search_from_date").val("{$get_from_date_plus_1_day}"); $("#search_to_date").val("{$get_from_date_plus_1_day}");searchDeposit("deposit-unclaimed");'> &nbsp;
		
		<input type='button' value='{$_LANG.clear}' onclick='$(".search_input").val("");searchDeposit("deposit");'>
		
	</div> 

</div>

<section class='col-lg-12 connectedSortable ui-sortable'>
    <!-- quick email widget -->
  <div class='box box-primary'>
    <div class='box-header'>
      <i class='fa fa-money'></i>
      <h3 class='box-title'>{$_LANG.view_deposit_unclaimed}</h3>
      <!-- tools box -->
      <!--<div class='pull-right box-tools'>
        <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></button>
      </div>-->
      <!-- /. tools -->
    </div>
    <div class='box-body' style=''>

		<form method='post' action='{$WEBSITE_HOME_BACKEND}/deposit-unclaimed/?from_date={$from_date}'>
		<div style='width:100%; overflow-x:scroll'>
		<table class='table table-bordered table-hover'>
		<tr>
			<th class='center' style='width:98px;'>{$_LANG.reference}</th>
			<th class='center' style='width:98px;'>{$_LANG.date}</th>
			<th class='center' style='width:150px;'>{$_LANG.bank}</th>
			<th class='center' style='width:150px;'>{$_LANG.amount}</th>
			<th class='center'>{$_LANG.linked_deposit}</th>
			<th class='center'>{$_LANG.status}</th>
			<th class='center'>{$_LANG.action}</th>
		</tr>
		
		{foreach from=$deposit_data item=$row}
			
			<tr style='
				{if $row.deposit_status=="pending"}
					background:#FCF288;
				{elseif $row.deposit_status=="approved"}
					background:#DCFCA4;
				{elseif $row.deposit_status=="rejected"}
					background:#EFEFEF; opacity:0.5;
				{else} 
					background:#FCF288";
				{/if}
				'>
				<td class='center'>{$row.d_id_formatted}</td>
				<td class='center'>{$row.deposit_date}</td>
				<td class='center'>
					
					{if $row.image_exist==1}
						<img src='{$WEBSITE_HOME_BACKEND}/images/banks/{$row.pay_name}.png' style='width:88px;'>
					{else}
						{$row.pay_name}
					{/if}
				
				</td>
				<td class='center'>
				
				{$CURRENCY}{$row.deposit_amount_formatted}
				
				{if $row.deposit_status!="rejected" && !$row.link_deposit}
					
					&nbsp;
					
					<input type='button' value='+D' onclick='
						$("#close_reload_frame_button").click();
						$("#button_deposit_add").click();
						$("#add_deposit_username").val("");
						checkUserVerification("deposit");
						checkPromoUsage("deposit");
						$(".search_user_name").val("");
						$("#add_deposit_deposit_amount").val("{$row.deposit_amount}");
						$("#add_deposit_uc_id").val("{$row.d_id}");
						$("#add_deposit_bank_select_{$row.deposit_bank}").click()
						$("#add_deposit_deposit_day").val("{$row.deposit_date_day}");
						$("#add_deposit_deposit_month").val("{$row.deposit_date_month}");
						$("#add_deposit_deposit_year").val("{$row.deposit_date_year}");
					'>
					
				{/if}
				
				</td>
				<td class='center'>
				
				{if $row.link_deposit}
				
					<a href='javascript:void(0);' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/deposit/{$row.link_deposit.d_id}/?lite=1")'>{$row.link_deposit.d_id_formatted}</a> <div style='margin-top:0px;'><a href='javascript:void()' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/user/{$row.link_deposit.deposit_user_id}/?lite=1")'>{$row.link_deposit.user_name}</a></div>
					
				{/if}
				
				</td>
				<td class='center'>
					
					{$row.deposit_status_formatted}
					
					<input type='hidden' name='d_id[]' value='$d_id'>
					
					<select id='uc_deposit_status_{$d_id}' name='deposit_status[]' style='display:none;'>
					
						{foreach from=$status_array key=$status_key item=$status_value}
							<option value='{$status_key}' {if $row.deposit_status==$status_key}selected{/if}>{$status_value}</option>
						{/foreach}
					
					</select>
					
					<input type='hidden' name='action' value='update_deposit_unclaimed'>
					
					{if $row.deposit_status=="pending" && !$row.link_deposit}
						
						&nbsp;
						
						<input type='submit' value='✗' onclick='$("#uc_deposit_status_{$row.d_id}").val("rejected");'>
						
					{/if}
					
				</td>
				<td>
					
				</td>
			</tr>
		
		{/foreach}
		
		</table>
		</div>
		</form>
		
		
		    </div>
		  </div>
		</section>
		
		<br>
		
		
		<section class='col-lg-12 connectedSortable ui-sortable'>
		    <!-- quick email widget -->
		  <div class='box box-primary'>
		    <div class='box-header'>
		      <i class='fa fa-money'></i>
		      <h3 class='box-title'>{$_LANG.add_unclaimed_depo} ({$_LANG.multiple})</h3>
		      <!-- tools box -->
		      <!--<div class='pull-right box-tools'>
		        <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></button>
		      </div>-->
		      <!-- /. tools -->
		    </div>
		    <div class='box-body' style=''>
		
		<form method='post' action='{$WEBSITE_HOME_BACKEND}/deposit-unclaimed/?from_date={$from_date}'>
		<div style='width:100%; overflow-x:scroll'>
		<table class='table table-bordered table-hover'>
		<tr>
			<th class='center' style='width:38px;'>#</th>
			<th class='center' style='width:158px;'>{$_LANG.date}</th>
			<th class='center' style='width:150px;'>{$_LANG.bank}</th>
			<th class='center' style='width:150px;'>{$_LANG.amount}</th>
			<th class='center'></th>
		</tr>
		
		{for $count=1 to 5}
			
			<tr>
				<td class='center'>{$count}</td>
				<td style='width:158px;'>
				<div style='color:red; font-weight:bold; vertical-align:middle; text-align:center;'>
				
					{$get_from_date}
				
				</div>
				</td>
				<td style='display:none;'>
					
					<select name='deposit_day[]' required>
					
						{foreach from=$day_array item=$day}
						
							<option value='{$day}' {if $get_from_date_day==$day}selected{/if}>{$day}</option>
							
						{/foreach}
					
					</select> 
					
					<select name='deposit_month[]' required>
					
						{foreach from=$month_array item=$month}
						
							<option value='{$month}' {if $get_from_date_month==$month}selected{/if}>{$month}</option>
							
						{/foreach}
					
					</select> 
					
					<select name='deposit_year[]' required>
		
						{foreach from=$year_array item=$year}
							
							<option value='{$year}' {if $get_from_date_month==$year}selected{/if}>{$year}</option>
							
						{/foreach}			
					
					</select>
					
					<select name='deposit_hour[]' style='display:none;'>
					
						{foreach from=$hour_array item=$hour}
							
							<option value='{$hour}' {if $current_hour==$hour}selected{/if}>{$hour}</option>
							
						{/foreach}	
								
					</select>
					
					<select name='deposit_min[]' style='display:none;'>
					
						{foreach from=$minute_array item=$minute}
							
							<option value='{$minute}' {if $current_minute==$minute}selected{/if}>{$minute}</option>
							
						{/foreach}
					
					</select>
				</td>
				<td class='center'>
				
					<select name='deposit_bank[]'>
				
					{foreach from=$payment_list key=$pay_id item=$pay_name}
						<option value='{$pay_id}'>{$pay_name}</option>
					{/foreach}
				
					</select>
				
				</td>
				<td class='center'>
					<input name='deposit_amount[]' type='number' step='0.01' min='' value='0' style='width:58px; text-align:center;' onclick='this.select()'>
				</td>
				<td></td>
			</tr>
		
		{/for}
		
		
		<tr>
			<td colspan='4' class='center'>
				
				<input type='hidden' name='action' value='add_unclaimed_deposit'>
				<input type='submit' value='{$_LANG.add_unclaimed_depo}'>
			
			</td>
			<td></td>
		</tr>
		</table>
		</div>
		</form>


    </div>
  </div>
</section>

</div>