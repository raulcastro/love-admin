$(function(){
	if ( $('#uploadAvatar').length ) { 
		$("#uploadAvatar").uploadFile({
			url:		"/ajax/media.php",
			fileName:	"myfile",
			multiple: 	true,
			doneStr:	"uploaded!",
			formData: {
					opt: 3 
				},
			onSuccess:function(files, data, xhr)
			{
				obj 			= JSON.parse(data);
				avatar		 	= obj.fileName;
				lastIdGallery 	= obj.lastId;
				$('#iconImg').attr('src', '/images/owners-profile/avatar/'+avatar);
				$('#userAvatarImg').attr('src', '/images/owners-profile/avatar/'+avatar);
				$('#avatarSide').attr('src', '/images/owners-profile/avatar/'+avatar);
				$('#avatarUp').attr('src', '/images/owners-profile/avatar/'+avatar);
				$('#avatarUpLittle').attr('src', '/images/owners-profile/avatar/'+avatar);
			}
		});
	}
});