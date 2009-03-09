<?

if($_POST) {

	function as_save_option($option) {
		if($_POST['options'][$option]) {
			if ( get_option($option) ) {
			    update_option($option, $_POST['options'][$option]);
			  } else {
			    add_option($option, $_POST['options'][$option]);
			  }
		}
	}
	
	foreach($_POST['options'] as $option=>$value) {
		as_save_option($option);
	}
?>	
	<div id="message" class="updated fade"><p><strong>Options saved.</strong></p></div>	
<?
}

?>

<div class="wrap"> 
	<div id="icon-options-general" class="icon32"><br /></div> 
<h2>Abstracts Options</h2> 

<script src="<? bloginfo('siteurl'); ?>/wp-content/plugins/abstract-submission/js/nicEdit.js" type="text/javascript"></script>

<form method="post" action="<?=$_SERVER['REQUEST_URI']?>"> 

<table class="form-table"> 
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_chars_count]">Abstract Chars Number</label></th> 
		<td><input name="options[abstracts_chars_count]" type="text" id="charscount" value="<?=get_option('abstracts_chars_count');?>" class="regular-text" /></td> 
	</tr> 
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_permitted_attachments]">Permitted Attachments</label></th> 
		<td><input name="options[abstracts_permitted_attachments]" type="text" id="charscount" value="<?=get_option('abstracts_permitted_attachments');?>" class="regular-text" /></td> 
	</tr>
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_maximum_attach_size]">Maximum Attach Size</label></th> 
		<td><input name="options[abstracts_maximum_attach_size]" type="text" id="charscount" value="<?=get_option('abstracts_maximum_attach_size');?>" class="regular-text" /></td> 
	</tr>
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_body_style]">Body Style (CSS)</label></th> 
		<td><textarea class="large-text code" cols="50" rows="10" name="options[abstracts_body_style]"><?=stripslashes(get_option('abstracts_body_style'));?></textarea></td> 
	</tr> 
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_title_style]">Title Style (CSS)</label></th> 
		<td><textarea class="large-text code" cols="50" rows="10" name="options[abstracts_title_style]"><?=stripslashes(get_option('abstracts_title_style'));?></textarea></td> 
	</tr>
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_authors_style]">Authors Style (CSS)</label></th> 
		<td><textarea class="large-text code" cols="50" rows="10" name="options[abstracts_authors_style]"><?=stripslashes(get_option('abstracts_authors_style'));?></textarea></td> 
	</tr> 
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_affiliation_style]">Author Affiliation Style (CSS)</label></th> 
		<td><textarea class="large-text code" cols="50" rows="10" name="options[abstracts_affiliation_style]"><?=stripslashes(get_option('abstracts_affiliation_style'));?></textarea></td> 
	</tr> 
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_text_style]">Text Style (CSS)</label></th> 
		<td><textarea class="large-text code" cols="50" rows="10" name="options[abstracts_text_style]"><?=stripslashes(get_option('abstracts_text_style'));?></textarea></td> 
	</tr> 
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_html_header]">Abstract HTML Header</label></th> 
		<td><textarea id="abstracts_html_header" class="large-text code" cols="50" rows="10" name="options[abstracts_html_header]"><?=stripslashes(get_option('abstracts_html_header'));?></textarea></td> 
	</tr>
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_redirect_page]">Redirect Page</label></th> 
		<td><input name="options[abstracts_redirect_page]" type="text" value="<?=get_option('abstracts_redirect_page');?>" class="regular-text" /></td> 
	</tr>
	<tr valign="top"> 
		<th scope="row"><label for="options[abstracts_mail_template]">Confirmation Mail Template</label><br/>Tags: <em>[AUTHOR], [ABSTRACT_TITLE], [BLOGTITLE]</em></th> 
		<td><textarea id="abstracts_mail_template" class="large-text code" cols="50" rows="10" name="options[abstracts_mail_template]"><?=stripslashes(get_option('abstracts_mail_template'));?></textarea></td> 
	</tr>
</table> 
 
<p class="submit"> 
<input type="submit" name="Submit" class="button-primary" value="Save options" /> 
</p> 
</form> 
<script type="text/javascript">
	new nicEditor({iconsPath : '<? bloginfo('siteurl'); ?>/wp-content/plugins/abstract-submission/images/nicEditorIcons.gif'}).panelInstance('abstracts_html_header');
	new nicEditor({iconsPath : '<? bloginfo('siteurl'); ?>/wp-content/plugins/abstract-submission/images/nicEditorIcons.gif'}).panelInstance('abstracts_mail_template');
</script> 
</div>