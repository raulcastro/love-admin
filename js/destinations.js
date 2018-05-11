$(function(){
	if ( $('#addDestination').length ) { 
		$('#addDestination').click(function(){
			addDestination();
			return false;
		});
	}
	
	if ( $('#updateDestination').length ) { 
		$('#updateDestination').click(function(){
			updateDestination();
			return false;
		});
	}
	
	if ( $('#deleteDestination').length ) { 
		$('#deleteDestination').click(function(){
			bootbox.confirm("Do you really want to delete this owner?", function(result) {
				if (result)
				{
					deleteDestination();
				}
			}); 
			return false;
		});
		
	if ( $('#addHotel').length ) { 
			$('#addHotel').click(function(){
				addHotel();
				return false;
			});
		}
	}
	
	if ( $('.hotelItem').length ) { 
		$('.hotelItem').click(function(){
			showHotelDetail(this);
			return false;
		});
	}
	
	var destination = $('#destinationId').val();
	
	if ( $('#uploadAvatar').length ) { 
		$("#uploadAvatar").uploadFile({
			url:		"/ajax/media.php",
			fileName:	"myfile",
			multiple: 	true,
			doneStr:	"uploaded!",
			formData: {
				destination: destination,
					opt: 4 
				},
			onSuccess:function(files, data, xhr)
			{
				obj 			= JSON.parse(data);
				avatar		 	= obj.fileName;
				lastIdGallery 	= obj.lastId;
				$('#iconImg').attr('src', '/img-up/destinations/avatar/'+avatar);
				//$('#userAvatarImg').attr('src', '/images/owners-profile/avatar/'+avatar);
			}
		});
	}
	
	$('#progressSaveMember').hide();
	$('#memberComplete').hide();
	
});

function addDestination()
{
	var destination = $('#destinationName').val();
	
	if (destination)
	{
		$.ajax({
		    type: "POST",
		    url: "/ajax/destinations.php",
		    data: {
		    	destination:	destination,
		    	opt:	1
		    },
		    success:
		        function(info)
		        {
		        	if (info != '0')
		        	{
		        		$('#destinationName').val('');
		        		setTimeout(func, 3000);
		            	function func() {
		            		$('#add-company-loader').hide();
		            		
		            		var editCompany = '/single-destination/'+info+'/new-destination/';
			            	
			            	pathArray 		= $(location).attr('href').split( '/' );
			        		newURL 			= pathArray[0]+'//'+pathArray[2]+editCompany;
			            	//alert(newURL);
			        		window.location = newURL;
		            	}
		        	}
		        	else
					{
					}
		        }
		    });
	}
}

function updateInventoryOptionsRooms(categoryId)
{
	roomId = $('#currentRoom').val();
	
	$.ajax({
    type: "POST",
    url: "/ajax/members.php",
    data: {
    	roomId:	roomId,
    	categoryId: categoryId,
    	opt:	9
    },
    success:
        function(info)
        {
        	if (info != '0')
        	{
        		$('#inventoryRoomList option').remove();
//        		$('#categoryRoomList').select2('destroy');
        		$('#inventoryRoomList').html(info);
        		$('#inventoryRoomList').select2();
        		
        		$('#inventoryRoomList').on("change", function(){
        			var inventoryId = $('#inventoryRoomList').val();
        			$('#currentInventory').val(inventoryId);
        		});
        	}
        	else
			{
			}
        }
    });
}

function updateDestination()
{
	destinationId = $('#destinationId').val();
	
	destinationName = $('#destinationName').val();
	smallDescription = $('#smallDescription').val();
	description = $('#description').val();
	
	if (destinationId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/destinations.php",
	    data: {
	    	destinationId:	destinationId,
	    	destinationName: destinationName,
	    	smallDescription: smallDescription,
	    	description: description,
	    	opt:			2
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		alert('Destination updated!');
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function deleteDestination()
{
	destinationId = $('#destinationId').val();
	
	if (destinationId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/destinations.php",
	    data: {
	    	destinationId:		destinationId,
	    	opt:			3
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		pathArray 		= $(location).attr('href').split( '/' );
	        		newURL 			= pathArray[0]+'//'+pathArray[2]+'/destinations/';
	            	window.location = newURL;
	        	}
	        	else
				{
				}
	        }
	    });
	}
}


function addHotel()
{
	var hotel = $('#hotelName').val();
	var destination = $('#destinationId').val();
	
	if (hotel)
	{
		$.ajax({
		    type: "POST",
		    url: "/ajax/destinations.php",
		    data: {
		    	destination:	destination,
		    	hotel: hotel,
		    	opt:	4
		    },
		    success:
		        function(info)
		        {
		        	if (info != '0')
		        	{
		        		getAllHotels();
		        	}
		        	else
					{
					}
		        }
		    });
		
	}
}

function getAllHotels()
{
	destinationId = $('#destinationId').val();
	
	if (destinationId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/destinations.php",
	    data: {
	    	destinationId:		destinationId,
	    	opt:			5
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#hotelsBox').html(info);
	        		$('.hotelItem').click(function(){
	        			showHotelDetail(this);
	        			return false;
	        		});
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function showHotelDetail(node, currentHotel)
{
	if (currentHotel)
	{
	 var destinationId = $('#destinationId').val();
	
	var hotelId = currentHotel;
	}
	else
	{
		var destinationId = $('#destinationId').val();
		
		var hotelId = $(node).attr('data-value');
	}
	
	if (hotelId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/destinations.php",
	    data: {
	    	hotelId:		hotelId,
	    	opt:			6
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#hotelDetail').show();
	        		$('#hotelDetail').html(info);
	        		$('.delete-single-hotel').click(function(){
	        			deleteHotel(this);
	        		});
	        		
	        		$('.datepicker').datepicker({
	        	          autoclose: true
	        	    });
	        		$('#addHotelRange').click(function(){
	        			addHotelRange();
	        			return false;
	        		});
	        		$('.deleteRange').click(function(){
	        			deleteHotelRange(this);
	        			return false;
	        		});
	        		
	        		$('#saveExperiencesPrices').click(function(){
	        			saveExperiencesPrices();
	        			return false;
	        		});
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function deleteHotel(node)
{
	var hotelId = $(node).attr('data-value');
	
	if (hotelId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/destinations.php",
	    data: {
	    	hotelId:		hotelId,
	    	opt:			7
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#hotelDetail').hide();
	        		$('#hotelDetail').html('');
	        		
	        		getAllHotels();
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function addHotelRange()
{
	var hotelId 	= $('#currentHotelId').val();
	var newFromDate = $('#newFromDate').val();
	var newToDate 	= $('#newToDate').val();
	var newPrice 	= $('#newPrice').val();
	
//	alert(hotelId + newFromDate + newToDate + newPrice);
	
	if (hotelId && newToDate && newPrice)
	{
		$.ajax({
		    type: "POST",
		    url: "/ajax/destinations.php",
		    data: {
		    	hotelId:		hotelId,
		    	newFromDate:	newFromDate,
		    	newToDate:	newToDate,
		    	newPrice: 	newPrice,
		    	opt:			8
		    },
		    success:
		        function(info)
		        {
		        	if (info != '0')
		        	{
		        		showHotelDetail(null, hotelId);
//		        		$('#hotelDetail').hide();
//		        		$('#hotelDetail').html('');
		        		
//		        		getAllHotels();
		        	}
		        	else
					{
					}
		        }
		    });
	}
	
}

function deleteHotelRange(node)
{
	var hotelId 	= $('#currentHotelId').val();
	var rangeId = $(node).attr('data-id');
//	alert(rangeId);
	
	if (rangeId)
	{
		$.ajax({
		    type: "POST",
		    url: "/ajax/destinations.php",
		    data: {
		    	hotelId:	hotelId,
		    	rangeId:	rangeId,
		    	opt:		9
		    },
		    success:
		        function(info)
		        {
		        	if (info != '0')
		        	{
		        		showHotelDetail(null, hotelId);
//		        		$('#hotelDetail').hide();
//		        		$('#hotelDetail').html('');
		        		
//		        		getAllHotels();
		        	}
		        	else
					{
					}
		        }
		    });
	}
}


function saveExperiencesPrices()
{
	var hotelId 	= $('#currentHotelId').val();
	
	$('#experiencesList input').each(function(node){
		var experienceId = $(this).attr('experienceId');
		var experiencePrice = $(this).val();
		
		if (experiencePrice > 0)
		{
			$.ajax({
			    type: "POST",
			    url: "/ajax/destinations.php",
			    data: {
			    	hotelId:	hotelId,
			    	experienceId:	experienceId,
			    	experiencePrice: experiencePrice,
			    	opt:		10
			    },
			    success:
			        function(info)
			        {
			        	if (info != '0')
			        	{
//			        		showHotelDetail(null, hotelId);
//			        		$('#hotelDetail').hide();
//			        		$('#hotelDetail').html('');
			        		
//			        		getAllHotels();
			        	}
			        	else
						{
						}
			        }
			    });
		}
	});
}










