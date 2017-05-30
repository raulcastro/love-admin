$(document).ready(function()
{
	$('.update-settings').click(function(){
		updateSettings();
		
		return false;
	});

});//Document ready ends here!

function updateSettings()
{
	siteTittle		= $('#siteTittle').val();
	siteName		= $('#siteName').val();
	siteUrl			= $('#siteUrl').val();
	siteContent		= $('#siteContent').val();
	siteDescription	= $('#siteDescription').val();
	siteKeywords	= $('#siteKeywords').val();
	siteEmail		= $('#siteEmail').val();
	siteLocation	= $('#siteLocation').val();
	siteTwitter		= $('#siteTwitter').val();
	siteFacebook	= $('#siteFacebook').val();
	siteGoogleplus	= $('#siteGoogleplus').val();
	sitePinterest	= $('#sitePinterest').val();
	siteLinkedin	= $('#siteLinkedin').val();
	siteYoutube		= $('#siteYoutube').val();
	siteInstagram	= $('#siteInstagram').val();
	
    $.ajax({
        type:   'POST',
        url:    '/ajax/back/settings.php?option=1',
        data:{  
        	siteTittle		: siteTittle,
        	siteName		: siteName,	
        	siteUrl			: siteUrl,			
        	siteContent		: siteContent,		
        	siteDescription	: siteDescription,	
        	siteKeywords	: siteKeywords,	
        	siteEmail		: siteEmail,	
        	siteLocation	: siteLocation,	
        	siteTwitter		: siteTwitter,		
        	siteFacebook	: siteFacebook,	
        	siteGoogleplus	: siteGoogleplus,	
        	sitePinterest	: sitePinterest,	
        	siteLinkedin	: siteLinkedin,	
        	siteYoutube		: siteYoutube,		
        	siteInstagram	: siteInstagram	
             },
        success:
        function(xml)
        {
            if ('1' == xml)
            {
//            	$('#cgid-'+pictureId).fadeOut();
            	bootbox.alert({
            	    message: "The general info has been succesfully updated! =)",
            	    size: 'small',
            	    backdrop: true
            	});
            }
        }
    });
}


















