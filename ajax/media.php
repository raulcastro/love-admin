<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Layout_Model.php');
require_once($root.'/models/back/Media_Model.php');
require_once($root.'/views/Layout_View.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';
$model	= new Layout_Model();

if (isset($_POST['memberId']))
{
	$memberId = (int) $_POST['memberId'];
}
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

	case 4:
		
		$destinationId = (int) $_POST['destination'];
	
		$sizeLimit 	= 20 * 1024 * 1024;
	
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
	
		$savePath 		= $root.'/img-up/destinations/original/';
		$medium 		= $root.'/img-up/destinations/avatar/';
		$pre	  		= 'avatar';
		$mediumWidth 	= 400;
	
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
				$model->updateDestinationPhoto($result['fileName'], $destinationId);
				$data  = array('success'=>true, 'fileName'=>$result['fileName'],
						'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
			}
	
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
	break;
	
	// Extras cover
	case 5:
		$destinationId = (int) $_POST['extra'];
	
		$sizeLimit 	= 20 * 1024 * 1024;
	
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
	
		$savePath 		= $root.'/img-up/extras/original/';
		$medium 		= $root.'/img-up/extras/avatar/';
		$pre	  		= 'avatar';
		$mediumWidth 	= 400;
	
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
				$model->updateExtraPhoto($result['fileName'], $destinationId);
				$data  = array('success'=>true, 'fileName'=>$result['fileName'],
						'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
			}
	
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
	break;
	
	// Experiences cover
	case 6:
		$experienceId = (int) $_POST['experience'];
	
		$sizeLimit 	= 20 * 1024 * 1024;
	
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
	
		$savePath 		= $root.'/img-up/experiences/original/';
		$medium 		= $root.'/img-up/experiences/avatar/';
		$pre	  		= 'avatar';
		$mediumWidth 	= 400;
	
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
				$model->updateExperiencesPhoto($result['fileName'], $experienceId);
				$data  = array('success'=>true, 'fileName'=>$result['fileName'],
						'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
			}
	
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
	break;
	
	// Experiences swipper
	case 7:
		$experienceId = (int) $_POST['experience'];
	
		$sizeLimit 	= 20 * 1024 * 1024;
	
		$uploader 	= new Media_Model($allowedExtensions, $sizeLimit);
	
		$savePath 		= $root.'/img-up/experiences/original/';
		$medium 		= $root.'/img-up/experiences/avatar/';
		$pre	  		= 'avatar';
		$mediumWidth 	= 400;
	
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
				$model->updateExperiencesSwiper($result['fileName'], $experienceId);
				$data  = array('success'=>true, 'fileName'=>$result['fileName'],
						'wp'=>$wp, 'hp'=>$hp, 'lastId'=>$lastId);
			}
	
			echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
		}
	break;
	
	default:
	break;
}