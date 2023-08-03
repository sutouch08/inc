<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount_rule extends PS_Controller
{
  public $menu_code = 'SCRULE';
	public $menu_group_code = 'SC';
	public $title = 'Discount Rules';
  public $error;
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'discount/discount_rule';
    $this->load->model('discount/discount_policy_model');
    $this->load->model('discount/discount_rule_model');
		$this->load->helper('discount_policy');
    $this->load->helper('discount_rule');
  }


  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'rule_code', ''),
			'name' => get_filter('name', 'rule_name', ''),
			'active' => get_filter('active', 'rule_active', 'all'),
			'type' => get_filter('type', 'rule_type', 'all'),
			'policy' => get_filter('policy', 'rule_policy', ''),
			'priority' => get_filter('priority', 'rule_priority', 'all')
		);

		$perpage = get_rows();


		$rows = $this->discount_rule_model->count_rows($filter);

		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);

		$filter['data'] = $this->discount_rule_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

  	$this->pagination->initialize($init);

    $this->load->view('discount/rule/rule_list', $filter);
  }



  public function add_new()
  {
    if($this->pm->can_add)
    {
			$ds = array("code" => $this->get_new_code());
      $this->load->view('discount/rule/rule_add', $ds);
    }
    else
    {
      $this->permission_page();
    }
  }



  public function add()
  {
		$sc = TRUE;

    if($this->pm->can_add)
    {
      if($this->input->post('name'))
      {
        $code = $this->get_new_code();
        $name = trim($this->input->post('name'));

        $arr = array(
          'code' => $code,
          'name' => $name,
          'user' => $this->_user->uname
        );

        $id = $this->discount_rule_model->add($arr);

        if(! $id)
        {
          set_error('insert');
        }
      }
      else
      {
        $sc = FALSE;
				set_error('required');
      }
    }
    else
    {
      $sc = FALSE;
			set_error('permission');
    }

		echo $sc === TRUE ? $id : $this->error;
  }



  public function edit($id, $tab = "discount")
  {
		if($this->pm->can_add OR $this->pm->can_edit)
		{
			$this->load->model('masters/channels_model');
			$this->load->model('masters/payment_term_model');
			$this->load->model('masters/customers_model');
			$this->load->model('masters/customer_group_model');
			$this->load->model('masters/customer_type_model');
			//$this->load->model('masters/customer_region_model');
			$this->load->model('masters/customer_area_model');
			$this->load->model('masters/customer_grade_model');
			$this->load->model('masters/products_model');
			$this->load->model('masters/product_model_model');
			$this->load->model('masters/product_category_model');
			$this->load->model('masters/product_type_model');
			$this->load->model('masters/product_brand_model');

			$data = array(
				"rule" => $this->discount_rule_model->get($id),
				"channels" => $this->channels_model->get_all(),
				"payments" => $this->payment_term_model->get_all(),
				"cusList" => $this->discount_rule_model->getRuleCustomerId($id),
				"custGroup" => $this->discount_rule_model->getRuleCustomerGroup($id),
				"custType" => $this->discount_rule_model->getRuleCustomerType($id),
				"custRegion" => $this->discount_rule_model->getRuleCustomerRegion($id),
				"custArea" => $this->discount_rule_model->getRuleCustomerArea($id),
				"custGrade" => $this->discount_rule_model->getRuleCustomerGrade($id),
				"customer_groups" => $this->customer_group_model->get_all(),
				"customer_types" => $this->customer_type_model->get_all(),
				"customer_regions" => $this->customers_model->get_customer_sales_team_list(),//$this->customer_region_model->get_all(),
				"customer_areas" => $this->customer_area_model->get_all(),
				"customer_grades" => $this->customer_grade_model->get_all(),
				"pdList" => $this->discount_rule_model->getRuleProductId($id),
				"pdModel" => $this->discount_rule_model->getRuleProductModel($id),
				"pdType" => $this->discount_rule_model->getRuleProductType($id),
				"pdCategory" => $this->discount_rule_model->getRuleProductCategory($id),
				"pdBrand" => $this->discount_rule_model->getRuleProductBrand($id),
				"product_categorys" => $this->product_category_model->get_by_level(5),
				"product_types" => $this->product_type_model->get_all(),
				"product_brands" => $this->product_brand_model->get_all(),
				"free_items" => $this->discount_rule_model->getRuleFreeProduct($id),
				"tab" => $tab
			);

			$this->load->view('discount/rule/rule_edit', $data);

		}
		else
		{
			$this->permission_page();
		}
  }



  public function update_rule($id)
  {
    $arr = array(
      'name' => $this->input->post('name'),
      'active' => $this->input->post('active')
    );

    $rs = $this->discount_rule_model->update($id, $arr);

    echo $rs === TRUE ? 'success' : 'แก้ไขรายการไม่สำเร็จ';
  }




  //---- set discount on discount tab
  public function set_discount()
  {
    $sc = TRUE;

		if($this->input->post('rule_id') && $this->input->post('discType'))
		{
			$rule_id  = $this->input->post('rule_id');
			$discType = $this->input->post('discType');
			$price = $this->input->post('price');
			$disc1 = $this->input->post('disc1');
			$disc2 = $this->input->post('disc2');
			$disc3 = $this->input->post('disc3');
			$disc4 = $this->input->post('disc4');
			$disc5 = $this->input->post('disc5');
			$freeQty = $this->input->post('freeQty');
			$minQty   = $this->input->post('minQty');
	    $minAmount = $this->input->post('minAmount');
	    $canGroup = $this->input->post('canGroup');
			$freeItems = $this->input->post('freeItems');
			$priority = $this->input->post('priority');

			$arr = array(
				"minQty" => $minQty,
				"minAmount" => $minAmount,
				"canGroup" => $canGroup,
				"type" => $discType,
				"price" => $discType == 'N' ? $price : 0.00,
				"freeQty" => $freeQty,
				"disc1" => $discType == 'P' ? $disc1 : 0.00,
				"disc2" => $discType == 'P' ? $disc2 : 0.00,
				"disc3" => $discType == 'P' ? $disc3 : 0.00,
				"disc4" => $discType == 'P' ? $disc4 : 0.00,
				"disc5" => $discType == 'P' ? $disc5 : 0.00,
				"priority" => $priority,
				"update_user" => $this->_user->uname
			);

			$this->db->trans_begin();

			//--- drop free items
			if( ! $this->discount_rule_model->drop_free_product($rule_id))
			{
				$sc = FALSE;
				$this->error = "Delete Free item rule failed";
			}

			if($sc === TRUE && ! $this->discount_rule_model->update($rule_id, $arr))
			{
				$sc = FALSE;
				$this->error = "Update discount rule failed";
			}

			if($sc === TRUE && ! empty($freeItems))
			{
				$this->load->model('masters/products_model');

				foreach($freeItems as $id => $qty)
				{
					if($sc === FALSE)
					{
						break;
					}

					$pd = $this->products_model->get_code_and_name($id);

					if( ! empty($pd))
					{
						$arr = array(
							"rule_id" => $rule_id,
							"product_id" => $id,
							"product_code" => $pd->code,
						);

						if( ! $this->discount_rule_model->set_discount_rule_free_product($arr))
						{
							$sc = FALSE;
							$this->error = "Set free Items failed";
						}
					}
				}
			}

			if($sc === TRUE)
			{
				$this->db->trans_commit();
			}
			else
			{
				$this->db->trans_rollback();
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);

  }





  //---- set rule in customer tab
  public function set_customer_rule()
  {
		$sc = TRUE;

    if($this->input->post('rule_id'))
    {
      $rule_id = $this->input->post('rule_id');

      //--- all customer ?
      $all = $this->input->post('all_customer') == 'Y' ? TRUE : FALSE;

      //--- customer name ?
      $custId = $this->input->post('customer_id') == 'Y' ? TRUE : FALSE;

      //--- customer group ?
      $group = $this->input->post('customer_group') == 'Y' ? TRUE : FALSE;

      //--- customer type ?
      $type = $this->input->post('customer_type') == 'Y' ? TRUE : FALSE;

      //--- customer region ?
      $region = $this->input->post('customer_region') == 'Y' ? TRUE : FALSE;

      //--- customer area ?
      $area = $this->input->post('customer_area') == 'Y' ? TRUE : FALSE;

      //--- customer grade ?
      $grade = $this->input->post('customer_grade') == 'Y' ? TRUE : FALSE;

      if($all === TRUE)
      {
        if( ! $this->discount_rule_model->set_all_customer($rule_id, 1))
				{
					$sc = FALSE;
					$this->error = "Set all customer failed";
				}
      }
			else
			{
				//--- เปลี่ยนเงื่อนไข set all_customer = 0
				if( ! $this->discount_rule_model->set_all_customer($rule_id, 0))
				{
					$sc = FALSE;
					$this->error = "Set all customer failed";
				}

				//--- กรณีระบุชื่อลูกค้า
				if($sc === TRUE && $custId === TRUE)
				{
					$cusList = $this->input->post('custId');

					if( ! empty($cusList))
					{
						$result = $this->discount_rule_model->set_customer_list($rule_id, $cusList);
						if($result->status === FALSE)
						{
							$sc = FALSE;
							$this->error = $result->message;
						}
					}
				}

				//--- กรณีไม่ระบุชื่อลูกค้า
				if($sc === TRUE && $custId === FALSE)
				{
					$group = $this->input->post('customerGroup');
					$type  = $this->input->post('customerType');
					$region  = $this->input->post('customerRegion');
					$area  = $this->input->post('customerArea');
					$grade = $this->input->post('customerGrade');

					$result = $this->discount_rule_model->set_customer_attr($rule_id, $group, $type, $region, $area, $grade);

					if($result->status === FALSE)
					{
						$sc = FALSE;
						$this->error = $result->message;
					}
				} //--- end if custId == false
			}
    }
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		echo $sc === TRUE ? 'success' : $this->error;
  }



  public function set_product_rule()
  {
		$sc = TRUE;
  
    $rule_id = $this->input->post('rule_id');

    //--- all product ?
    $all = is_true($this->input->post('all_product'));

		//--- product SKU ?
		$item = is_true($this->input->post('product_id'));

    //--- product model ?
    $model = is_true($this->input->post('product_model'));

    //--- product category ?
    $category = is_true($this->input->post('product_category'));

    //--- product type ?
    $type = is_true($this->input->post('product_type'));

    //--- product brand ?
    $brand = is_true($this->input->post('product_brand'));

		$this->db->trans_begin();

		if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product($rule_id))
		{
			$sc = FALSE;
			$this->error = "Delete product rule failed";
		}

		if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_model($rule_id))
		{
			$sc = FALSE;
			$this->error = "Delete product model rule failed";
		}

		if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_category($rule_id))
		{
			$sc = FALSE;
			$this->error = "Delete product category rule failed";
		}

		if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_type($rule_id))
		{
			$sc = FALSE;
			$this->error = "Delete product type rule failed";
		}

		if($sc === TRUE && ! $this->discount_rule_model->drop_rule_product_brand($rule_id))
		{
			$sc = FALSE;
			$this->error = "Delete product brand rule failed";
		}

    if($sc === TRUE && $all === TRUE)
    {
      if( ! $this->discount_rule_model->set_all_product($rule_id, 1))
			{
				$sc = FALSE;
				$this->error = "Set all product failed";
			}
    }

		if($sc === TRUE && $all === FALSE)
		{
			//--- เปลี่ยนเงื่อนไข set all_product = 0
      if($sc === TRUE && ! $this->discount_rule_model->set_all_product($rule_id, 0))
			{
				$sc = FALSE;
				$this->error = "Set all product failed";
			}

			//--- set discount rule produt
			if($sc === TRUE && $item === TRUE)
			{
				$items = $this->input->post('productId');

				if( ! empty($items))
				{
					$this->load->model('masters/products_model');

					foreach($items as $product_id)
					{
						if($sc === FALSE)
						{
							break;
						}

						$pd = $this->products_model->get_code_and_name($product_id);

						if(! empty($pd))
						{
							$arr = array(
								"rule_id" => $rule_id,
								"product_id" => $pd->id,
								"product_code" => $pd->code
							);

							if( ! $this->discount_rule_model->set_discount_rule_product($arr))
							{
								$sc = FALSE;
								$this->error = "Insert product rule failed";
							}
						}
					}
				}
			}

			//--- set discount rule produt model
			if($sc === TRUE && $model === TRUE)
			{
				$modelList = $this->input->post('modelId');

				if( ! empty($modelList))
				{

					foreach($modelList as $model_id)
					{
						if($sc === FALSE)
						{
							break;
						}

						$arr = array(
							"rule_id" => $rule_id,
							"model_id" => $model_id
						);


						if( ! $this->discount_rule_model->set_discount_rule_product_model($arr))
						{
							$sc = FALSE;
							$this->error = "Insert product rule failed";
						}
					}
				}
			}

      //--- กรณีไม่ระบุชื่อสินค้า
      if($sc === TRUE && $model === FALSE && $item === FALSE)
      {
				//--- set discount rule product category
				if($category === TRUE)
				{
					$cateList = $this->input->post('productCategory');

					if( ! empty($cateList))
					{
						foreach($cateList as $cate_id)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr = array(
								"rule_id" => $rule_id,
								"category_id" => $cate_id
							);

							if( ! $this->discount_rule_model->set_discount_rule_product_category($arr))
							{
								$sc = FALSE;
								$this->error = "Insert product category failed";
							}
						}
					}
				}

				//--- set rule product type
				if($type === TRUE)
				{
					$typeList = $this->input->post('productType');

					if( ! empty($typeList))
					{
						foreach($typeList as $type_id)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr = array(
								"rule_id" => $rule_id,
								"type_id" => $type_id
							);

							if( ! $this->discount_rule_model->set_discount_rule_product_type($arr))
							{
								$sc = FALSE;
								$this->error = "Insert product type failed";
							}
						}
					}
				}

				//--- set rule product brand
				if($brand === TRUE)
				{
					$brandList = $this->input->post('productBrand');

					if( ! empty($brandList))
					{
						foreach($brandList as $brand_id)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr = array(
								"rule_id" => $rule_id,
								"brand_id" => $brand_id
							);

							if( ! $this->discount_rule_model->set_discount_rule_product_brand($arr))
							{
								$sc = FALSE;
								$this->error = "Insert product brand failed";
							}
						}
					}
				}
      }
		}

		if($sc === TRUE)
		{
			$this->db->trans_commit();
		}
		else
		{
			$this->db->trans_rollback();
		}

		$this->_response($sc);
  }



  public function set_channels_rule()
  {
		$sc = TRUE;

    $rule_id = $this->input->post('rule_id');

    //--- all channels ?
    $all = is_true($this->input->post('all_channels'));
		$chList = $this->input->post('channels');

		$this->db->trans_begin();

		//--- drop rule channels
		if( ! $this->discount_rule_model->drop_rule_channels($rule_id))
		{
			$sc = FALSE;
			$this->error = "Delete rule channels failed";
		}

    if($sc === TRUE && $all === TRUE)
    {
      if( ! $this->discount_rule_model->set_all_channels($rule_id, 1))
			{
				$sc = FALSE;
				$this->error = "Set all channels failed";
			}
    }

    if($sc === TRUE && $all === FALSE)
    {
			if( ! $this->discount_rule_model->set_all_channels($rule_id, 0))
			{
				$sc = FALSE;
				$this->error = "Set all channels failed";
			}

      if($sc === TRUE && ! empty($chList))
			{
				foreach($chList as $ch_id)
				{
					$arr = array(
						"rule_id" => $rule_id,
						"channels_id" => $ch_id
					);

					if( ! $this->discount_rule_model->set_discount_rule_channels($arr))
					{
						$sc = FALSE;
						$this->error = "Insert channels failed";
					}
				}
			}
    }

		if($sc === TRUE)
		{
			$this->db->trans_commit();
		}
		else
		{
			$this->db->trans_rollback();
		}

		$this->_response($sc);
  }




  public function set_payment_rule()
  {
		$sc = TRUE;

    $rule_id = $this->input->post('rule_id');
    //--- all channels ?
    $all = is_true($this->input->post('all_payment'));
		$paymentList = $this->input->post('payment');

		$this->db->trans_begin();

		//--- drop payment rule
		if( ! $this->discount_rule_model->drop_rule_payment($rule_id))
		{
			$sc = FALSE;
			$this->error = "Delete payment rule failed";
		}

    if($sc === TRUE && $all === TRUE)
    {
      if( ! $this->discount_rule_model->set_all_payment($rule_id, 1))
			{
				$sc = FALSE;
				$this->error = "Set all payment failed";
			}
    }

    if($sc === TRUE && $all === FALSE)
    {
			if( ! $this->discount_rule_model->set_all_payment($rule_id, 0))
			{
				$sc = FALSE;
				$this->error = "Set all payment failed";
			}

			if($sc === TRUE && ! empty($paymentList))
			{
				foreach($paymentList as $pm_id)
				{
					if($sc === FALSE)
					{
						break;
					}

					$arr = array(
						"rule_id" => $rule_id,
						"payment_id" => $pm_id
					);

					if( ! $this->discount_rule_model->set_discount_rule_payment($arr))
					{
						$sc = FALSE;
						$this->error = "Insert payment rule failed";
					}
				}
			}
    }

		if($sc === TRUE)
		{
			$this->db->trans_commit();
		}
		else
		{
			$this->db->trans_rollback();
		}

		$this->_response($sc);
  }




  public function add_policy_rule()
  {
    $sc = TRUE;

    $id_policy = $this->input->post('id_policy');
  	$rule = $this->input->post('rule');

  	if(!empty($rule))
  	{
  		foreach($rule as $rule_id)
  		{
  			if($this->discount_rule_model->update_policy($rule_id, $id_policy) === FALSE)
  			{
  				$sc = FALSE;
  				$message = 'เพิ่มกฏไม่สำเร็จ';
  			}
  		}	//--- end foreach
  	}	//--- end if empty

  	echo $sc === TRUE ? 'success' : $message;
  }



  public function unlink_rule()
  {
    $sc = TRUE;
    $rule_id = $this->input->post('rule_id');
    if($this->discount_rule_model->update_policy($rule_id, NULL) === FALSE)
    {
      $sc = FALSE;
      $message = 'ลบกฏไม่สำเร็จ';
    }

    echo $sc === TRUE ? 'success' : $message;
  }


  public function delete_rule()
  {
    $sc = TRUE;
    //--- check before delete
    $id = $this->input->post('rule_id');
    $rule = $this->discount_rule_model->get($id);

    if(!empty($rule))
    {
      if(!empty($rule->id_policy))
      {
        $policy_code = $this->discount_policy_model->get_code($rule->id_policy);
        $sc = FALSE;
        $this->error = "มีการเชื่อมโยงเงื่อนไขไว้กับนโยบายเลขที่ : {$policy_code} กรุณาลบการเชื่อมโยงก่อนลบเงื่อนไขนี้";
      }
      else
      {
        if(! $this->discount_rule_model->delete_rule($id))
        {
          $sc = FALSE;
          $this->error = "ลบรายการไม่สำเร็จ";
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Not found";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function view_rule_detail($id)
  {
    $this->load->library('printer');
    $rule = $this->discount_rule_model->get($id);
    $policy = $this->discount_policy_model->get($rule->id_policy);
    $ds['rule_id'] = $id;
    $ds['rule'] = $rule;
    $ds['policy'] = $policy;
    $this->load->view('discount/policy/view_rule_detail', $ds);
  }

  public function get_new_code()
  {
    $date = date('Y-m-d');
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_RULE');
    $run_digit = getConfig('RUN_DIGIT_RULE');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->discount_rule_model->get_max_code($pre);
    if(! is_null($code))
    {
      $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
    }
    else
    {
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
    }

    return $new_code;
  }




  public function clear_filter()
  {
    $filter = array('rule_code', 'rule_name', 'rule_active','rule_type', 'rule_policy', 'rule_priority');
    clear_filter($filter);
  }
} //--- end grade
?>
