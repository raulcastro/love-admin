$(function(){
//	common scripts
//	$('#x-show-search').click(function(){
//		showSearchBar();
//		return false;
//	});
//	
//	$('#x-hide-search').click(function(){
//		hideSearchBar();
//		return false;
//	});
	
	if ( $('#sign-in').length ) { 
		$('#login').click(function(){
			$('#slick-login').submit();
			return false;
		});
	}
	
});

function showSearchBar()
{
	$('#x-scopes-bar').slideUp('slow');
	$('#x-search').slideDown('slow');
	$('#input-search').focus();
}

function hideSearchBar()
{
	$('#x-scopes-bar').slideDown('slow');
	$('#x-search').slideUp('slow');
}

function check(e)
{
    key = (document.all) ? e.keyCode : e.which;
    
    if (13 == key)
    {
        $('#slick-login').submit();
    }
}

