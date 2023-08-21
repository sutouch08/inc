<?php
class Suspended extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }


  public function index()
  {

		if($this->config->item('system_date'))
		{
			redirect(base_url().'main');
			exit;
		}

		$ds['text'] = "บัญชีของคุณถูกระงับการใช้งาน เนื่องจากสัญญาการให้บริการสิ้นสุดลง โปรดติดต่อตัวแทนผู้ให้บริการเพื่อชำระค่าบริการและเปิดใช้งานระบบอีกครั้ง";

    $this->load->view('suspended', $ds);
  }

}


 ?>
