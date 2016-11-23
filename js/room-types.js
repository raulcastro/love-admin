$(function(){
	
	if ( $('#addRoomType').length ) { 
		$('#addRoomType').click(function(){
			addRoomType();
			return false;
		});
	}
	
	if ( $('.deleteType').length ) { 
		$('.deleteType').click(function(){
			var typeId = $(this).attr("data-id");
			bootbox.confirm("Do you really want to delete this room type?", function(result) {
				if (result)
				{
					deleteType(typeId);
				}
			}); 
			return false;
		});
	}
});

function addRoomType()
{
	var roomTypeName = $('#roomTypeName').val();
	var roomTypeDescription = $('#roomTypeDescription').val();

	if (roomTypeName)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/room-types.php",
	    data: {
	    	roomTypeName: 	roomTypeName,
	    	roomTypeDescription: 	roomTypeDescription, 
	    	opt:			'1'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		var item = '<li id="typeId'+info+'"><a href="#">'+roomTypeName+' <span class="pull-right badge bg-red"><i class="fa fa-close deleteType" data-id="'+info+'"></i></span></a></li>'
	        		$('#roomTypesBox').prepend(item);
	        		$('#roomTypeName').val('');
	        		$('#roomTypeDescription').val('');
	        		
	        		$('.deleteType').click(function(){
	        			var typeId = $(this).attr("data-id");
	        			bootbox.confirm("Do you really want to delete this room type?", function(result) {
	        				if (result)
	        				{
	        					deleteType(typeId);
	        				}
	        			}); 
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

function deleteType(typeId)
{
	if (typeId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/room-types.php",
	    data: {
	    	typeId: typeId,
	    	opt:	'2'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#typeId'+typeId).remove();
	        	}
	        	else
				{
				}
	        }
	    });
	}
}