<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Media_Model.php');
require_once($root.'/models/back/Layout_Model.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';

$model	= new Layout_Model();
$data 	= $backend->loadBackend();

switch ($_GET['option'])
{
// 	**************************************************** Main Gallery **************************************************** 
// 	Upload main Gallery
	case 1:
		$allowedExtensions = array("jpg", "JPG", "jpeg", "png");
		$sizeLimit 	= 20 * 1024 * 1024;
		
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
		
		$savePath 		= $root.'/img-up/main-gallery/original/';
		$medium 		= $root.'/img-up/main-gallery/medium/';
		$pre	  		= Tools::slugify($data['appInfo']['siteName']);
		$mediumWidth 	= 900;
		
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
				
				$lastId = $model->addSlider($result['fileName']);
			}
		
			$data  = array('success'=>true, 'fileName'=>$result['fileName'],
					'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
		
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
	break;

// 	Crop
	case 2:
		$model	= new Media_Model();
		$data 	= $backend->loadBackend();
		
		if (!empty($_POST))
		{
			$dstWidth 		= 1920;
			$dstImageHeight = 852;
			
			$imgId = '';
			
			if (isset($_POST['imgId']))
			{
				$imgId = $_POST['imgId'];
			}
			
			
			$source 		= $root.'img-up/main-gallery/original/'.$imgId;
			$destination 	= $root.'img-up/main-gallery/front/'.$imgId;
			
			if ($model -> cropImage($_POST, $dstWidth, $dstImageHeight, $source, $destination))
			{
				if ($model->getThumb($imgId, $root.'img-up/main-gallery/front/', $root.'img-up/main-gallery/thumb/', 200, 'width', ''))
				{
					echo '1';
				}
				else
				{
					echo '0';
				}
			}
		}
	break;
	
	// 	Update
	case 3:
		$model	= new Layout_Model();
	
		if (!empty($_POST))
		{
			$model->editSliderInfo($_POST);
		}
	break;
	
	// 	Delete
	case 4:
		$model	= new Layout_Model();
	
		if (!empty($_POST))
		{
			if ($model->deleteSlider($_POST['sliderId']))
				echo 1;
		}
	break;
	
// 	**************************************************** Main Sliders ****************************************************
	
	// 	Upload
	case 5:
		$model	= new Layout_Model();
		$data 	= $backend->loadBackend();
	
		$allowedExtensions = array("jpg", "JPG", "jpeg", "png");
		$sizeLimit 	= 20 * 1024 * 1024;
	
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
	
		$savePath 		= $root.'/img-up/main-gallery/original/';
		$medium 		= $root.'/img-up/main-gallery/medium/';
		$pre	  		= Tools::slugify($data['appInfo']['siteName']);
		$mediumWidth 	= 900;
	
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
	
				$lastId = $model->addGeneralSlider($result['fileName']);
			}
	
			$data  = array('success'=>true, 'fileName'=>$result['fileName'],
					'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
	
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
		break;
	
		// 	Crop
	case 6:
		$model	= new Media_Model();
		$data 	= $backend->loadBackend();
	
		if (!empty($_POST))
		{
			$dstWidth 		= 1076;
			$dstImageHeight = 1000;
				
			$imgId = '';
				
			if (isset($_POST['imgId']))
			{
				$imgId = $_POST['imgId'];
			}
				
				
			$source 		= $root.'img-up/main-gallery/original/'.$imgId;
			$destination 	= $root.'img-up/main-gallery/front/'.$imgId;
				
			if ($model -> cropImage($_POST, $dstWidth, $dstImageHeight, $source, $destination))
			{
				if ($model->getThumb($imgId, $root.'img-up/main-gallery/front/', $root.'img-up/main-gallery/thumb/', 200, 'width', ''))
				{
					echo '1';
				}
				else
				{
					echo '0';
				}
			}
		}
		break;
	
		// 	Update
	case 7:
		$model	= new Layout_Model();
	
		if (!empty($_POST))
		{
			$model->editGeneralSliderInfo($_POST);
		}
		break;
	
		// 	Delete
	case 8:
		$model	= new Layout_Model();
	
		if (!empty($_POST))
		{
			if ($model->deleteGeneralSlider($_POST['sliderId']))
				echo 1;
		}
		break;
}
?>