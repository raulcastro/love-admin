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
		
		if ($newMember = $model->addMember($_POST))
		{
			$model->addUser($_POST, $newMember);	
			echo str_pad($newMember, 4, 0, STR_PAD_LEFT);
		}
		else
		{
			echo 0;
		}
			
		
	break;
	
	case 2:
		if ($updateMember = $model->updateMember($_POST))
			echo str_pad($updateMember, 4, 0, STR_PAD_LEFT);
		else
			echo 0;
	break;
	
	// Add History
	case 4:
		var_dump($_POST);
		if ($_POST['memberId'])
		{
			if ($model->addHistory($_POST))
				echo 1;
			else 
				echo 0;
		}
	break;
	
	case 5:
		if ($_POST['memberId'])
		{
			if ($historyArray = $model->getHistoryEntries($_POST['memberId']))
			{
				foreach ($historyArray as $history)
				{
					?>
					<li>
						<div class="header"><?php echo $history['name']; ?> | <?php echo Tools::formatMYSQLToFront($history['date']).'  '.Tools::formatHourMYSQLToFront($history['time']); ?></div>
						<div>
							<i class="glyphicon glyphicon-option-vertical"></i>
							<div class="history-title">
								<span class="task-title-sp">
									<?php echo $history['history']; ?>
								</span>
							</div>
						</div>
					</li>
					<?php
				}
			}
		}
	break;
	
	case 6:
		if ($_POST['memberId'])
		{
			if ($model->addMemberTask($_POST))
				echo 1;
			else 
				echo 0;
		}
	break;
	
	case 7:
		if ($_POST['memberId'])
		{
			if ($memberTasksArray	= $model->getMemberTaskByMemberId($_POST['memberId']))
			{
				echo Layout_View::listTasks($memberTasksArray);
			}
		}
	break;
	
	case 8:
		
		if ($categoryArray = $model->getCategoriesInventoryByRoom($_POST['roomId']))
		{
			?>
			<option value="0">Category</option>
			<?php
			foreach ($categoryArray as $category)
			{
			?>
			<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category']; ?></option>
			<?php
			}
		}
		else 
		{
			?>
			<option value="0">Category empty</option>
			<?php 
		}
	break;
	
	case 9:
		
		if ($inventoryArray = $model->getInventoryByCategoryRoom($_POST['roomId'], $_POST['categoryId']))
		{
			?>
			<option value="0">Inventory</option>
			<?php
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
	
	case 10:
		if ($model->deleteOwner($_POST['memberId']))
		{
			echo 1;
		}
	break;
	
	default:
	break;
}