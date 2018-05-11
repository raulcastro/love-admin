<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/Framework/Back_Default_Header.php';

/**
 * Contains the methods for interact with the databases
 *
 * @package    Reservation System
 * @subpackage Tropical Casa Blanca Hotel
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Raul Castro <rd.castro.silva@gmail.com>
 */
class Layout_Model
{
    private $db; 
	
    /**
     * Initialize the MySQL Link
     */
	public function __construct()
	{
		$this->db = new Mysqli_Tool(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	}
	
	/**
	 * getGeneralAppInfo
	 *
	 * get all the info that from the table app_info, this is about the application
	 * the name, url, creator and so
	 *
	 * @return array row containing the info
	 */
	
	public function getGeneralAppInfo()
	{
		try {
			$query = 'SELECT * FROM app_info';
	
			return $this->db->getRow($query);
	
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * Get the user info
	 * 
	 * Get's the user detail {user_id, name, ...}
	 * 
	 * @return mixed|bool An array of info or false
	 */
	public function getUserInfo()
	{
		try {
			$query = "SELECT 
					u.user_id,
					u.type,
					d.name,
					d.avatar,
					u.type, 
					ue.email as user_email, 
					ue.inbox
					FROM users u 
					LEFT JOIN user_detail d ON u.user_id = d.user_id 
					LEFT JOIN user_emails ue ON u.user_id = ue.user_id
					WHERE u.user_id = ".$_SESSION['userId'];
			return $this->db->getRow($query);
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * Get only the active users
	 * 
	 * @return mixed|bool An array of info or false
	 */
	public function getActiveUsers()
	{
		try {
			$query = 'SELECT 
					ud.user_id, 
					ud.name 
					FROM users u 
					LEFT JOIN user_detail ud ON ud.user_id = u.user_id
					WHERE u.active = 1 AND third_user = 0
					';
			return $this->db->getArray($query);
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addMember($data)
	{
		try {
			$query = 'INSERT INTO members
					(
					name, 
					last_name, 
					address, 
					phone_one, 
					phone_two, 
					email_one, 
					email_two, 
					notes, 
					condo,
					user_id,
					date
					) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())';
			
			$prep = $this->db->prepare($query);
			
			$prep->bind_param('sssssssssi', 
					$data['memberFirst'],
					$data['memberLast'],
					$data['memberAddress'],
					$data['phoneOne'],
					$data['phoneTwo'],
					$data['emailOne'],
					$data['emailTwo'],
					$data['notes'],
					$data['memberCondo'],
					$_SESSION['userId']);
			
			if ($prep->execute())
				return $prep->insert_id;
			else 
				printf("Errormessage: %s\n", $prep->error);
			
		} catch (Exception $e) {
			
			return false;
		}
	}
	
	public function addUser($data, $member_id)
	{
		try {
			$query = 'INSERT INTO users(user, password, type, active, third_user) VALUES(?, SHA1("password"), 1, 1, 1)';
			$prep = $this->db->prepare($query);
			$prep->bind_param('s',$data['emailOne']);
			
			if ($prep->execute())
			{
				$lastId =  $prep->insert_id;
				$query = 'INSERT INTO user_detail(user_id, name, member_id) VALUES(?, ?, ?)';
				$prep = $this->db->prepare($query);
				$prep->bind_param('isi', $lastId, $data['memberFirst'], $member_id);
				$prep->execute();
			}
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateMember($data)
	{
		try {
			$query = 'UPDATE members
					SET name 	= ?,
					last_name 	= ?,
					address 	= ?,
					phone_one	= ?,
					phone_two	= ?,
					email_one 	= ?,
					email_two 	= ?,
					notes 		= ?,
					condo		= ?
					WHERE member_id = ?';
				
			$prep = $this->db->prepare($query);
				
			$prep->bind_param('sssssssssi',
					$data['memberFirst'],
					$data['memberLast'],
					$data['memberAddress'],
					$data['phoneOne'],
					$data['phoneTwo'],
					$data['emailOne'],
					$data['emailTwo'],
					$data['notes'],
					$data['memberCondo'],
					$data['memberId']
					);
				
			if ($prep->execute())
			{
				return $data['memberId'];
			}
			else {
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * Get the last 10 members added
	 * 
	 * If the user is an admin then all the members will show
	 * If not, only the user that belongs to the user will be show
	 * 
	 * @return mixed|bool An array of info or false
	 */
	public function getLastMembers()
	{
		try {
			$filter = '';
			
			if ($_SESSION['loginType'] != 1)
			{
				$filter = 'WHERE m.user_id = '.$_SESSION['userId'];
			}
			
			$query = 'SELECT 
					lpad(m.member_id, 4, 0) AS member_id, 
					m.user_id, 
					m.name, 
					m.last_name, 
					m.active,
					m.phone_one,
					m.email_one,
					m.date,
					d.name AS user_name
					FROM members m
					LEFT JOIN user_detail d ON m.user_id = d.user_id
					'.$filter.'
					 ORDER BY m.member_id DESC
					LIMIT 0, 20
					';

			return $this->db->getArray($query);
			
		} catch (Exception $e) {
			return false;
		}
	}

	public function getTotalMembers()
	{
		try {
			$query = 'SELECT COUNT(*) FROM members WHERE active = 1';
			return $this->db->getValue($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * Get all the members 
	 * 
	 * With all the details
	 * 
	 * @return mixed|bool An array of info or false
	 */
	public function getAllMembers()
	{
		try {
			$filter = '';
			
			if ($_SESSION['loginType'] != 1)
			{
				$filter = 'WHERE m.user_id = '.$_SESSION['userId'];
			}
			
			$query = 'SELECT 
					lpad(m.member_id, 4, 0) AS member_id, 
					m.user_id, 
					m.name, 
					m.last_name, 
					m.active,
					m.phone_one,
					m.email_one,
					m.date,
					d.name AS user_name
					FROM members m
					LEFT JOIN user_detail d ON m.user_id = d.user_id
					'.$filter.'
					 ORDER BY m.member_id DESC
					';
			return $this->db->getArray($query);
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getRecentMembers()
	{
		try {
			$query = 'SELECT COUNT(*) FROM members WHERE date = CURDATE() AND user_id = '.$_SESSION['userId'];
			return $this->db->getValue($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * Get all the members
	 *
	 * With all the details
	 *
	 * @return mixed|bool An array of info or false
	 */
	public function getRoomsByCondo($condo_id)
	{
		try {	
			$query = 'SELECT 
				mr.member_room_id,
				m.member_id,
				m.name,
				m.last_name,
				c.condo,
				r.room, 
				r.condo_id,
				(SELECT IFNULL((
					SELECT 
					SUM(p.amount)
					FROM payments p
					WHERE p.member_id = m.member_id
					AND p.room_id = r.room_id 
				), 0)) AS total,
				(SELECT IFNULL((
					SELECT 
					SUM(p.amount)
					FROM payments p
					WHERE p.member_id = m.member_id
					AND p.room_id = r.room_id 
					AND p.status = 2
				), 0)) AS paid,
				(SELECT IFNULL((
					SELECT 
					SUM(p.amount)
					FROM payments p
					WHERE p.member_id = m.member_id
					AND p.room_id = r.room_id 
					AND p.status = 1
				), 0)) AS pending
				FROM member_rooms mr
				LEFT JOIN rooms r ON r.room_id = mr.room_id
				LEFT JOIN members m ON m.member_id = mr.member_id
				LEFT JOIN condos c ON r.condo_id = c.condo_id
				WHERE r.condo_id = '.$condo_id.'
				ORDER BY m.member_id DESC
					';
			return $this->db->getArray($query);
				
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateMemberAvatar($file, $memberId)
	{
		try {
			$query = 'UPDATE members SET avatar = ? WHERE member_id = ?';
			$prep = $this->db->prepare($query);
			$prep->bind_param('si', $file, $memberId);
			return $prep->execute();
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateUserAvatar($file)
	{
		try {
			$query = 'UPDATE user_detail SET avatar = ? WHERE user_id = '.$_SESSION['userId'];
			$prep = $this->db->prepare($query);
			$prep->bind_param('s', $file);
			return $prep->execute();
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getMemberByMemberId($memberId)
	{
		try {
			$query = 'SELECT m.*
					FROM members m
					WHERE m.member_id = 
					'.$memberId;
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getMemberHistoryById($memberId)
	{
		try {
			$query = 'SELECT mh.* , ud.name
					FROM member_history mh 
					LEFT JOIN user_detail ud ON mh.user_id = ud.user_id
					WHERE mh.member_id = '.$memberId.'
					ORDER BY mh.history_id DESC		
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addHistory($data)
	{
	    try
	    {
	    	$query = 'INSERT INTO member_history(user_id, member_id, date, time, history) 
	    			VALUES('.$_SESSION["userId"].', ?, CURDATE(), CURTIME(), ?)';
			
	        $prep = $this->db->prepare($query);

	        $prep->bind_param('is', 
	        		$data['memberId'],
	        		$data['historyEntry']);
			
             return $prep->execute();
	    }
	    catch (Exception $e)
	    {
	    	echo $e->getMessage();
	    }
	}
	
	public function getHistoryEntries($member_id)
	{
		try 
		{
			$member_id = (int) $member_id;
			$query = 'SELECT h.*, ud.name
					FROM member_history h
					LEFT JOIN user_detail ud ON ud.user_id = h.user_id
					WHERE h.member_id = '.$member_id.'
					ORDER BY h.history_id DESC';
			
			return $this->db->getArray($query);
		}
		catch (Exception $e)
		{
			return false;			
		}
	}
	
	public function addMemberTask($data)
	{
		$date = Tools::formatToMYSQL($data['task_date']);
	
		$time = $data['task_hour'].':00';
		$member_id = (int) $data['memberId'];
		try {
			$query = 'INSERT INTO member_tasks(task_to, task_from, date, created_on, time, content, member_id)
					VALUES(?, ?, ?, CURDATE(), ?, ?, ?)';
			$prep = $this->db->prepare($query);
				
			$prep->bind_param('iisssi',
					$data['task_to'],
					$_SESSION['userId'],
					$date,
					$time,
					$data['task_content'],
					$member_id);
			// 			Pretty good piece of code!
			// 			if(!$prep->execute())
				// 			{
				// 				printf("Errormessage: %s\n", $prep->error);
				// 			}
				return $prep->execute();
		} catch (Exception $e) {
			echo $e->getMessage();
			return false;
		}
	}
	
	public function getMemberTaskByMemberId($member_id)
	{
		try {
			$member_id = (int) $member_id;
			
			$query = 'SELECT t.*,
					ud.name AS assigned_by,
					uds.name AS assigned_to
					FROM member_tasks t
					LEFT JOIN user_detail ud ON ud.user_id = t.task_from
					LEFT JOIN user_detail uds ON uds.user_id = t.task_to
					WHERE t.member_id = '.$member_id.'
					ORDER BY t.date ASC
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getAllMemberTasks()
	{
		try {
			$member_id = (int) $member_id;
			
			$query = 'SELECT t.*,
					ud.name AS assigned_by,
					uds.name AS assigned_to,
					m.name, m.last_name
					FROM member_tasks t
					LEFT JOIN user_detail ud ON ud.user_id = t.task_from
					LEFT JOIN user_detail uds ON uds.user_id = t.task_to
					LEFT JOIN members m ON m.member_id = t.member_id
					WHERE t.status = 0
					ORDER BY t.date DESC
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getAllTasksByUser()
	{
		try {
			$member_id = (int) $member_id;
	
			$query = 'SELECT t.*,
					ud.name AS assigned_by,
					uds.name AS assigned_to,
					m.name, m.last_name
					FROM member_tasks t
					LEFT JOIN user_detail ud ON ud.user_id = t.task_from
					LEFT JOIN user_detail uds ON uds.user_id = t.task_to
					LEFT JOIN members m ON m.member_id = t.member_id
					WHERE t.assigned_to = '.$_SESSION['userId'].'
					AND t.status = 0
					ORDER BY t.date DESC
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getTotalTodayTasksByMemberId()
	{
		try {
			$query = 'SELECT COUNT(*) 
					FROM member_tasks 
					WHERE date = CURDATE() 
					AND task_to = '.$_SESSION['userId'].'
					AND status = 0';
			return $this->db->getValue($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getTodayTasksByUser()
	{
		try {
			$query = 'SELECT t.*,
					ud.name AS assigned_by,
					uds.name AS assigned_to,
					m.name, m.last_name
					FROM member_tasks t
					LEFT JOIN user_detail ud ON ud.user_id = t.task_from
					LEFT JOIN user_detail uds ON uds.user_id = t.task_to
					LEFT JOIN members m ON m.member_id = t.member_id
					WHERE t.date = CURDATE() 
					AND t.task_to = '.$_SESSION['userId'].'
					AND t.status = 0
					ORDER BY t.date DESC
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getTotalPendingTasksByMemberId()
	{
		try {
			$query = 'SELECT COUNT(*) 
					FROM member_tasks 
					WHERE date < CURDATE()
					AND task_to = '.$_SESSION['userId'].'
					AND status = 0';
			return $this->db->getValue($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getPendingTasksByUser()
	{
		try {
			$query = 'SELECT t.*,
					ud.name AS assigned_by,
					uds.name AS assigned_to,
					m.name, m.last_name
					FROM member_tasks t
					LEFT JOIN user_detail ud ON ud.user_id = t.task_from
					LEFT JOIN user_detail uds ON uds.user_id = t.task_to
					LEFT JOIN members m ON m.member_id = t.member_id
					WHERE t.date < CURDATE()
					AND t.task_to = '.$_SESSION['userId'].'
					AND t.status = 0
					ORDER BY t.date DESC
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getTotalFutureTasksByMemberId()
	{
		try {
			$query = 'SELECT COUNT(*)
					FROM member_tasks
					WHERE date > CURDATE()
					AND task_to = '.$_SESSION['userId'].'
					AND status = 0';
			return $this->db->getValue($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getFutureTasksByUser()
	{
		try {
			$query = 'SELECT t.*,
					ud.name AS assigned_by,
					uds.name AS assigned_to,
					m.name, m.last_name
					FROM member_tasks t
					LEFT JOIN user_detail ud ON ud.user_id = t.task_from
					LEFT JOIN user_detail uds ON uds.user_id = t.task_to
					LEFT JOIN members m ON m.member_id = t.member_id
					WHERE t.date > CURDATE()
					AND t.task_to = '.$_SESSION['userId'].'
					AND t.status = 0
					ORDER BY t.date DESC
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getCompletedTasksByUser()
	{
		try {
			$query = 'SELECT t.*,
					ud.name AS assigned_by,
					uds.name AS assigned_to,
					m.name, m.last_name
					FROM member_tasks t
					LEFT JOIN user_detail ud ON ud.user_id = t.task_from
					LEFT JOIN user_detail uds ON uds.user_id = t.task_to
					LEFT JOIN members m ON m.member_id = t.member_id
					WHERE t.task_to = '.$_SESSION['userId'].'
					AND t.status = 1
					ORDER BY t.date DESC
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function completeTask($task_id)
	{
		try {
			$task_id = (int) $task_id;
			$query = 'UPDATE member_tasks SET status = 1, completed_by = '.$_SESSION['userId'].', completed_date = CURDATE()
					WHERE task_id = '.$task_id;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addInventory($data)
	{
		try {
			$query 	= 'INSERT INTO inventory(category_id, inventory, description) VALUES(?, ?, ?)';
			$prep 	= $this->db->prepare($query);
	
			$prep->bind_param('iss', $data['categoryId'], $data['inventoryName'], $data['inventoryDescription']);
	
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else
			{
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteInventory($inventoryId)
	{
		try {
			$inventoryId = (int) $inventoryId;
			$query = 'DELETE FROM inventory WHERE inventory_id = '.$inventoryId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateInventoryCategory($data)
	{
		try {
			$query = 'UPDATE inventory_categories SET category = ?, description = ? WHERE category_id = '.$data['categoryId'];
			$prep = $this->db->prepare($query);
			$prep->bind_param('ss', $data['categoryName'], $data['categoryDescription']);
			return $prep->execute();
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getAllInventoryCategories()
	{
		try {
			$query = 'SELECT * FROM inventory_categories ORDER BY category_id DESC';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addInventoryCategories($data)
	{
		try {
			$query = 'INSERT INTO inventory_categories(category, description) VALUES(?, ?)';
			$prep = $this->db->prepare($query);
			
			$prep->bind_param('ss', $data['categoryName'], $data['categoryDescription']);
			
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else 
			{
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteCategory($categoryId)
	{
		try {
			$categoryId = (int) $categoryId;
			
			$query = 'DELETE FROM inventory_categories WHERE category_id = '.$categoryId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getInVentoryCategoryById($id)
	{
		try {
			$query = 'SELECT * FROM inventory_categories WHERE category_id = '.$id;
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getInventoryByCategory($id)
	{
		try {
			$id = (int) $id;
			$query = 'SELECT * FROM inventory WHERE category_id = '.$id;
			
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addRoom($data)
	{
		try {
			$query = 'INSERT INTO rooms(room_type_id, room, description, condo_id) VALUES(?, ?, ?, ?)';
			$prep = $this->db->prepare($query);
			$prep->bind_param('issi', $data['roomType'], $data['roomName'], $data['roomDescription'], $data['condoId']);
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else
			{
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteRoom($roomId)
	{
		try {
			$roomId = (int) $roomId;
			$query = 'DELETE FROM rooms WHERE room_id = '.$roomId;
			
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateRoom($data)
	{
		try {
			$query = 'UPDATE rooms SET room_type_id = ?, room = ?, description = ?, condo_id = ? WHERE room_id = ?';
			$prep = $this->db->prepare($query);
			$prep->bind_param('issii',$data['roomType'], $data['roomName'], $data['roomDescription'], $data['condoId'], $data['roomId']);
				
			if ($prep->execute())
			{
				return true;
			}
			else
			{
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * getAllRooms
	 *
	 * Returns the collection of rooms on table rooms
	 * it works for the section 'Rooms'
	 *
	 * @return multitype:unknown |boolean
	 */
	
	public function getAllRooms()
	{
		try {
			$query = 'SELECT r.*, rt.room_type, rt.abbr, c.condo
					FROM rooms r
					LEFT JOIN room_types rt ON rt.room_type_id = r.room_type_id
					LEFT JOIN condos c ON c.condo_id = r.condo_id
					ORDER BY r.room_id DESC
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * getSingleRoomById
	 *
	 * Return the info a single room by a given room id
	 *
	 * @return multitype:unknown |boolean
	 */
	
	public function getRoomById($roomId)
	{
		try {
			$roomId = (int) $roomId;
			$query = 'SELECT
					r.*,
					rt.room_type,
					rt.abbr
					FROM rooms r
					LEFT JOIN room_types rt ON rt.room_type_id = r.room_type_id
					WHERE r.room_id = '.$roomId.'
					';
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getLastInventoryList()
	{
		try {
			$query = 'SELECT category_id FROM inventory_categories ORDER BY category_id DESC LIMIT 1';
			$last =  $this->db->getValue($query);
			
			$query = 'SELECT * FROM inventory WHERE category_id = '.$last;
			return $this->db->getArray($query);
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getInvetoryByCategory($category)
	{
		try {
			$query = 'SELECT * FROM inventory WHERE category_id = '.$category;
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addRoomInventory($data)
	{
		try {
			$query = 'INSERT INTO rooms_inventory(room_id, category_id, inventory_id) VALUES(?, ?, ?)';
			
			$prep = $this->db->prepare($query);
			
			$prep->bind_param('iii',$data['roomId'], $data['categoryId'], $data['inventoryId']);
				
			if ($prep->execute())
			{
				return true;
			}
			else
			{
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getRoomInventoryByRoom($roomId)
	{
		try {
			$query = 'SELECT ic.category, i.inventory
					FROM rooms_inventory ri
					LEFT JOIN inventory_categories ic ON ic.category_id = ri.category_id
					LEFT JOIN inventory i ON i.inventory_id = ri.inventory_id 
					WHERE ri.room_id = '.$roomId;
			
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addMemberRoom($data)
	{
		try {
			$query = 'INSERT INTO member_rooms(member_id, room_id) VALUES(?, ?)';
			$prep = $this->db->prepare($query);
			$prep->bind_param('ii', $data['memberId'], $data['roomId']);
			return $prep->execute();
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteMemberRoom($data)
	{
		try {
			$query = 'DELETE FROM member_rooms WHERE member_id = '.$data['memberId'].' AND room_id = '.$data['roomId'];
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getRoomsByMember($memberId)
	{
		try {
			$query = 'SELECT r.room_id, r.room, r.description, rt.room_type, c.condo
					FROM member_rooms mr
					LEFT JOIN rooms r ON r.room_id = mr.room_id
					LEFT JOIN condos c ON c.condo_id = r.condo_id
					LEFT JOIN room_types rt ON rt.room_type_id = r.room_type_id
					WHERE mr.member_id = '.$memberId;
			
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getCategoriesInventoryByRoom($roomId)
	{
		try {
			$query = 'SELECT ri.category_id, ic.category 
				FROM rooms_inventory ri 
				LEFT JOIN inventory_categories ic ON ic.category_id = ri.category_id
				WHERE ri.room_id = '.$roomId.'
				GROUP BY ri.category_id';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getInventoryByCategoryRoom($roomId, $categoryId)
	{
		try {
			$query = 'SELECT ri.inventory_id, i.inventory 
				FROM rooms_inventory ri 
				LEFT JOIN inventory i ON i.inventory_id = ri.inventory_id
				WHERE ri.room_id = '.$roomId.' AND ri.category_id = '.$categoryId;
			return $this->db->getArray($query);
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addPayment($data)
	{
		try {
			$query = 'INSERT INTO payments(
					user_id, 
					member_id, 
					room_id, 
					category_id, 
					inventory_id, 
					due_date, 
					time, 
					amount, 
					description)
					VALUES('.$_SESSION['userId'].', ?, ?, ?, ?, ?, CURTIME(), ?, ?)';
			
			$prep = $this->db->prepare($query);
			
			$prep->bind_param('iiiisds', 
					$data['memberId'],
					$data['currentRoom'],
					$data['currentCategory'],
					$data['currentInventory'],
					Tools::formatToMYSQL($data['paymentDate']),
					$data['paymentAmount'],
					$data['paymentDescription']
			);
			
			return $prep->execute();

			// 			Pretty good piece of code!
// 						if(!$prep->execute())
// 						{
// 							printf("Errormessage: %s\n", $prep->error);
// 						}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getPaymentsByRoom($data)
	{
		$conditional = "";
		
		switch ($_POST['statusP'])
		{
			case "pending":
				$conditional = ' AND status = 1 ';
			break;
			
			case "past":
				$conditional = ' AND status = 1 AND p.due_date < CURDATE() ';
			break;
			
			case "paid":
				$conditional = ' AND status = 2 ';
			break;
			
			case "cancel":
				$conditional = ' AND status = 3 ';
			break;
		}
		
		try {
			$query = 'SELECT 
					p.payment_id, 
					p.amount, 
					p.due_date, 
					p.status,
					p.description,
					DATEDIFF(p.due_date, CURDATE()) AS days, 
					ic.category, 
					i.inventory  
					FROM payments p
					LEFT JOIN inventory_categories ic ON ic.category_id = p.category_id
					LEFT JOIN inventory i ON i.inventory_id = p.inventory_id
					WHERE p.member_id = '.$data['memberId'].'
					AND p.room_id = '.$data['currentRoom'].'
					'.$conditional.'
					ORDER BY p.due_date ASC		
					';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function calculatePayments($data)
	{
		try {
			$query = 'SELECT IFNULL((
					SELECT 
					SUM(p.amount)
					FROM payments p
					WHERE p.member_id = '.$data['memberId'].'
					AND p.room_id = '.$data['currentRoom'].' 
					AND status != 3
				), 0) AS total,
				IFNULL((	
					SELECT 
					SUM(p.amount)
					FROM payments p
					WHERE p.member_id = '.$data['memberId'].'
					AND p.room_id = '.$data['currentRoom'].'
					AND p.status = 2
				), 0) AS paid,
				IFNULL(
				(	
					SELECT 
					SUM(p.amount)
					FROM payments p
					WHERE p.member_id = '.$data['memberId'].'
					AND p.room_id = '.$data['currentRoom'].'
					AND p.status = 1
				), 0) AS pending;';
			
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getPaymentsByPaymentId($data)
	{
		try {
			$query = 'SELECT
					p.payment_id,
					p.amount,
					p.due_date,
					p.time,
					p.description,
					p.status,
					DATEDIFF(p.due_date, CURDATE()) AS days,
					ic.category,
					i.inventory
					FROM payments p
					LEFT JOIN inventory_categories ic ON ic.category_id = p.category_id
					LEFT JOIN inventory i ON i.inventory_id = p.inventory_id
					WHERE p.payment_id = '.$data['paymentId'].'
					';
// 			echo $query;
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function setPaymentStatus($paymentId, $status)
	{
		try {
			$paymentId = (int) $paymentId;
			$status = (int) $status;
			$query = 'UPDATE payments SET status = '.$status.' WHERE payment_id = '.$paymentId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addRoomTypes($data)
	{
		try {
			$query = 'INSERT INTO room_types(room_type, description) VALUES(?, ?)';
			$prep = $this->db->prepare($query);
			
			$prep->bind_param('ss', $data['roomTypeName'], $data['roomTypeDescription']);
			
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else
			{
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteType($typeId)
	{
		try {
			$typeId = (int) $typeId;
			$query = 'DELETE FROM room_types WHERE room_type_id = '.$typeId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getRoomTypes()
	{
		try {
			$query = 'SELECT * FROM room_types';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addCondos($data)
	{
		try {
			$query = 'INSERT INTO condos(condo, description) VALUES(?, ?)';
			$prep=$this->db->prepare($query);
			
			$prep->bind_param('ss', $data['condoName'], $data['condoDescription']);
			
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else
			{
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteCondo($condoId)
	{
		try {
			$condoId = (int) $condoId;
			$query = 'DELETE FROM condos WHERE condo_id = '.$condoId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getAllCondos()
	{
		try {
			$query = 'SELECT * FROM condos ORDER BY condo_id DESC';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getMessagesByMember($member_id)
	{
		try {
			$query = 'SELECT m.message_id,
					m.user_id,
					m.from_user,
					m.to_user,
					DATE_FORMAT(m.date, "%e %b %l:%i %p") as date,
					m.message,
					m.status,
					ud.name AS user_name,
					CONCAT(me.name, " ", me.last_name ) AS member_name,
					me.avatar
					FROM messages m
					LEFT JOIN user_detail ud ON ud.user_id = m.user_id
					LEFT JOIN members me ON me.member_id = m.member_id
					WHERE m.member_id = '.$member_id.'
					ORDER BY m.message_id ASC
					';
			
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;			
		}
	}
	
	public function getMessageByMessageId($message_id)
	{
		try {
			$query = 'SELECT m.message_id,
					m.user_id,
					m.from_user,
					m.to_user,
					DATE_FORMAT(m.date, "%e %b %l:%i %p") as date,
					m.message,
					m.status,
					ud.name AS user_name,
					CONCAT(me.name, " ", me.last_name ) AS member_name
					FROM messages m
					LEFT JOIN user_detail ud ON ud.user_id = m.user_id
					LEFT JOIN members me ON me.member_id = m.member_id
					WHERE m.message_id = '.$message_id.'
					ORDER BY m.message_id ASC
					';
				
			return $this->db->getRow($query);
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addMemberMessage($data)
	{
		try {
			$query = 'INSERT INTO messages(member_id, user_id, from_user, to_user, message) VALUES(?, '.$_SESSION['userId'].' , '.$_SESSION['userId'].', ?, ?)';
			$prep = $this->db->prepare($query);
			$prep->bind_param('iis', $data['memberId'], $data['memberId'], $data['message']);
			if ($prep->execute())
			{
				return $this->getMessageByMessageId($prep->insert_id);
			}
			else 
			{
				printf("Errormessage: %s\n", $prep->error);
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addDocument($data)
	{
		try {
			$query = 'INSERT INTO documents(member_id, payment_id, document) VALUES(?, ?, ?)';
			$prep = $this->db->prepare($query);
			$prep->bind_param('iis', $data['memberId'], $data['paymentId'], $data['documentUploaded']);
			if ($prep->execute())
			{
				return true;
			}
			else 
			{
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getDocumentsByPaymentId($paymentId)
	{
		try {
			$query = 'SELECT * FROM documents WHERE payment_id = '.$paymentId;
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteOwner($memberId)
	{
		try {
			$query = 'DELETE FROM members WHERE member_id = '.$memberId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deletePayment($paymentId)
	{
		try {
			$query = 'DELETE FROM payments WHERE payment_id = '.$paymentId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * getMainSliders
	 *
	 * sliders that should be displayed in the index
	 * @param NULL
	 * @return array on succes, false on fail
	 */
	
	public function getMainSliders()
	{
		try
		{
			$query = 'SELECT *
	        		FROM main_gallery
	                ORDER BY picture_id DESC';
			return $this->db->getArray($query);
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public function addSlider($name)
	{
		try
		{
			$query = 'INSERT INTO main_gallery(name)
	                VALUES("'.$name.'")';
			if ($this->db->run($query))
				return $this->db->insert_id;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public function editSliderInfo($data)
	{
		try
		{
			$query = 'UPDATE main_gallery
					SET title = ?,
					link = ?,
					promos = ?
					WHERE picture_id = ?';
			$prep = $this->db->prepare($query);
			$prep->bind_param('sssi',
					$data['title'],
					$data['link'],
					$data['promos'],
					$data['sliderId']);
			return $prep->execute();
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public function deleteSlider($picture_id)
	{
		try
		{
			$picture_id = (int) $picture_id;
	
			$query = 'DELETE FROM main_gallery WHERE picture_id = '.$picture_id;
	
			return $this->db->run($query);
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	/**
	 * getMainSliders
	 *
	 * sliders that should be displayed in the index
	 * @param NULL
	 * @return array on succes, false on fail
	 */
	
	public function getGeneralSliders()
	{
		try
		{
			$query = 'SELECT *
	        		FROM general_gallery
	                ORDER BY picture_id DESC';
			return $this->db->getArray($query);
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public function addGeneralSlider($name)
	{
		try
		{
			$query = 'INSERT INTO general_gallery(name)
	                VALUES("'.$name.'")';
			if ($this->db->run($query))
				return $this->db->insert_id;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public function editGeneralSliderInfo($data)
	{
		try
		{
			$query = 'UPDATE general_gallery
					SET title = ?,
					link = ?,
					promos = ?
					WHERE picture_id = ?';
			$prep = $this->db->prepare($query);
			$prep->bind_param('sssi',
					$data['title'],
					$data['link'],
					$data['promos'],
					$data['sliderId']);
			return $prep->execute();
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public function deleteGeneralSlider($picture_id)
	{
		try
		{
			$picture_id = (int) $picture_id;
	
			$query = 'DELETE FROM general_gallery WHERE picture_id = '.$picture_id;
	
			return $this->db->run($query);
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public function addDestination($data)
	{
		try {
			$query = 'INSERT INTO destinations(name) VALUES(?)';
			
			$prep = $this->db->prepare($query);
			
			$prep->bind_param('s',
					$data['destination']);
			
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else 
			{
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getDestinationById($id)
	{
		try {
			$query = 'SELECT * FROM destinations WHERE destination_id = '.$id;
			
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateDestination($data)
	{
		$query = 'UPDATE destinations SET name = ?, small_description = ?, description = ? WHERE destination_id = '.$data['destinationId'];
			
		$prep = $this->db->prepare($query);
			
		$prep->bind_param('sss',
				$data['destinationName'],
				$data['smallDescription'],
				$data['description']);
			
		return $prep->execute();
		
	}
	
	public function getAllDestinations()
	{
		try {
			$query = 'SELECT * FROM destinations ORDER BY destination_id DESC';
			
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteDestination($id)
	{
		try {
			$query = 'DELETE FROM destinations WHERE destination_id = '.$id;
			
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateDestinationPhoto($name, $id)
	{
		try {
			$query = 'UPDATE destinations SET photo = "'.$name.'" WHERE destination_id = '.$id;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
		
	}
	
	public function addTestimonial($data)
	{
		try {
			$query = 'INSERT INTO testimonials(name, testimonial) VALUES(?, ?)';
			
			$prep = $this->db->prepare($query);
				
			$prep->bind_param('ss',
					$data['testimonialName'],
					$data['testimonialDescription']);
				
			return $prep->execute();
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getAllTestimonials()
	{
		try {
			$query = 'SELECT * FROM testimonials ORDER BY testimonial_id DESC';
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteTestimonial($testimonialId)
	{
		try {
			$testimonialId = (int) $testimonialId;
			$query = 'DELETE FROM testimonials WHERE testimonial_id = '.$testimonialId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addExtra($data)
	{
		try {
			$query = 'INSERT INTO extras(name) VALUES(?)';
				
			$prep = $this->db->prepare($query);
				
			$prep->bind_param('s',
					$data['extra']);
				
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else
			{
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getExtraById($id)
	{
		try {
			$query = 'SELECT * FROM extras WHERE extra_id = '.$id;
				
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateExtra($data)
	{
		$query = 'UPDATE extras SET name = ?, price = ?, small_description = ?, description = ? WHERE extra_id = '.$data['extraId'];
			
		$prep = $this->db->prepare($query);
			
		$prep->bind_param('sdss',
				$data['extraName'],
				$data['extraPrice'],
				$data['smallDescription'],
				$data['description']);
			
		return $prep->execute();
	
	}
	
	public function getAllExtras()
	{
		try {
			$query = 'SELECT * FROM extras ORDER BY extra_id DESC';
				
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteExtra($id)
	{
		try {
			$query = 'DELETE FROM extras WHERE extra_id = '.$id;
				
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateExtraPhoto($name, $id)
	{
		try {
			$query = 'UPDATE extras SET photo = "'.$name.'" WHERE extra_id = '.$id;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	
	}
	
	public function addExperiences($data)
	{
		try {
			$query = 'INSERT INTO experiencies(name) VALUES(?)';
				
			$prep = $this->db->prepare($query);
				
			$prep->bind_param('s',
					$data['experience']);
				
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else
			{
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addHotel($data)
	{
		try {
			$query = 'INSERT INTO hotels(name, destination_id) VALUES(?, ?)';
	
			$prep = $this->db->prepare($query);
	
			$prep->bind_param('si',
					$data['hotel'],
					$data['destination']);
	
			return $prep->execute();
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getHotelsByDestinationId($destinationID)
	{
		try {
			$destinationID = (int) $destinationID;
			$query = 'SELECT * FROM hotels WHERE destination_id = '.$destinationID.' ORDER BY hotel_id DESC';
			
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getHotelByHotelId($hotelId)
	{
		try {
			$hotelId = (int) $hotelId;
			$query = 'SELECT * FROM hotels WHERE hotel_id = '.$hotelId;
			return $this->db->getRow($query);
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteHotel($hotelId)
	{
		try {
			$hotelId = (int) $hotelId;
			$query = 'DELETE FROM hotels WHERE hotel_id = '.$hotelId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getAllHotelRangesByHotel($hotelId)
	{
		try {
			$hotelId = (int) $hotelId;
			$query = 'SELECT DATE_FORMAT(from_date, "%m/%d/%Y") AS from_date,
					DATE_FORMAT(to_date, "%m/%d/%Y") AS to_date,
					price,
					hotel_range_id
					FROM hotel_ranges WHERE hotel_id = '.$hotelId;
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addHotelRange($hotelId, $fromDate, $toDate, $price)
	{
		try {
			$hotelId = (int) $hotelId;
			
			$query = 'INSERT INTO hotel_ranges(hotel_id, from_date, to_date, price) VALUES(?, ?, ?, ?)';
			
			$prep = $this->db->prepare($query);
			
			$prep->bind_param('issd',
					$hotelId,
					$fromDate,
					$toDate,
					$price);
			
			return $prep->execute();
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getLastHotelRanges($hotelId)
	{
		try {
			$hotelId = (int) $hotelId;
			
			$query = 'SELECT 
				DATE_FORMAT(DATE_ADD(to_date, INTERVAL 1 DAY), "%m/%d/%Y") AS from_date, 
				DATE_FORMAT(DATE_ADD(to_date, INTERVAL 2 DAY), "%m/%d/%Y") AS to_date  
				FROM hotel_ranges 
				WHERE hotel_id = '.$hotelId.'
				ORDER BY hotel_range_id DESC
				limit 1;';
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteHotelRange($rangeId)
	{
		try {
			$rangeId = (int) $rangeId;
			$query = 'DELETE FROM hotel_ranges WHERE hotel_range_id = '.$rangeId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addExperience($data)
	{
		try {
			$query = 'INSERT INTO experiences(name) VALUES(?)';
				
			$prep = $this->db->prepare($query);
				
			$prep->bind_param('s',
					$data['experience']);
				
			if ($prep->execute())
			{
				return $prep->insert_id;
			}
			else
			{
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getExperienceById($id)
	{
		try {
			$query = 'SELECT * FROM experiences WHERE experience_id = '.$id;
				
			return $this->db->getRow($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateExperience($data)
	{
		$query = 'UPDATE experiences SET name = ?, small_description = ?, description = ? WHERE experience_id = '.$data['experienceId'];
			
		$prep = $this->db->prepare($query);
			
		$prep->bind_param('sss',
				$data['experienceName'],
				$data['smallDescription'],
				$data['description']);
			
		return $prep->execute();
	
	}
	
	public function deleteExperience($id)
	{
		try {
			$query = 'DELETE FROM experiences WHERE experience_id = '.$id;
				
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function updateExperiencesPhoto($name, $id)
	{
		try {
			$query = 'UPDATE experiences SET photo = "'.$name.'" WHERE experience_id = '.$id;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	
	}
	
	public function updateExperiencesSwiper($name, $id)
	{
		try {
			$query = 'UPDATE experiences SET swiper = "'.$name.'" WHERE experience_id = '.$id;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	
	}
	
	public function getAllExperiences()
	{
		try {
			$query = 'SELECT * FROM experiences ORDER BY experience_id DESC';
				
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addRelacionDestinationsExperiences($sectionId, $aliadoId)
	{
		try {
			$query = 'INSERT INTO experiences_destinations(experience_id, destination_id) VALUES('.$sectionId.', '.$aliadoId.')';
	
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteRelacionDestinationsExperiences($sectionId)
	{
		try {
			$query = 'DELETE FROM experiences_destinations WHERE experience_id = '.$sectionId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function checkRelacionDestinationsExperiences($experienceId, $destinationId)
	{
		try {
			$query = 'SELECT * FROM experiences_destinations WHERE experience_id = '.$experienceId.' AND destination_id = '.$destinationId;
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addRelacionExtrasExperiences($sectionId, $aliadoId)
	{
		try {
			$query = 'INSERT INTO experiences_extras(experience_id, extra_id) VALUES('.$sectionId.', '.$aliadoId.')';
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function deleteRelacionExtrasExperiences($sectionId)
	{
		try {
			$query = 'DELETE FROM experiences_extras WHERE experience_id = '.$sectionId;
			return $this->db->run($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function checkRelacionExtrasExperiences($experienceId, $extraId)
	{
		try {
			$query = 'SELECT * FROM experiences_extras WHERE experience_id = '.$experienceId.' AND extra_id = '.$extraId;
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getExperiencesByLocation($destinationId, $hotelId)
	{
		try {
			$destinationId = (int) $destinationId;
			$query = '
			SELECT
			e.name,
			ed.experience_id,
			he.price
			FROM experiences_destinations ed
			LEFT JOIN experiences e ON ed.experience_id = e.experience_id
			LEFT JOIN hotel_experiences he ON e.experience_id = he.experience_id
			WHERE ed.destination_id = '.$destinationId.' AND he.hotel_id = '.$hotelId.'
			GROUP BY ed.experience_id';
			//WHERE ed.destination_id = '.$destinationId.' AND he.hotel_id = '.$hotelId.'
// 			echo $query;
			return $this->db->getArray($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getLocationByHotelId($hotelId)
	{
		try {
			$hotelId = (int) $hotelId;
			$query = 'SELECT destination_id FROM hotels WHERE hotel_id = '.$hotelId;
			return $this->db->getValue($query);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function addHotelExperiencesPrice($data)
	{
		try {
			$query = "SELECT relacion_id FROM hotel_experiences WHERE hotel_id = ".$data['hotelId'].' && experience_id = '.$data['experienceId'];
			
			$relationId = $this->db->getValue($query);
			if ($relationId > 0)
			{
				#update
				$query = 'UPDATE hotel_experiences SET price = '.$data['experiencePrice'].' WHERE relacion_id = '.$relationId;
				return $this->db->run($query);
			}
			else
			{
				#insert
				$query = 'INSERT INTO hotel_experiences(hotel_id, experience_id, price) VALUES('.$data['hotelId'].', '.$data['experienceId'].', '.$data['experiencePrice'].' )';
				return $this->db->run($query);
			}
			
		} catch (Exception $e) {
			return false;
		}
	}
}









































