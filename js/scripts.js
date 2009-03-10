/**
 * @author xGear
 */
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
	if(document.getElementById('abs_name').value!='') {
		document.getElementById('abs_name').value = document.getElementById(lastname).value;
		document.getElementById('abs_firstname').value = document.getElementById(firstname).value;
	}
}