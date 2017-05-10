<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Media_Model.php');
require_once($root.'/models/back/Layout_Model.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';

switch ($_GET['option'])
{
// 	Upload 
	case 1:
		$model	= new Layout_Model();
		$data 	= $backend->loadBackend();
		
		$allowedExtensions = array("jpg", "JPG", "jpeg", "png");
		$sizeLimit 	= 20 * 1024 * 1024;
		
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
		
		$savePath 		= $root.'/img-up/companies_pictures/original/';
		$medium 		= $root.'/img-up/companies_pictures/galery/';
		$pre	  		= 'gallery-'.Tools::slugify($_POST['companyName']);
		$mediumWidth 	= 200;
		
		if ($result = $uploader->handleUpload($savePath, $pre))
		{
			$uploader->getThumb($result['fileName']	, $savePath, $medium, $mediumWidth,
					'width', '');
		
			$newData = getimagesize($medium.$result['fileName']);
		
			$wp     = $newData[0];
			$hp     = $newData[1];
			
			
			$lastId = 0;
			
			if ($newData)
			{
				$lastId = $model->addCompanyGallery($_POST['companyId'], $result['fileName']);
			}
		
			$data  = array('success'=>true, 'fileName'=>$result['fileName'],
					'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
		
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
	break;

// 	Delete
	case 2:
		$model	= new Layout_Model();
	
		if (!empty($_POST))
		{
			if ($model->deleteCompanyGallery($_POST['pictureId']))
				echo 1;
		}
	break;
	
}
?>