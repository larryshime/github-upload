<section class='col-lg-12 connectedSortable ui-sortable'>
    <!-- quick email widget -->
    <div class='box box-primary'>
        <div class='box-header'>
            <i class='fa fa-money'></i>
            <h3 class='box-title'>{$_LANG.view_transfers}</h3>
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
                        <th class='center'>{$_LANG.game_from}</th>
                        <th class='center'>{$_LANG.game_to}</th>
                        <th class='center'>{$_LANG.amount}</th>
                        <th class='center'>{$_LANG.admin}</th>
                    </tr>

                    {foreach from=$transfer_data item=$row}

                    <tr style='{if $row.deposit_status=="rejected"} background:#EFEFEF; opacity:0.5; {/if} background: #fcf092;'>
                        <td class='center'>{$row.count}</td>
                        <td class='center'><a href='javascript:void();' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/transfer/{$row.d_id}/?lite=1")'>{$row.d_id_formatted}</a></td>
                        <td class='center'>{$row.datetime_formatted}</td>
                        <td class='center'>

                            <a href='javascript:void();' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}/user/{$deposit_user_id}/?lite=1")'>{$row.user_name}</a> {if $row.user_name2}
                            <div>({$row.user_name2})</div>
                            {/if} {if $row.is_blacklist==1}
                            <div class='label label-danger'>Blacklisted</div>
                            {/if}

                        </td>
                        <td class='center'>

                            {$row.goods_name_long_from}

                            <div style='margin-top:0; font-size:12px; opacity:0.8;'><i>{$row.deposit_ug_u}</i></div>

                            {if $row.shortcut_url_log_game_from!=""}
                            
	                            {if $row.goods_code_from=="main_wallet"}
	                            
		                            <center>
		                                <div style=''>
		                                    <button type='button' class='btn btn-block btn-default btn-xs' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}//win-lose-logs/?user_name={$user_name2}&lite=1")' style='width:auto; font-size:10px;'>{$_LANG.game_log}</button>
		                                </div>
		                            </center>
	                            
	                            {else}
	                            
		                            <center>
		                                <div style=''>
		                                    <button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_log_game_from}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.game_log}</button>
		                                </div>
		                            </center>
		                            
	                            {/if}
                            
                            {/if}
                            
                            {if $row.shortcut_url_log_score_from!=""}
	
	                            <center>
	                                <div style='margin-top:5px;'>
	                                    <button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_log_score_from}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.score_log}</button>
	                                </div>
	                            </center>
                            
                            {/if}

                        </td>
                        <td class='center'>

                            {$row.goods_name_long_to}

                            <div style='margin-top:0; font-size:12px; opacity:0.8;'><i>{$row.deposit_ug_u_to}</i></div>

                            {if $row.shortcut_url_log_game_to!=""}
                            
	                            {if $row.goods_code_to=="main_wallet"}
	                           
		                            <center>
		                                <div style=''>
		                                    <button type='button' class='btn btn-block btn-default btn-xs' onclick='fastSectionDisplay("{$WEBSITE_HOME_BACKEND}//win-lose-logs/?user_name={$row.user_name2}&lite=1")' style='width:auto; font-size:10px;'>{$_LANG.game_log}</button>
		                                </div>
		                            </center>
	                            
	                            {else}
	
		                            <center>
		                                <div style=''>
		                                    <button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_log_game_to}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.game_log}</button>
		                                </div>
		                            </center>
		                            
	                            {/if}
                            {/if}
                            
                            {if $row.shortcut_url_log_score_to!=""}

	                            <center>
	                                <div style='margin-top:5px;'>
	                                    <button type='button' class='btn btn-block btn-default btn-xs' onclick='window.open("{$row.shortcut_url_log_score_to}","_blank")' style='width:auto; font-size:10px; padding: 1px 3px;'>{$_LANG.score_log}</button>
	                                </div>
	                            </center>

                            {/if}

                        </td>
                        <td class='center'>{$row.deposit_amount_formatted}</td>
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