$(document).ready(function()
{
	$('.addCategory').click(function(){
		addCategory();
		return false;
	});
	
	$('#categoryList li a').click(function(){
		getCategoryInfo(this);
	});

	$('.update-category').click(function(){
		updateCategory();
		return false;
	});
	
	$('.delete-category').click(function(){
		deleteCategory();
		return false;
	});
	
	$('.addSubcategory').click(function(){
		addSubcategory();
		return false;
	});
	
});//Document ready ends here!

function addSubcategory()
{
	catId = $('#currentCategory').val();
	subName = $('#subcategoryName').val();
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/categories.php?option=5',
        data:{  
        	catId : catId,
        	subName: subName
             },
        success:
        function(data)
        {
            if ('0' != data)
            {
            	$('#subcategoryName').val('');
            	itemSub = '<li><a href="javascript:void(0);">'+subName+'</a></li>';
        		$('#subcategoryList').prepend(itemSub);
            }
        }
    });
}

function deleteCategory(){
	catId 			= $('#currentCategory').val();
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/categories.php?option=4',
        data:{  
        	catId : catId
             },
        success:
        function(data)
        {
            if ('1' == data)
            {
            	$('.categories-settings .right').hide();
            	$('#cat-'+catId).hide();
            }
        }
    });
}

function updateCategory()
{
	catId 			= $('#currentCategory').val();
	catName 		= $('#currentName').val();
	catTitle 		= $('#currentTitle').val();
	catDescription 	= $('#currentDescription').val();
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/categories.php?option=3',
        data:{  
        	catId			: catId,
        	catName			: catName,
        	catTitle		: catTitle,
        	catDescription	: catDescription
             },
        success:
        function(data)
        {
            if ('0' != data)
            {
            	bootbox.alert({
            	    message: "Category info has been succesfully updated! =)",
            	    size: 'small',
            	    backdrop: true
            	});
            }
        }
    });
}

function getCategoryInfo(node)
{
	$('#subcategoryList').html('');
	catId = $(node).attr('catId');
	$('#categoryList li a').removeClass('active');
	$(node).addClass('active');
	
	$.ajax({
        type:   'POST',
        url:    '/ajax/back/categories.php?option=2',
        data:{  
        	catId	: catId
             },
        success:
        function(data)
        {
            if ('0' != data)
            {
            	obj 		= JSON.parse(data);
            	$('#currentCategory').val(obj.categoryInfo.category_id);
            	$('#catName').html(obj.categoryInfo.name);
            	$('#currentName').val(obj.categoryInfo.name);
            	$('#currentTitle').val(obj.categoryInfo.title);
            	$('#currentDescription').val(obj.categoryInfo.description);
            	$(obj.subcategories).each(function(index, node){
            		itemSub = '<li><a href="javascript:void(0);">'+node.name+'</a></li>';
            		$('#subcategoryList').append(itemSub);
            	});
            }
        }
    });
	
	$('#categorySettingsRight').show();
}

function addCategory()
{
	categoryName = $('#categoryName').val();
	
    $.ajax({
        type:   'POST',
        url:    '/ajax/back/categories.php?option=1',
        data:{  
        	categoryName	: categoryName
             },
        success:
        function(xml)
        {
            if ('0' != xml)
            {
            	newCategory = '<li id="cat-'+xml+'"><a href="javascript:void(0);" catId="'+xml+'">'+categoryName+'</a></li>';
            	$('#categoryList').prepend(newCategory);
            	$('#categoryName').val('');
            	$('#categoryList li a').click(function(){
            		getCategoryInfo(this);
            	});
            }
        }
    });
}


















