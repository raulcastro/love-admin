<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Layout_Model.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';


$model	= new Layout_Model();

switch ($_POST['opt'])
{
	/// add extra
	case 1:
		if (!empty($_POST))
		{
			if ($destinationId = $model->addExtra($_POST))
				echo $destinationId;
			else
				echo 0;
		}
	break;
	
// 	update destination
	case 2:
		if (!empty($_POST))
		{
			if ($model->updateExtra($_POST))
				echo 1;
			else
				echo 0;
		}
	break;
	
	case 3:
		if (!empty($_POST))
		{
			if ($model->deleteExtra($_POST['extraId']))
				echo 1;
				else
					echo 0;
		}
	break;
	
	default:
	break;
}