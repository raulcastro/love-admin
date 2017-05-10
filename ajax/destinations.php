<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once($root.'/models/back/Layout_Model.php');
require_once $root.'/backends/admin-backend.php';
require_once $root.'/Framework/Tools.php';


$model	= new Layout_Model();

switch ($_POST['opt'])
{
	/// add destination
	case 1:
		
		if (!empty($_POST))
		{
			if ($destinationId = $model->addDestination($_POST))
				echo $destinationId;
			else
				echo 0;
		}
	break;
	
// 	update destination
	case 2:
	
		if (!empty($_POST))
		{
			if ($model->updateDestination($_POST))
				echo 1;
			else
				echo 0;
		}
	break;
	
// 	Delete destination
	case 3:
	
		if (!empty($_POST))
		{
			if ($model->deleteDestination($_POST['destinationId']))
				echo 1;
			else
				echo 0;
		}
	break;
	
// 	Add hotel to a destination
	case 4:
	
		if (!empty($_POST))
		{
			if ($model->addHotel($_POST))
				echo 1;
			else
				echo 0;
		}
	break;
	
// 	Get hotels by destination
	case 5:
		if ($_POST['destinationId'])
		{
			$hotels = $model->getHotelsByDestinationId($_POST['destinationId']);
			
			foreach ($hotels as $hotel)
			{
				?>
			<li><a href="#" class="hotelItem" data-value="<?php echo $hotel['hotel_id']; ?>"><strong class="text-info"><?php echo $hotel['name']; ?></strong></a></li>
				<?php
			}
		}
	break;
	
// 	Get hotel detail
	case 6:
		if ($_POST['hotelId'])
		{
			if ($hotel = $model->getHotelByHotelId($_POST['hotelId']))
			{
				$hotelRanges = $model->getAllHotelRangesByHotel($_POST['hotelId']);
				
				if (!$hotelRanges)
				{
					$fromDate = '01/01/'.date("Y");
					$toDate = '01/02/'.date("Y");
				}
				else 
				{
					$dates = $model->getLastHotelRanges($_POST['hotelId']);
					$fromDate = $dates['from_date'];
					$toDate = $dates['to_date'];
				}
				?>
				<input type="hidden" value="<?php echo $hotel['hotel_id']; ?>" id="currentHotelId">
				<!-- Horizontal Form -->
				<!-- general form elements disabled -->
				<div class="box box-success">
					<div class="box-header with-border">
						<h3>
							<span><?php echo $hotel['name']; ?></span> / 
							<a href="javascript: void(0);" data-value="<?php echo $hotel['hotel_id']; ?>" class="text-red delete-single-hotel">Delete</a>
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<form role="form">
							<label>Hotel prices</label>
							<div class="row">
								<div class="col-xs-4">
									<input type="text" class="form-control" placeholder="From" id="newFromDate" value="<?php echo $fromDate; ?>" disabled>
								</div>
								
								<div class="col-xs-4">
									<input type="text" class="form-control datepicker" id="newToDate" value="<?php echo $toDate; ?>" placeholder="To">
								</div>
								                
								<div class="col-xs-4">
									<input type="text" class="form-control" id="newPrice" placeholder="$ Price">
								</div>
							</div>
							<div class="box-footer">
								<button type="submit" class="btn btn-info pull-right" id="addHotelRange">Add range</button>
							</div>
							<!-- /.box-footer -->
						</form>
					</div>
					            
					<div class="box-body">
						<ul>
							<?php 
							if (isset($hotelRanges))
							{
								$c = sizeof($hotelRanges);
								$i = 1;
								
								foreach ($hotelRanges as $range)
								{
									$deleteLink = '[<a href="#" class="deleteRange" data-id="'.$range['hotel_range_id'].'"><i class="text-danger">delete</i></a>] </li>';
									?>
							<li>
								From: <strong><?php echo $range['from_date']; ?></strong> 
								To: <strong><?php echo $range['to_date']; ?></strong>, 
								Price: <strong>$<?php echo $range['price']; ?></strong> 
							
									<?php
									if ($c == $i)
									{
										echo $deleteLink;
									}
									$i++;
								?>
							</li>
								<?php 
								}
							}
							?>
							
						</ul>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
				<?php
			}
			
		}
	break;
	
	// delete hotel
	case 7:
		if ($_POST['hotelId'])
		{
			if ($model->deleteHotel($_POST['hotelId']))
				echo 1;
			else 
				echo 0;
		}
	break;
	
// 	Add hotel range-price
	case 8:
		if ($_POST)
		{
			$fromDate = Tools::formatToMYSQL($_POST['newFromDate']);
			$toDate = Tools::formatToMYSQL($_POST['newToDate']);
			
			if ($model->addHotelRange($_POST['hotelId'], $fromDate, $toDate, $_POST['newPrice']))
			{
				echo 1;
			}
		}
	break;
	
	case 9:
		if ($_POST['rangeId'])
		{
			if ($model->deleteHotelRange($_POST['rangeId']))
				echo 1;
			else
				echo 0;
		}
	break;
	
	default:
	break;
}