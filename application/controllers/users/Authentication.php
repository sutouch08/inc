<?php
class Authentication extends CI_Controller
{
	public $error;

  public function __construct()
	{
		parent::__construct();
		$this->home = base_url()."users/authentication";
	}


	public function index()
	{
		$this->load->view("login");
	}



	public function validate_credentials()
	{
    $sc = TRUE;
    $user_name = $this->input->post('uname');
    $pwd = $this->input->post('pwd');
		$rem = $this->input->post('remember') == 1 ? TRUE : FALSE;

		$rs = $this->user_model->get_user_credentials($user_name);

    if(! empty($rs))
    {
			if(password_verify($pwd, $rs->pwd))
			{
				if($rs->active == 0)
				{
					$sc = FALSE;
	        $this->error = 'Your account has been suspended';
				}
				else
				{
					$ds = array(
						'uid' => $rs->uid,
						'uname' => $rs->uname,
						'displayName' => $rs->name,
						'id_profile' => $rs->id_profile
					);

					$this->create_user_data($ds, $rem);
				}
			}
			else
			{
				$sc = FALSE;
        $this->error = 'Username or password is incorrect';
			}
    }
    else
    {
			$sc = FALSE;
			$this->error = 'Username or password is incorrect';
    }

		echo $sc === TRUE ? 'success' : $this->error;
	}



  public function create_user_data(array $ds = array(), $remember = FALSE )
  {
    if(!empty($ds))
    {
      $times = intval(86400); //-- 1 days

			$times = $remember ? $time * 30 : $times;

      foreach($ds as $key => $val)
      {
        $cookie = array(
          'name' => $key,
          'value' => $val,
          'expire' => $times,
          'path' => '/'
        );

        $this->input->set_cookie($cookie);
      }
    }
  }




	public function logout()
	{
		delete_cookie('uid');
    delete_cookie('displayName');
    delete_cookie('id_profile');
    redirect($this->home);
	}


} //--- end class


 ?>
