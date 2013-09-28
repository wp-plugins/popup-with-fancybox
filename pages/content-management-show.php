<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_Popupwfb_display']) && $_POST['frm_Popupwfb_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$Popupwfb_success = '';
	$Popupwfb_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".Popupwfb_Table."
		WHERE `Popupwfb_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('Popupwfb_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".Popupwfb_Table."`
					WHERE `Popupwfb_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$Popupwfb_success_msg = TRUE;
			$Popupwfb_success = __('Selected record was successfully deleted.', Popupwfb_UNIQUE_NAME);
		}
	}
	
	if ($Popupwfb_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $Popupwfb_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php echo Popupwfb_TITLE; ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=popup-with-fancybox&amp;ac=add">Add New</a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".Popupwfb_Table."` order by Popupwfb_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/popup-with-fancybox/pages/setting.js"></script>
		<form name="frm_Popupwfb_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="Popupwfb_group_item[]" /></th>
			<th scope="col">Id</th>
			<th scope="col">Title</th>
            <th scope="col">Width</th>
			<th scope="col">Timeout</th>
			<th scope="col">Group</th>
			<th scope="col">Status</th>
			<th scope="col">Expiration</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="Popupwfb_group_item[]" /></th>
			<th scope="col">Id</th>
			<th scope="col">Title</th>
            <th scope="col">Width</th>
			<th scope="col">Timeout</th>
			<th scope="col">Group</th>
			<th scope="col">Status</th>
			<th scope="col">Expiration</th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['Popupwfb_id']; ?>" name="Popupwfb_group_item[]"></td>
						<td><?php echo $data['Popupwfb_id']; ?></td>
						<td><?php echo stripslashes($data['Popupwfb_title']); ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=popup-with-fancybox&amp;ac=edit&amp;did=<?php echo $data['Popupwfb_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:_Popupwfb_delete('<?php echo $data['Popupwfb_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
						</div>
						</td>
						<td><?php echo $data['Popupwfb_width']; ?></td>
						<td><?php echo $data['Popupwfb_timeout']; ?></td>
						<td><?php echo $data['Popupwfb_group']; ?></td>
						<td><?php echo $data['Popupwfb_status']; ?></td>
						<td><?php echo substr($data['Popupwfb_expiration'],0,10); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="8" align="center">No records available.</td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('Popupwfb_form_show'); ?>
		<input type="hidden" name="frm_Popupwfb_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=popup-with-fancybox&amp;ac=add">Add New</a>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=popup-with-fancybox&amp;ac=set">Popup Setting</a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo Popupwfb_FAV; ?>">Help</a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3>Plugin configuration option</h3>
	<ol>
		<li>Drag and drop the widget (Display entire website).</li>
		<li>Add popup into specific  post or page using short code.</li>
		<li>Add directly in to the theme using PHP code.</li>
	</ol>
	<p class="description"><?php echo Popupwfb_LINK; ?></p>
	</div>
</div>