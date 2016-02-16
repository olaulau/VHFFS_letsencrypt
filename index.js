$( document ).ready(function(){
	
	//  disapearance of alerts
	$(".alert").delay(3000).fadeOut(2000, function(){
		$(this).remove();
	});
	
	
	//  AJAX domain list
	$.getJSON( "ajax/domains.php", function( data ) {
		  var items = [];
		  $.each( data, function( key, val ) {
		    items.push( "<option value='" + val + "'>" + val + "</option>" );
		  });
		 
		  $("#domains").html( items.join(" ") );
	});
	
});