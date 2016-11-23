$(function(){
	
	if ( $('#addCondo').length ) { 
		$('#addCondo').click(function(){
			addCondo();
			return false;
		});
	}
	
	if ( $('.deleteCondo').length ) { 
		$('.deleteCondo').click(function(){
			var condoId = $(this).attr("data-id");
			bootbox.confirm("Do you really want to delete this condo?", function(result) {
				if (result)
				{
					deleteCondo(condoId);
				}
			}); 
		});
	}
});

function addCondo()
{
	var condoName = $('#condoName').val();
	var condoDescription = $('#condoDescription').val();

	if (condoName)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/condos.php",
	    data: {
	    	condoName: 	condoName,
	    	condoDescription: 	condoDescription, 
	    	opt:			'1'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		var item = '<li id="condoId'+info+'"><a href="#">'+condoName+' <span class="pull-right badge bg-red"><i class="fa fa-close deleteCondo" data-id="'+info+'"></i></span></a></li>'
	        		$('#condoBox').prepend(item);
	        		$('#condoName').val('');
	        		$('#condoDescription').val('');
	        		
	        		$('.deleteCondo').click(function(){
	        			var condoId = $(this).attr("data-id");
	        			bootbox.confirm("Do you really want to delete this condo?", function(result) {
	        				if (result)
	        				{
	        					deleteCondo(condoId);
	        				}
	        			}); 
	        		});
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function deleteCondo(condoId)
{
	if (condoId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/condos.php",
	    data: {
	    	condoId: 			condoId,
	    	opt:			'2'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#condoId'+condoId).remove();
	        	}
	        	else
				{
				}
	        }
	    });
	}
}