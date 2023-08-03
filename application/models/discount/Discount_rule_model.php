<?php
class Discount_rule_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


	public function count_rows(array $ds = array())
	{
		$this->db->from('discount_rule AS r')->join('discount_policy AS p', 'r.id_policy = p.id', 'left');

		if(isset($ds['code']) && $ds['code'] != "")
		{
			$this->db->like('r.code', $ds['code']);
		}

		if(isset($ds['name']) && $ds['name'] != "")
		{
			$this->db->like('r.name', $ds['name']);
		}

		if(isset($ds['type']) && $ds['type'] != "all")
		{
			$this->db->where('r.type', $ds['type']);
		}

		if(isset($ds['active']) && $ds['active'] != "all")
		{
			$this->db->where('r.active', $ds['active']);
		}

		if(isset($ds['priority']) && $ds['priority'] != "all")
		{
			$this->db->where('r.priority', $ds['priority']);
		}

		if(isset($ds['policy']) && $ds['policy'] != "")
		{
			$this->db->like('p.code', $ds['policy']);
		}

		return $this->db->count_all_results();
	}


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
		->select('r.*, p.code AS policy_code, p.name AS policy_name')
		->from('discount_rule AS r')
		->join('discount_policy AS p', 'r.id_policy = p.id', 'left');

		if(isset($ds['code']) && $ds['code'] != "")
		{
			$this->db->like('r.code', $ds['code']);
		}

		if(isset($ds['name']) && $ds['name'] != "")
		{
			$this->db->like('r.name', $ds['name']);
		}

		if(isset($ds['type']) && $ds['type'] != "all")
		{
			$this->db->where('r.type', $ds['type']);
		}

		if(isset($ds['active']) && $ds['active'] != "all")
		{
			$this->db->where('r.active', $ds['active']);
		}

		if(isset($ds['priority']) && $ds['priority'] != "all")
		{
			$this->db->where('r.priority', $ds['priority']);
		}

		if(isset($ds['policy']) && $ds['policy'] != "")
		{
			if(isset($ds['policy']) && $ds['policy'] != "")
			{
				$this->db->like('p.code', $ds['policy']);
			}
		}

		$rs = $this->db->order_by('r.code', 'DESC')->limit($perpage, $offset)->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


  public function add(array $ds = array())
  {
    $rs = $this->db->insert('discount_rule', $ds);
    if($rs)
    {
      return $this->db->insert_id();
    }

    return FALSE;
  }



  public function update($id, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('id', $id)->update('discount_rule', $ds);
    }

    return FALSE;
  }



  public function get($id)
  {
    $rs = $this->db->where('id', $id)->get('discount_rule');
    if($rs->num_rows() == 1)
    {
      return $rs->row();
    }

    return NULL;
  }


	public function get_policy_id($id)
	{
		$rs = $this->db->select('id_policy')->where('id', $id)->get('discount_rule');
		if($rs->num_rows() === 1)
		{
			return $rs->row()->id_policy;
		}

		return NULL;
	}
  /*
  |----------------------------------
  | BEGIN ใช้สำหรับแสดงรายละเอียดในหน้าพิมพ์
  |----------------------------------
  */

  public function getCustomerRuleList($id)
  {
		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }

  public function getCustomerGroupRule($id)
  {
		$rs = $this->db
		->select('r.group_code AS code, n.name')
		->from('discount_rule_customer_group AS r')
		->join('customer_group AS n', 'r.group_code = n.code', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }


  public function getCustomerTypeRule($id)
  {
		$rs = $this->db
		->select('r.type_id AS id, n.name AS name')
		->from('discount_rule_customer_type AS r')
		->join('customer_type AS n', 'r.type_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }


  // public function getCustomerRegionRule($id)
  // {
	// 	$rs = $this->db
	// 	->select('r.region_id AS id, n.name AS name')
	// 	->from('discount_rule_customer_region AS r')
	// 	->join('customer_region AS n', 'r.region_id = n.id', 'left')
	// 	->where('r.rule_id', $id)
	// 	->get();
  //
	// 	if($rs->num_rows() > 0)
	// 	{
	// 		return $rs->result();
	// 	}
  //
	// 	return NULL;
  // }


  public function getCustomerRegionRule($id)
  {
		$rs = $this->db
		->select('region_id AS id')
		->where('rule_id', $id)
		->get('discount_rule_customer_region');

		if($rs->num_rows() > 0)
		{
			$list = $rs->result();

      foreach($list as $ds)
      {
        $ds->name = $this->get_customer_sales_team_name($ds->id);
      }

      return $list;
		}

		return NULL;
  }

  public function get_customer_sales_team_name($id)
  {
    $rs = $this->db->select('SaleTeamName AS name')->where('SaleTeam', $id)->limit(1)->get('customers');

    if($rs->num_rows() === 1)
    {
      return $rs->row()->name;
    }

    return NULL;
  }


  public function getCustomerAreaRule($id)
  {
		$rs = $this->db
		->select('r.area_id AS id, n.name AS name')
		->from('discount_rule_customer_area AS r')
		->join('customer_area AS n', 'r.area_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }

  public function getCustomerGradeRule($id)
  {
		$rs = $this->db
		->select('r.grade_id AS id, n.name AS name')
		->from('discount_rule_customer_grade AS r')
		->join('customer_grade AS n', 'r.grade_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }


	public function getProductItemRule($id)
  {
		$rs = $this->db
		->select('dr.*, pd.code, pd.name')
		->from('discount_rule_product AS dr')
		->join('products AS pd', 'dr.product_id = pd.id', 'left')
		->where('rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }



  public function getProductModelRule($id)
  {
		$rs = $this->db
		->select('r.model_id AS id, n.name AS name')
		->from('discount_rule_product_model AS r')
		->join('product_model AS n', 'r.model_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }


  public function getProductTypeRule($id)
  {
		$rs = $this->db
		->select('r.type_id AS id, n.name AS name')
		->from('discount_rule_product_type AS r')
		->join('product_type AS n', 'r.type_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }


  public function getProductCategoryRule($id)
  {
		$rs = $this->db
		->select('r.category_id AS id, n.name AS name')
		->from('discount_rule_product_category AS r')
		->join('product_category AS n', 'r.category_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }


  public function getProductBrandRule($id)
  {
		$rs = $this->db
		->select('r.brand_id AS id, n.name AS name')
		->from('discount_rule_product_brand AS r')
		->join('product_brand AS n', 'r.brand_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }



  public function getChannelsRule($id)
  {
		$rs = $this->db
		->select('r.channels_id AS id, n.name AS name')
		->from('discount_rule_channels AS r')
		->join('channels AS n', 'r.channels_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }


  public function getPaymentRule($id)
  {
		$rs = $this->db
		->select('r.payment_id AS id, n.name AS name')
		->from('discount_rule_payment AS r')
		->join('payment_term AS n', 'r.payment_id = n.id', 'left')
		->where('r.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }


  /*
  |----------------------------------
  | END ใช้สำหรับแสดงรายละเอียดในหน้าพิมพ์
  |----------------------------------
  */



  /*
  |----------------------------------
  | BEGIN ใช้สำหรับหน้ากำหนดเงื่อนไข
  |----------------------------------
  */
  public function getRuleCustomerId($id)
  {
		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
  }



  public function getRuleCustomerGroup($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_group');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->group_code] = $rs->group_code;
			}
		}

		return $sc;
  }



  public function getRuleCustomerType($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_type');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->type_id] = $rs->type_id;
			}
		}

		return $sc;
  }


  public function getRuleCustomerRegion($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_region');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->region_id] = $rs->region_id;
			}
		}

		return $sc;
  }



  public function getRuleCustomerArea($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_area');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->area_id] = $rs->area_id;
			}
		}

		return $sc;
  }



  public function getRuleCustomerGrade($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_customer_grade');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->grade_id] = $rs->grade_id;
			}
		}

		return $sc;
  }


	public function getRuleFreeProduct($id)
	{
		$rs = $this->db
		->select('dr.*, pd.code, pd.name')
		->from('discount_rule_free_product AS dr')
		->join('products AS pd', 'dr.product_id = pd.id', 'left')
		->where('dr.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}



	public function getRuleProductId($id)
	{
		$rs = $this->db
		->select('dr.*, pd.code, pd.name')
		->from('discount_rule_product AS dr')
		->join('products AS pd', 'dr.product_id = pd.id', 'left')
		->where('rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return array();
	}


  public function getRuleProductModel($id)
  {
		$rs = $this->db
		->select('dr.*, pm.code , pm.name')
		->from('discount_rule_product_model AS dr')
		->join('product_model AS pm', 'dr.model_id = pm.id', 'left')
		->where('dr.rule_id', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return array();
  }




  public function getRuleProductType($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_product_type');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->type_id] = $rs->type_id;
			}
		}

		return $sc;
  }




  public function getRuleProductCategory($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_product_category');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->category_id] = $rs->category_id;
			}
		}

		return $sc;
  }


  public function getRuleProductBrand($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_product_brand');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->brand_id] = $rs->brand_id;
			}
		}

		return $sc;
  }




  public function getRuleChannels($id)
  {
		$sc = array();

	$rs = $this->db->where('rule_id', $id)->get('discount_rule_channels');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->channels_id] = $rs->channels_id;
			}
		}

		return $sc;
  }


  public function getRulePayment($id)
  {
		$sc = array();

		$rs = $this->db->where('rule_id', $id)->get('discount_rule_payment');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() AS $rs)
			{
				$sc[$rs->payment_id] = $rs->payment_id;
			}
		}

		return $sc;
  }



  public function set_all_customer($id, $value)
  {
    /*
    1. set all customer = 1
    2. delete customer rule
    3. delete customer_group rule;
    4. delete customer_type rule;
    5. delete customer_region rule;
    6. delete customer_area rule;
    7. delete customer_grade rule;
    */


    if($value === 1)
    {
      //--- start transection
      $this->db->trans_start();

      //--- 1
			$this->db->set('all_customer', 1)->where('id', $id)->update('discount_rule');
			$this->db->where('rule_id', $id)->delete('discount_rule_customer');
			$this->db->where('rule_id', $id)->delete('discount_rule_customer_group');
			$this->db->where('rule_id', $id)->delete('discount_rule_customer_type');
			$this->db->where('rule_id', $id)->delete('discount_rule_customer_region');
			$this->db->where('rule_id', $id)->delete('discount_rule_customer_area');
			$this->db->where('rule_id', $id)->delete('discount_rule_customer_grade');

      //--- end transection
      $this->db->trans_complete();

      return $this->db->trans_status();
    }
    else
    {
			return $this->db->set('all_customer', 0)->where('id', $id)->update('discount_rule');
    }
  }


	public function drop_rule_customer($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer');
	}

	public function drop_rule_customer_group($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_group');
	}

	public function drop_rule_customer_type($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_type');
	}

	public function drop_rule_customer_region($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_region');
	}

	public function drop_rule_customer_area($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_area');
	}

	public function drop_rule_customer_grade($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_customer_grade');
	}



	private function get_customer($id)
	{
		$rs = $this->db->where('id', $id)->get('customers');

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}



  public function set_customer_list($id, $cust_list)
  {
		$sc  = TRUE;
		$result = new stdClass();
    $result->status = TRUE;
    $result->message = 'success';

    //---- start transection
    $this->db->trans_begin();

		if( ! $this->drop_rule_customer($id))
		{
			$sc = FALSE;
			$error = "Drop customer list failed";
		}
		else
		{
			if( ! empty($cust_list))
			{
				foreach($cust_list as $customer_id)
				{
					if($sc === TRUE)
					{
						$customer = $this->get_customer($customer_id);

						if( ! empty($customer))
						{
							$arr = array(
								"rule_id" => $id,
								"customer_id" => $customer_id,
								"customer_code" => $customer->CardCode,
								"customer_name" => $customer->CardName
							);

							if( ! $this->db->insert("discount_rule_customer", $arr))
							{
								$sc = FALSE;
								$error = "Insert customer list failed {$customer_id}";
							}
						}
						else
						{
							$sc = FALSE;
							$error = "Customer id ({$customer_id}) not exists";
						}
					}
				}
			}
		}

		if($sc === TRUE)
		{
			if( ! $this->drop_rule_customer_group($id))
			{
				$sc = FALSE;
				$error = "Drop customer group rule failed";
			}
		}

		if($sc === TRUE)
		{
			if( ! $this->drop_rule_customer_type($id))
			{
				$sc = FALSE;
				$error = "Drop customer type rule failed";
			}
		}


		if($sc === TRUE)
		{
			if( ! $this->drop_rule_customer_region($id))
			{
				$sc = FALSE;
				$error = "Drop customer region rule failed";
			}
		}


		if($sc === TRUE)
		{
			if( ! $this->drop_rule_customer_area($id))
			{
				$sc = FALSE;
				$error = "Drop customer area rule failed";
			}
		}

		if($sc === TRUE)
		{
			if( ! $this->drop_rule_customer_grade($id))
			{
				$sc = FALSE;
				$error = "Drop customer grade rule failed";
			}
		}

    if($sc === TRUE)
		{
			$this->db->trans_commit();
		}
		else
		{
			$this->trans_rollback();
			$result->status = FALSE;
			$result->message = $error;
		}

		return $result;

  }



  public function set_customer_attr($rule_id, $group, $type, $region, $area, $grade)
  {
		$result = new stdClass();
    $result->status = TRUE;
    $result->message = 'success';

    //--- start transection
    $this->db->trans_start();

    //--- 1.
    $this->drop_rule_customer($rule_id);


    //--- 2
    $this->drop_rule_customer_group($rule_id);

    if( ! empty($group))
    {
      foreach($group as $code)
      {
				$arr = array(
					"rule_id" => $rule_id,
					"group_code" => $code
				);

        $this->db->insert('discount_rule_customer_group', $arr);
      }
    }

    //--- 3
    $this->drop_rule_customer_type($rule_id);

    if( ! empty($type))
    {
      foreach($type as $id)
      {
				$arr = array(
					"rule_id" => $rule_id,
					"type_id" => $id
				);

				$this->db->insert("discount_rule_customer_type", $arr);
      }
    }


    //--- 4
		$this->drop_rule_customer_region($rule_id);

    if( ! empty($region))
    {
      foreach($region as $id)
      {
				$arr = array(
					"rule_id" => $rule_id,
					"region_id" => $id
				);

				$this->db->insert("discount_rule_customer_region", $arr);
      }
    }

    //--- 5
		$this->drop_rule_customer_area($rule_id);

    if( ! empty($area))
    {
      foreach($area as $id)
      {
				$arr = array(
					"rule_id" => $rule_id,
					"area_id" => $id
				);

				$this->db->insert("discount_rule_customer_area", $arr);
      }
    }


		//--- 6
    $this->drop_rule_customer_grade($rule_id);

    if( ! empty($grade))
    {
      foreach($grade as $id)
      {
				$arr = array(
					"rule_id" => $rule_id,
					"grade_id" => $id
				);

				$this->db->insert("discount_rule_customer_grade", $arr);
      }
    }


    //--- end transection
    $this->db->trans_complete();

    if($this->db->trans_status() === FALSE)
    {
      $result->status = FALSE;
      $result->message = 'กำหนดเงื่อนไขคุณลักษณะลูกค้าไม่สำเร็จ';
    }

    return $result;
  }




	public function drop_free_product($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_free_product');
	}


  public function set_all_product($id, $value = 1)
  {
		return $this->db->set('all_product', $value)->where('id', $id)->update('discount_rule');
  }


	public function drop_rule_product($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product');
	}


	public function drop_rule_product_model($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_model');
	}


	public function drop_rule_product_category($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_category');
	}


	public function drop_rule_product_type($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_type');
	}


	public function drop_rule_product_brand($rule_id)
	{
		return $this->db->where('rule_id', $rule_id)->delete('discount_rule_product_brand');
	}


	public function drop_rule_channels($id)
	{
		return $this->db->where('rule_id', $id)->delete('discount_rule_channels');
	}


	public function drop_rule_payment($id)
	{
		return $this->db->where('rule_id', $id)->delete('discount_rule_payment');
	}


	public function set_discount_rule_free_product(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert("discount_rule_free_product", $ds);
		}

		return FALSE;
	}


	public function set_discount_rule_product(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert("discount_rule_product", $ds);
		}

		return FALSE;
	}


  public function set_discount_rule_product_model(array $ds = array())
  {
		if( ! empty($ds))
		{
			return $this->db->insert("discount_rule_product_model", $ds);
		}

		return FALSE;
  }


	public function set_discount_rule_product_category(array $ds = array())
  {
		if( ! empty($ds))
		{
			return $this->db->insert("discount_rule_product_category", $ds);
		}

		return FALSE;
  }


	public function set_discount_rule_product_type(array $ds = array())
  {
		if( ! empty($ds))
		{
			return $this->db->insert("discount_rule_product_type", $ds);
		}

		return FALSE;
  }


	public function set_discount_rule_product_brand(array $ds = array())
  {
		if( ! empty($ds))
		{
			return $this->db->insert("discount_rule_product_brand", $ds);
		}

		return FALSE;
  }



  public function set_all_channels($id, $value = 1)
  {
		return $this->db->set('all_channels', $value)->where('id', $id)->update('discount_rule');
  }



  public function set_discount_rule_channels(array $ds = array())
  {
    if( ! empty($ds))
		{
			return $this->db->insert("discount_rule_channels", $ds);
		}

		return FALSE;
  }



  public function set_all_payment($id, $value = 1)
  {
		return $this->db->set('all_payment', $value)->where('id', $id)->update('discount_rule');
  }


	public function set_discount_rule_payment(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert("discount_rule_payment", $ds);
		}

		return FALSE;
	}



  /*
  |----------------------------------
  | END ใช้สำหรับหน้ากำหนดเงื่อนไข
  |----------------------------------
  */


  public function update_policy($rule_id, $id_policy)
  {
    return $this->db->set('id_policy', $id_policy)->where('id', $rule_id)->update('discount_rule');
  }









  public function get_policy_rules($id_policy)
  {
    $rs = $this->db->where('id_policy', $id_policy)->get('discount_rule');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return array();
  }




  public function get_active_rule()
  {
    $rs = $this->db->where('active', 1)->where('id_policy IS NULL')->get('discount_rule');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return array();
  }



  public function get_max_code($code)
  {
    $qr = "SELECT MAX(code) AS code FROM discount_rule WHERE code LIKE '".$code."%' ORDER BY code DESC";
    $rs = $this->db->query($qr);
    return $rs->row()->code;
  }



  public function search($txt)
  {
    $rs = $this->db->select('id')
    ->like('code', $txt)
    ->like('name', $txt)
    ->get('discount_rule');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return array();
  }


  public function delete_rule($id)
  {
    //--- start transection
    $this->db->trans_start();

    //--- 1.
		$this->db->where('rule_id', $id)->delete('discount_rule_product');

		$this->db->where('rule_id', $id)->delete('discount_rule_product_model');

		$this->db->where('rule_id', $id)->delete('discount_rule_product_brand');

		$this->db->where('rule_id', $id)->delete('discount_rule_product_category');

		$this->db->where('rule_id', $id)->delete('discount_rule_product_type');

    $this->db->where('rule_id', $id)->delete('discount_rule_customer');

    $this->db->where('rule_id', $id)->delete('discount_rule_customer_group');

    $this->db->where('rule_id', $id)->delete('discount_rule_customer_region');

		$this->db->where('rule_id', $id)->delete('discount_rule_customer_area');

		$this->db->where('rule_id', $id)->delete('discount_rule_customer_grade');

		$this->db->where('rule_id', $id)->delete('discount_rule_customer_type');

		$this->db->where('rule_id', $id)->delete('discount_rule_channels');

		$this->db->where('rule_id', $id)->delete('discount_rule_payment');

    $this->db->where('id', $id)->delete('discount_rule');

    //--- end transection
    $this->db->trans_complete();

    return $this->db->trans_status();
  }

} //--- end grade

 ?>
