<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_approval extends PS_Controller
{
	public $menu_code = 'APODSQ';
	public $menu_group_code = 'AP';
  public $menu_sub_group_code = '';
	public $title = 'Quotation Approval';
	public $docType = 'SQ';
	public $segment = 4;
	public $readOnly = FALSE;

	public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'approval/quotation_approval';
		$this->load->model('orders/quotation_model');
    $this->load->model('users/approver_model');
    $this->load->model('orders/discount_model');
		$this->load->model('masters/customers_model');
		$this->load->model('masters/customer_address_model');
		$this->load->model('masters/products_model');
		$this->load->model('masters/payment_term_model');
		$this->load->helper('discount');
		$this->load->helper('order');
		$this->load->helper('warehouse');

  }


  public function index()
  {

		$filter = array(
			'code' => get_filter('code', 'sq_code', ''),
			'customer' => get_filter('customer', 'sq_customer', ''),
			'project' => get_filter('project', 'project', ''),
			'from_date' => get_filter('from_date', 'sq_from_date', ''),
			'to_date' => get_filter('to_date', 'sq_to_date', '')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			//--- แสดงผลกี่รายการต่อหน้า
			$perpage = get_rows();

      $approver = $this->approver_model->get_by_user_id($this->_user->id);
      $rule = NULL;

      if( ! empty($approver))
      {
        $rule = $this->approver_model->get_doc_rule($approver->id, $this->docType);

        if( ! empty($rule) && $rule->approve == 1)
        {
          $filter['minDisc'] = $rule->minDisc;
          $filter['maxDisc'] = $rule->maxDisc;
          $filter['minAmount'] = $rule->minAmount;
          $filter['maxAmount'] = $rule->maxAmount;
        }
      }
			
			$rows = empty($rule) ? 0 : $this->quotation_model->count_approval_rows($filter);
			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$filter['data'] = empty($rule) ? NULL : $this->quotation_model->get_approval_list($filter, $perpage, $this->uri->segment($this->segment));
			$this->pagination->initialize($init);
			$this->load->view('approval/quotation/sq_list', $filter);
		}
  }


	public function view_detail($code)
	{
		$this->load->model('users/approver_model');
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/employee_model');

		$totalAmount = 0;
		$totalVat = 0;

		$order = $this->quotation_model->get_header($code);

		if( ! empty($order))
		{
			$details = $this->quotation_model->get_details($order->code);

			if(!empty($details))
			{
				foreach($details as $rs)
				{
					$totalAmount += $rs->LineTotal;
					$totalVat += $rs->totalVatAmount;
				}
			}

			$order->payment_name = $this->payment_term_model->get_name($order->Payment);

			$ap = $this->approver_model->get_rule_by_user_id($this->_user->id, $this->docType);

			if(empty($ap))
			{
				$ap = (object) array(
					'id' => NULL,
					'user_id' => $this->_user->id,
					'review' => 0,
					'approve' => 0,
					'maxDisc' => 0,
					'maxAmount' => 0
				);
			}

			$ds = array(
				'order' => $order,
				'details' => $details,
				'totalAmount' => $totalAmount,
				'totalVat' => $totalVat,
				'sale_name' => $this->sales_person_model->get_name($order->SlpCode),
				'owner' => $this->employee_model->get_name($order->OwnerCode),
				'ap' => $ap
			);

			$this->load->view('approval/quotation/sq_view', $ds);
		}
		else
		{
			$this->page_error();
		}
	}



	public function get_logs()
	{
		$sc = TRUE;
		$ds = array();
		$code = $this->input->get('code');

		if( ! empty($code))
		{
			$logs = $this->quotation_model->get_logs($code);

			if( ! empty($logs))
			{
				foreach($logs as $lg)
				{
					$arr = array(
						'name' => action_name($lg->action),
						'uname' => $lg->uname,
						'date' => thai_date($lg->date_upd, TRUE)
					);

					array_push($ds, $arr);
				}
			}
			else
			{
				$arr = array('nodata' => 'nodata');
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing required parameter";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}


	public function clear_filter()
	{
		$filter = array(
			'sq_code',
			'sq_customer',
			'project',
			'sq_from_date',
			'sq_to_date'
		);

		return clear_filter($filter);
	}


}//--- end class


 ?>
