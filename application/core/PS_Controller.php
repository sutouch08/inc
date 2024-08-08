<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PS_Controller extends CI_Controller
{
  public $pm;
  public $home;
	public $_user;
	public $_SuperAdmin = FALSE;
	public $_Admin = FALSE;
  public $_Manager = FALSE;
  public $_User = FALSE;
	public $ms;
	public $mc;
  public $error;

  public function __construct()
  {
    parent::__construct();


    //--- check is user has logged in ?
    _check_login();

    $uid = get_cookie('uid');

		$this->_user = $this->user_model->get_user_by_uid($uid);

		$this->close_system   = getConfig('CLOSE_SYSTEM'); //--- ปิดระบบทั้งหมดหรือไม่
		$this->_SuperAdmin = $this->_user->group_id == 1 ? TRUE : FALSE;
		$this->_Admin = $this->_user->group_id == 2 ? TRUE : FALSE;
    $this->_Manager = $this->_user->group_id == 3 ? TRUE : FALSE;
    $this->_User = $this->_user->group_id > 3 ? TRUE : FALSE;

    if($this->close_system == 1 && $this->_SuperAdmin === FALSE)
    {
      redirect(base_url().'maintenance');
    }

		if( ! $this->_SuperAdmin && $this->is_expire_password($this->_user->last_pass_change))
		{
			redirect(base_url().'change_password/e');
		}

		if($this->_user->force_reset)
		{
			redirect(base_url().'change_password/f');
		}


    //--- get permission for user
    $this->pm = get_permission($this->menu_code, $uid, $this->_user->id_profile);

    if($this->pm->can_view == 0)
    {
      $this->load->view('deny_page');
    }

    $this->ms = $this->load->database('ms', TRUE);
    // $this->mc = $this->load->database('mc', TRUE);

  }


	public function is_expire_password($last_pass_change)
	{
		$today = date('Y-m-d');

		$last_change = empty($last_pass_change) ? date('2021-01-01') : $last_pass_change;

		$expire_days = intval(getConfig('USER_PASSWORD_AGE'));

		if($expire_days != 0)
		{
			$expire_date = date('Y-m-d', strtotime("+{$expire_days} days", strtotime($last_change)));

			if($today > $expire_date)
			{
				return true;
			}
		}

		return FALSE;
	}


	public function _response($sc = TRUE)
  {
    echo $sc === TRUE ? 'success' : $this->error;
  }

  public function deny_page()
  {
    return $this->load->view('deny_page');
  }

  public function permission_deny()
  {
    return $this->load->view('permission_deny');
  }

	public function permission_page()
  {
    return $this->load->view('permission_deny');
  }

  public function expired_page()
  {
    return $this->load->view('expired_page');
  }


  public function error_page()
  {
    return $this->load->view('page_error');
  }

  public function page_error()
  {
    return $this->load->view('page_error');
  }
}

?>
