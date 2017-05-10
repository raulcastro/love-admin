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
			$videoInfo = array();
			if ($videoInfo = $model->addVideo($_POST['video']))
				echo htmlspecialchars(json_encode($videoInfo), ENT_NOQUOTES);
			else 
				echo 0;
		}
	break;
	
	case 2:
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($model->deleteVideo($_POST['videoId']))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
	break;	
}
?>