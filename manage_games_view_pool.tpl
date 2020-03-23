
	<div style='margin-left:15px; margin-right:15px;'>
	
	<table class='table table-hover table-bordered'>
	<tr>
		<td colspan='10'>
			Total: {$count}
			<div style='float:right;'>Last Pool Username: {$last_pool_id}</div>
		</td>
	</tr>
	<tr>
		<td style='width:38px;'>#</td>
		<td style='width:188px;'>Username</td>
		<td style='width:128px;'>Password</td>
		<td style='width:168px;'>Add Account ({$count_verify})</td>
		<td style='width:168px;'>Change Password ({$count_change_pass})</td>
		<td>Change Settings ({$count_change_setting})</td>
	</tr>
	
	{foreach from=$pool_data item=$row}
		<tr>
			<td>{$row.count}</td>
			<td>
				<div style='float:left; margin-right:18px;'>{$row.ug_u}</div>
				<div style='float:left;'><form method='post'><input type='hidden' name='ug_id' value='{$row.ug_id}'><input type='hidden' name='action' value='delete_account'><input type='submit' value='{$_LANG.delete}'></form></div></td>
			<td>{$row.ug_p}</td>
			<td>
				{if $row.ug_is_verify==1}
					<img src='{$WEBSITE_HOME_BACKEND}/images/r.png' style='width:16px;'>
				{/if}
			</td>
			<td>
				{if $ug_is_change_password==1}
					<img src='{$WEBSITE_HOME_BACKEND}/images/r.png' style='width:16px;'>
				{/if}
			</td>
			<td>
				{if $ug_is_change_setting==1}
					<img src='{$WEBSITE_HOME_BACKEND}/images/r.png' style='width:16px;'>
				{/if}
			</td>
		</tr>
	{/foreach}
	
	</table>
	
	</div>