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
 
<div class="tablenav-pages"><span class="displaying-num">Sono visualizzati 1&#8211;20 su 27</span><span class='page-numbers current'>1</span> 
<a class='page-numbers' href='/wp-admin/edit-pages.php?pagenum=2'>2</a> 
<a class='next page-numbers' href='/wp-admin/edit-pages.php?pagenum=2'>&raquo;</a></div> 
 
</div> 

<div class="clear"></div> 

<table class="widefat page fixed" cellspacing="0"> 
  <thead> 
  <tr> 
	<th scope="col" id="title" class="manage-column column-title" style="">Titolo</th> 
	<th scope="col" id="author" class="manage-column column-author" style="">Autore</th> 
	<th scope="col" id="date" class="manage-column column-date" style="">Data</th> 
  </tr> 
  </thead> 
 
  <tfoot> 
  <tr> 
	<th scope="col"  class="manage-column column-title" style="">Titolo</th> 
	<th scope="col"  class="manage-column column-author" style="">Autore</th> 
	<th scope="col"  class="manage-column column-date" style="">Data</th> 
  </tr> 
  </tfoot> 
  
  <tbody> 
<?php

$abstracts = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."submitted_abstracts ORDER BY data DESC");

foreach($abstracts as $abstract) {
?>
  <tr class="alternate"> 
	<td><a href="admin.php?page=abstract-submission/abstracts-view-abstract.php&id=<?=$abstract->id?>"><?=$abstract->title?></a></td> 
	<td><?=$abstract->authors?></td> 
	<td><?=$abstract->data?></td> 
  </tr> 
<? } ?>
  </tbody> 
 </table>  
 
</div>