
<!-- right col (We are only adding the ID to make the widgets sortable)-->
<section class='col-lg-6 connectedSortable ui-sortable'>
    <!-- quick email widget -->
  <div class='box box-primary'>
    <div class='box-header'>
      <i class='fa fa-user'></i>
      <h3 class='box-title'>{$_LANG.transfer_details}</h3>
      <!-- tools box -->
      <!--<div class='pull-right box-tools'>
        <button class='btn btn-info btn-sm' data-widget='remove' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></button>
      </div>-->
      <!-- /. tools -->
    </div>
    <div class='box-body' style=''>
    
    <form method='post' action='{$WEBSITE_HOME_BACKEND}/transfer/{$row.d_id}/'>
		<table class='table table-hover table-bordered'>
		<tr>
			<th colspan='2'>{$_LANG.update_status}</th>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.status}</td>
			<td>
				
				{if $row.deposit_is_complete==1}
				
					{$_LANG.{$row.deposit_status}}
				
				{else}
				
					<select name='deposit_status' style='width:168px;' onchange='$("#transfer_view_update_1").click();'>
				
					{foreach from=$pn_status_array key=$pn_key item=$pn_value}
						<option value='{$pn_key}' {if $row.deposit_status==$pn_key}selected{/if}>{$pn_value}</option>
					{/foreach}
					
					</select>
					
				{/if}
			</td>
		</tr>
		<tr>
			<td>{$_LANG.completed}</td>
			<td>
				
				{if $row.deposit_status=="pending"}
				
					{$_LANG.pending}
				
				{else}
				
					<select name='deposit_is_complete' style='width:168px;' onchange='$("#transfer_view_update_1").click();'>
						<option value='1' {if $row.deposit_is_complete==1}selected{/if}>{$_LANG.yes}</option>				
						<option value='0' {if $row.deposit_is_complete==0}selected{/if}>{$_LANG.no}</option>				
					</select>
				
				{/if}
				
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type='hidden' name='action' value='update_transfer'>
				<input type='hidden' name='d_id' value='{$row.d_id}'>
				<input type='hidden' name='user_id' value='{$row.user_id}'>
				<input type='submit' value='Update' id='transfer_view_update_1'>
			</td>
		</tr>
		</table>
		
		</form>
		
		<br>
		    
		<table class='table table-hover table-bordered'>
		<tr>
			<th colspan='2'>{$_LANG.transfer_details}</th>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.transfer_id}</td>
			<td>{$row.d_id_formatted}</td>
		</tr>
		<tr>
			<td>{$_LANG.transfer_date}</td>
			<td>{$row.deposit_add_time_formatted}</td>
		</tr>
		<tr>
			<td>{$_LANG.game_from}</td>
			<td><font color='red'><b>{$row.goods_name_long_from}</b></font></td>
		</tr>
		<tr>
			<td>{$_LANG.game_to}</td>
			<td><font color='red'><b>{$row.goods_name_long_to}</b></font></td>
		</tr>
		<tr style='background:#FCF288;'>
			<td>{$_LANG.transfer_amount}</td>
			<td>{$CURRENCY} {$row.deposit_amount_formatted}
			
			<div id='top_pending_transfer_success_textarea_{$row.d_id}'>
				
				<textarea id='pending_transfer_success_textarea_{$row.d_id}' class='pending_transfer_success_textarea_{$row.d_id}' onclick='this.select(); document.execCommand("copy"); $(this).fadeOut(200).fadeIn(200);' style='background:#00a65a; color:white; line-height: 8px; margin-top:2px; width:108px; max-width: 100%; height: 50px; margin-top: 2px; font-size:8px; $textareastyle'>{$row.success_message}</textarea>
				
			</div>
			
			{*
			 * TRANFER FROM
			 *}
			
			{if $row.goods_code!="main_wallet"}
				
				{$row.goods_name_long_from}
				
				{if $row.ug_u!=""}
					<div style='margin-top:0px; font-size:12px; opacity:0.8;'><i>ID: {$row.ug_u}</i></div>
				{/if}
					
				<div style=''><textarea style='padding:5px; line-height: 28px; margin-top:0px; width:88px; max-width: 100%; height: 38px; margin-top: 2px; font-size:28px; overflow:hidden;' onclick='this.select(); document.execCommand("copy"); $(this).fadeOut(200).fadeIn(200); $("#set_score_{$row.d_id}").removeAttr("disabled"); $("#set_score_{$row.d_id}").click();'>-{$row.deposit_amount}</textarea></div>
				
				{if $row.from_shortcut_url_balance!=""}
				
					<div style='float:left; margin-right:4px; margin-bottom:4px;'><button id='set_score_{$row.d_id}' type='button' class=' btn btn-block btn-default btn-xs' onclick='window.open("{$row.from_shortcut_url_balance}","_blank"); $("#pending_transfer_copy_amount_to_{$row.d_id}").removeAttr("disabled"); $("#set_score_tick_to_{$row.d_id}").fadeIn(0); $("#turbo_set_score_tick_to_{$row.d_id}").fadeIn(0);' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.set_score} </button></div>
					
				{/if}
				
				{if $row.from_shortcut_url_log_score!=""}
				
					<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.from_shortcut_url_log_score}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.score_log}</button></div>
							
				{/if}
				
				{if $row.from_shortcut_url_log_game!=""}
				
					{if $row.goods_code=="main_wallet"}
				
						<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/win-lose-logs/?user_name={$row.user_name2}&lite=1")' style='width:auto; font-size:10px;'>{$_LANG.game_log}</button></div>
				
					{else}
				
						<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.from_shortcut_url_log_game}","_blank"); editPendingDepositIsViewGameLog("","{$row.d_id}");' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.game_log}</button></div>
				
					{/if}
				
				{/if}
				
				{if $row.from_shortcut_url_edit!=""}
					<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.from_shortcut_url_edit}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.edit}</button></div>
				
				{/if}
				
				<div style='clear:both;'></div>
			
			{/if}
			
			{*
			 * TRANSFER TO
			 *}
			
			{if $row.goods_code_to!="main_wallet"}
			
				{$row.goods_name_long_to}
				
				{if $row.ug_u_to!=""}
					<div style='margin-top:0px; font-size:12px; opacity:0.8;'><i>ID: {$row.ug_u_to}</i></div>
				{/if}
					
				<div style=''><textarea style='padding:5px; line-height: 28px; margin-top:0px; width:88px; max-width: 100%; height: 38px; margin-top: 2px; font-size:28px; overflow:hidden;' onclick='this.select(); document.execCommand("copy"); $(this).fadeOut(200).fadeIn(200); $("#set_score_{$d_id}").removeAttr("disabled"); $("#set_score_{$d_id}").click();'>{$row.deposit_amount}</textarea></div>
				
				{if $row.to_shortcut_url_balance!=""}
					
					<div style='float:left; margin-right:4px; margin-bottom:4px;'><button id='set_score_{$row.d_id}' type='button' class=' btn btn-block btn-default btn-xs' onclick='window.open("$to_shortcut_url_balance","_blank"); $("#pending_transfer_copy_amount_to_{$d_id}").removeAttr("disabled"); $("#set_score_tick_to_{$d_id}").fadeIn(0); $("#turbo_set_score_tick_to_{$d_id}").fadeIn(0);' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.set_score}</button></div>
				{/if}
				
				{if $row.to_shortcut_url_log_score!=""}
					<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.to_shortcut_url_log_score}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.score_log}</button></div>
				{/if}
				
				{if $row.to_shortcut_url_log_game!=""}
					
					{if $row.goods_code=="main_wallet"}
						<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/win-lose-logs/?user_name={$user_name2}&lite=1")' style='width:auto; font-size:10px;'>{$_LANG.game_log}</button></div>
					{else}
						<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.to_shortcut_url_log_game}","_blank"); editPendingDepositIsViewGameLog("","$d_id");' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.game_log}</button></div>
					{/if}
					
				{/if}
				
				{if $row.to_shortcut_url_edit!=""}
					<div style='float:left; margin-right:4px; margin-bottom:4px;'><button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.to_shortcut_url_edit}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.edit}</button></div>
				{/if}
				
				<div style='clear:both;'></div>
				
			{/if}
			
			</td>
		</tr>
		</table>
		
		<br>
		
		<table class='table table-hover table-bordered'>
		<tr>
			<th colspan='2'>{$_LANG.action_details}</th>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.add}</td>
			<td>
				
				{$row.employee_short_name_add}
			
				{if $row.deposit_add_time>0}
					<div style="float:right;">{$row.deposit_add_time_formatted}</div>
				{/if}
				
			</td>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.approve}</td>
			<td>
			
				{$row.employee_short_name_approve}
				
				{if $row.deposit_approve_time>0}
					<div style="float:right;">{$row.deposit_approve_time_formatted}</div>
				{/if}
			
			</td>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.reject}</td>
			<td>
				
				{$row.employee_short_name_reject}
				
				{if $row.deposit_reject_time>0}
					<div style="float:right;">{$row.deposit_reject_time_formatted}</div>
				{/if}
			
			</td>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.complete}</td>
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
		<section class='col-lg-6 connectedSortable ui-sortable'>
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
		
		<form method='post' action='{$WEBSITE_HOME_BACKEND}/transfer/{$d_id}/'>
		<table class='table table-hover table-bordered'>
		<tr>
			<th colspan='2'>{$_LANG.user_details}</th>
		</tr>
		<tr style='display:none;'>
			<td style='width:128px;'>{$_LANG.user_id}</td>
			<td>{$row.user_id}</td>
		</tr>
		<tr>
			<td style='width:128px;'>{$_LANG.username}</td>
			<td><a href='javascript:void();' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/user/{$user_id}/?lite=1")'>{$row.user_name}</a></td>
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
		
		
		
