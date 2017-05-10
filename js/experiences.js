$(function(){
	
	if ( $('#addExperience').length ) { 
		$('#addExperience').click(function(){
			addExperience();
			return false;
		});
	}
	
	
	if ( $('#updateExperience').length ) { 
		$('#updateExperience').click(function(){
			updateExperience();
			return false;
		});
	}
	
	if ( $('#deleteExperience').length ) { 
		$('#deleteExperience').click(function(){
			bootbox.confirm("Do you really want to delete this experience?", function(result) {
				if (result)
				{
					deleteExperience();
				}
			}); 
			return false;
		});
	}
	
	$('#updateDestinations').click(function(){
		updateDestinations();
		return false;
	});
	
	$('#updateExtras').click(function(){
		updateExtras();
		return false;
	});
	
	var experience = $('#experienceId').val();
	
	if ( $('#uploadAvatar').length ) { 
		$("#uploadAvatar").uploadFile({
			url:		"/ajax/media.php",
			fileName:	"myfile",
			multiple: 	true,
			doneStr:	"uploaded!",
			formData: {
				experience: experience,
					opt: 6 
				},
			onSuccess:function(files, data, xhr)
			{
				obj 			= JSON.parse(data);
				avatar		 	= obj.fileName;
				lastIdGallery 	= obj.lastId;
				$('#iconImg').attr('src', '/img-up/experiences/avatar/'+avatar);
				//$('#userAvatarImg').attr('src', '/images/owners-profile/avatar/'+avatar);
			}
		});
	}
	
	if ( $('#uploadSwipper').length ) { 
		$("#uploadSwipper").uploadFile({
			url:		"/ajax/media.php",
			fileName:	"myfile",
			multiple: 	true,
			doneStr:	"uploaded!",
			formData: {
				experience: experience,
					opt: 7
				},
			onSuccess:function(files, data, xhr)
			{
				obj 			= JSON.parse(data);
				avatar		 	= obj.fileName;
				lastIdGallery 	= obj.lastId;
				$('#swiperImg').attr('src', '/img-up/experiences/avatar/'+avatar);
				//$('#userAvatarImg').attr('src', '/images/owners-profile/avatar/'+avatar);
			}
		});
	}
	
	$('#progressSaveMember').hide();
	$('#memberComplete').hide();
	
});

function addExperience()
{
	var experience = $('#experienceName').val();
	
	if (experience)
	{
		$.ajax({
		    type: "POST",
		    url: "/ajax/experiences.php",
		    data: {
		    	experience:	experience,
		    	opt:	1
		    },
		    success:
		        function(info)
		        {
		        	if (info != '0')
		        	{
		        		$('#experienceName').val('');
		        		setTimeout(func, 3000);
		            	function func() {
//		            		$('#add-company-loader').hide();
		            		
		            		var editCompany = '/single-experience/'+info+'/new-experience/';
			            	
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


function updateExperience()
{
	experienceId 		= $('#experienceId').val();
	experienceName 		= $('#experienceName').val();
	smallDescription 	= $('#smallDescription').val();
	description 		= $('#description').val();
	
	if (experienceId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/experiences.php",
	    data: {
	    	experienceId:	experienceId,
	    	experienceName: experienceName,
	    	smallDescription: smallDescription,
	    	description: description,
	    	opt:			2
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		alert('Experience updated!');
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function deleteExperience()
{
	experienceId = $('#experienceId').val();
	
	if (experienceId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/experiences.php",
	    data: {
	    	experienceId:		experienceId,
	    	opt:			3
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		pathArray 		= $(location).attr('href').split( '/' );
	        		newURL 			= pathArray[0]+'//'+pathArray[2]+'/experiences/';
	            	window.location = newURL;
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function updateDestinations()
{
	var sectionId 	= $('#experienceId').val();
	
	var infoUpdate = [];
	
	$('#aliadosBoxItems .aliado-item').each(function(){
		if ($(this).is(':checked'))
		{
			aliadoId = $(this).attr('aliadoId');
			infoUpdate.push(aliadoId);
		}
	});
	
	if (infoUpdate)
	{
		$.ajax({
	        type:   'POST',
	        url:    '/ajax/experiences.php',
	        data:{  sectionId:	sectionId,
	        		infoUpdate: 	infoUpdate,
	            	opt: 		9
	             },
	        success:
	        function(xml)
	        {
	            if (0 == xml)
	            {
	            	alert('Actualizado');
	            }
	        }
	    });
	}
	return false
}

function updateExtras()
{
	var sectionId 	= $('#experienceId').val();
	
	var infoUpdate = [];
	
	$('#extrasBoxItems .aliado-item').each(function(){
		if ($(this).is(':checked'))
		{
			aliadoId = $(this).attr('aliadoId');
			infoUpdate.push(aliadoId);
		}
	});
	
	if (infoUpdate)
	{
		$.ajax({
	        type:   'POST',
	        url:    '/ajax/experiences.php',
	        data:{  sectionId:	sectionId,
	        		infoUpdate: 	infoUpdate,
	            	opt: 		10
	             },
	        success:
	        function(xml)
	        {
	            if (0 == xml)
	            {
	            	alert('Actualizado');
	            }
	        }
	    });
	}
	return false
}

