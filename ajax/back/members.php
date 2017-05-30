<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Media_Model.php');
require_once($root.'/models/back/Layout_Model.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';

switch ($_POST['section'])
{
	// 	Info
	case 'info':
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			$model->updateMemberInfo($_POST);
		}
	break;
	
	// 	Email
	case 'email':
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($_POST['emailId'] == 0)
			{
				$model->addMemberEmail($_POST);
			}
			else 
			{
				$model->updateMemberEmail($_POST);
				
			}
		}
	break;
	
	// 	Phones
	case 'phone':
		$model	= new Layout_Model();
		if (!empty($_POST))
		{
			if ($_POST['phoneId'] == 0)
			{
				$model->addMemberPhone($_POST);
			}
			else
			{
				$model->updateMemberPhone($_POST);
	
			}
		}
	break;
	
}
?>