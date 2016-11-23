$(function(){
	if ( $('#sendEmailOwner').length ) { 
		$('#sendEmailOwner').click(function(){
			sendEmail();
			return false;
		});
	}
	
	
});

function sendEmail()
{
	var sendEmailTo 		= $('#sendEmailTo').val();
	var sendEmailSubject 		= $('#sendEmailSubject').val();
	var sendEmailContent = $('#sendEmailContent').val();

	if (sendEmailTo)
	{
		$.ajax({
	    type: "POST",
	    url: "/email/send-email.php",
	    data: {
	    	sendEmailTo: 			sendEmailTo,
	    	sendEmailSubject: 	sendEmailSubject,
	    	sendEmailContent: 	sendEmailContent
	    },
	    success:
	        function(info)
	        {
	        	if (info == 'success')
	        	{
	        		alert("Your message has been sent");
//	        		$('#sendEmailTo').val('');
	        		$('#sendEmailSubject').val('');
	        		$('#sendEmailContent').val('');
	        	}
	        }
	    });
	}
}