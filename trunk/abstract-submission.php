<?php
/*
Plugin Name: Abstract Submission
Plugin URI: 
Description: 
Version: 0.1
Author: Marco Piccardo
Author URI: http://www.xgear.info/
*/

/* Changelog - Release Notes
* v0.1
- First non-Public Version.
*/

// Admin Panel
global $wpdb;
add_action('admin_menu', 'abstracts_add_pages');
add_action('wp_head', 'abstracts_css');
add_action('init', 'abstracts_download_file');
add_action('init', 'abstracts_download_pdf_file');
register_activation_hook(__FILE__,'abstracts_install');
add_filter('the_content', 'abstracts_submission_page');

function abstracts_add_pages() {
    add_menu_page('Abstracts', 'Abstracts', 8, __FILE__, 'abstracts_view_page');
    add_submenu_page(__FILE__, 'Options', 'Options', 8, 'abstracts_options', 'abstracts_options_page');
}

function abstracts_view_page() {
	global $wpdb;
    include('abstracts-view.php');
}

function abstracts_options_page() {
	global $wpdb;
    include('abstracts-options.php');
}

function abstracts_css() {  
	echo '<link type="text/css" rel="stylesheet" href="'.get_bloginfo('wpurl').'/wp-content/plugins/abstract-submission/css/style.css" />' . "\n";  
} 

function abstracts_submission_page($content) {
	global $wpdb;
	ob_start();
    include('abstracts-submit.php');
	$html = ob_get_clean();
	$tags = array('[abstract-submission-form]','[abstract-submission-chars-count]','[abstract-submission-max-attach-size]');
	$subst = array($html,get_option('abstracts_chars_count'),get_option('abstracts_maximum_attach_size'));
	return str_replace($tags,$subst,$content);
}

function abstracts_download_file() {
	global $wpdb;
	if($_GET['abstracts_download_file']) {
		$file = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."abstracts_attachments where id=".$_GET['abstracts_download_file']);
		$content = rawurldecode($file->filecontent);

		header("Content-length: ".$file->filesize);
		header("Content-type: ".$file->filemeta);
		header("Content-Disposition: attachment; filename=".$file->filename);
		echo $content;
		exit(0);
	}
}

function abstracts_download_pdf_file() {
	global $wpdb;
	if($_GET['abstracts_download_pdf_file']) {
		$file = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."submitted_abstracts where id=".$_GET['abstracts_download_pdf_file']);
		$content = stripslashes($file->html);

		require_once("dompdf-0.5.1/dompdf_config.inc.php");
		
		$dompdf = new DOMPDF();
		$dompdf->load_html($content);
		$dompdf->set_paper('a4', 'vertical');
		$dompdf->render();
		$dompdf->stream('abstract.pdf');
		exit(0);
	}
}


function abstracts_install() {
   global $wpdb;
   require_once(ABSPATH.'wp-admin/includes/upgrade.php');      

   $table_name = $wpdb->prefix."submitted_abstracts";
   
      $sql = "CREATE TABLE ".$table_name." (
		  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		  title longblob,
		  authors longblob,
		  author_affiliation longblob,
		  text longblob,
		  presentation_mode varchar(200),
		  html longblob,
		  data date,
		  PRIMARY KEY  (id)
	  );";

      dbDelta($sql);

	$table_name = $wpdb->prefix."abstracts_attachments";

      $sql = "CREATE TABLE ".$table_name." (
	  	id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		abstracts_id int(11),
		filecontent longblob,
		filename varchar(255),
		filemeta varchar(255),
		filetype varchar(255),
		filesize varchar(255),
		PRIMARY KEY  (id)
	  );";

      dbDelta($sql);

	add_option("abstracts_chars_count", 5000);
	add_option("abstracts_maximum_attach_size", 512000);
	
}

?>