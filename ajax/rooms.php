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
		if ($last = $model->addRoom($_POST))
		{
			echo $last;
		}
		else 
		{
			echo 0;
		}
	break;
	
	case 2:	 
		if ($model->updateInventoryCategory($_POST))
		{
			echo 1;
		}
	break;
	
	case 3:
		if ($last = $model->addInventory($_POST))
		{
			echo $last;
		}
		else 
		{
			echo 0;
		}
	break;
	
	case 4:
		if ($model->updateRoom($_POST))
		{
			echo 1;
		}
	break;
	
	case 5:
		if ($inventoryArray = $model->getInventoryByCategory($_POST['categoryId']))
		{
			foreach ($inventoryArray as $inventory)
			{
				?>
			<option value="<?php echo $inventory['inventory_id']; ?>"><?php echo $inventory['inventory']; ?></option>
				<?php
			}
		}
		else 
		{
			?>
			<option value="0">Inventory empty</option>
			<?php 
		}
	break;
	
	case 6:
		if (!$model->addRoomInventory($_POST))
		{
			echo 0;
		}
	break;

	case 7:
		if ($inventoryArray = $model->getRoomInventoryByRoom($_POST['roomId']))
		{
			foreach ($inventoryArray as $inventory)
			{
				?>
			<li><a href="#"><?php echo $inventory['category'].' / '.$inventory['inventory']; ?></a></li>
				<?php
			}
		}
		else 
		{
			echo 0;
		}
	break;
	
	case 8:
		if ($model->addMemberRoom($_POST))
		{
			echo 1;
		}
	break;
	
	case 9:
		if ($model->deleteMemberRoom($_POST))
		{
			echo 1;
		}
	break;
	
	case 10:
		if ($model->deleteRoom($_POST['roomId']))
		{
			echo 1;
		}
	break;
	
	default:
	break;
}