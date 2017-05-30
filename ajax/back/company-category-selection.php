<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Media_Model.php');
require_once($root.'/models/back/Layout_Model.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';

$model	= new Layout_Model();
$data 	= $backend->loadBackend();

switch ($_GET['option'])
{
// 	get subcategories by category_id 
	case 1:
		$subcategories = $model->getSubcategoriesByCategoryId($_POST['category']);
		if (sizeof($subcategories) > 0)
		{
			?>
			<ul>
   				<?php 
   				foreach ($subcategories as $subcategory) {
   				?>
   				<li><a href="javascript: void(0);" id="sub_<?php echo $subcategory['subcategory_id']; ?>" subcategory="<?php echo $subcategory['subcategory_id']; ?>" ><?php echo $subcategory['name']; ?></a></li>
   				<?php
   				}
   				?>
   			</ul>
			<?php 
		}
		else 
		{
			?>
			This category doesn't has any subcategory
			<?php 
		}
	break;

// 	Change Company of Category
	case 2:
		$model->changeCompanyOfCategory($_POST);
	break;
	
// 	create relations of company and subcategories
	case 3:
		$model->updateCompanySubcategory($_POST);
	break;
	
// 	update the company location 
	case 4:
		$model->updateCompanyLocation($_POST);
	break;
	
}
?>