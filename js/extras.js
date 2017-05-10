$(function(){
	
	if ( $('#addExtra').length ) { 
		$('#addExtra').click(function(){
			addExtra();
			return false;
		});
	}
	
	
	if ( $('#updateExtra').length ) { 
		$('#updateExtra').click(function(){
			updateExtra();
			return false;
		});
	}
	
	if ( $('#deleteExtra').length ) { 
		$('#deleteExtra').click(function(){
			bootbox.confirm("Do you really want to delete this extra?", function(result) {
				if (result)
				{
					deleteExtra();
				}
			}); 
			return false;
		});
	}
	
	var extra = $('#extraId').val();
	
	if ( $('#uploadAvatar').length ) { 
		$("#uploadAvatar").uploadFile({
			url:		"/ajax/media.php",
			fileName:	"myfile",
			multiple: 	true,
			doneStr:	"uploaded!",
			formData: {
				extra: extra,
					opt: 5 
				},
			onSuccess:function(files, data, xhr)
			{
				obj 			= JSON.parse(data);
				avatar		 	= obj.fileName;
				lastIdGallery 	= obj.lastId;
				$('#iconImg').attr('src', '/img-up/extras/avatar/'+avatar);
				//$('#userAvatarImg').attr('src', '/images/owners-profile/avatar/'+avatar);
			}
		});
	}
	
	$('#progressSaveMember').hide();
	$('#memberComplete').hide();
	
});

function addExtra()
{
	var extra = $('#extraName').val();
	
	if (extra)
	{
		$.ajax({
		    type: "POST",
		    url: "/ajax/extras.php",
		    data: {
		    	extra:	extra,
		    	opt:	1
		    },
		    success:
		        function(info)
		        {
		        	if (info != '0')
		        	{
		        		$('#extraName').val('');
		        		setTimeout(func, 2000);
		            	function func() {
		            		$('#add-company-loader').hide();
		            		
		            		var editCompany = '/single-extra/'+info+'/new-extra/';
			            	
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


function updateExtra()
{
	extraId = $('#extraId').val();
	
	extraName 			= $('#extraName').val();
	extraPrice			= $('#extraPrice').val();
	smallDescription 	= $('#smallDescription').val();
	description 		= $('#description').val();
	
	if (extraId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/extras.php",
	    data: {
	    	extraId:			extraId,
	    	extraName: 			extraName,
	    	extraPrice:			extraPrice,
	    	smallDescription: 	smallDescription,
	    	description: 		description,
	    	opt:				2
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		alert('Extra updated!');
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function deleteExtra()
{
	extraId = $('#extraId').val();
	
	if (extraId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/extras.php",
	    data: {
	    	extraId:		extraId,
	    	opt:			3
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		pathArray 		= $(location).attr('href').split( '/' );
	        		newURL 			= pathArray[0]+'//'+pathArray[2]+'/extras/';
	            	window.location = newURL;
	        	}
	        	else
				{
				}
	        }
	    });
	}
}
