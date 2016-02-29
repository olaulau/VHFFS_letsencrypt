$( document ).ready(function(){
	
	//  disapearance of alerts
	$("#messages .alert").delay(3000).fadeOut(2000, function(){
		$(this).remove();
	});
	
	
	//  AJAX alpha-domains list
	$.getJSON( "ajax/alpha-domains.php", function( data ) {
		var items = [];
		items.push("<option value=''></option>");
		$.each(data, function(key, val) {
			items.push("<option value='" + val + "'>" + val + "</option>");
		});

		$("#alpha-domain").html(items.join(" "));
	});
	
	//  AJAX project-domains list
	$.getJSON( "ajax/project-domains.php", function( data ) {
		var items = [];
		items.push("<option value=''></option>");
		$.each(data, function(key, val) {
//			console.log(key);
			items.push('<optgroup label="' + key + '">');
//			console.log(val);
			$.each(val, function(key, val) {
//				console.log(val);
				items.push('<option value="' + val + '">' + val + '</option>');
			});
			items.push('</optgroup>');
		});
		$("#project-domain").html( items.join(" ") );
	});
	
	
	//  set the hidden field with value selected in alpha-domain
	$("#alpha-domain").change(function(){
		$("#project-domain")[0].selectedIndex = 0;
		$("#domain").val($(this).val());
	});
	
	//  set the hidden field with value selected in project-domain
	$("#project-domain").change(function(){
		$("#alpha-domain")[0].selectedIndex = 0;
		$("#domain").val($(this).val());
	});
	
	
	//  handle form submit (send only particular values)
	$("form[name=easyform]").submit(function() {
//		$("#alpha-domain").attr("disabled", "disabled");
//		$("#project-domain").attr("disabled", "disabled");
		return true;
	});
	
});