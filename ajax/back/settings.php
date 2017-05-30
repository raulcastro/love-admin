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
			if ($model->updateSettings($_POST))
				echo 1;
		}
	break;
}
?>