<?php
class Users extends CI_Model {
/*
| [:START:]
| ADMIN: VOOLSY
| AUTHOR: VINIT
| -------------------------------------------------------------------
| CHECKING FOR MODEL
| -------------------------------------------------------------------
| This file specifies which MODEL should be loaded by default.
*/	 
	private $getTimestamp;
	protected $per_page_reocrd = 10;
	protected $admin_email = "qcorig6@gmail.com";
	protected $logPath = "tmp/logs/parameter_logs.txt";
	public function __construct() {
       parent::__construct();
        $date = new DateTime();
        $this->getTimestamp = $date->getTimestamp();
        if(error_get_last()) {
        	$errlogs = fopen("tmp/logs/err_logs.txt", "w+") or die("Unable to open file!");
			$txt = gmdate('d/m/Y H:i:s')." --- ";
			fwrite($errlogs, $txt);
			$txt = implode(', ', error_get_last()) . " \n";
			fwrite($errlogs, $txt);
			fclose($errlogs);
        }
	}
/* 
| [:START:]
| -------------------------------------------------------------------
| CHECKING FOR USER IS REGISTERED OR NOT
| -------------------------------------------------------------------
*/	
	public function is_registered_user($args = array()) {
		// $txt = gmdate('d/m/Y H:i:s') . ' - ' . __CLASS__ .'/'. __function__ . ' :' . "\n";
		// $txt .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n";
		// $txt .= "Checking for Registered user at Token: " . $args . "\n\n";
		// $para_log = fopen($this->logPath, "a+") or die("unable to open file!");
		// fwrite($para_log, $txt);
		// fclose($para_log);

		if(isset($args['data'])) {
			$data = json_decode($args['data']);
			$query = $this->db->get_where('ws_user_info', array('token' => $data->token));
			if($res = $query->result()) 
				return $res;
			else 
				return false;
		} 
		else {
			$query = $this->db->get_where('ws_user_info', array('token' => $args));
			if($query->result_array()) 
				return true;
			else 
				return false;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| LIST USER DETAIL
| -------------------------------------------------------------------
*/	
	public function get_user_detail($args = NULL, $time_difference = '') {
		if( ! $args) {
			http_response(498, 0);
			return false;
		}
		if ($time_difference || $time_difference == '0') {
			$current_local_time = time() - ($time_difference);
			$current_local_date = date('Y-m-d', $current_local_time);
			$currentLocalDate = new DateTime($current_local_date);
		}
		else {
			http_response(400, 0, 'TXN_INVALID_LOCAL_TIME_DIFFERENCE');
			return false;
		}	
		
		$this->db->select("token, user_email, user_name, user_image, device_type, device_id, is_notification, current_streak, current_streak_date, created_date, updated_date, is_admin");
		$response = $this->db->get_where('ws_user_info', array('token' => $args))->row_array();
		if($response) {
			$current_streak_local_time = $response['current_streak_date'] - ($time_difference);
			$current_streak_local_date = date('Y-m-d', $current_streak_local_time);
			$currentStreakLocalDate = new DateTime($current_streak_local_date);

			$dateDiff = $currentLocalDate->diff($currentStreakLocalDate);
			if(((int)$dateDiff->d === 1 || (int)$dateDiff->d === 0) && (int)$dateDiff->y === 0 && (int)$dateDiff->m === 0) {
 			   $data['token'] = $response['token'];
 			   $data['created_date'] = $this->getTimestamp;
 			   $data['is_admin'] = 1;
 			   $data['time_difference'] = $time_difference;
 			   $streakCount =  $this->set_streak($data);
 			   $response['current_streak'] = "$streakCount";
 			   return (object)$response;	
			}
			else {
				$this->db->update('ws_user_info', array('current_streak' => '0', 'current_streak_date' => $this->getTimestamp), array('token' => $response['token']));
				$response['current_streak'] = '0';
				$response['current_streak_date'] = "$this->getTimestamp";
				return (object)$response;	 	
			}			
		} 
		else {
			http_response(498, 0);
			return false;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| INSERT USER DETAIL IF USER IS NEW
| -------------------------------------------------------------------
*/	
	public function register_user($args = array()) {
		if($args['data']) {
			$data = json_decode($args['data'], true);
			if(is_array($data)) {
				$data['user_email'] = strtolower($data['user_email']);
				$data['token'] = md5($data['user_email']);
				$data['created_date'] = $this->getTimestamp;
				$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

				if (array_key_exists("time_difference",$data)) { /*CHECKING FOR USER'S TIMEZONE*/
					$local_time = $this->getTimestamp - $data['time_difference'];
				}
				else {
					http_response(400, 0, 'TXN_INVALID_LOCAL_TIME_DIFFERENCE');
					return false;
				}
				if( ! $this->is_registered_user($data['token'])) {
					if($this->db->insert('ws_user_info', $data)) {
						//$response = array("token" => $data['token']);	
						$response = $this->get_user_detail($data['token'], $data['time_difference']);
						http_response(201, 1, 'TXN_REGISTERED_MSG', $response);
						return true;						
					} 
					else {
						http_response(406, 0);
						return false;
					}	
				} 
				else {	
					$this->db->update('ws_user_info', $data, array('token' => $data['token']));
					$response = $this->get_user_detail($data['token'], $data['time_difference']);
					http_response(202, 1, 'TXN_LOGIN_MSG', $response);
					return true;
				}
			} 
			else {
				http_response(406, 0);
				return false;
			}
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| EDIT USER PROFILE i.e. NAME AND IMAGE
| -------------------------------------------------------------------
*/	
	public function edit_user_details($args = NULL) {
		if( ! $args) {
			http_response(400, 0);
			return false;
		}
		if($args['data']) {
			$data = json_decode($args['data'], true);
			$data['updated_date'] = $this->getTimestamp;
				if(is_array($data)) {
				if ( ! $data['token']) {
					http_response(498, 0);	
					return false;
				}
				if (array_key_exists("time_difference",$data)) { /*CHECKING FOR USER'S TIMEZONE*/
					$local_time = $this->getTimestamp - ($data['time_difference']);
				}
				else {
					http_response(400, 0, 'TXN_INVALID_LOCAL_TIME_DIFFERENCE');
					return false;
				}
				if ( ! $data['user_name']) {
					http_response(400, 0);
					return false;
				}
				if(isset($args['user_image']) && $args['user_image']) {
					$data['user_image'] = $args['user_image'];
				}

				if($this->db->update('ws_user_info', $data, array('token' => $data['token']))) {
					http_response(202, 1, 'TXN_UPDATED_PROFILE_MSG');
					return true;
				} 
				else {
					http_response(400, 0);
					return false;
				}		
			} 
			else {
				http_response(406, 0);
				return false;
			}
		} 
		else {
			http_response(406, 0);
			return false;
		}
	}

/*
| [:START:]
| -------------------------------------------------------------------
| INSERT USER RECORD i.e. ONE RECORD PAR DAY
| -------------------------------------------------------------------
*/	

	public function insert_user_note($args = array()) {
		if($args['data']) {
			$data = json_decode($args['data'], true);
/*
 * [:START:]
 * parameter log for user note
*/
			$txt = gmdate('d/m/Y H:i:s') . ' - ' . __CLASS__ .'/'. __function__ . ' :' . "\n";
			$txt .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n";
			$txt .= "Checking for insert_user_note parameter: " . implode(', ', $data) . "\n\n";
			$para_log = fopen($this->logPath, "a+") or die("unable to open file!");
			fwrite($para_log, $txt);
			fclose($para_log);
/*[:END:]*/

			if(is_array($data)) {
				if( ! $data['token']) {
					http_response(498, 0);
					return false;
				}
				if ( ! array_key_exists("time_difference",$data)) {
					http_response(400, 0, 'TXN_INVALID_LOCAL_TIME_DIFFERENCE');
					return false;
				}
				if(array_key_exists('is_admin', $data)) {
					if( ! array_key_exists('created_date', $data)) {
						$data['created_date'] = $this->getTimestamp;	
					}
					elseif (array_key_exists('created_date', $data) && ($data['created_date'] == '' || $data['created_date'] == 0)) {
						$data['created_date'] = $this->getTimestamp;
					}
					else {
						$curDate = strtotime(gmdate("Y-m-d", $this->getTimestamp));
						$prevDate = strtotime(gmdate("Y-m-d", $data['created_date']));
						if($curDate < $prevDate) {
							http_response(400, 0, 'TXN_DATE_EXCEDDED_MSG');
							return false;
						}
					}	
				}
				else
					$data['created_date'] = $this->getTimestamp;
				
				$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				if( ! $this->is_note_available($data)) {	
					if($this->db->insert('ws_user_record', $data)) {

						$this->set_streak($data);  	// function to set highest streak i.e. highest continuous record of user
						
						http_response(201, 1, 'TXN_INSERTED_RECORD_MSG');
						return true;						
					} 
					else {
						http_response(406, 0);
						return false;
					}	
				} 
				else {
					http_response(402, 0, 'TXN_RECORD_EXCEDDED_MSG');
					return true;
				}
			} 
			else {
				http_response(406, 0);
				return false;
			}
		} 
		else {
			http_response(400, 0);
			return false;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| CHECKING RECORD IS INSERTED IN A DAY OR NOT 
| -------------------------------------------------------------------
*/	

/*	public function is_note_available($args = array()) {
		if( ! $args) {
			http_response(400, 0);
			return false;
		}
		if (array_key_exists("time_difference",$args)) { /*CHECKING FOR USER'S TIMEZONE
			$time_difference_from_gmt = $args['time_difference'];
		}
		else {
			http_response(400, 0, 'TXN_INVALID_LOCAL_TIME_DIFFERENCE');
			return false;
		}
		$local_time = $args['created_date'] - ($time_difference_from_gmt);
		
		$from = $local_time;
		$to = $local_time + 86399;
		$this->db->where("created_date BETWEEN '$from' AND '$to'");
		$this->db->select('record_id, user_note, created_date');
		$this->db->order_by('created_date', 'desc');
		$this->db->where('status', '1');
		$this->db->where('token', $args['token']);
		$query = $this->db->get('ws_user_record');
		
		//echo $this->db->last_query(); die;
		if($query->result_array()) 
			return true;
		else 
			return false;
	}*/
	public function is_note_available($args = array()) {
		if( ! $args) {
			http_response(400, 0);
			return false;
		}

		$diff = $args['time_difference'];
		$date = $args['created_date'] + ($diff);
		$this->db->where("DATE_FORMAT(CONVERT_TZ(from_unixtime(created_date+($diff)), @@session.time_zone, '+00:00' ), '%Y-%m-%d') = DATE_FORMAT(CONVERT_TZ(from_unixtime('$date'), @@session.time_zone, '+00:00' ), '%Y-%m-%d')");
		$this->db->select('record_id, user_note, created_date');
		$this->db->order_by('created_date', 'desc');
		$this->db->where('status', '1');
		$this->db->where('token', $args['token']);
		$query = $this->db->get('ws_user_record');
		if($query->result_array()) 
			return true;
		else 
			return false;
	}
/*
| [:START:]
| -------------------------------------------------------------------
| COUNTING FOR HIGHEST NUMBER OF CONTINUES RECORD 
| -------------------------------------------------------------------
*/	
	public function set_streak($args = NULL) {
		if ( ! $args) {
			http_response(400, 0);
			return false;
		}
		if ( ! $args['token']) {
			http_response(498, 0);
			return false;
		}
		if (array_key_exists("time_difference",$args)) { /*CHECKING FOR USER'S TIMEZONE*/
			$time_difference_from_gmt = $args['time_difference'];
			$current_local_time = $this->getTimestamp + ($time_difference_from_gmt);
			$current_local_date = strtotime(date('Y-m-d', $current_local_time));
		}
		else {
			http_response(400, 0, 'TXN_INVALID_LOCAL_TIME_DIFFERENCE');
			return false;
		}
	
		$token = $args['token'];

		if(array_key_exists('is_admin', $args)) {

			$sql = "select record_id, created_date from ws_user_record where token = '$token' order by created_date desc limit 0, 2000";
			$adminRecord = $this->db->query($sql)->result();
			$ptr = count($adminRecord);
			$i = 0;
			if($ptr > 0) {
				foreach ($adminRecord as $key => $value) {
					$current_streak_local_time = $value->created_date + ($time_difference_from_gmt);
					$current_streak_local_date = strtotime(date('Y-m-d', $current_streak_local_time));
					$currentLocalDate = ($current_local_date - $current_streak_local_date)/(60*60*24);

					if((int)$currentLocalDate === 0 || (int)$currentLocalDate === 1) {
						$i++;
					}
					else {
						 break;
					}
					$current_local_date = $current_streak_local_date;	
				}
			}
			$this->db->update('ws_user_info', array('current_streak' => $i, 'current_streak_date' => $this->getTimestamp), array('token' => $args['token']));	
			return $i;
		}

	
		$userSql = "select record_id, created_date from ws_user_record where token = '$token' order by created_date desc limit 1";
		$response = $this->db->query($userSql)->result();

		/*	$gmDate = $this->getTimestamp;

		$response = $this->db->query("SELECT DATEDIFF(FROM_UNIXTIME($gmDate), FROM_UNIXTIME(created_date)) as last_note_date_difference FROM `ws_user_record` where token = '".$args['token']."' order by created_date desc limit 1")->row_array();
		if ((int)$response['last_note_date_difference'] === 1 || (int)$response['last_note_date_difference'] === 0) {
			$this->db->select('current_streak');
			$get_streak = $this->db->get_where('ws_user_info', array('token'=>$args['token']))->row();
			$current_streak = (int)$get_streak->current_streak+1;
			$this->db->update('ws_user_info', array('current_streak' => $current_streak), array('token' => $args['token']));	
			return true;
		}*/

		if($response) {
			foreach ($response as $key => $value) {
				$current_streak_local_time = $value->created_date + ($time_difference_from_gmt);
				$current_streak_local_date = strtotime(date('Y-m-d', $current_streak_local_time));
				$currentLocalDate = ($current_local_date - $current_streak_local_date)/(60*60*24);
				if((int)$currentLocalDate === 0 || (int)$currentLocalDate === 1) {
					$this->db->select('current_streak');
					$get_streak = $this->db->get_where('ws_user_info', array('token'=>$args['token']))->row();
					
					$current_streak = (int)$get_streak->current_streak+1;
					$this->db->update('ws_user_info', array('current_streak' => $current_streak), array('token' => $args['token']));	
					return true;	
				}
			}
		}
		else {
			$this->db->update('ws_user_info', array('current_streak' => 1), array('token' => $args['token']));	
			return true;	
		}
	}

/*
| [:START:]
| -------------------------------------------------------------------
| EDIT USER NOTE
| -------------------------------------------------------------------
*/
	public function edit_user_note($args = NULL) {
		if( ! $args) {
			http_response(400, 0);
			return false;
		}		
		if($args['data']) {
			$data = json_decode($args['data'], true);
			$data['updated_date'] = $this->getTimestamp;
		
			if(is_array($data)) {
				if( ! $data['token']) {
					http_response(498, 0);
					return false;
				}
				if( ! $data['record_id']) {
					http_response(400, 0);	
					return false;
				}
				if($this->db->update('ws_user_record', $data, array('record_id' => $data['record_id']))) {
					http_response(202, 1, 'TXN_UPDATED_RECORD_MSG');
					return true;
				} 
				else {
					http_response(400, 0);
					return false;
				}		
			} 
			else {
				http_response(406, 0);
				return false;
			}
		} 
		else {
				http_response(406, 0);
				return false;
			}
	}

/*
| [:START:]
| -------------------------------------------------------------------
| DELETE USER NOTE
| -------------------------------------------------------------------
*/
	public function delete_user_note($args = NULL) {
		if( ! $args) {
			http_response(400, 0);
			return false;
		}		
		if($args['data']) {
			$data = json_decode($args['data'], true);		
			if(is_array($data)) {
				if( ! $data['token']) {
					http_response(498, 0);
					return false;
				}
				if( ! $data['record_id']) {
					http_response(400, 0);	
					return false;
				}
				if($this->db->delete('ws_user_record', array('record_id' => $data['record_id']))) {
					_dx($this->db->last_query());
					http_response(202, 1, 'TXN_DELETED_RECORD_MSG');
					return true;
				} 
				else {
					http_response(400, 0);
					return false;
				}		
			} 
			else {
				http_response(406, 0);
				return false;
			}
		} 
		else {
				http_response(406, 0);
				return false;
			}
	}	
/*
| [:START:]
| -------------------------------------------------------------------
| SEARCH USER RECORD i.e. DATE WISE AS WELL STRING OR ALL
| -------------------------------------------------------------------
*/	
	public function search_user_notes($args = NULL) {
		$current_page_number = $totalPage = '';
		if( ! $args) {
			http_response(400, 0);
			return false;
		}
		if(! $args['token']) {
			http_response(498, 0);
			return false;
		}
		if (array_key_exists("time_difference",$args)) { /*CHECKING FOR USER'S TIMEZONE*/
			$time_difference_from_gmt = $args['time_difference'];
		}
		else {
			http_response(400, 0, 'TXN_INVALID_LOCAL_TIME_DIFFERENCE');
			return false;
		}

		if(array_key_exists('type', $args)) {
			if($args['type'] === 'date') { // search note via date
				$diff = $args['time_difference'];
				$date = $args['date'] + ($diff);
				$this->db->where("DATE_FORMAT(CONVERT_TZ(from_unixtime(created_date+($diff)), @@session.time_zone, '+00:00' ), '%Y-%m-%d') = DATE_FORMAT(CONVERT_TZ(from_unixtime('$date'), @@session.time_zone, '+00:00' ), '%Y-%m-%d')");
			}
			elseif($args['type'] === 'str') { // search note via string
				if( ! $args['search_txt']) {
					http_response(404, 1, 'TXN_RECORD_NOT_FOUND_MSG');
					return false;
				}
				$this->db->like('user_note', $args['search_txt']);
			}
		}
		
		if(isset($args['type']) && $args['type'] === 'date') {
			$this->db->limit(1);
		}
		else {
			 $this->db->where(array('token' => $args['token'], 'status' => '1'));
			 $totalRecords = $this->db->count_all_results('ws_user_record');
			 if($totalRecords > 0) {
			 	$roundOffPage = floor($totalRecords / $this->per_page_reocrd);
			 	if($roundOffPage < ($totalRecords / $this->per_page_reocrd)) {
					$totalPage = floor($totalRecords / $this->per_page_reocrd) + 1;
			 	}
			 	else {
			 		$totalPage = $totalRecords / $this->per_page_reocrd;
			 	}
			 	
			 	if(isset($args['page_number']) && $args['page_number']) {
			 		$current_page_number = $args['page_number'];
			 		$page = ($args['page_number'] - 1 );
			 		$start = ($this->per_page_reocrd * $page);
			 		$this->db->limit($this->per_page_reocrd, $start);

			 	}
			 	else {
			 		$current_page_number = 1;
			 		$this->db->limit($this->per_page_reocrd);
			 	}
			 }
		}

		if(isset($args['type']) && $args['type'] === 'str') {
			$this->db->like('user_note', $args['search_txt']);
		}
		$this->db->select('record_id, user_note, created_date');
		$this->db->order_by('created_date', 'desc');
		$this->db->where('status', '1');
		$this->db->where('token', $args['token']);
		$response = $this->db->get('ws_user_record')->result();
		if($response) {
			http_response(200, 1, '', $response, $current_page_number, $totalPage);	
		} 
		else {
			http_response(404, 1, 'TXN_RECORD_NOT_FOUND_MSG', $response, $current_page_number, $totalPage);
			return false;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| PICKING UP ANY RENDOM RECORD FOR MEMORY NOTIFICATION 
| -------------------------------------------------------------------
*/	
	public function get_current_note($args = NULL) {
		if( ! $args) {
			http_response(400, 0);
			return false;
		}
		if(! $args['token']) {
			http_response(498, 0);
			return false;
		}
		if (array_key_exists("time_difference",$args)) { /*CHECKING FOR USER'S TIMEZONE*/
			$time_difference_from_gmt = $args['time_difference'];
			$localTime = $this->getTimestamp + ($time_difference_from_gmt);
			$localDate = date("Y-m-d", $localTime);
		}
		else {
			http_response(400, 0, 'TXN_INVALID_LOCAL_TIME_DIFFERENCE');
			return false;
		}

		$from = strtotime($localDate . " 00:00");
		$to = $from + 86399;
		unset($args['time_difference']);
		$this->db->limit(1);
		$this->db->order_by('created_date','desc');
		$this->db->where("created_date + ($time_difference_from_gmt) BETWEEN '$from' AND '$to'");
		$this->db->where("status","1");
	    $this->db->where($args);
		$this->db->select("record_id, user_note, created_date");
	    $response = $this->db->get('ws_user_record')->row();
	    if($response) {
	    	http_response(200, 1, '', $response);
	    } 
	    else {
	    	$response = array('real_time' => $this->getTimestamp);
			http_response(404, 0, 'TXN_NO_RECORD_FOR_THE_DAY_MSG', $response);
			return false;	
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| PICKING UP ANY RENDOM RECORD FOR MEMORY NOTIFICATION 
| -------------------------------------------------------------------
*/	
	public function get_rendom_note($args = NULL) {
		if(! $args['token']) {
			http_response(498, 0);
			return false;
		}
		$token = $args['token'];

		$result = $this->db->query("select count(record_id) as totalRecords from ws_user_record where token = '$token'")->row();
		if($result->totalRecords) {
			$rand = mt_rand(0, (int)$result->totalRecords - 1);
			
			$response = $this->db->query("select record_id, user_note, created_date from ws_user_record where token = '$token' and user_note != '' and status = '1' limit $rand, 1")->row();
			return $response;	
		}
		else {
			return false;	
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| CONTACT US
| -------------------------------------------------------------------
*/	
	public function contact_us_feedback($args = NULL) {
		if(!$args) {
			http_response(400, 0);
			return false;
		}
		if($args['data']) {
			$data = json_decode($args['data'], true);
			$data['created_date'] = $this->getTimestamp;
			
			if(is_array($data)) {
				if( ! $data['token']) {
					http_response(498, 0);
					return false;
				}
				if($user_detail = $this->get_user_detail($data['token'], $data['time_difference'])) {
					$data['user_email'] = $user_detail->user_email;
					// Compose a simple HTML email message
					if($this->db->insert('ws_contact_us', $data)) {
						$subject = "Microjournal- '" . $data['subject'] . "' -" . date("jS F, Y", strtotime(date('Y-m-d'))); 
						
						$message = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body>';
					
						$message .= '<p> User Comment: '.$data['user_comment'].' <hr /></p>';
						
						$message .= '<p> User Detail: <br /> ' .$user_detail->user_name. ' [ '.$user_detail->user_email.' ]</p>';

						$message .= '<p> <img src="http://alphademo.in/microjournal/' . $user_detail->user_image.'" alt="'.$user_detail->user_name . '" width="50" height="50"> </p>';

						$message .= '</body></html>';
												
						@send_mail($user_detail->user_email, $this->admin_email, $subject, $message);
						http_response(201, 1, 'TXN_INSERETED_CONTACT_US_MSG');
						return true;						
					} 
					else {
						http_response(406, 0);
						return false;
					}
				}
			} 
			else {
				http_response(406, 0);
				return false;
			}
		} 
		else {
				http_response(406, 0);
				return false;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| NUMBER OF REGISTERED USER WHOSE MEMORY NOTIFICATION IS ON
| -------------------------------------------------------------------
*/	
	public function list_notification_users() {
		$this->db->select("token, user_email, device_notification_token, device_type");
		$this->db->where('device_notification_token !=', '');
		$response = $this->db->get_where('ws_user_info', array('is_notification	' => '1'))->result();
		if ($response) 
			return $response;
		
		http_response(404, 0);
		return false;
	}
/*
| [:START:]
| -------------------------------------------------------------------
| ENBALE/DISABLE NOTIFICATION STATUS
| -------------------------------------------------------------------
*/	
	public function on_off_notification($args = array()) {
		$display_msg = 'TXN_OFF_MEMORY_NOTIFICATION_MSG';	
		if( ! $args) {
			http_response(400, 0);
			return false;
		}	
			
		if($args['data']) {
			$data = json_decode($args['data'], true);
			if(is_array($data)) {
				if( ! $data['token']) {
					http_response(498, 0);
					return false;	
				}
				
				if($data['is_notification']) {
					$display_msg = 'TXN_ON_MEMORY_NOTIFICATION_MSG';
				}
				
				if($this->db->update('ws_user_info', $data, array('token' => $data['token']))) {
					http_response(202, 1, $display_msg);
					return true;
				} 
				else {
					http_response(400, 0);
					return false;
				}		
			} 
			else {
				http_response(406, 0);
				return false;
			}
		} 
		else {
				http_response(406, 0);
				return false;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| COUNTING OF TOTAL NUMBER OF RECORDS
| -------------------------------------------------------------------
*/	
	public function get_total_number_of_records($args = NULL) {
		if( ! $args) {
			http_response(400, 0);
			return false;
		}
		
		if( ! $args['token']) {
			http_response(498, 0);
			return false;
		}	
		if(isset($args['time_difference']))
			unset($args['time_difference']);
		
					$this->db->select("count('record_id') as days_grateful");
					$this->db->where('status', '1');
		$response = $this->db->get_where('ws_user_record',$args)->row();
		if($response) {
			return $response;
		} 
		else {
				http_response(404, 0, 'TXN_RECORD_NOT_FOUND_MSG');
				return false;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| INSERT DEVICE TOKEN TO SEND PUSH NOTIFICATION
| -------------------------------------------------------------------
*/	
	public function update_device_token($args = array()) {
		if( ! $args) {
			http_response(400, 0);
			return false;
		}	
			
		if($args['data']) {
			$data = json_decode($args['data'], true);
			if(is_array($data)) {
				if( ! $data['token']) {
					http_response(498, 0);
					return false;	
				}
				
				if($this->db->update('ws_user_info', $data, array('token' => $data['token']))) {
					http_response(202, 1, $display_msg);
					return true;
				} 
				else {
					http_response(400, 0);
					return false;
				}		
			} 
			else {
				http_response(406, 0);
				return false;
			}
		} 
		else {
				http_response(406, 0);
				return false;
		}
	}		
/*[:END:]*/		
}