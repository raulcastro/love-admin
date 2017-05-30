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
			$locationId = 0;
			if ($locationId = $model->addLocation($_POST))
				echo $locationId;
			else 
				echo 0;
		}
	break;
	
	case 2:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($info = $model->getLocationInfoById($_POST['locId']))
			{
				$data['locationInfo'] = $info;
				
				echo htmlspecialchars(json_encode($data), ENT_NOQUOTES);
			}
		}
	break;
	
	case 3:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($model->updateLocation($_POST))
				echo 1;
			else
				echo 0;
		}
	break;
	
	case 4:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($model->deleteLocation($_POST))
				echo 1;
			else
				echo 0;
		}
	break;
	
}
?>