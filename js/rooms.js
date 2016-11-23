$(function(){
	if ( $('#addRoom').length ) { 
		$('#addRoom').click(function(){
			addRoom();
			return false;
		});
	}
	
	if ( $('#addInventory').length ) { 
		$('#addInventory').click(function(){
			addInventory();
			return false;
		});
	}
	
	if ( $('#updateRoom').length ) { 
		$('#updateRoom').click(function(){
			updateRoom();
			return false;
		});
	}
	
	$('#categoriesList').on("change", function(){
		var categoryId = $('#categoriesList').val();
		updateInventoryOptions(categoryId);
	});
	
	if ( $('#addRoomInventory').length ) { 
		$('#addRoomInventory').click(function(){
			addRoomInventory();
			return false;
		});
	}
	
	if ( $('#addMemberRoom').length ) { 
		$('#addMemberRoom').click(function(){
			addMemberRoom();
			return false;
		});
	}
	
	if ( $('#showAddRoom').length ) { 
		$('#showAddRoom').click(function(){
			$('#showAddRoomBox').hide();
			$('.add-room-boxes').show();
			return false;
		});
	}
	
	if ( $('.deleteApartment').length ) { 
		$('.deleteApartment').click(function(){
			bootbox.confirm("Do you really want to delete this apartment?", function(result) {
				if (result)
				{
					deleteMemberRoom();
				}
			}); 
			//return false;
		});
	}
	
	if ( $('#deleteRoom').length ) { 
		$('#deleteRoom').click(function(){
			bootbox.confirm("Do you really want to delete this apartment?", function(result) {
				if (result)
				{
					deleteRoom();
				}
			}); 
			//return false;
		});
	}
});

function addRoom()
{
	var roomName 		= $('#roomName').val();
	var roomType 		= $('#roomType').val();
	var roomDescription = $('#roomDescription').val();
	var condoId			= $('#condoId').val();

	if (roomName)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/rooms.php",
	    data: {
	    	roomName: 			roomName,
	    	roomType: 			roomType,
	    	roomDescription: 	roomDescription,
	    	condoId:			condoId,
	    	opt:				'1'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		var item = '<li><a href="/edit-room/'+info+'/">'+roomName+'</a></li>'
	        		$('#roomsBox').prepend(item);
	        		$('#roomName').val('');
	        		$('#roomType').val('');
	        		$('#roomDescription').val('');
	        	}
	        }
	    });
	}
}

function updateRoom()
{
	var roomId 			= $('#roomId').val();
	var roomName 		= $('#roomName').val();
	var roomType 		= $('#roomType').val();
	var roomDescription = $('#roomDescription').val();
	var condoId			= $('#condoId').val();

	if (roomName)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/rooms.php",
	    data: {
	    	roomId:				roomId,
	    	roomName: 			roomName,
	    	roomType: 			roomType,
	    	roomDescription: 	roomDescription, 
	    	condoId:			condoId,
	    	opt:				'4'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		alert('Room updated!');
	        	}
	        }
	    });
	}
}

function deleteRoom()
{
	var roomId 	= $('#roomId').val();

	if (roomId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/rooms.php",
	    data: {
	    	roomId:				roomId,
	    	opt:				10
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		
	        		pathArray 		= $(location).attr('href').split( '/' );
	        		newURL 			= pathArray[0]+'//'+pathArray[2]+'/rooms/';
	            	window.location = newURL;
	        	}
	        }
	    });
	}
}

function updateCategory()
{
	var categoryId			= $('#categoryId').val();
	var categoryName 		= $('#categoryName').val();
	var categoryDescription = $('#categoryDescription').val();
	
	if (categoryName)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/settings.php",
	    data: {
	    	categoryId: categoryId,
	    	categoryName: 	categoryName,
	    	categoryDescription: 	categoryDescription, 
	    	opt:			'2'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		alert('Category updated!');
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function addInventory()
{
	var categoryId				= $('#categoryId').val();
	var inventoryName 			= $('#inventoryName').val();
	var inventoryDescription 	= $('#inventoryDescription').val();
	
	if (inventoryName)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/settings.php",
	    data: {
	    	categoryId:		categoryId,
	    	inventoryName: 	inventoryName,
	    	inventoryDescription: 	inventoryDescription, 
	    	opt:			'3'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		var item = '<li><a href="/edit-inventory-category/'+info+'/">'+inventoryName+'</a></li>'
	        		$('#inventoryBox').prepend(item);
	        		$('#inventoryName').val('');
	        		$('#inventoryDescription').val('');
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function updateInventoryOptions(categoryId)
{
	if (categoryId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/rooms.php",
	    data: {
	    	categoryId:	categoryId,
	    	opt:			'5'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#inventoryList option').remove();
	        		$('#inventoryList').select2('destroy');
	        		$('#inventoryList').html(info);
	        		$('#inventoryList').select2();
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function addRoomInventory()
{
	var roomId		= $('#roomId').val();
	var categoryId	= $('#categoriesList').val();
	var inventoryId	= $('#inventoryList').val();
	
	if (inventoryId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/rooms.php",
	    data: {
	    	categoryId:		categoryId,
	    	roomId: 		roomId,
	    	inventoryId: 	inventoryId, 
	    	opt:			'6'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		getRoomInventory(roomId);
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function getRoomInventory(roomId)
{
	if (roomId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/rooms.php",
	    data: {
	    	roomId: 		roomId,
	    	opt:			'7'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#inventoryBox').html(info);
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function addMemberRoom()
{
	var memberId = $('#memberId').val();
	var roomId	 = $('#roomList').val();
	
	if (roomId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/rooms.php",
	    data: {
	    	memberId:		memberId,
	    	roomId: 		roomId,
	    	opt:			'8'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		pathArray 		= $(location).attr('href').split( '/' );
	            	newURL 			= pathArray[0]+'//'+pathArray[2]+'/'+pathArray[3]+'/'+pathArray[4]+'/'+pathArray[5]+'-'+Math.floor((Math.random() * 100) + 1)+'/#utilitiesBox';
	            	window.location = newURL;
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function deleteMemberRoom()
{
	var memberId = $('#memberId').val();
	var currentRoom = $('#currentRoom').val();
	
	if (currentRoom)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/rooms.php",
	    data: {
	    	memberId:		memberId,
	    	roomId: 		currentRoom,
	    	opt:			'9'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		pathArray 		= $(location).attr('href').split( '/' );
	            	newURL 			= pathArray[0]+'//'+pathArray[2]+'/'+pathArray[3]+'/'+pathArray[4]+'/'+pathArray[5]+'-'+Math.floor((Math.random() * 100) + 1)+'/#utilitiesBox';
	            	window.location = newURL;
	        	}
	        	else
				{
				}
	        }
	    });
	}
	
	
}























