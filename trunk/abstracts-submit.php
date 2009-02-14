<? if($_POST) { 

require_once("dompdf-0.5.1/dompdf_config.inc.php");
if ( isset( $_POST["text"] ) ) {

  if ( get_magic_quotes_gpc() )
    $_POST["text"] = stripslashes($_POST["text"]);

  //$old_limit = ini_set("memory_limit", "16M");
  
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
	    <div class="title">'.$_POST["title"].'</div>
	    <div class="authors">'.$_POST["authors"].'</div>
	    <div class="affiliation">'.nl2br($_POST["affiliation"]).'</div>
	    <div class="text">'.nl2br($_POST["text"]).'</div>
	</div>
	</body>
	</html>
  ';
  
  $wpdb->show_errors();
  $wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."submitted_abstracts (title,authors,author_affiliation,text,html,data) VALUES (%s,%s,%s,%s,%s,NOW())",$_POST["title"],$_POST["authors"],$_POST["affiliation"],$_POST["text"],$html));
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
<h2><a href="">Confirm your submission &raquo;</a></h2>
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
	if(document.getElementById('abs_title').innerHTML=='') {
		errors = true;
		document.getElementById('abs_title_error').style.display='inline';
	} else {
		document.getElementById('abs_title_error').style.display='none';
	}
	if(document.getElementById('abs_authors').innerHTML=='') {
		errors = true;
		document.getElementById('abs_authors_error').style.display='inline';
	} else {
		document.getElementById('abs_authors_error').style.display='none';
	}
	if(document.getElementById('abs_affiliation').innerHTML=='') {
		errors = true;
		document.getElementById('abs_affiliation_error').style.display='inline';
	} else {
		document.getElementById('abs_affiliation_error').style.display='none';
	}
	if(document.getElementById('abs_text').innerHTML=='') {
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
</script>

<form method="post" enctype="multipart/form-data" id="abs_form">
<h2>Abstract</h2>
	<h4><strong>* Title <span class="abstract_form_error" style="display: none;" id="abs_title_error">Required field.</span></strong></h4>
	<textarea id="abs_title" name="title" class="large-text code" cols="50" rows="2"></textarea>
	<div class="abstract_form_explanation">Insert here the title of your abstract.</div>
	<h4><strong>* Authors <span class="abstract_form_error" style="display: none;" id="abs_authors_error">Required field.</span></strong></h4>
	<textarea id="abs_authors" name="authors" class="large-text code" cols="50" rows="2"></textarea>
	<div class="abstract_form_explanation">Insert here author names separated by commas.</div>
	<h4><strong>* Author Affiliation <span class="abstract_form_error" style="display: none;" id="abs_affiliation_error">Required field.</span></strong></h4>
	<textarea id="abs_affiliation" name="affiliation" class="large-text code" cols="50" rows="3"></textarea>
	<div class="abstract_form_explanation">Insert here author affiliations separated by commas.</div>
	<h4><strong>* Text <span class="abstract_form_error" style="display: none;" id="abs_text_error">Required field.</span></strong></h4>
	<textarea id="abs_text" name="text" class="large-text code" cols="50" rows="10" id="eBann" onKeyUp="toCount('eBann','sBann','<strong>{CHAR}</strong> characters left',<?=get_option('abstracts_chars_count')?>);"></textarea>
	<div class="abstract_form_explanation">Insert here the text of your abstract. <span id="sBann"><strong><?=get_option('abstracts_chars_count')?></strong> characters left.</span></div>
<h2>Attachments</h2>
	<div class="abstract_form_explanation">Use this form to upload your images, fotos or tables.<br/>Supported formats: <strong><?=implode(', ',explode(' ',get_option('abstracts_permitted_attachments')));?></strong><br/>Maximum attachment size: <strong><?=number_format((get_option('abstracts_maximum_attach_size')/1024),0)?>kb</strong></div>
	<div id="abstract_form_attachments">
		<div class="abstract_form_attachment">
			<input type="file" name="attachments[]">
		</div>
	</div>
	<div class="abstract_form_add_attachments"><a onclick="abstracts_add_attachment();">Add an attachment.</a></div>
<h2>Presenter Informations</h2>
	<div class="abstract_form_explanation">Insert here your personal informations and preferences.</div>
	<h4><strong>* Full Name <span class="abstract_form_error" style="display: none;" id="abs_name_error">Required field.</span></strong></h4>
	<input type="text" name="name" style="width:100%;" id="abs_name" />
	<h4><strong>* Email <span class="abstract_form_error" style="display: none;" id="abs_email_error">Required field.</span></strong></h4>
	<input type="text" name="email" style="width:100%;" id="abs_email" />
	<h4><strong>Preferred mode of presentation</strong></h4>
	<select>
		<option>No preference</option>
		<option value="oral">Oral presentation</option>
		<option value="poster">Poster</option>
	</select>
<h2><a style="cursor:pointer;" onclick="check_abstract_form();">Submit your abstract &raquo;</a></h2>
	<div class="abstract_form_explanation">Click the above link to continue in the abstract submission. Please note that if you are uploading big files the process may take a while. <strong>Please be patient and press submit link only once.</strong></div>
	<p>* Required field</p>
</form>
<? } ?>