function saveSliderData(sliderId)
{
	title 	= $('#sid-'+sliderId+' .slider-title').val();
	link 	= $('#sid-'+sliderId+' .slider-link').val();
	promos 	= $('#sid-'+sliderId+' .slider-promos').val();
	
    $.ajax({
        type:   'POST',
        url:    '/ajax/back/main-sliders.php?option=3',
        data:{  title: title,
                link: link,
                promos: promos,
                sliderId: sliderId
             },
        success:
        function(xml)
        {
            if (0 != xml)
            {
//            	$('.main-slider-upload').fadeOut();
            }
        }
    });
}

function deleteSlider(sliderId)
{
    $.ajax({
        type:   'POST',
        url:    '/ajax/back/main-sliders.php?option=4',
        data:{  
                sliderId: sliderId
             },
        success:
        function(xml)
        {
            if ('1' == xml)
            {
            	$('#sid-'+sliderId).fadeOut();
            }
        }
    });
}

function saveMainSliderData(sliderId)
{
	title 	= $('#sid-'+sliderId+' .slider-title').val();
	link 	= $('#sid-'+sliderId+' .slider-link').val();
	promos 	= $('#sid-'+sliderId+' .slider-promos').val();
	
    $.ajax({
        type:   'POST',
        url:    '/ajax/back/main-sliders.php?option=7',
        data:{  title: title,
                link: link,
                promos: promos,
                sliderId: sliderId
             },
        success:
        function(xml)
        {
            if (0 != xml)
            {
//            	$('.main-slider-upload').fadeOut();
            }
        }
    });
}

function deleteMainSlider(sliderId)
{
    $.ajax({
        type:   'POST',
        url:    '/ajax/back/main-sliders.php?option=8',
        data:{  
                sliderId: sliderId
             },
        success:
        function(xml)
        {
            if ('1' == xml)
            {
            	$('#sid-'+sliderId).fadeOut();
            }
        }
    });
}