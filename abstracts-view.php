<?

if($_GET['delete']=='true') {
	$wpdb->show_errors();
	$wpdb->query("delete from ".$wpdb->prefix."submitted_abstracts where id=".$_GET['id']);	
	$wpdb->query("delete from ".$wpdb->prefix."abstracts_attachments where abstracts_id=".$_GET['id']);	
?>
		<div id="message" class="updated fade"><p><strong>Abstract #<?=$_GET['id']?> deleted.</strong></p></div>	
<? }

$per_page = 20;

$abs_current_page = ($_GET['pagenum']) ? $_GET['pagenum'] : 1;

$abstracts = $wpdb->get_results("SELECT SQL_CALC_FOUND_ROWS * FROM ".$wpdb->prefix."submitted_abstracts ORDER BY data DESC LIMIT ".(($abs_current_page-1)*$per_page).",".$per_page);

$abs_tot = $wpdb->get_var("SELECT FOUND_ROWS()");

if($abs_tot>20) {
	$abs_pages = ceil($abs_tot/20);
	$abs_page_start = 1;
	$abs_page_end = 20;
} else {
	$abs_page_start = ($abs_current_page-1)*$per_page;
	$abs_page_end = $abs_tot;
}

?>
<script>
	function abs_delete_confirm(id) {
		if(confirm('Do you really want to delete the abstract and all his attachments?')) {
			location.href = "admin.php?page=abstract-submission/abstract-submission.php&id="+id+"&delete=true";
		}
	}
</script>
<div class="wrap"> 
	<div id="icon-edit-pages" class="icon32"><br /></div> 
<h2>Submitted Abstracts</h2> 

<form id="posts-filter" action="" method="get"> 
<!--
<ul class="subsubsub"> 
	<li><a href='edit-pages.php' class="current">Totale <span class="count">(27)</span></a> |</li> 
	<li><a href='edit-pages.php?post_status=publish'>Pubblicate <span class="count">(20)</span></a> |</li> 
	<li><a href='edit-pages.php?post_status=draft'>Bozze <span class="count">(7)</span></a></li>
</ul> 
-->
<div class="tablenav"> 
 
<div class="tablenav-pages"><span class="displaying-num">Displaying <?=$abs_page_start?>&#8211;<?=$abs_page_end?> of <?=$abs_tot?></span>
<? if($abs_tot>$per_page) { ?>
<a class='prev page-numbers' href='/wp-admin/admin.php?page=abstract-submission/abstract-submission.php&pagenum=<?=($abs_current_page-1)?>'>&laquo;</a>
<? for($i=1; $i<=$abs_pages; $i++) { ?>
<? if($abs_current_page == $i) { ?>
	<span class='page-numbers current'><?=$i?></span> 
<? } else { ?>
<a class='page-numbers' href='/wp-admin/admin.php?page=abstract-submission/abstract-submission.php&pagenum=<?=$i?>'><?=$i?></a>
<? } ?>
<? } ?>
<a class='next page-numbers' href='/wp-admin/admin.php?page=abstract-submission/abstract-submission.php&pagenum=<?=$abs_pages?>'>&raquo;</a>
<? } ?>
</div> 
 
</div> 

<div class="clear"></div> 

<table class="widefat page fixed" cellspacing="0"> 
  <thead> 
  <tr> 
	<th scope="col" id="title" class="manage-column column-title" style="">Title</th> 
	<th scope="col" id="author" class="manage-column column-author" style="">Presenter</th> 
	<th scope="col" id="author" class="manage-column column-author" style="">Preference</th> 
	<th scope="col" id="date" class="manage-column column-date" style="">Date</th> 
	<th scope="col" id="date" class="manage-column column-date" style=""></th> 
  </tr> 
  </thead> 
 
  <tfoot> 
  <tr> 
	<th scope="col" id="title" class="manage-column column-title" style="">Title</th> 
	<th scope="col" id="author" class="manage-column column-author" style="">Presenter</th> 
	<th scope="col" id="author" class="manage-column column-author" style="">Preference</th> 
	<th scope="col" id="date" class="manage-column column-date" style="">Date</th> 
	<th scope="col" id="date" class="manage-column column-date" style=""></th> 
  </tr> 
  </tfoot> 
  
  <tbody> 
<?php

foreach($abstracts as $abstract) {

?>
  <tr class="alternate"> 
	<td><a href="admin.php?page=abstract-submission/abstracts-view-abstract.php&id=<?=$abstract->id?>"><?=$abstract->title?></a></td> 
	<td><a href="mailto:<?=$abstract->email?>"><?=$abstract->name?></a></td> 
	<td><?=$abstract->presentation_mode?></td> 
	<td><?=$abstract->data?></td> 
	<td><a href="javascript:abs_delete_confirm(<?=$abstract->id?>)">Delete</a></td> 
  </tr> 
<? } ?>
  </tbody> 
 </table>  
 
</div>