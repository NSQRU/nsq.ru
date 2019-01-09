<?
#################################################################
## PHP Pro Bid v6.10															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################


 
 
if ($layout['nb_want_ads'])
{
	$recent_wa_header = header4(MSG_RECENTLY_LISTED_WANTED_ADS . ' [ <span class="sell"><a href="' . process_link('wanted_ads') . '">' . MSG_VIEW_ALL . '</a></span> ]');
	$template->set('recent_wa_header', $recent_wa_header);

	$sql_select_recent_wa = $db->query("SELECT wanted_ad_id,zip_code, state, creation_in_progress, description, live_pm_processor, nb_clicks, owner_id, country, addl_category_id, keywords, category_id, start_time, name FROM " . DB_PREFIX . "wanted_ads
		FORCE INDEX (wa_mainpage) WHERE
		closed=0 AND active=1 AND deleted=0 AND creation_in_progress=0 " . $adult_cats_query . " ORDER BY 
		start_time DESC LIMIT 0," . $layout['nb_want_ads']);

	$template->set('sql_select_recent_wa', $sql_select_recent_wa);
}

if ($layout['r_hpfeat_nb'] && $setts['enable_reverse_auctions'])
{
	$featured_reverse_auctions_header = header1(MSG_FEATURED_REVERSE_AUCTIONS);
	$template->set('featured_reverse_auctions_header', $featured_reverse_auctions_header);

	$select_condition = "WHERE
		hpfeat=1 AND active=1 AND closed=0 AND creation_in_progress=0 AND deleted=0";

	$template->set('featured_ra_columns', min((floor($db->count_rows('reverse_auctions', $select_condition)/$layout['r_hpfeat_nb']) + 1), ceil($layout['r_hpfeat_max']/$layout['r_hpfeat_nb'])));

	$template->set('feat_fees', $fees);
	$template->set('feat_db', $db);

	$ra_details = $db->random_rows('reverse_auctions', 'reverse_id, name, budget_id, nb_bids, currency, bold, hl, start_time', $select_condition, $layout['r_hpfeat_max']);
	$template->set('ra_details', $ra_details);
}



































































	
	if ($nb_items)
	{
		$pagination = paginate($start, $limit, $nb_items, $page_url . '.php', $additional_vars . $order_link); //g
		$template->set('pagination', $pagination); 
		
		$sql_select_reverse = $db->query("SELECT a.reverse_id, a.start_time, a.name, a.nb_bids, a.hidden_bidding, a.currency, a.hpfeat, a.addl_category_id,
			a.end_time, a.closed, am.media_url, a.hpfeat, a.catfeat, a.bold, a.hl FROM " . DB_PREFIX . "reverse_auctions a 
			LEFT JOIN " . DB_PREFIX . "auction_media am ON a.reverse_id=am.reverse_id AND am.media_type=1 AND am.upload_in_progress=0 
			" . $where_query . "
			GROUP BY a.reverse_id ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit); //g
		
		(string) $browse_reverse_content = null; 
		while ($item_details = $db->fetch_array($sql_select_reverse))
		{
			$background = ($counter++%2) ? 'c1' : 'c5';
			
//			$background .= ($item_details['bold']) ? ' bold_item' : '';
//			$background .= ($item_details['hl']) ? ' hl_item' : '';
			
			$ra_link = process_link('reverse_details', array('name' => $item_details['name'], 'reverse_id' => $item_details['reverse_id']));
			$ra_image = (!empty($item_details['media_url'])) ? $item_details['media_url'] : 'themes/' . $setts['default_theme'] . '/noimg.svg';
			
			$browse_reverse_content .= ' <div class="col-sm-12 "> 
			
			
			 <div class="card bg-light mb-2 " > 
  <div class="card-header">  <a   href="' . $ra_link . '"><i class=" icon-tag"></i>&nbsp; ' . $item_details['bold'] . '</a>&nbsp; <i class=" icon-time"></i>&nbsp; ' . show_date($item_details['start_time']). '</div>
  <div class="card-body" > <a href="' . $ra_link . '"><img class="img-thumbnail grey rounded"  align="right"  hspace="11" vspace="5" src="thumbnail.php?pic=' . $ra_image . '&w=190&sq=Y&b=Y" border="0" alt="' . $item_details['name'] . '"></a>
    <h6 class="card-title"><a href="' . $ra_link . '"> &nbsp;<i class=" icon-pushpin"></i>&nbsp;' . $item_details['name'] . ' </a>	</h6>
    <p class="card-text">777777<small class="text-muted"><a href="' . $ra_link . '"> &nbsp;<i class=" icon-pushpin"></i>&nbsp;' . $item_details['currency'] . ' </a></small></p> <p class="card-text">6666 3<small class="text-muted">' . $item_details['hidden_bidding'] . '</small></p>
   
  </div>   

</div> </br></div>  '.'

 ';				
			
		 
 

		  		
 
		}
	}
	else 
	{
		$browse_reverse_content = '<tr><td colspan="6" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
	}
	$template->set('browse_reverse_content', $browse_reverse_content);










$template->change_path('themes/' . $setts['default_theme'] . '/templates/');

$template_output .= $template->process('mainpage.tpl.php');

$template->change_path('templates/');
?>