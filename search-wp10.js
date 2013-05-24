jQuery(document).ready(function($){

	$('#find-nearby-events').click(function(){
		if( ! navigator.geolocation ){
			return;
		}
		navigator.geolocation.getCurrentPosition(function(pos){
			alert( 'You are at lat:'+pos.coords.latitude+' lng:'+pos.coords.longitude );
			data = {
				search_wp10 : 1,
				latitude    : pos.coords.latitude,
				longitude   : pos.coords.longitude
			};
			$.getJSON(wp10_ajax_url,data,function( json ){
				console.log( json );
			});
		},function(){
			alert('Sorry, couldn\'t find your position!');
		});
	});

});