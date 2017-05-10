<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Layout_Model.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';


$model	= new Layout_Model();

switch ($_POST['opt'])
{
	/// add testimonial
	case 1:
		
		if (!empty($_POST))
		{
			if ($destinationId = $model->addTestimonial($_POST))
				echo $destinationId;
			else
				echo 0;
		}
	break;
	
// 	delete testimonial
	case 2:
		if (!empty($_POST))
		{
			if ($model->deleteTestimonial($_POST['testimonialId']))
				echo 1;
			else
				echo 0;
		}
	break;
	
// 	Get all testimonials
	case 3:
		if (!empty($_POST))
		{
			$testimonials = $model->getAllTestimonials();
			
			if ($testimonials)
			{
				foreach ($testimonials as $testimonial)
				{
					?>
				<div class="post testimonial-post clearfix" id="testimonial-<?php echo $testimonial['testimonial_id']; ?>">
					<div class="user-block">
						<span class="username">
							<a href="#"><?php echo $testimonial['name']; ?></a>
							<a href="#" class="pull-right btn-box-tool"><i class="fa fa-times delete-testimonial" data-id="<?php echo $testimonial['testimonial_id']; ?>"></i></a>
						</span>
					</div>
					<!-- /.user-block -->
					<p><?php echo $testimonial['testimonial']; ?></p>
				</div>
						<?php
					}
				}
		}
	break;
	
	default:
	break;
}