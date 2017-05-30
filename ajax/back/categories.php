<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Media_Model.php');
require_once($root.'/models/back/Layout_Model.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';

switch ($_GET['option'])
{
// 	Update settings
	case 1:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			$categoryId = 0;
			if ($categoryId = $model->addCategory($_POST))
				echo $categoryId;
			else 
				echo 0;
		}
	break;
	
	case 2:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($info = $model->getCategoryInfoById($_POST['catId']))
			{
				$data['categoryInfo'] = $info;
				$subcategories = $model->getSubcategoriesByCategoryId($_POST['catId']);
				$data['subcategories'] = $subcategories;
				
				echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
			}
		}
	break;
	
	case 3:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($model->updateCategory($_POST))
				echo 1;
			else
				echo 0;
		}
	break;
	
	case 4:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($model->deleteCategory($_POST))
				echo 1;
			else
				echo 0;
		}
	break;
	
	case 5:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			$lastSub = 0;
			if ($lastSub = $model->addSubcategory($_POST))
				echo $lastSub;
			else
				echo 0;
		}
	break;
}
?>