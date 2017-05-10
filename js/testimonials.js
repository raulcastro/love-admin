$(function(){
	
	if ( $('#addTestimonial').length ) { 
		$('#addTestimonial').click(function(){
			addTestimonial();
			return false;
		});
	}
	
	
	if ( $('.delete-testimonial').length ) { 
		$('.delete-testimonial').click(function(){
			var testimonialId = $(this).attr('data-id');
			bootbox.confirm("Do you really want to delete this testimonial?", function(result) {
				if (result)
				{
					deleteTestimonial(testimonialId);
				}
			}); 
			return false;
		});
	}
	
});

function addTestimonial()
{
	var testimonialName = $('#testimonialName').val();
	var testimonialDescription = $('#testimonialDescription').val();
	
	if (testimonialName)
	{
		$.ajax({
		    type: "POST",
		    url: "/ajax/testimonials.php",
		    data: {
		    	testimonialName:		testimonialName,
		    	testimonialDescription: testimonialDescription,
		    	opt:					1
		    },
		    success:
		        function(info)
		        {
		        	if (info != '0')
		        	{
		        		$('#testimonialName').val('');
		        		$('#testimonialDescription').val('');
		        		
		        		getAllTestimonials();
		        		
		        	}
		        	else
					{
					}
		        }
		    });
	}
}

function getAllTestimonials()
{
	$.ajax({
    type: "POST",
    url: "/ajax/testimonials.php",
    data: {
    	opt:			3
    },
    success:
        function(info)
        {
        	if (info != '0')
        	{
//        		alert('Destination updated!');
        		$('#testimonialsBox').html(info);
        		
        		if ( $('.delete-testimonial').length ) { 
        			$('.delete-testimonial').click(function(){
        				var testimonialId = $(this).attr('data-id');
        				bootbox.confirm("Do you really want to delete this testimonial?", function(result) {
        					if (result)
        					{
        						deleteTestimonial(testimonialId);
        					}
        				}); 
        				return false;
        			});
        		}
        	}
        	else
			{
			}
        }
    });
}

function deleteTestimonial(testimonialId)
{
	if (testimonialId)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/testimonials.php",
	    data: {
	    	testimonialId:		testimonialId,
	    	opt:			2
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		$('#testimonial-'+testimonialId).remove();
//	        		pathArray 		= $(location).attr('href').split( '/' );
//	        		newURL 			= pathArray[0]+'//'+pathArray[2]+'/destinations/';
//	            	window.location = newURL;
	        	}
	        	else
				{
				}
	        }
	    });
	}
}
