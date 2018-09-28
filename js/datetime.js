var datefield=document.createElement("input");
datefield.setAttribute("type", "datetime-local");
if (datefield.type!="datetime-local"){
	document.write('<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />\n');
}

jQuery(document).ready(function($) {	
    if (datefield.type!="datetime-local"){ 
        $('input[type=datetime-local]').datetimepicker({ dateFormat: 'yy-mm-dd', timeFormat: 'HH:mm', separator:'T', firstDay:1});
    }
});