<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

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
	?><div class="error fade"><p><strong>Oops, selected details doesn't exist.</strong></p></div><?php
}
else
{
	$Popupwfb_errors = array();
	$Popupwfb_success = '';
	$Popupwfb_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".Popupwfb_Table."`
		WHERE `Popupwfb_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'Popupwfb_width' => $data['Popupwfb_width'],
		'Popupwfb_timeout' => $data['Popupwfb_timeout'],
		'Popupwfb_title' => $data['Popupwfb_title'],
		'Popupwfb_content' => $data['Popupwfb_content'],
		'Popupwfb_group' => $data['Popupwfb_group'],
		'Popupwfb_status' => $data['Popupwfb_status'],
		'Popupwfb_expiration' => $data['Popupwfb_expiration'],
		'Popupwfb_extra1' => $data['Popupwfb_extra1'],
		'Popupwfb_id' => $data['Popupwfb_id']
	);
}
// Form submitted, check the data
if (isset($_POST['Popupwfb_form_submit']) && $_POST['Popupwfb_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('Popupwfb_form_edit');
	
	$form['Popupwfb_width'] = isset($_POST['Popupwfb_width']) ? $_POST['Popupwfb_width'] : '';
	if ($form['Popupwfb_width'] == '')
	{
		$Popupwfb_errors[] = __('Please enter the popup window width, only number.', Popupwfb_UNIQUE_NAME);
		$Popupwfb_error_found = TRUE;
	}
	
	$form['Popupwfb_timeout'] = isset($_POST['Popupwfb_timeout']) ? $_POST['Popupwfb_timeout'] : '';
	if ($form['Popupwfb_timeout'] == '')
	{
		$Popupwfb_errors[] = __('Please enter popup timeout, only number.', Popupwfb_UNIQUE_NAME);
		$Popupwfb_error_found = TRUE;
	}

	$form['Popupwfb_title'] = isset($_POST['Popupwfb_title']) ? $_POST['Popupwfb_title'] : '';
	if ($form['Popupwfb_title'] == '')
	{
		$Popupwfb_errors[] = __('Please enter popup title.', Popupwfb_UNIQUE_NAME);
		$Popupwfb_error_found = TRUE;
	}
	
	$form['Popupwfb_content'] = isset($_POST['Popupwfb_content']) ? $_POST['Popupwfb_content'] : '';
	if ($form['Popupwfb_content'] == '')
	{
		$Popupwfb_errors[] = __('Please enter popup message.', Popupwfb_UNIQUE_NAME);
		$Popupwfb_error_found = TRUE;
	}
	
	$form['Popupwfb_group'] = isset($_POST['Popupwfb_group']) ? $_POST['Popupwfb_group'] : '';
	if ($form['Popupwfb_group'] == '')
	{
		$Popupwfb_errors[] = __('Please select available group for your popup message.', Popupwfb_UNIQUE_NAME);
		$Popupwfb_error_found = TRUE;
	}
	
	$form['Popupwfb_status'] = isset($_POST['Popupwfb_status']) ? $_POST['Popupwfb_status'] : '';
	if ($form['Popupwfb_status'] == '')
	{
		$Popupwfb_errors[] = __('Please select popup status.', Popupwfb_UNIQUE_NAME);
		$Popupwfb_error_found = TRUE;
	}

	//	No errors found, we can add this Group to the table
	if ($Popupwfb_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".Popupwfb_Table."`
				SET `Popupwfb_width` = %s,
				`Popupwfb_timeout` = %s,
				`Popupwfb_title` = %s,
				`Popupwfb_content` = %s,
				`Popupwfb_group` = %s,
				`Popupwfb_status` = %s
				WHERE Popupwfb_id = %d
				LIMIT 1",
				array($form['Popupwfb_width'], $form['Popupwfb_timeout'], $form['Popupwfb_title'], $form['Popupwfb_content'], $form['Popupwfb_group'], $form['Popupwfb_status'], $did)
			);
		$wpdb->query($sSql);
		
		$Popupwfb_success = 'Details was successfully updated.';
	}
}

if ($Popupwfb_error_found == TRUE && isset($Popupwfb_errors[0]) == TRUE)
{
?>
  <div class="error fade">
    <p><strong><?php echo $Popupwfb_errors[0]; ?></strong></p>
  </div>
  <?php
}
if ($Popupwfb_error_found == FALSE && strlen($Popupwfb_success) > 0)
{
?>
  <div class="updated fade">
    <p><strong><?php echo $Popupwfb_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=popup-with-fancybox">Click here</a> to view the details</strong></p>
  </div>
  <?php
}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/popup-with-fancybox/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo Popupwfb_TITLE; ?></h2>
	<form name="Popupwfb_form" method="post" action="#" onsubmit="return _Popupwfb_submit()"  >
      <h3>Update details</h3>
	  
	    <label for="tag-a">Popup width</label>
		<input name="Popupwfb_width" type="text" id="Popupwfb_width" value="<?php echo $form['Popupwfb_width']; ?>" size="20" maxlength="4" />
		<p>Enter your popup window width. (Ex: 500)</p>
		
		<label for="tag-a">Popup timeout</label>
		<input name="Popupwfb_timeout" type="text" id="Popupwfb_timeout" value="<?php echo $form['Popupwfb_timeout']; ?>" size="20" maxlength="5" />
		<p>Enter your popup window timeout in millisecond. (Ex: 3000)</p>
		
		<label for="tag-a">Popup title</label>
		<input name="Popupwfb_title" type="text" id="Popupwfb_title" value="<?php echo esc_html(stripslashes($form['Popupwfb_title'])); ?>" size="50" maxlength="250" />
		<p>Enter your popup title.</p>
	  
	  	<label for="tag-a">Popup message</label>
		<?php wp_editor(stripslashes($form['Popupwfb_content']), "Popupwfb_content"); ?>
		<p>Enter your popup message.</p>
		
		<label for="tag-a">Popup display</label>
		<select name="Popupwfb_status" id="Popupwfb_status">
			<option value='YES' <?php if($form['Popupwfb_status'] == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
			<option value='NO' <?php if($form['Popupwfb_status'] == 'NO') { echo "selected='selected'" ; } ?>>No</option>
		</select>
		<p>Please select your popup display status. (Select NO if you want to hide the popup in front end)</p>
		
		<label for="tag-a">Popup group</label>
	    <select name="Popupwfb_group" id="Popupwfb_group">
		<option value=''>Select</option>
		<?php
		$sSql = "SELECT distinct(Popupwfb_group) as Popupwfb_group FROM `".Popupwfb_Table."` order by Popupwfb_group";
		$myDistinctData = array();
		$arrDistinctDatas = array();
		$thisselected = "";
		$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
		$i = 1;
		foreach ($myDistinctData as $DistinctData)
		{
			$arrDistinctData[$i]["Popupwfb_group"] = strtoupper($DistinctData['Popupwfb_group']);
			$i = $i+1;
		}
		for($j=$i; $j<$i+10; $j++)
		{
			$arrDistinctData[$j]["Popupwfb_group"] = "GROUP" . $j;
		}
		$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
		foreach ($arrDistinctDatas as $arrDistinct)
		{
			if(strtoupper($form['Popupwfb_group']) == strtoupper($arrDistinct["Popupwfb_group"])) 
			{ 
				$thisselected = "selected='selected'" ; 
			}
			?><option value='<?php echo strtoupper($arrDistinct["Popupwfb_group"]); ?>' <?php echo $thisselected; ?>><?php echo strtoupper($arrDistinct["Popupwfb_group"]); ?></option><?php
			$thisselected = "";
		}
		?>
		</select>
		<p>Please select available group for your popup message.</p>
	  
      <input name="Popupwfb_id" id="Popupwfb_id" type="hidden" value="<?php echo $form['Popupwfb_id']; ?>">
      <input type="hidden" name="Popupwfb_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Update Details" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_Popupwfb_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="_Popupwfb_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('Popupwfb_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo Popupwfb_LINK; ?></p>
</div>