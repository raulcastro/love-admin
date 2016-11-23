<?php
$root = $_SERVER['DOCUMENT_ROOT'].'/';

/**
 * Includes the file /models/front/Layout_Model.php
 * in order to interact with the database
 */
require_once $root.'models/back/Layout_Model.php';

/**
 * Contains the classes for access to the basic app after log-in
 *
 * @package    Reservation System
 * @subpackage Tropical Casa Blanca Hotel
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Raul Castro <rd.castro.silva@gmail.com>
 */
class generalBackend
{
	protected  $model;
	
	/**
	 * Initialize a class, the model one
	 */
	
	public function __construct()
	{
		$this->model = new Layout_Model();
	}
	
	/**
	 * Based on the section it returns the right info that could be propagated along the application
	 *
	 * @param string $section
	 * @return array Array with the asked info of the application
	 */
	public function loadBackend($section = '', $memberId = '')
	{
		$data 		= array();
		
// 		Info of the Application
		
		$appInfoRow = $this->model->getGeneralAppInfo();
		
		$appInfo = array( 
				'title' 		=> $appInfoRow['title'],
				'siteName' 		=> $appInfoRow['site_name'],
				'url' 			=> $appInfoRow['url'],
				'content' 		=> $appInfoRow['content'],
				'description'	=> $appInfoRow['description'],
				'keywords' 		=> $appInfoRow['keywords'],
				'location'		=> $appInfoRow['location'],	
				'creator' 		=> $appInfoRow['creator'],
				'creatorUrl' 	=> $appInfoRow['creator_url'],
				'twitter' 		=> $appInfoRow['twitter'],
				'facebook' 		=> $appInfoRow['facebook'],
				'googleplus' 	=> $appInfoRow['googleplus'],
				'pinterest' 	=> $appInfoRow['pinterest'],
				'linkedin' 		=> $appInfoRow['linkedin'],
				'youtube' 		=> $appInfoRow['youtube'],
				'instagram'		=> $appInfoRow['instagram'],
				'email'			=> $appInfoRow['email'],
				'lang'			=> $appInfoRow['lang'],
				'phone'			=> $appInfoRow['phone']
		);
		
		$data['appInfo'] = $appInfo;

		// Active Users
		$usersActiveArray 			= $this->model->getActiveUsers();
		$data['usersActive'] 		= $usersActiveArray;
		
		// User Info
		$userInfoRow 				= $this->model->getUserInfo();
		$data['userInfo'] 			= $userInfoRow;
		
		// Last 20 members
		$lastMembersArray 			= $this->model->getLastMembers();
		$data['lastMembers'] 		= $lastMembersArray;
		
		// Task Info
		$data['taskInfo']['today'] 		= $this->model->getTotalTodayTasksByMemberId();
		$data['taskInfo']['pending'] 	= $this->model->getTotalPendingTasksByMemberId();
		$data['taskInfo']['future'] 	= $this->model->getTotalFutureTasksByMemberId();
		$data['totalMembers'] 			= $this->model->getTotalMembers();
		
		// Condo List
		$condosArray = $this->model->getAllCondos();
		$data['condos'] = $condosArray;
		
		switch ($section) 
		{
			case 'settings':
				// 		get All room types
				$infoArray 	= $this->model->getRoomTypes();
				$data['types'] 	= $infoArray;
				
				$infoArray = $this->model->getAllInventoryCategories();
				$data['categories'] = $infoArray;
				
				$infoArray = $this->model->getAllCondos();
				$data['condos'] = $infoArray;
			break;
			
			case 'inventory-category':
				$infoSection = $this->model->getInVentoryCategoryById($_GET['categoryId']);
				$data['category'] = $infoSection;
				
				$inventoryArray = $this->model->getInventoryByCategory($_GET['categoryId']);
				$data['inventoryArray'] = $inventoryArray;
			break;
			
			case 'members':
				// 		get all members
				$membersArray 		= $this->model->getAllMembers();
				$data['members'] 	= $membersArray;
			break;
			
			case 'condo':
				// 		get rooms by condo
				$membersArray 		= $this->model->getRoomsByCondo($_GET['condo']);
				$data['members'] 	= $membersArray;
			break;
			
			case 'rooms':
				$infoArray 	= $this->model->getRoomTypes();
				$data['types'] 	= $infoArray;
				
				$roomsArray = $this->model->getAllRooms();
				$data['rooms'] = $roomsArray;
				
				$condosArray = $this->model->getAllCondos();
				$data['condos'] = $condosArray;
			break;
			
			case 'room':
				$infoArray 	= $this->model->getRoomTypes();
				$data['types'] 	= $infoArray;
			
				$roomsArray = $this->model->getAllRooms();
				$data['rooms'] = $roomsArray;
				
				$roomInfo = $this->model->getRoomById($_GET['roomId']);
				$data['room'] = $roomInfo;
				
				$arrayCategories = $this->model->getAllInventoryCategories();
				$data['categories'] = $arrayCategories;
				
				$arrayLastInventory = $this->model->getLastInventoryList();
				$data['inventory'] = $arrayLastInventory; 
				
				$inventoryArray = $this->model->getRoomInventoryByRoom($_GET['roomId']);
				$data['roomInventory'] = $inventoryArray;
			break;
			
			case 'member':
				$memberInfoRow 			= $this->model->getMemberByMemberId($memberId);
				$data['memberInfo'] 	= $memberInfoRow;
				
// 				History
				$memberHistoryArray 	= $this->model->getMemberHistoryById($memberId);
				$data['memberHistory'] 	= $memberHistoryArray;
				
// 				Tasks
				$memberTasksArray		= $this->model->getMemberTaskByMemberId($memberId);
				$data['memberTasks'] 	= $memberTasksArray; 
				
				$roomsArray 			= $this->model->getAllRooms();
				$data['rooms'] 			= $roomsArray;
				
				$memberRooms 			= $this->model->getRoomsByMember($memberId);
				$data['memberRooms'] 	= $memberRooms;
				
				$data['messages'] 	= $this->model->getMessagesByMember($memberId);
				
				
			break;
			
// 			Tasks
			case 'tasks':
				if ($data['userInfo']['type'] == 1)
					$memberTasksArray	= $this->model->getAllMemberTasks();
				else
					$memberTasksArray	= $this->model->getAllTasksByUser();
				
				$data['memberTasks'] 	= $memberTasksArray;
			break;
			
			case 'reports':
				if (!$_GET['from'])
				{
					$from = date('Y-m-d', strtotime(' -1 day'));
					$start = date('Y-m-d', strtotime(' -1 day', strtotime($from)));
					$end = date('Y-m-d', strtotime(' +31 day', strtotime($from)));
				}
				else
				{
					$from = date('Y-m-d', strtotime($_GET['from']));
					$start = date('Y-m-d', strtotime(' -1 day', strtotime($_GET['from'])));
					$end = date('Y-m-d', strtotime(' +32 day', strtotime($_GET['from'])));
				}
				
				$reservationsArray = $this->model->getReservationsByRange($start, $end);
				$data['reservations'] = $reservationsArray; 
			break;
			
			default:
			break;
		}
		
		return $data;
	}
}

$backend = new generalBackend();

// $info = $backend->loadBackend();
// var_dump($info['categoryInfo']);