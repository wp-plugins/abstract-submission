<div class="wrap"> 
	<div id="icon-edit-pages" class="icon32"><br /></div> 
<h2>View Abstract</h2>

<?

$abstract = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."submitted_abstracts where id=".$_GET['id']);

?>
<div class="metabox-holder" id="poststuff" style="width:800px">
<div id="post-body">
<div id="post-body-content">
<div class="postbox" id="epagepostcustom">
	<h3 class="hndle"><span>Abstract</span></h3>
	<div class="inside">
		<?=$abstract->html?>
    </div>
</div>
<div class="postbox" id="epagepostcustom">
	<h3 class="hndle"><span>Attachments</span></h3>
	<div class="inside">
		<div style="height: 30px; line-height:30px;"><a href="?abstracts_download_pdf_file=<?=$abstract->id?>"><input style="float:right;" type="button" value="Download" accesskey="p" tabindex="5" class="button-primary" name="publish"/></a><span style="width:650px;"><strong>abstract.pdf</strong> [~<?=number_format(strlen($abstract->html)/1024,2)?>kb]</span></div>
<? $attachments = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."abstracts_attachments where abstracts_id=".$_GET['id']); ?>
<? foreach($attachments as $attachment) { ?>
		<div style="height: 30px; line-height:30px;"><a href="?abstracts_download_file=<?=$attachment->id?>"><input style="float:right;" type="button" value="Download" accesskey="p" tabindex="5" class="button-primary" name="publish"/></a><span style="width:650px;"><strong><?=$attachment->filename?></strong> [<?=number_format(($attachment->filesize/1024),2)?>kb]</span></div>
<? } ?>
    </div>
</div>
</div>
</div>
</div>
</div>