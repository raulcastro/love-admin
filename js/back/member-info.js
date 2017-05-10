$(document).ready(function()
{
	$('#addEmailField').click(function(){
		emailField = '<input type="text" value="" class="memberEmail" meid="0" />'
			+'<div class="clr"></div>';
		$('#memberEmails').append(emailField);
	});
	
	$('#addPhoneField').click(function(){
		phoneField = '<input type="text" value="" class="memberPhone" mpid="0" />'
			+'<div class="clr"></div>';
		$('#memberPhones').append(phoneField);
	});
	
	$('.save-member-info').click(function(){
		updateMember();
		saveEmails();
		savePhones();
	});
});

function updateMember()
{
	memberId			= $('#memberId').val();
	
	memberName 			= $('#memberName').val();
	memberAddress 		= $('#memberAddress').val();
	memberCompany 		= $('#memberCompany').val();
	memberPosition		= $('#memberPosition').val();
	memberCompanyType 	= $('#memberCompanyType').val();
	memberNotes 		= $('#memberNotes').val();

	$.ajax({
        type:   'POST',
        url:    '/ajax/back/members.php',
        data:{  memberId: 	memberId,
        	memberName: 	memberName,
        	memberAddress: 	memberAddress,
        	memberCompany: 	memberCompany,
        	memberPosition: memberPosition,
        	memberCompanyType: memberCompanyType,
        	memberNotes: 	memberNotes,
        	section:		'info'
             },
        success:
        function(xml)
        {
            if (0 != xml)
            {
            	
            }
        }
    });
}

function saveEmails()
{
	emailId 	= 0;
	emailVal 	= '';
	memberId 	= $('#memberId').val();
	
	$('.memberEmail').each(function(){
		emailId 	= 0;
		if ($(this).attr('meid'))
		{
			emailId		= $(this).attr('meid');
			emailVal	= $(this).val();
			
			$.ajax({
		        type:   'POST',
		        url:    '/ajax/back/members.php',
		        data:{  memberId: 	memberId,
		        	emailId: 		emailId,
		        	emailVal: 		emailVal,
		            section: 		'email'
		             },
		        success:
		        function(xml)
		        {
		            if (0 != xml)
		            {
		            	
		            }
		        }
		    });
		}
	});
}

function savePhones()
{
	phoneVal 	= '';
	memberId 	= $('#memberId').val();
	
	$('.memberPhone').each(function(){
		phoneId		= $(this).attr('mpid');
		phoneVal	= $(this).val();
		
		$.ajax({
	        type:   'POST',
	        url:    '/ajax/back/members.php',
	        data:{  memberId: 	memberId,
	        	phoneId: 		phoneId,
	        	phoneVal: 		phoneVal,
	            section: 		'phone'
	             },
	        success:
	        function(xml)
	        {
	            if (0 != xml)
	            {
	            	
	            }
	        }
	    });
	});
}