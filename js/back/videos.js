$(document).ready(function()
{
	$('.addVideo').click(function(){
		addVideo();
	});
	
	$('.delete').click(function(){
		deleteVideo(this);
	});
	
});//Document ready ends here!

function addVideo()
{
	videoURL 	= $('#videoURL').val();
	youtubeUrl 	= videoURL.match( /watch\?v=([a-zA-Z0-9\-_]+)/ ),
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/videos.php?option=1',
        data:{  
        	video : youtubeUrl[1] 
             },
        success:
        function(data)
        {
            if ('0' != data)
            {
            	obj			= JSON.parse(data);
            	videoTitle 	= obj.title[0];
            	videoImage 	= obj.image;
            	videoId 	= obj.id;
            	
            	itemVideo = '<li id="video-'+videoId+'">'
				+'<article class="protip">'
				+'<header>'
				+'<div class="img-cover">'	
				+'<a href="https://www.youtube.com/watch?v='+youtubeUrl[1]+'" rel="youtube" class="title">'
				+'<img src="'+videoImage+'"'
				+'class="protip_li_img"/>'
				+'</a>'
				+'</div>'
				+'</header>'
				+'<div class="clr"></div>'
				+'<a href="https://www.youtube.com/watch?v='+youtubeUrl[1]+'" rel="youtube" class="title " style="font-size: 1.2em; font-weight: bold;">'
				+videoTitle
				+'</a>'
				+'<a href="javascript:void(0);" vid="'+videoId+'" class="delete">delete</a>'
				+'</article>'
				+'</li>';
            	
            	$('.videos').prepend(itemVideo);
            	$('#videoURL').val('');
            }
        }
    });
}

function deleteVideo(node)
{
	videoId = $(node).attr('vid');
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/videos.php?option=2',
        data:{  
        	videoId : videoId
             },
        success:
        function(data)
        {
            if ('1' == data)
            {
            	$('#video-'+videoId).hide();
            }
        }
    });
}