$(function(){
	
	if ( $('#memberSave').length ) { 
		$('#memberSave').click(function(){
			saveMember();
			return false;
		});
	}
	
	if ( $('#updateMember').length ) { 
		$('#updateMember').click(function(){
			updateMember();
			return false;
		});
	}
	
	if ( $('#showEditUser').length ) { 
		$('#showEditUser').click(function(){
			$('.edit-user-info').show('slow');
			return false;
		});
	}
	
	if ( $('#cancelEditUser').length ) { 
		$('#cancelEditUser').click(function(){
			$('.edit-user-info').hide('slow');
			return false;
		});
	}
	
	if ( $('.room-id').length ) { 
		$('.room-id').click(function(){
			loadRoomData(this);
		});
	}
	
	var memberId = 0;
	
	if ( $('#memberId').length ) { 
		var memberId = $('#memberId').val();
	}
	
	if ( $('#getPendingTab').length ) { 
		$('#getPendingTab').click(function(){
			getPayments("pending");
			$('#currentPaymentSelection').val("pending");
		});
	}
	
	if ( $('#getPastDueTab').length ) { 
		$('#getPastDueTab').click(function(){
			getPayments("past");
			$('#currentPaymentSelection').val("past");
		});
	}
	
	if ( $('#getPaidTab').length ) { 
		$('#getPaidTab').click(function(){
			getPayments("paid");
			$('#currentPaymentSelection').val("paid");
		});
	}
	
	if ( $('#getCancelledTab').length ) { 
		$('#getCancelledTab').click(function(){
			getPayments("cancel");
			$('#currentPaymentSelection').val("cancel");
		});
	}
	
	if ( $('#getDisplayAllPayments').length ) { 
		$('#getDisplayAllPayments').click(function(){
			displayAllPayments();
		});
	}
	
	if ( $('#deleteOwner').length ) { 
		$('#deleteOwner').click(function(){
			bootbox.confirm("Do you really want to delete this owner?", function(result) {
				if (result)
				{
					deleteOwner();
				}
			}); 
		});
	}
	
	if ( $('#uploadAvatar').length ) { 
		$("#uploadAvatar").uploadFile({
			url:		"/ajax/media.php",
			fileName:	"myfile",
			multiple: 	true,
			doneStr:	"uploaded!",
			formData: {
					memberId: memberId,
					opt: 1 
				},
			onSuccess:function(files, data, xhr)
			{
				obj 			= JSON.parse(data);
				avatar		 	= obj.fileName;
				lastIdGallery 	= obj.lastId;
				$('#iconImg').attr('src', '/images/owners-profile/avatar/'+avatar);
				$('#userAvatarImg').attr('src', '/images/owners-profile/avatar/'+avatar);
			}
		});
	}
	
	$('#progressSaveMember').hide();
	$('#memberComplete').hide();
	
});

function saveMember()
{
	
	var memberFirst 	= $('#memberFirst').val(); 
	var memberLast		= $('#memberLast').val();
	var memberAddress	= $('#memberAddress').val();
	var notes		 	= $('#notes').val();
	var phoneOne		= $('#phoneOne').val();
	var phoneTwo		= $('#phoneTwo').val();
	var emailOne		= $('#emailOne').val();
	var emailTwo		= $('#emailTwo').val();
	var memberCondo		= $('#memberCondo').val();
	
	if (memberFirst)
	{
		$('#progressSaveMember').show();
		
		$.ajax({
	    type: "POST",
	    url: "/ajax/members.php",
	    data: {
	    	memberFirst: 	memberFirst,
	    	memberLast: 	memberLast, 
	    	memberAddress: 	memberAddress,
	    	phoneOne:		phoneOne,
	    	phoneTwo:		phoneTwo,
	    	emailOne:		emailOne,
	    	emailTwo:		emailTwo,
	    	notes:			notes,
	    	memberCondo:	memberCondo,
	    	opt:			'1'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#progressSaveMember').hide();
	        		$('#memberSave').hide();
	        		$('#memberComplete').attr('href', '/owner/'+info+'/new-member/')
	        		$('#memberComplete').show();
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function updateMember()
{
	var memberId		= $('#memberId').val();
	var memberFirst 	= $('#memberFirst').val(); 
	var memberLast		= $('#memberLast').val();
	var memberAddress	= $('#memberAddress').val();
	var notes		 	= $('#notes').val();
	var phoneOne		= $('#phoneOne').val();
	var phoneTwo		= $('#phoneTwo').val();
	var emailOne		= $('#emailOne').val();
	var emailTwo		= $('#emailTwo').val();
	var memberCondo		= $('#memberCondo').val();
	
	if (memberFirst)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/members.php",
	    data: {
	    	memberId:		memberId,
	    	memberFirst: 	memberFirst,
	    	memberLast: 	memberLast, 
	    	memberAddress: 	memberAddress,
	    	phoneOne:		phoneOne,
	    	phoneTwo:		phoneTwo,
	    	emailOne:		emailOne,
	    	emailTwo:		emailTwo,
	    	notes:			notes,
	    	memberCondo:	memberCondo,
	    	opt:			'2'
	    },
	    success:
	        function(info)
	        {
		    	if (info != '0')
	        	{
	        		pathArray 		= $(location).attr('href').split( '/' );
	            	newURL 			= pathArray[0]+'//'+pathArray[2]+'/'+pathArray[3]+'/'+pathArray[4]+'/'+pathArray[5]+'-'+Math.floor((Math.random() * 100) + 1)+'/#';
	            	window.location = newURL;
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function loadRoomData(node)
{
//	$('#categoryRoomList').select2();
//	$('#inventoryRoomList').select2();
	
	var currentRoom = $(node).attr('data-id');
	$('#currentRoom').val(currentRoom);
	getCategories();
	getPayments("pending");
	calculatePayments();
	displayAllPayments();
}

function getCategories()
{
	roomId = $('#currentRoom').val();
	
	$.ajax({
    type: "POST",
    url: "/ajax/members.php",
    data: {
    	roomId:	roomId,
    	opt:	8
    },
    success:
        function(info)
        {
        	if (info != '0')
        	{
        		$('#categoryRoomList option').remove();
//        		$('#categoryRoomList').select2('destroy');
        		$('#categoryRoomList').html(info);
        		$('#categoryRoomList').select2();
        		
        		$('#categoryRoomList').on("change", function(){
        			var categoryId = $('#categoryRoomList').val();
        			$('#currentCategory').val(categoryId);
        			updateInventoryOptionsRooms(categoryId);
        		});
        	}
        	else
			{
			}
        }
    });
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

function deleteOwner()
{
	var memberId = $('#memberId').val();
	
	if (memberId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/members.php",
	    data: {
	    	memberId:		memberId,
	    	opt:			10
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		pathArray 		= $(location).attr('href').split( '/' );
	        		newURL 			= pathArray[0]+'//'+pathArray[2]+'/dashboard/';
	            	window.location = newURL;
	        	}
	        	else
				{
				}
	        }
	    });
	}
}
