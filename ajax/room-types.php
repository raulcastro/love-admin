<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Layout_Model.php');
require_once($root.'/views/Layout_View.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';
$model	= new Layout_Model();


$memberId = (int) $_POST['memberId'];

switch ($_POST['opt'])
{
	
	case 1:
		if ($last = $model->addRoomTypes($_POST))
		{
			echo $last;
		}
		else 
		{
			echo 0;
		}
	break;
	
	case 2:
		if ($model->deleteType($_POST['typeId']))
		{
			echo 1;
		}
	break;
	
	default:
	break;
}