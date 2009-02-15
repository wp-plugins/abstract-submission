<? if($_POST) { 

require_once("dompdf-0.5.1/dompdf_config.inc.php");
if ( isset( $_POST["abs_text"] ) ) {

  if ( get_magic_quotes_gpc() )
    $_POST["abs_text"] = stripslashes($_POST["abs_text"]);

  //$old_limit = ini_set("memory_limit", "16M");
  
  $abs_title = ucfirst($_POST["abs_title"]);
  if(count($_POST["abs_author_lastname"])>1) {
	  foreach($_POST["abs_author_lastname"] as $key=>$author) {
	  	$author = ucfirst($_POST["abs_author_firstname"][$key])." ".ucfirst($_POST["abs_author_lastname"][$key]);
	  	if($_POST["abs_presenter"][$key]==1) {
		  	$abs_authors[] = "<u>".$author."</u> (".($key+1).")";
	  	} else {
		  	$abs_authors[] = $author." (".($key+1).")";
	  	}
	  }
	  $abs_authors = implode(', ',$abs_authors);
  } else {
 	$author = ucfirst($_POST["abs_author_firstname"][0])." ".ucfirst($_POST["abs_author_lastname"][0]);
  	if($_POST["abs_presenter"][0]==1) {
	  	$abs_authors = "<u>".$author."</u>";
  	} else {
	  	$abs_authors = $author;
  	}
  }

  if(count($_POST["abs_author_lastname"])>1) {
	  foreach($_POST["abs_author_lastname"] as $key=>$author) {
	  	$abs_affiliations[] = "(".($key+1).") ".ucfirst($_POST["abs_affiliation"][$key]);
	  }
	  $abs_affiliations = implode(', ',$abs_affiliations);
  } else {
	 $abs_affiliations = ucfirst($_POST["abs_affiliation"][0]);
  }
  
  $html = '
	<html>
	<head>
  	<style>
	.body {
		'.stripslashes(get_option('abstracts_body_style')).'
	}
	.title {
		'.stripslashes(get_option('abstracts_title_style')).'
	}
	.authors {
		'.stripslashes(get_option('abstracts_authors_style')).'
	}
	.affiliation {
		'.stripslashes(get_option('abstracts_affiliation_style')).'
	}
	.text {
		'.stripslashes(get_option('abstracts_text_style')).'
	}
	</style>
	</head>
	<body>
	<div class="body">
	'.stripslashes(get_option('abstracts_html_header')).'
	    <div class="title">'.$abs_title.'</div>
	    <div class="authors">'.$abs_authors.'</div>
	    <div class="affiliation">'.$abs_affiliations.'</div>
	    <div class="text">'.nl2br($_POST["abs_text"]).'</div>
	</div>
	</body>
	</html>
  ';
  
  $wpdb->show_errors();
  $wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."submitted_abstracts (title,authors,author_affiliation,text,html,name,email,presentation_mode,data) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,NOW())",$abs_title,$abs_authors,$abs_affiliations,$_POST["abs_text"],$html,$_POST["your_firstname"].' '.$_POST["your_lastname"],$_POST["your_email"],$_POST["your_preference"]));
  $abstract_id = $wpdb->insert_id;
  
  if($_FILES) {
  	foreach($_FILES['attachments']['error'] as $key=>$error) {
  		if($error==0) {
			$fileName = $_FILES['attachments']['name'][$key];
			$tmpName  = $_FILES['attachments']['tmp_name'][$key];
			$fileSize = $_FILES['attachments']['size'][$key];
			$fileType = $_FILES['attachments']['type'][$key];
			$fileExtension = explode('.',$fileName);
			$fileExtension = strtolower($fileExtension[count($fileExtension)-1]);

			if(in_array($fileExtension,explode(' ',get_option('abstracts_permitted_attachments'))) and $fileSize<=get_option('abstracts_maximum_attach_size')) {
				$fp      	 = fopen($tmpName, 'r');
				$fileContent = rawurlencode(fread($fp, $fileSize));
				fclose($fp);
				
				$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."abstracts_attachments (abstracts_id,filecontent,filename,filemeta,filetype,filesize) VALUES (%d,%s,%s,%s,%s,%s)",$abstract_id,$fileContent,$fileName,$fileType,'',$fileSize));		
				$uploads[] = array('id'=>$wpdb->insert_id,'filename'=>$fileName,'filesize'=>$fileSize);
			} elseif($fileSize<get_option('abstracts_maximum_attach_size')) {
				$uploads[] = array('id'=>0,'filename'=>$fileName,'filesize'=>$fileSize,'error'=>'File not supported');
			} else {
				$uploads[] = array('id'=>0,'filename'=>$fileName,'filesize'=>$fileSize,'error'=>'File too big');
			}
  		}
  	}
  }

}
  
?>
<h2>Review Your Abstract</h2>
<div style="border: 1px solid #000000;">
<?=$html?>	
</div>
<h2>Attachments</h2>
	<div class="abstract_form_explanation">Here you can download a pdf version of your submitted abstract and verify your attachments.</strong></div>
<div>
	<div class="abstract_attachment_row"><a href="?abstracts_download_pdf_file=<?=$abstract_id?>"><input style="float:right;" type="button" value="Download" accesskey="p" tabindex="5" class="button-primary" name="publish"/></a><span style="width:650px;"><strong>abstract.pdf</strong> [~<?=number_format(strlen($html)/1024,2)?>kb]</span></div>
<? foreach($uploads as $attachment) { ?>
<? if($attachment['id']!=0) { ?>
	<div class="abstract_attachment_row"><a href="?abstracts_download_file=<?=$attachment['id']?>"><input style="float:right;" type="button" value="Download" accesskey="p" tabindex="5" class="button-primary" name="publish"/></a><span style="width:650px;"><strong><?=$attachment['filename']?></strong> [<?=number_format(($attachment['filesize']/1024),2)?>kb]</span></div>
<? } else { ?>
	<div class="abstract_attachment_row"><span style="width:650px;"><strong><?=$attachment['filename']?></strong> [<span class="abstract_form_error"><?=$attachment['error']?></span>]</span></div>
<? } ?>
<? } ?>	
</div>
<h2><a href="<?=get_permalink(get_option('abstracts_redirect_page'))?>">Complete your submission &raquo;</a></h2>
	<div class="abstract_form_explanation">Click the above link to complete the abstract submission process.</strong></div>
<? } else { ?>
<script>
/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Created by: Steve | http://jsmadeeasy.com/ */
function getObject(obj) {
  var theObj;
  if(document.all) {
    if(typeof obj=="string") {
      return document.all(obj);
    } else {
      return obj.style;
    }
  }
  if(document.getElementById) {
    if(typeof obj=="string") {
      return document.getElementById(obj);
    } else {
      return obj.style;
    }
  }
  return null;
}

function toCount(entrance,exit,text,characters) {
  var entranceObj=getObject(entrance);
  var exitObj=getObject(exit);
  var length=characters - entranceObj.value.length;
  if(length <= 0) {
    length=0;
    text='<span class="disable"> '+text+' </span>';
    entranceObj.value=entranceObj.value.substr(0,characters);
  }
  exitObj.innerHTML = text.replace("{CHAR}",length);
}

function abstracts_add_attachment() {
   var container = document.createElement('div');
   container.setAttribute('class','abstract_form_attachment');
   
   var input = document.createElement('input');
   input.setAttribute('type','file');
   input.setAttribute('name','attachments[]');
   
   container.appendChild(input);

   document.getElementById('abstract_form_attachments').appendChild(container); 
}

function check_abstract_form() {
	var errors = false;
	if(document.getElementById('abs_title').value=='') {
		errors = true;
		document.getElementById('abs_title_error').style.display='inline';
	} else {
		document.getElementById('abs_title_error').style.display='none';
	}
	if(document.getElementById('abs_authors').value=='') {
		errors = true;
		document.getElementById('abs_authors_error').style.display='inline';
	} else {
		document.getElementById('abs_authors_error').style.display='none';
	}
	if(document.getElementById('abs_affiliation').value=='') {
		errors = true;
		document.getElementById('abs_authors_error').style.display='inline';
	} else {
		document.getElementById('abs_authors_error').style.display='none';
	}
	if(document.getElementById('eBann').value=='') {
		errors = true;
		document.getElementById('abs_text_error').style.display='inline';
	} else {
		document.getElementById('abs_text_error').style.display='none';
	}
	if(document.getElementById('abs_name').value=='') {
		errors = true;
		document.getElementById('abs_name_error').style.display='inline';
	} else {
		document.getElementById('abs_name_error').style.display='none';
	}
	if(document.getElementById('abs_email').value=='') {
		errors = true;
		document.getElementById('abs_email_error').style.display='inline';
	} else {
		document.getElementById('abs_email_error').style.display='none';
	}
	if(errors) {
		alert('Please fill in all required fields.');
	} else {
		document.getElementById('abs_form').submit();
	}
}

abs_coauthor = 1;

function abstracts_add_coauthor(){
	var tr_name = document.createElement("TR")
	tr_name.setAttribute('class','abstract_form_table_row');
	   var td_name_label = document.createElement("TD")
	   td_name_label.setAttribute('class','abstract_form_table_label');
	   td_name_label.appendChild(document.createTextNode('Firstname'));
	   tr_name.appendChild(td_name_label);
	   var td_name_input = document.createElement("TD")
		   var input_name_input = document.createElement("INPUT")
		   input_name_input.setAttribute('type','text');
		   input_name_input.setAttribute('name','abs_author_firstname['+abs_coauthor+']');
		   input_name_input.setAttribute('style','width:200px;');
		   input_name_input.setAttribute('id','abs_authors_firstname'+abs_coauthor);
		   td_name_input.appendChild(input_name_input);
	   tr_name.appendChild(td_name_input);

    document.getElementById('abstract_coauthors_table').appendChild(tr_name);

	var tr_name = document.createElement("TR")
	tr_name.setAttribute('class','abstract_form_table_row');
	   var td_name_label = document.createElement("TD")
	   td_name_label.setAttribute('class','abstract_form_table_label');
	   td_name_label.appendChild(document.createTextNode('Lastname'));
	   tr_name.appendChild(td_name_label);
	   var td_name_input = document.createElement("TD")
		   var input_name_input = document.createElement("INPUT")
		   input_name_input.setAttribute('type','text');
		   input_name_input.setAttribute('name','abs_author_lastname['+abs_coauthor+']');
		   input_name_input.setAttribute('style','width:200px;');
		   input_name_input.setAttribute('id','abs_authors_lastname'+abs_coauthor);
		   td_name_input.appendChild(input_name_input);
	   tr_name.appendChild(td_name_input);

    document.getElementById('abstract_coauthors_table').appendChild(tr_name);

	var tr_name = document.createElement("TR")
	tr_name.setAttribute('class','abstract_form_table_row');
	   var td_name_label = document.createElement("TD")
	   td_name_label.setAttribute('class','abstract_form_table_label');
	   td_name_label.appendChild(document.createTextNode('Affiliation'));
	   tr_name.appendChild(td_name_label);
	   var td_name_input = document.createElement("TD")
		   var input_name_input = document.createElement("INPUT")
		   input_name_input.setAttribute('type','text');
		   input_name_input.setAttribute('name','abs_affiliation['+abs_coauthor+']');
		   input_name_input.setAttribute('style','width:100%;');
		   td_name_input.appendChild(input_name_input);
	   tr_name.appendChild(td_name_input);

    document.getElementById('abstract_coauthors_table').appendChild(tr_name);

	var tr_name = document.createElement("TR")
	tr_name.setAttribute('class','abstract_form_table_row');
	   var td_name_label = document.createElement("TD")
	   td_name_label.setAttribute('class','abstract_form_table_label');
	   tr_name.appendChild(td_name_label);
	   var td_name_input = document.createElement("TD")
		   td_name_input.appendChild(document.createTextNode('Is this author the presenter? '));
		   var input_name_input = document.createElement("INPUT")
		   input_name_input.setAttribute('type','checkbox');
		   input_name_input.setAttribute('name','abs_presenter['+abs_coauthor+']');
		   input_name_input.setAttribute('value','1');
		   td_name_input.appendChild(input_name_input);
	   tr_name.appendChild(td_name_input);

    document.getElementById('abstract_coauthors_table').appendChild(tr_name);
	
	abs_coauthor = (abs_coauthor+1);
}

function set_presenter(firstname,lastname) {
	alert('sdasda');
	if(document.getElementById('abs_name').value!='') {
		document.getElementById('abs_name').value = document.getElementById(lastname).value;
		document.getElementById('abs_firstname').value = document.getElementById(firstname).value;
	}
}

</script>

<form method="post" enctype="multipart/form-data" id="abs_form">
<h2>Abstract</h2>
	<h4><strong>* Title <span class="abstract_form_error" style="display: none;" id="abs_title_error">Required field.</span></strong></h4>
	<textarea id="abs_title" name="abs_title" class="large-text code" cols="50" rows="2"></textarea>
	<div class="abstract_form_explanation">Insert the title of your abstract here.</div>
	<h4><strong>* <a name="coauthors">Authors</a> <span class="abstract_form_error" style="display: none;" id="abs_authors_error">Required field.</span></strong></h4>
	<div class="abstract_form_explanation">Insert authors and theirs affiliation here. Add more co-authors using the appropriate link.</div>
	<table width="100%">
		<tbody id="abstract_coauthors_table">
		<tr class="abstract_form_table_row">
			<td class="abstract_form_table_label">Firstname</td>
			<td><input type="text" name="abs_author_firstname[0]" style="width:200px;" id="abs_authors_name" /></td>
		</tr>
		<tr class="abstract_form_table_row">
			<td class="abstract_form_table_label">Lastname</td>
			<td><input type="text" name="abs_author_lastname[0]" style="width:200px;" id="abs_authors" /></td>
		</tr>
		<tr class="abstract_form_table_row">
			<td class="abstract_form_table_label">Affiliation</td>
			<td><input type="text" name="abs_affiliation[0]" style="width:100%;" id="abs_affiliation" /></td>
		</tr>
		<tr class="abstract_form_table_row">
			<td class="abstract_form_table_label"></td>
			<td>Is this author the presenter? <input type="checkbox" name="abs_presenter[0]" value="1" /></td>
		</tr>		
		</tbody>
	</table>
	<div class="abstract_form_add_coauthor" onclick="abstracts_add_coauthor();"><a href="#coauthors">Add a co-author.</a></div>
	<h4><strong>* Text <span class="abstract_form_error" style="display: none;" id="abs_text_error">Required field.</span></strong></h4>
	<textarea name="abs_text" class="large-text code" cols="50" rows="10" id="eBann" onKeyUp="toCount('eBann','sBann','<strong>{CHAR}</strong> characters left',<?=get_option('abstracts_chars_count')?>);"></textarea>
	<div class="abstract_form_explanation">Insert here the text of your abstract. <span id="sBann"><strong><?=get_option('abstracts_chars_count')?></strong> characters left.</span></div>
<h2><a name="attachments">Attachments</a></h2>
	<div class="abstract_form_explanation">Use this form to upload your images, photos or tables.<br/>Supported formats: <strong><?=implode(', ',explode(' ',get_option('abstracts_permitted_attachments')));?></strong><br/>Maximum attachment size: <strong><?=number_format((get_option('abstracts_maximum_attach_size')/1024),0)?>kb</strong></div>
	<div id="abstract_form_attachments">
		<div class="abstract_form_attachment">
			<input type="file" name="attachments[]">
		</div>
	</div>
	<div class="abstract_form_add_attachments" onclick="abstracts_add_attachment();"><a href="#attachments">Add an attachment.</a></div>
<h2>Presenting Author Informations</h2>
	<div class="abstract_form_explanation">Insert here your personal informations and preferences.</div>
	<table width="100%">
		<tr class="abstract_form_table_row">
			<td class="abstract_form_table_label">Firstname</td>
			<td><input type="text" name="your_firstname" style="width:200px;" id="abs_firstname"  /></td>
		</tr>
		<tr class="abstract_form_table_row">
			<td class="abstract_form_table_label">* Lastname</td>
			<td><input type="text" name="your_lastname" style="width:200px;" id="abs_name" value="" /><span class="abstract_form_error" style="display: none;" id="abs_name_error"> Required field.</span></td>
		</tr>
		<tr class="abstract_form_table_row">
			<td class="abstract_form_table_label">* Email</td>
			<td><input type="text" name="your_email" style="width:200px;" id="abs_email" value="" /><span class="abstract_form_error" style="display: none;" id="abs_email_error"> Required field.</span></td>
		</tr>
	</table>
	<div class="abstract_form_explanation">Insert here your preferred mode of presentation.</div>
	<table width="100%">
		<tr class="abstract_form_table_row">
			<td class="abstract_form_table_label">Preference</td>
			<td><select name="your_preference">
					<option>No preference</option>
					<option>Oral presentation</option>
					<option>Poster</option>
				</select>
			</td>
		</tr>
	</table>
<h2><a style="cursor:pointer;" onclick="check_abstract_form();">Submit your abstract &raquo;</a></h2>
	<div class="abstract_form_explanation">Click the above link to continue in the abstract submission. Please note that if you are uploading big files the process may take a while. <strong>Please be patient and press submit link only once.</strong></div>
	<p>* Required field</p>
</form>
<? } ?>