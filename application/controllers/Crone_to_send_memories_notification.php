<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class Crone_to_send_memories_notification extends MY_Controller {
/*
| [:START:]
| ADMIN: VOOLSY
| AUTHOR: VINIT
| -------------------------------------------------------------------
| CHECKING FOR CONTROLLER
| -------------------------------------------------------------------
| This file specifies which controller should be loaded by default.
*/	
	public function __construct() {
         parent::__construct();
    }
	
	public function index() {
		_d(date_default_timezone_get());
		_dx(__CLASS__ ."/". __function__);	
	}
	
	public function get_notification_to_send() {
		$i = 1;
		if($user_list = $this->users->list_notification_users()) {
			foreach($user_list as $key=>$val) {
				$notification_arr = array();	
				$data['token'] = $val->token;
				if($note = $this->users->get_rendom_note($data)) {
					
					$notification_arr['device_id']		= $val->device_notification_token;
					$notification_arr['device_type']	= $val->device_type;
					$notification_arr['notification']	= $note->user_note; 	
					
					$response = $this->send_push_notification($notification_arr); // Execution of sending push notification
					if($i === 1) {
						echo "notification has been sent";
						$i++;
					}
				}
			}
		}	
		return false;
	}
}
/*[:END:]*/