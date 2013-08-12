<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php echo Popupwfb_TITLE; ?></h2>
    <?php
	$Popupwfb_session = get_option('Popupwfb_session');
	$Popupwfb_group = get_option('Popupwfb_group');

	if (isset($_POST['Popupwfb_form_submit']) && $_POST['Popupwfb_form_submit'] == 'yes')
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('Popupwfb_form_setting');
			
		$Popupwfb_session = stripslashes($_POST['Popupwfb_session']);	
		$Popupwfb_group = stripslashes($_POST['Popupwfb_group']);
		update_option('Popupwfb_session', $Popupwfb_session );
		update_option('Popupwfb_group', $Popupwfb_group );
		
		?>
		<div class="updated fade">
			<p><strong>Details successfully updated.</strong></p>
		</div>
		<?php
	}
	?>
	<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/popup-with-fancybox/pages/setting.js"></script>
	<h3>Popup setting</h3>
	<form name="Popupwfb_form_setting" method="post" action="#" onsubmit="return _Popupwfb_submit_setting()">
	
		<label for="tag-title">Popup group (Widget setting)</label>
		<select name="Popupwfb_group" id="Popupwfb_group">
		<option value=''></option>
		<?php
		$sSql = "SELECT distinct(Popupwfb_group) as Popupwfb_group FROM `".Popupwfb_Table."` order by Popupwfb_group";
		$myDistinctData = array();
		$thisselected = "";
		$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
		foreach ($myDistinctData as $DistinctData)
		{
			if($Popupwfb_group == strtoupper($DistinctData["Popupwfb_group"])) 
			{ 
				$thisselected = "selected='selected'" ; 
			}
			?><option value='<?php echo strtoupper($DistinctData["Popupwfb_group"]); ?>' <?php echo $thisselected; ?>><?php echo strtoupper($DistinctData["Popupwfb_group"]); ?></option><?php
			$thisselected = "";
		}
		?>
		</select>
		<p>Select popup group for widget option.</p>
	
		<label for="tag-title">Session option (Global setting)</label>
		<select name="Popupwfb_session" id="Popupwfb_session">
            <option value=''>Select</option>
			<option value='NO' <?php if($Popupwfb_session == 'NO') { echo 'selected' ; } ?>>NO</option>
            <option value='YES' <?php if($Popupwfb_session == 'YES') { echo 'selected' ; } ?>>YES</option>
          </select>
		<p>Select YES to show popup once per session, Meaning, popup never appear again if user navigate to another page.</p>
				
		<div style="height:10px;"></div>
		<input type="hidden" name="Popupwfb_form_submit" value="yes"/>
		<input name="Popupwfb_submit" id="Popupwfb_submit" class="button" value="Submit" type="submit" />
		<input name="publish" lang="publish" class="button" onclick="_Popupwfb_redirect()" value="Cancel" type="button" />
		<input name="Help" lang="publish" class="button" onclick="_Popupwfb_help()" value="Help" type="button" />
		<?php wp_nonce_field('Popupwfb_form_setting'); ?>
	</form>
  </div>
  <br /><p class="description"><?php echo Popupwfb_LINK; ?></p>
</div>
