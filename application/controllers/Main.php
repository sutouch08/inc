<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends PS_Controller
{
	public $title = 'Welcome';
	public $menu_code = 'HOME';
	public $menu_group_code = '';
	public $home;

	public function __construct()
	{
		parent::__construct();
		_check_login();
		$this->pm = new stdClass();
		$this->pm->can_view = 1;
		$this->home = base_url()."main";

		$this->load->model('orders/discount_model');
	}


	public function index()
	{
		$this->load->model('users/approver_model');

		$is_approver = $this->approver_model->is_approver($this->_user->id);
		$aps = array();

		if($is_approver)
		{
			$doc_list = $this->approver_model->doc_type_list();

			if( ! empty($doc_list))
			{
				foreach($doc_list as $doc)
				{
					$ap = $this->approver_model->get_rule_by_user_id($this->_user->id, $doc->code);

					if( ! empty($ap))
					{
						$ap->doc_name = $doc->name;
						$ap->count = $this->count_doc($ap);
						array_push($aps, $ap);
					}
				}
			}
		}

		$ds = array(
			'is_approver' => $is_approver,
			'aps' => $aps
		);

		$this->load->view('main_view', $ds);
	}

	public function count_doc($ap)
	{
		if($ap->review)
		{
			return $this->count_unreview_sq();
		}

		if($ap->approve)
		{
			return $this->count_unapprove_sq($ap->maxDisc, $ap->maxAmount);
		}
	}

	function count_sq()
	{
		$right = $this->input->get('right');
		$maxDisc = $this->input->get('maxDisc');
		$maxAmount = $this->input->get('maxAmount');

		if($right == 'review')
		{
			echo $this->count_unreview_sq();
		}
		else
		{
			echo $this->count_unapprove_sq($maxDisc, $maxAmount);
		}
	}
	

	public function count_unapprove_sq($maxDisc, $maxAmount)
	{
		$this->db->where('Status', 0)
		->where('must_approve', 1)->where('Approved', 'P')
		->group_start()->where('Review', 'A')->or_where('Review', 'S')->group_end()
		->where('disc_diff <=', $maxDisc)->where('DocTotal <=', $maxAmount);

		return $this->db->count_all_results('quotation');
	}

	public function count_unreview_sq()
	{
		$this->db->where('Status', 0)->where('Review', 'P');

		return $this->db->count_all_results('quotation');
	}


	public function set_rows()
  {
    if($this->input->post('set_rows') && $this->input->post('set_rows') > 0)
    {
      $rows = intval($this->input->post('set_rows'));
      $cookie = array(
        'name' => 'rows',
        'value' => $rows > 300 ? 300 : $rows,
        'expire' => 2592000, //--- 30 days
        'path' => '/'
      );

      $this->input->set_cookie($cookie);
    }
  }


	public function testApi()
  {
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'bec.electric.co.th:1995/api/GetQuotaStock',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT_MS => 200,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_POSTFIELDS =>'{
		    "ItemCode": "FG-BEL0489",
		    "WhsCode": [
		        "BC-G",
		        "L4-G",
		        "L4-P",
		        "L5-G",
		        "L8-G"
		    ],
		    "QuotaNo": "B20G"
		}',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		if($response === FALSE)
		{
			$err_code = curl_errno($curl);
			$response = "Error: {$err_code} ".curl_error($curl);
		}

		curl_close($curl);

		$rs = json_decode($response);

		if(! empty($rs))
		{
			print_r($rs);
		}
		else
		{
			echo $response;
		}
  }

} //--- end class
