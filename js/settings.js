$(function(){
	
	if ( $('#addCategory').length ) { 
		$('#addCategory').click(function(){
			addCategory();
			return false;
		});
	}
	
	if ( $('#updateCategory').length ) { 
		$('#updateCategory').click(function(){
			updateCategory();
			return false;
		});
	}
	
	if ( $('#addInventory').length ) { 
		$('#addInventory').click(function(){
			addInventory();
			return false;
		});
	}
	
	if ( $('.deleteInventory').length ) { 
		$('.deleteInventory').click(function(){
			var inventoryId = $(this).attr("data-id");
			bootbox.confirm("Do you really want to delete this inventory?", function(result) {
				if (result)
				{
					deleteInventory(inventoryId);
				}
			}); 
		});
	}
	
	if ( $('#deleteCategory').length ) { 
		$('#deleteCategory').click(function(){
			bootbox.confirm("Do you really want to delete this category?", function(result) {
				if (result)
				{
					deleteCategory();
				}
			}); 
		});
	}
});

function addCategory()
{
	var categoryName = $('#categoryName').val();
	var categoryDescription = $('#categoryDescription').val();
	
	if (categoryName)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/settings.php",
	    data: {
	    	categoryName: 	categoryName,
	    	categoryDescription: 	categoryDescription, 
	    	opt:			'1'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		var item = '<li><a href="/edit-inventory-category/'+info+'/">'+categoryName+'</a></li>'
	        		$('#categoryBox').prepend(item);
	        		$('#categoryName').val('');
	        		$('#categoryDescription').val('');
	        	}
	        	else
				{
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
//	        		var item = '<li><a href="/edit-inventory-category/'+info+'/">'+categoryName+'</a></li>'
//	        		$('#categoryBox').prepend(item);
//	        		$('#categoryName').val('');
//	        		$('#categoryDescription').val('');
	        		alert('Category updated!');
	        	}
	        	else
				{
				}
	        }
	    });
	}
}

function deleteCategory()
{
	var categoryId	= $('#categoryId').val();
	
	if (categoryId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/settings.php",
	    data: {
	    	categoryId: categoryId,
	    	opt:		'5'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		pathArray 		= $(location).attr('href').split( '/' );
	        		newURL 			= pathArray[0]+'//'+pathArray[2]+'/settings/';
	            	window.location = newURL
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
	        		var item = '<li id="inventoryId'+info+'"><a href="#">'+inventoryName+' <span class="pull-right badge bg-red"><i class="fa fa-close deleteInventory" data-id="'+info+'"></i></span></a></a></li>'
	        		$('#inventoryBox').prepend(item);
	        		$('#inventoryName').val('');
	        		$('#inventoryDescription').val('');
	        		
	        		$('.deleteInventory').click(function(){
	        			var inventoryId = $(this).attr("data-id");
	        			bootbox.confirm("Do you really want to delete this inventory?", function(result) {
	        				if (result)
	        				{
	        					deleteInventory(inventoryId);
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

function deleteInventory(inventoryId)
{
	if (inventoryId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/settings.php",
	    data: {
	    	inventoryId: inventoryId,
	    	opt:	'4'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#inventoryId'+inventoryId).remove();
	        	}
	        	else
				{
				}
	        }
	    });
	}
}