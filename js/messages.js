$(function(){
	if ( $('#addChatMessage').length ) { 
		$('#addChatMessage').click(function(){
			addMessage();
			return false;
		});
	}
	
	$("#chatMessage").keyup(function(event){
	    if(event.keyCode == 13){
	    	addMessage();
	    }
	});
	
	if ( $('#tabMessageSender').length ) { 
		$('#tabMessageSender').click(function(){
			scrollToBottom();
		});
	}
});

function scrollToBottom()
{
	var elem = document.getElementById('boxChat');
	elem.scrollTop = elem.scrollHeight;
}

function addMessage()
{
	var memberId 	= $('#memberId').val();
	var chatMessage = $('#chatMessage').val();

	if (chatMessage)
	{
		$.ajax({
	    type: "POST",
	    url: "/ajax/messages.php",
	    data: {
	    	memberId: 	memberId,
	    	message: 	chatMessage,
	    	opt:			'1'
	    },
	    success:
	        function(info)
	        {
	        	if (info != '0')
	        	{
	        		data = JSON.parse(info);
	        		
	        		var avatar = $('#avatarSide').attr('src');
	        		var item = '<div class="direct-chat-msg "> ' +
	        					'	<div class="direct-chat-info clearfix"> ' +
								'		<span class="direct-chat-name pull-left">'+data.user_name+'</span> ' +
								'		<span class="direct-chat-timestamp pull-right">'+data.date+'</span> ' +
								'	</div> ' +
								'	<img class="direct-chat-img" src="'+avatar+'" alt="message user image"> ' +
								'	<div class="direct-chat-text"> ' +
										data.message +
								'	</div> ' +
								'</div>';
	        		
	        		$('#boxChat').append(item);
	        		$('#chatMessage').val('');
	        		scrollToBottom();
	        	}
	        	else
				{
				}
	        }
	    });
	}
}