<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Layout_Model.php');
require_once($root.'/models/back/Media_Model.php');
require_once($root.'/views/Layout_View.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';
$model	= new Layout_Model();

$memberId = (int) $_POST['memberId'];
$model	= new Layout_Model();
$data 	= $backend->loadBackend();
$allowedExtensions = array("jpg", "JPG", "jpeg", "png");

switch ($_POST['opt'])
{
// 	Add Slider
	case 1: 
		
		$sizeLimit 	= 20 * 1024 * 1024;
		
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
		
		$savePath 		= $root.'/images/owners-profile/original/';
		$medium 		= $root.'/images/owners-profile/avatar/';
		$pre	  		= 'avatar';
		$mediumWidth 	= 128;
		
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
				$model->updateMemberAvatar($result['fileName'], $memberId);
				$data  = array('success'=>true, 'fileName'=>$result['fileName'],
						'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
			}
		
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
	break;
	
	case 2:
		$allowedExtensions = array("pdf", "PDF", "doc", "DOC", "jpg", "JPG", "jpeg", "JPEG", "docx", "pps", "ppsx", "xml", "xmlx", "rtfd");
		$memberId = (int) $_POST['memberId'];
		$sizeLimit 	= 20 * 1024 * 1024;
		
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
		
		$savePath 	= $root.'/uploads/documents/';
		$pre		= Tools::getRandom(6);
		
		if ($result = $uploader->handleUpload($savePath, $pre))
		{
			
			$data = array('success'=>true, 'fileName'=>$result['fileName']);
		}
		
		echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
	break;
	
	case 3:
	
		$sizeLimit 	= 20 * 1024 * 1024;
	
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
	
		$savePath 		= $root.'/images/owners-profile/original/';
		$medium 		= $root.'/images/owners-profile/avatar/';
		$pre	  		= 'avatar';
		$mediumWidth 	= 128;
	
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
				$model->updateUserAvatar($result['fileName']);
				$data  = array('success'=>true, 'fileName'=>$result['fileName'],
						'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
			}
	
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
	break;
	
	default:
	break;
}