$(document).ready(function()
{
	$('.addLocation').click(function(){
		addLocation();
		return false;
	});
	
	$('#locationList li a').click(function(){
		getLocationInfo(this);
	});

	$('.update-location').click(function(){
		updateLocation();
		return false;
	});
	
	$('.delete-location').click(function(){
		deleteLocation();
		return false;
	});
	
});//Document ready ends here!

function deleteLocation(){
	locId 			= $('#currentLocation').val();
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/locations.php?option=4',
        data:{  
        	locId : locId
             },
        success:
        function(data)
        {
            if ('1' == data)
            {
            	$('.categories-settings .right').hide();
            	$('#loc-'+locId).hide();
            }
        }
    });
}

function updateLocation()
{
	locId 			= $('#currentLocation').val();
	locName 		= $('#currentLocationName').val();
	locDescription 	= $('#currentLocationDescription').val();
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/locations.php?option=3',
        data:{  
        	locId			: locId,
        	locName			: locName,
        	locDescription	: locDescription
             },
        success:
        function(data)
        {
            if ('0' != data)
            {
            }
        }
    });
}

function getLocationInfo(node)
{
	locId = $(node).attr('locId');
	$('#locationList li a').removeClass('active');
	$(node).addClass('active');
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/locations.php?option=2',
        data:{  
        	locId	: locId
             },
        success:
        function(data)
        {
            if ('0' != data)
            {
            	obj 		= JSON.parse(data);
            	$('#currentLocation').val(obj.locationInfo.location_id);
            	$('#locName').html(obj.locationInfo.name);
            	$('#currentLocationName').val(obj.locationInfo.name);
            	$('#currentLocationDescription').val(obj.locationInfo.description);
            }
        }
    });
	
	$('#locationSettingsRight').show();
}

function addLocation()
{
	locationName = $('#locationName').val();
	
    $.ajax({
        type:   'POST',
        url:    '/ajax/back/locations.php?option=1',
        data:{  
        	locationName : locationName
             },
        success:
        function(xml)
        {
            if ('0' != xml)
            {
            	newLocation = '<li id="loc-'+xml+'"><a href="javascript:void(0);" locId="'+xml+'">'+locationName+'</a></li>';
            	$('#locationList').prepend(newLocation);
            	$('#locationName').val('');
            	$('#locationList li a').click(function(){
            		getLocationInfo(this);
            	});
            }
        }
    });
}