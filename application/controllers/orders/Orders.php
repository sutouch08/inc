<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends PS_Controller
{
  public $menu_code = 'SOODSO';
	public $menu_group_code = 'SO';
  public $menu_sub_group_code = 'ORDER';
	public $title = 'ORDER';
	public $segment = 4;
	public $not_ap = array();
	public $can_approve = TRUE;
	public $readOnly = FALSE;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'orders/orders';
		$this->load->model('orders/orders_model');
		$this->load->model('masters/customers_model');
		$this->load->model('masters/customer_address_model');
		$this->load->model('masters/products_model');
		$this->load->model('masters/payment_term_model');
		$this->load->model('masters/channels_model');
		$this->load->model('orders/discount_model');
		$this->load->model('masters/warehouse_model');
		$this->load->helper('order');
		$this->load->helper('channels');
		$this->load->helper('customer');
		$this->load->helper('product_images');
		$this->load->helper('discount');
		$this->load->helper('warehouse');

		$this->readOnly = getConfig('CLOSE_SYSTEM') ==  2 ? TRUE : FALSE;
  }


	public function test()
	{
		$itemCode = 'FG-BEC0027';
		$customer_code = 'CC00002';
		$channels = 2;
		$payment = 27;
		$date = '2022-08-05';
		$qty = 1;
		$amount = 1000;

		$rs = $this->discount_model->get_free_item_rule($itemCode, $customer_code, $payment, $channels, $date, $qty, $amount);

		print_r($rs);
	}



  public function get_credit_balance()
	{
		$CardCode = trim($this->input->get('CardCode'));
		$orderCode = trim($this->input->get('orderCode'));
		$this->load->library('order_api');
		$balance = $this->order_api->getCreditBalance($CardCode);
		$used = 0; //$this->orders_model->get_credit_used($CardCode, $orderCode);

    if($balance === FALSE)
    {
      echo "API Request Timeout";
    }
    else
    {
      $available = $balance - $used;

      $arr = array(
        'status' => 'success',
        'balance' => $available < 0 ? 0 : $available,
        'used' => $used
      );

      echo json_encode($arr);
    }
	}


  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'order_code', ''),
			'customer' => get_filter('customer', 'order_customer', ''),
			'sqNo' => get_filter('sqNo', 'sqNo', ''),
			'soNo' => get_filter('soNo', 'soNo', ''),
			'role' => get_filter('role', 'order_role', 'all'),
			'sale_id' => get_filter('sale_id', 'order_sale_id', 'all'),
			'channels' => get_filter('channels', 'order_channels', 'all'),
			'payment' => get_filter('payment', 'order_payment', 'all'),
			'approval' => get_filter('approval', 'order_approval', 'all'),
			'status' => get_filter('status', 'order_status', 'all'),
			'from_date' => get_filter('from_date', 'order_from_date', ''),
			'to_date' => get_filter('to_date', 'order_to_date', ''),
			'onlyMe' => get_filter('onlyMe', 'onlyMe', 0),
			'user_id' => get_filter('user_id', 'order_user_id', 'all'),
			'chk_sqNo' => get_filter('chk_sqNo', 'chk_sqNo', 0),
			'chk_channels' => get_filter('chk_channels', 'chk_channels', 0),
			'chk_payment' => get_filter('chk_payment', 'chk_payment', 0),
			'chk_ship' => get_filter('chk_ship', 'chk_ship', 0),
		);

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();

		$rows = $this->orders_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
    $filter['data'] = $this->orders_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$this->pagination->initialize($init);
    $this->load->view('sales_order/sales_order_list', $filter);
  }




  public function add_new()
  {
		$this->load->model('masters/sales_person_model');
		$ds = array(
			'sale_name' => $this->sales_person_model->get_name($this->_user->sale_id),
			'default_channels' => $this->channels_model->get_default()
		);

    $this->load->view('sales_order/sales_order_add', $ds);
  }


	public function add()
	{
		$sc = TRUE;
		if($this->pm->can_add)
		{
			$json = file_get_contents('php://input');

			$data = json_decode($json);

			if(! empty($data))
			{
				$hd = $data->header;
				$details = $data->details;

				$docDate = db_date($hd->DocDate, FALSE);
				$customer = $this->customers_model->get($hd->CardCode);
				$code = $this->get_new_code($docDate);

				if( ! empty($customer))
				{
					$arr = array(
						'code' => $code,
						'role' => 'S',
						'CardCode' => $customer->CardCode,
						'CardName' => $customer->CardName,
						'PriceList' => get_null($customer->ListNum),
						'SlpCode' => empty($hd->SlpCode) ? $customer->SlpCode : $hd->SlpCode,
						'Channels' => $hd->Channels,
						'Payment' => $hd->Payment,
						'DocCur' => getConfig('DEFAULT_CURRENCY'),
						'DocRate' => 1,
						'DocTotal' => $hd->docTotal,
						'DocDate' => $docDate,
						'DocDueDate' => db_date($hd->DocDueDate, FALSE),
						'TextDate' => db_date($hd->TextDate, FALSE),
						'PayToCode' => $hd->PayToCode,
						'ShipToCode' => $hd->ShipToCode,
						'Address' => $hd->BillTo,
						'Address2' => $hd->ShipTo,
						'DiscPrcnt' => $hd->discPrcnt,
						'DiscAmount' => $hd->disAmount,
						'VatSum' => $hd->tax,
						'RoundDif' => $hd->roundDif,
						'sale_team' => $hd->sale_team,
						'user_id' => $this->_user->id,
						'uname' => $this->_user->uname,
						'Comments' => get_null($hd->comments),
						'must_approve' => $hd->mustApprove,
						'disc_diff' => $hd->maxDiff,
						'VatGroup' => $hd->VatGroup,
						'VatRate' => $hd->VatRate,
						'Status' => $hd->isDraft == 1 ? -1 : ($hd->mustApprove == 1 ? 0 : 1),
						'Approved' => $hd->isDraft == 1 ? 'P' : ($hd->mustApprove == 1 ? 'P' : 'S'),
						'OwnerCode' => $hd->OwnerCode,
						'dimCode1' => $hd->dimCode1,
						'dimCode2' => $hd->dimCode2,
						'dimCode3' => $hd->dimCode3,
						'dimCode4' => $hd->dimCode4,
						'dimCode5' => $hd->dimCode5
					);

					$this->db->trans_begin();

					if(! $this->orders_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "Create Order failed";
					}
					else
					{
						if( ! empty($details))
						{
							foreach($details as $rs)
							{
								if($sc === FALSE)
								{
									break;
								}

								$pd = $this->products_model->get($rs->ItemCode);

								if( ! empty($pd))
								{
									$disc = parse_discount_text($rs->discLabel, $rs->Price);
									$sysdisc = parse_discount_text($rs->sysDiscLabel, $rs->Price);

									$arr = array(
										'order_code' => $code,
										'LineNum' => $rs->LineNum,
										'ItemCode' => $rs->ItemCode,
										'ItemName' => $rs->Description,
										'Qty' => $rs->Quantity,
                    'OpenQty' => $rs->Quantity,
										'UomCode' => $pd->uom_code,
										'UomEntry' => $pd->uom_id,
										'StdPrice' => $rs->StdPrice,
										'Price' => $rs->Price,
										'SellPrice' => $rs->SellPrice,
										'sysSellPrice' => $rs->sysSellPrice,
										'disc1' => $disc['discount1'],
										'disc2' => $disc['discount2'],
										'disc3' => $disc['discount3'],
										'disc4' => $disc['discount4'],
										'disc5' => $disc['discount5'],
										'sysDisc1' => $sysdisc['discount1'],
										'sysDisc2' => $sysdisc['discount2'],
										'sysDisc3' => $sysdisc['discount3'],
										'sysDisc4' => $sysdisc['discount4'],
										'sysDisc5' => $sysdisc['discount5'],
										'discLabel' => $rs->discLabel,
										'sysDiscLabel' => $rs->sysDiscLabel,
										'discDiff' => $rs->discDiff,
										'DiscPrcnt' => $rs->DiscPrcnt, //discountAmountToPercent($rs->discAmount, 1, $rs->Price),
										'discAmount' => $rs->discAmount,
										'totalDiscAmount' => $rs->totalDiscAmount,
										'VatGroup' => $pd->vat_group,
										'VatRate' => $pd->vat_rate,
										'VatAmount' => $rs->VatAmount,
										'totalVatAmount' => $rs->totalVatAmount,
										'LineTotal' => $rs->LineTotal,
										'policy_id' => $rs->policy_id,
										'rule_id' => $rs->rule_id,
										'WhsCode' => $rs->WhsCode,
										'QuotaNo' => $rs->QuotaNo,
										'uid' => $rs->uid,
										'parent_uid' => $rs->parent_uid,
										'is_free' => $rs->is_free,
										'discType' => $rs->discType,
										'channels_id' => $hd->Channels,
										'payment_id' => $hd->Payment,
										'product_id' => $pd->id,
										'product_model_id' => $pd->model_id,
										'product_category_id' => $pd->category_id,
										'product_type_id' => $pd->type_id,
										'product_brand_id' => $pd->brand_id,
										'customer_id' => $customer->id,
										'customer_group_id' => $customer->GroupCode,
										'customer_type_id' => $customer->TypeCode,
										'customer_region_id' => $customer->SaleTeam,
										'customer_area_id' => $customer->AreaCode,
										'customer_grade_id' => $customer->GradeCode,
										'user_id' => $this->_user->id,
										'uname' => $this->_user->uname,
										'sale_team' => $rs->sale_team,
										'count_stock' => $rs->count_stock,
										'allow_change_discount' => $rs->allow_change_discount
									);

									if(! $this->orders_model->add_detail($arr))
									{
										$sc = FALSE;
										$this->error = "Insert detail failed";
									}
								}
							}
						}
					}

					if($sc === TRUE)
					{
						$this->db->trans_commit();

						$arr = array(
							'code' => $code,
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname,
							'action' => 'add'
						);

						$this->orders_model->add_logs($arr);

						if($hd->isDraft == 0)
						{
							if($hd->mustApprove == 0)
							{
								$rs = $this->do_export($code);

								if(! $rs)
								{
									$sc = FALSE;
								}
							}
						}
					}
					else
					{
						$this->db->trans_rollback();
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Invalid customer";
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

		echo $sc === TRUE ? json_encode(array('status' => 'success', 'code' => $code)) : $this->error;
	}



  public function edit($code)
  {
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/customer_address_model');
		$this->load->model('masters/quota_model');

		if($this->pm->can_edit OR $this->pm->can_add)
		{
			$totalAmount = 0;
			$totalVat = 0;

			$order = $this->orders_model->get($code);

			if( ! empty($order))
			{
				$details = $this->orders_model->get_details($order->code);

				if(!empty($details))
				{
					foreach($details as $rs)
					{
						$totalAmount += $rs->LineTotal;
						$stock = $this->getStock($rs->ItemCode, $rs->WhsCode, $rs->QuotaNo);
						$rs->instock = !empty($stock) ? $stock['OnHand'] : 0;
						$rs->team = !empty($stock) ? $stock['QuotaQty'] : 0;
						$rs->commit = !empty($stock) ? ($stock['Committed'] > 0 ? $stock['Committed'] - $rs->Qty : 0) : 0;
            $available = $rs->team - $rs->commit;
						$rs->available = $available > 0 ? $available : 0;
						$rs->image = get_image_path($rs->product_id, 'mini');
					}
				}

				$ds = array(
					'order' => $order,
					'details' => $details,
					'totalAmount' => $totalAmount,
					'whsList' => $this->warehouse_model->get_listed(),
					'quotaList' => $this->quota_model->get_all_listed(),
					'logs' => $this->orders_model->get_logs($code)
				);

				$this->load->view('sales_order/sales_order_edit', $ds);
			}
			else
			{
				$this->error_page();
			}
		}
		else
		{
			$this->permission_deny();
		}

  }



	public function update()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$json = file_get_contents('php://input');

			$data = json_decode($json);      

			if(! empty($data))
			{
				$hd = $data->header;
				$details = $data->details;

				if(!empty($hd->code))
				{
					$docDate = db_date($hd->DocDate, FALSE);
					$customer = $this->customers_model->get($hd->CardCode);
					$code = $hd->code;

					$order = $this->orders_model->get($code);

					if(! empty($order))
					{
						if(($order->Status == -1 OR $order->Status == 0 OR $order->Status == 3))
						{
							if($order->Approved != 'A' )
							{
								if( ! empty($customer))
								{
									$arr = array(
										'CardCode' => $customer->CardCode,
										'CardName' => $customer->CardName,
										'PriceList' => get_null($customer->ListNum),
										'SlpCode' => empty($hd->SlpCode) ? $customer->SlpCode : $hd->SlpCode,
										'Channels' => $hd->Channels,
										'Payment' => $hd->Payment,
										'DocCur' => getConfig('DEFAULT_CURRENCY'),
										'DocRate' => 1,
										'DocDate' => $docDate,
										'DocTotal' => $hd->docTotal,
										'DocDueDate' => db_date($hd->DocDueDate, FALSE),
										'TextDate' => db_date($hd->TextDate, FALSE),
										'PayToCode' => $hd->PayToCode,
										'ShipToCode' => $hd->ShipToCode,
										'Address' => $hd->BillTo,
										'Address2' => $hd->ShipTo,
										'DiscPrcnt' => $hd->discPrcnt,
										'DiscAmount' => $hd->disAmount,
										'VatSum' => $hd->tax,
										'RoundDif' => $hd->roundDif,
										'sale_team' => $hd->sale_team,
										'user_id' => $hd->user_id,
										'uname' => $hd->uname,
										'Comments' => get_null($hd->comments),
										'must_approve' => $hd->mustApprove,
										'disc_diff' => $hd->maxDiff,
										'VatGroup' => $hd->VatGroup,
										'VatRate' => $hd->VatRate,
										'Status' => ($hd->isDraft == 1 ? -1 : ($hd->mustApprove == 1 ? 0 : 1)),
										'Approved' => ($hd->isDraft == 1 ? 'P' : ($hd->mustApprove == 1 ? 'P' : 'S')),
										'upd_user_id' => $this->_user->id,
										'OwnerCode' => $hd->OwnerCode,
										'dimCode1' => $hd->dimCode1,
										'dimCode2' => $hd->dimCode2,
										'dimCode3' => $hd->dimCode3,
										'dimCode4' => $hd->dimCode4,
										'dimCode5' => $hd->dimCode5
									);

									$this->db->trans_begin();

									if(! $this->orders_model->update($code, $arr))
									{
										$sc = FALSE;
										$this->error = "Update Order failed";
									}
									else
									{
										if($this->orders_model->drop_details($code))
										{
											if( ! empty($details))
											{
												foreach($details as $rs)
												{
													if($sc === FALSE)
													{
														break;
													}

													$pd = $this->products_model->get($rs->ItemCode);

													if( ! empty($pd))
													{
														$disc = parse_discount_text($rs->discLabel, $rs->Price);
														$sysdisc = parse_discount_text($rs->sysDiscLabel, $rs->Price);

														$arr = array(
															'order_code' => $code,
															'LineNum' => $rs->LineNum,
															'ItemCode' => $rs->ItemCode,
															'ItemName' => $rs->Description,
															'Qty' => $rs->Quantity,
                              'OpenQty' => $rs->Quantity,
															'UomCode' => $pd->uom_code,
															'UomEntry' => $pd->uom_id,
															'StdPrice' => $rs->StdPrice,
															'Price' => $rs->Price,
															'SellPrice' => $rs->SellPrice,
															'sysSellPrice' => $rs->sysSellPrice,
															'disc1' => $disc['discount1'],
															'disc2' => $disc['discount2'],
															'disc3' => $disc['discount3'],
															'disc4' => $disc['discount4'],
															'disc5' => $disc['discount5'],
															'sysDisc1' => $sysdisc['discount1'],
															'sysDisc2' => $sysdisc['discount2'],
															'sysDisc3' => $sysdisc['discount3'],
															'sysDisc4' => $sysdisc['discount4'],
															'sysDisc5' => $sysdisc['discount5'],
															'discLabel' => $rs->discLabel,
															'sysDiscLabel' => $rs->sysDiscLabel,
															'discDiff' => $rs->discDiff,
															'DiscPrcnt' => discountAmountToPercent($rs->discAmount, 1, $rs->Price),
															'discAmount' => $rs->discAmount,
															'totalDiscAmount' => $rs->totalDiscAmount,
															'VatGroup' => $pd->vat_group,
															'VatRate' => $pd->vat_rate,
															'VatAmount' => $rs->VatAmount,
															'totalVatAmount' => $rs->totalVatAmount,
															'LineTotal' => $rs->LineTotal,
															'policy_id' => $rs->policy_id,
															'rule_id' => $rs->rule_id,
															'WhsCode' => $rs->WhsCode,
															'QuotaNo' => $rs->QuotaNo,
															'uid' => $rs->uid,
															'parent_uid' => $rs->parent_uid,
															'is_free' => $rs->is_free,
															'discType' => $rs->discType,
															'channels_id' => $hd->Channels,
															'payment_id' => $hd->Payment,
															'product_id' => $pd->id,
															'product_model_id' => $pd->model_id,
															'product_category_id' => $pd->category_id,
															'product_type_id' => $pd->type_id,
															'product_brand_id' => $pd->brand_id,
															'customer_id' => $customer->id,
															'customer_group_id' => $customer->GroupCode,
															'customer_type_id' => $customer->TypeCode,
															'customer_region_id' => $customer->SaleTeam,
															'customer_area_id' => $customer->AreaCode,
															'customer_grade_id' => $customer->GradeCode,
															'user_id' => $this->_user->id,
															'uname' => $this->_user->uname,
															'sale_team' => $rs->sale_team,
															'count_stock' => $rs->count_stock,
															'allow_change_discount' => $rs->allow_change_discount
															);

														if(! $this->orders_model->add_detail($arr))
														{
															$sc = FALSE;
															$this->error = "Insert detail failed";
														}
													}
												}//--- end foreach
											} //--- end if ! empty($details)
										}
										else
										{
											$sc = FALSE;
											$this->error = "Drop current order details failed";
										}
									}

									if($sc === TRUE)
									{
										$this->db->trans_commit();
										$arr = array(
											'code' => $code,
											'user_id' => $this->_user->id,
											'uname' => $this->_user->uname,
											'action' => 'edit'
										);

										$this->orders_model->add_logs($arr);

										if($hd->isDraft == 0)
										{
											if($hd->mustApprove == 0)
											{
												$this->load->library('order_api');
												$rs = $this->order_api->exportOrder($code);

												if(! $rs)
												{
													$sc = FALSE;
													$this->error = $this->order_api->error;
												}
											}
										}
									}
									else
									{
										$this->db->trans_rollback();
									}
								}
								else
								{
									$sc = FALSE;
									$this->error = "Invalid customer";
								}
							}
							else
							{
								$sc = FALSE;
								$this->error = "ไม่สามารถบันทึกเอกสารได้เนื่องจากเอกสารถูกอนุมัติไปแล้ว";
							}
						}
						else
						{
							$sc = FALSE;
							switch($order->Status)
							{
								case 2 :
									$this->error = "ไม่สามารถบันทึกเอกสารได้เนื่องจากเอกสารถูกยกเลิกแล้ว";
									break;
								case 1 :
									$this->error = "ไม่สามารถบันทึกออเดอร์ได้ เนื่องจากเอกสารเข้า SAP แล้ว";
									break;
								default :
									$this->error = "ไม่สามารถบันทึกออเดอร์ได้ เนื่องจากสถานะเอกสารไม่ถูกต้อง";
									break;
							}
						}
					}
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

		echo $sc === TRUE ? json_encode(array('status' => 'success', 'code' => $code)) : $this->error;
	}



	public function approve()
	{
		$sc = TRUE;
		$code = trim($this->input->post('code'));

		if(!empty($code))
		{
			$rs = $this->do_approve($code);

			if($rs === TRUE)
			{
				$this->load->library('order_api');
				$export = $this->order_api->exportOrder($code);

				if($export == FALSE)
				{
					$sc = FALSE;
					$this->error = $this->order_api->error;
				}
			}
			else
			{
				$sc = FALSE;
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}




	public function do_approve($code)
	{
		$sc = TRUE;
		$this->load->model('users/approver_model');
		$doc = $this->orders_model->get($code);

		if(!empty($doc))
		{
			//--- ตัองยังไม่ได้อนุมัติ และ ยังไม่เข้า SAP และ ยังไม่มีเลขที่เอกสารใน SAP
			if($doc->Approved === 'P' && $doc->Status == 0 && $doc->DocNum === NULL)
			{
				//--- ตรวจสอบสิทธิ์ในการอนุาัติ
				$approver_id = $this->approver_model->is_approver($this->_user->id, $doc->sale_team);

				if(! empty($approver_id) OR $this->_SuperAdmin)
				{
					$can_approve = TRUE;

					$details = $this->orders_model->get_details($code);

					if(!empty($details))
					{
						$brand = array();

						$brands = $this->approver_model->get_approver_brand($approver_id);

						if(! empty($brands))
						{
							foreach($brands as $bs)
							{
								$brand[$bs->id_brand] = $bs->max_disc;
							}
						}
						else
						{
							$sc = FALSE;
							$this->error = "You don't have permission to perform this operation.5";
						}


						if($sc === TRUE)
						{
							foreach($details as $rs)
							{
								if($sc === FALSE)
								{
									break;
								}

								if($rs->discDiff > 0)
								{
									if(isset($brand[$rs->product_brand_id]))
									{
										if($rs->discDiff > $brand[$rs->product_brand_id])
										{
											$can_approve = FALSE;
										}
									}
									else
									{
										$sc = FALSE;
										$this->error = "You don't have permission to perform this operation.({$rs->brand_name})";
									}
								}
							}
						}
					}

					if($sc === TRUE)
					{
						if($this->_SuperAdmin OR $can_approve === TRUE)
						{
							$arr = array(
								'Approved' => 'A',
								'Approver' => $this->_user->uname
							);

							if(! $this->orders_model->update($code, $arr))
							{
								$sc = FALSE;
								$this->error = "Approve failed";
							}
							else
							{
								$arr = array(
									'code' => $code,
									'user_id' => $this->_user->id,
									'uname' => $this->_user->uname,
									'action' => 'approve'
								);

								$this->orders_model->add_logs($arr);
							}
						}
						else
						{
							$sc = FALSE;
							$this->error = "You don't have permission to perform this operation.3";
						}
					}

				}
				else
				{
					$sc = FALSE;
					$this->error = "You don't have permission to perform this operation.1";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid Document Status";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Document not found";
		}

		return $sc;
	}



	public function reject()
	{
		$sc = TRUE;
		$code = trim($this->input->post('code'));

		if(!empty($code))
		{
			if( ! $this->do_reject($code))
			{
				$sc = FALSE;
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}




	public function do_reject($code)
	{
		$sc = TRUE;

		$this->load->model('users/approver_model');
		$order = $this->orders_model->get($code);

		if(! empty($order))
		{
			if($order->Status == 0 && $order->Approved == 'P')
			{
				$approve = $this->approver_model->get_approve_right($this->_user->id, $order->sale_team);

				if( ! empty($approve))
				{
					if($order->disc_diff <= $approve->max_disc)
					{
						$arr = array(
							'Approved' => 'R',
							'Approver' => $this->_user->uname
						);

						if( ! $this->orders_model->update($code, $arr))
						{
							$sc = FALSE;
							$this->error = "Reject failed";
						}
						else
						{
							$arr = array(
								'code' => $code,
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'action' => 'reject'
							);

							$this->orders_model->add_logs($arr);
						}
					}
				}
				else
				{
					$sc = FALSE;
					set_error('permission');
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid document status";
			}

		}
		else
		{
			$sc = FALSE;
			$this->error = "Document not found";
		}


		return $sc;
	}



	public function view_detail($code)
	{
		$this->load->model('users/approver_model');
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/employee_model');
		$this->load->model('masters/cost_center_model');

		$totalAmount = 0;
		$totalVat = 0;

		$order = $this->orders_model->get_header($code);
		$approver_id = NULL;
		$brand = NULL;

		if( ! empty($order))
		{
			$details = $this->orders_model->get_details($order->code);

			if(!empty($details))
			{
				foreach($details as $rs)
				{
          $totalAmount += $rs->LineTotal;
					$rs->image = get_image_path($rs->product_id, 'mini');
					$rs->ruleCode = $this->discount_model->getRuleCode($rs->rule_id);
				}
			}

			if($order->must_approve)
			{
				$approver_id = $this->approver_model->is_approver($this->_user->id, $order->sale_team); //-- return id if approver of team  return false if not

				if($approver_id)
				{
					$brands = $this->approver_model->get_approver_brand($approver_id);

					if(! empty($brands))
					{
						$brand = array();

						foreach($brands as $bs)
						{
							$brand[$bs->id_brand] = $bs->max_disc;
						}
					}
				}
			}

			$ds = array(
				'order' => $order,
				'details' => $details,
				'totalAmount' => $totalAmount,
				'sale_name' => $this->sales_person_model->get_name($order->SlpCode),
				'owner' => $this->employee_model->get_name($order->OwnerCode),
				'dimCode1' => $this->cost_center_model->get_name($order->dimCode1),
				'dimCode2' => $this->cost_center_model->get_name($order->dimCode2),
				'dimCode3' => $this->cost_center_model->get_name($order->dimCode3),
				'dimCode4' => $this->cost_center_model->get_name($order->dimCode4),
				'dimCode5' => $this->cost_center_model->get_name($order->dimCode5),
				'logs' => $this->orders_model->get_logs($code),
				'is_approver' => $approver_id,
				'brand' => $brand
			);

			$this->load->view('sales_order/sales_order_view', $ds);
		}
		else
		{
			$this->page_error();
		}
	}



	public function duplicate_sales_order()
	{
		$sc = TRUE;

		if($this->pm->can_add)
		{
			$original = $this->input->post('code');

			$hd = $this->orders_model->get($original);

			if(! empty($hd))
			{
				$details = $this->orders_model->get_details($original);

				$docDate = db_date($hd->DocDate, FALSE);
				$code = $this->get_new_code($docDate);

				$arr = array(
					'code' => $code,
					'role' => 'S',
					'CardCode' => $hd->CardCode,
					'CardName' => $hd->CardName,
					'PriceList' => $hd->PriceList,
					'SlpCode' => $hd->SlpCode,
					'Channels' => $hd->Channels,
					'Payment' => $hd->Payment,
					'DocCur' => $hd->DocCur,
					'DocRate' => $hd->DocRate,
					'DocTotal' => $hd->DocTotal,
					'DocDate' => $hd->DocDate,
					'DocDueDate' => $hd->DocDueDate,
					'TextDate' => $hd->TextDate,
					'PayToCode' => $hd->PayToCode,
					'ShipToCode' => $hd->ShipToCode,
					'Address' => $hd->Address,
					'Address2' => $hd->Address2,
					'DiscPrcnt' => $hd->DiscPrcnt,
					'DiscAmount' => $hd->DiscAmount,
					'VatSum' => $hd->VatSum,
					'RoundDif' => $hd->RoundDif,
					'sale_team' => $hd->sale_team,
					'user_id' => $this->_user->id,
					'uname' => $this->_user->uname,
					'Comments' => $hd->Comments,
					'must_approve' => $hd->must_approve,
					'disc_diff' => $hd->disc_diff,
					'VatGroup' => $hd->VatGroup,
					'VatRate' => $hd->VatRate,
					'Status' => -1,
					'Approved' => $hd->must_approve == 1 ? 'P' : 'S',
					'OwnerCode' => $hd->OwnerCode,
					'dimCode1' => $hd->dimCode1,
					'dimCode2' => $hd->dimCode2,
					'dimCode3' => $hd->dimCode3,
					'dimCode4' => $hd->dimCode4,
					'dimCode5' => $hd->dimCode5,
					'is_duplicate' => 1,
					'OriginalSO' => $original
				);

				$this->db->trans_begin();

				if(! $this->orders_model->add($arr))
				{
					$sc = FALSE;
					$this->error = "Create Order failed";
				}
				else
				{
					if( ! empty($details))
					{
						foreach($details as $rs)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr = array(
								'order_code' => $code,
								'LineNum' => $rs->LineNum,
								'ItemCode' => $rs->ItemCode,
								'ItemName' => $rs->ItemName,
								'Qty' => $rs->Qty,
								'OpenQty' => $rs->Qty,
								'UomCode' => $rs->UomCode,
								'UomEntry' => $rs->UomEntry,
								'Price' => $rs->Price,
								'SellPrice' => $rs->SellPrice,
								'sysSellPrice' => $rs->sysSellPrice,
								'disc1' => $rs->disc1,
								'disc2' => $rs->disc2,
								'disc3' => $rs->disc3,
								'disc4' => $rs->disc4,
								'disc5' => $rs->disc5,
								'sysDisc1' => $rs->sysDisc1,
								'sysDisc2' => $rs->sysDisc2,
								'sysDisc3' => $rs->sysDisc3,
								'sysDisc4' => $rs->sysDisc4,
								'sysDisc5' => $rs->sysDisc5,
								'discLabel' => $rs->discLabel,
								'sysDiscLabel' => $rs->sysDiscLabel,
								'discDiff' => $rs->discDiff,
								'DiscPrcnt' => $rs->DiscPrcnt,
								'discAmount' => $rs->discAmount,
								'totalDiscAmount' => $rs->totalDiscAmount,
								'VatGroup' => $rs->VatGroup,
								'VatRate' => $rs->VatRate,
								'VatAmount' => $rs->VatAmount,
								'totalVatAmount' => $rs->totalVatAmount,
								'LineTotal' => $rs->LineTotal,
								'policy_id' => $rs->policy_id,
								'rule_id' => $rs->rule_id,
								'WhsCode' => $rs->WhsCode,
								'QuotaNo' => $rs->QuotaNo,
								'free_item' => $rs->free_item,
								'uid' => $rs->uid,
								'parent_uid' => $rs->parent_uid,
								'is_free' => $rs->is_free,
								'discType' => $rs->discType,
								'picked' => $rs->picked,
								'channels_id' => $rs->channels_id,
								'payment_id' => $rs->payment_id,
								'product_id' => $rs->product_id,
								'product_model_id' => $rs->product_model_id,
								'product_category_id' => $rs->product_category_id,
								'product_type_id' => $rs->product_type_id,
								'product_brand_id' => $rs->product_brand_id,
								'customer_id' => $rs->customer_id,
								'customer_group_id' => $rs->customer_group_id,
								'customer_type_id' => $rs->customer_type_id,
								'customer_region_id' => $rs->customer_region_id,
								'customer_area_id' => $rs->customer_area_id,
								'customer_grade_id' => $rs->customer_grade_id,
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'sale_team' => $rs->sale_team
							);

							if(! $this->orders_model->add_detail($arr))
							{
								$sc = FALSE;
								$this->error = "Insert detail failed";
							}

						}
					}
				}

				if($sc === TRUE)
				{
					$this->db->trans_commit();

					$arr = array(
						'code' => $code,
						'user_id' => $this->_user->id,
						'uname' => $this->_user->uname,
						'action' => 'add'
					);

					$this->orders_model->add_logs($arr);
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
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		echo $sc === TRUE ? json_encode(array('status' => 'success', 'code' => $code)) : $this->error;
	}



	public function create_from_sq()
	{
		$sc = TRUE;

		if($this->pm->can_add)
		{
			$this->load->model('orders/quotation_model');

			$sqCode = $this->input->post('sq_code');

			$hd = $this->quotation_model->get($sqCode);

			if(! empty($hd))
			{
				$details = $this->quotation_model->get_order_line($sqCode);

				$docDate = date('Y-m-d');
				$code = $this->get_new_code($docDate);

				$arr = array(
					'code' => $code,
					'role' => 'S',
					'CardCode' => $hd->CardCode,
					'CardName' => $hd->CardName,
					'PriceList' => $hd->PriceList,
					'SlpCode' => $hd->SlpCode,
					'Channels' => $hd->Channels,
					'Payment' => $hd->Payment,
					'DocCur' => $hd->DocCur,
					'DocRate' => $hd->DocRate,
					'DocTotal' => $hd->DocTotal,
					'DocDate' => $hd->DocDate,
					'DocDueDate' => $hd->DocDueDate,
					'TextDate' => $hd->TextDate,
					'PayToCode' => $hd->PayToCode,
					'ShipToCode' => $hd->ShipToCode,
					'Address' => $hd->Address,
					'Address2' => $hd->Address2,
					'DiscPrcnt' => $hd->DiscPrcnt,
					'DiscAmount' => $hd->DiscAmount,
					'VatSum' => $hd->VatSum,
					'RoundDif' => $hd->RoundDif,
					'sale_team' => $hd->sale_team,
					'user_id' => $this->_user->id,
					'uname' => $this->_user->uname,
					'Comments' => $hd->Comments,
					'must_approve' => $hd->must_approve,
					'disc_diff' => $hd->disc_diff,
					'VatGroup' => $hd->VatGroup,
					'VatRate' => $hd->VatRate,
					'Status' => -1,
					'Approved' => $hd->must_approve == 1 ? 'P' : 'S',
					'OwnerCode' => $hd->OwnerCode,
					'dimCode1' => $hd->dimCode1,
					'dimCode2' => $hd->dimCode2,
					'dimCode3' => $hd->dimCode3,
					'dimCode4' => $hd->dimCode4,
					'dimCode5' => $hd->dimCode5,
					'is_duplicate' => 0,
					'SqNo' => $sqCode
				);

				$this->db->trans_begin();

				if(! $this->orders_model->add($arr))
				{
					$sc = FALSE;
					$this->error = "Create Order failed";
				}
				else
				{
					if( ! empty($details))
					{
						foreach($details as $rs)
						{
							if($sc === FALSE)
							{
								break;
							}
							$LineNum = 0;
							$arr = array(
								'order_code' => $code,
								'LineNum' => $LineNum,
								'ItemCode' => $rs->ItemCode,
								'ItemName' => $rs->ItemName,
								'Qty' => $rs->Qty,
								'OpenQty' => $rs->Qty,
								'UomCode' => $rs->UomCode,
								'UomEntry' => $rs->UomEntry,
								'Price' => $rs->Price,
								'SellPrice' => $rs->SellPrice,
								'sysSellPrice' => $rs->sysSellPrice,
								'disc1' => $rs->disc1,
								'disc2' => $rs->disc2,
								'disc3' => $rs->disc3,
								'disc4' => $rs->disc4,
								'disc5' => $rs->disc5,
								'sysDisc1' => $rs->sysDisc1,
								'sysDisc2' => $rs->sysDisc2,
								'sysDisc3' => $rs->sysDisc3,
								'sysDisc4' => $rs->sysDisc4,
								'sysDisc5' => $rs->sysDisc5,
								'discLabel' => $rs->discLabel,
								'sysDiscLabel' => $rs->sysDiscLabel,
								'discDiff' => $rs->discDiff,
								'DiscPrcnt' => $rs->DiscPrcnt,
								'discAmount' => $rs->discAmount,
								'totalDiscAmount' => $rs->totalDiscAmount,
								'VatGroup' => $rs->VatGroup,
								'VatRate' => $rs->VatRate,
								'VatAmount' => $rs->VatAmount,
								'totalVatAmount' => $rs->totalVatAmount,
								'LineTotal' => $rs->LineTotal,
								'policy_id' => $rs->policy_id,
								'rule_id' => $rs->rule_id,
								'WhsCode' => $rs->WhsCode,
								'QuotaNo' => $rs->QuotaNo,
								'free_item' => $rs->free_item,
								'uid' => $rs->uid,
								'parent_uid' => $rs->parent_uid,
								'is_free' => $rs->is_free,
								'discType' => $rs->discType,
								'picked' => $rs->picked,
								'channels_id' => $rs->channels_id,
								'payment_id' => $rs->payment_id,
								'product_id' => $rs->product_id,
								'product_model_id' => $rs->product_model_id,
								'product_category_id' => $rs->product_category_id,
								'product_type_id' => $rs->product_type_id,
								'product_brand_id' => $rs->product_brand_id,
								'customer_id' => $rs->customer_id,
								'customer_group_id' => $rs->customer_group_id,
								'customer_type_id' => $rs->customer_type_id,
								'customer_region_id' => $rs->customer_region_id,
								'customer_area_id' => $rs->customer_area_id,
								'customer_grade_id' => $rs->customer_grade_id,
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'sale_team' => $rs->sale_team
							);

							if(! $this->orders_model->add_detail($arr))
							{
								$sc = FALSE;
								$this->error = "Insert detail failed";
							}

						}
					}
				}

				if($sc === TRUE)
				{
					$this->db->trans_commit();

					$arr = array(
						'code' => $code,
						'user_id' => $this->_user->id,
						'uname' => $this->_user->uname,
						'action' => 'add'
					);

					$this->orders_model->add_logs($arr);
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
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		echo $sc === TRUE ? json_encode(array('status' => 'success', 'code' => $code)) : $this->error;
	}



	public function do_export($code)
	{
		$this->load->library('order_api');
		return $this->order_api->exportOrder($code);
	}



	public function getJSON()
	{
		$code = $this->input->get('code');
		$order = $this->orders_model->get($code);
		$details = $this->orders_model->get_details($code);

		$ds = array(
			'nodata' => 'nodata'
		);

		if(! empty($order) && ! empty($details))
		{
      $ds = array(
				"WEBORDER" => $order->code,
				"CardCode" => $order->CardCode,
				"CardName" => $order->CardName,
				"SlpCode" => intval($order->SlpCode),
				"GroupNum" => intval($order->Payment),
				"DocCur" => $order->DocCur,
				"DocRate" => round($order->DocRate, 2),
				"DocTotal" => NULL, //round($order->DocTotal, 2),
				"DocDate" => $order->DocDate,
				"DocDueDate" => $order->DocDueDate,
				"TaxDate" => $order->TextDate,
				"PayToCode" => $order->PayToCode,
				"ShipToCode" => $order->ShipToCode,
				"Address" => NULL,//$order->Address,
				"Address2" => NULL, //$order->Address2,
				"DiscPrcnt" => round($order->DiscPrcnt, 2),
				"RoundDif" => round($order->RoundDif, 2),
				"Comments" => $order->Comments,
				"OwnerCode" => intval($order->OwnerCode),
				"OcrCode" => $order->dimCode1,
				"OcrCode2" => $order->dimCode2,
				"OcrCode3" => $order->dimCode3,
				"OcrCode4" => $order->dimCode4,
				"OcrCode5" => $order->dimCode5
			);



			$orderLine = array();

			foreach($details AS $rs)
			{
        $line = array(
					"LineNum" => intval($rs->LineNum),
					"ItemCode" => $rs->ItemCode,
					"ItemName" => $rs->ItemName,
					"Quantity" => round($rs->Qty, 2),
					"UomEntry" => intval($rs->UomEntry),
					"Price" => round($rs->Price, 2),
					"LineTotal" => round($rs->LineTotal, 2),
					"DiscPrcnt" => NULL, //round($rs->DiscPrcnt, 2),
					"PriceBefDi" => round($rs->Price, 2),
					"Currency" => $order->DocCur,
					"Rate" => round($order->DocRate, 2),
					"VatGroup" => $rs->VatGroup,
					"VatPrcnt" => round($rs->VatRate, 2),
					"PriceAfVAT" => round(add_vat($rs->SellPrice, $rs->VatRate), 2),
					"VatSum" => round($rs->totalVatAmount, 2),
					"SlpCode" => intval($order->SlpCode),
					"U_DISC_LABEL" => get_null($rs->discLabel),
					"Sale_Discount1" => round($rs->disc1, 2),
					"Sale_Discount2" => round($rs->disc2, 2),
					"Sale_Discount3" => round($rs->disc3, 2),
					"Sale_Discount4" => round($rs->disc4, 2),
					"Sale_Discount5" => round($rs->disc5, 2),
					"WhsCode" => $rs->WhsCode,
					"Quota" => $rs->QuotaNo,
					"OcrCode" => $order->dimCode1,
					"OcrCode2" => $order->dimCode2,
					"OcrCode3" => $order->dimCode3,
					"OcrCode4" => $order->dimCode4,
					"OcrCode5" => $order->dimCode5,
					"SaleTeam" => $rs->team_code
				);


				array_push($orderLine, $line);
			}

			$ds['DocLine'] = $orderLine;
		}

			echo json_encode($ds);
	}

	public function cancle_sap_order()
	{
		$sc = TRUE;
		$code = $this->input->post('code');
		$this->load->library('order_api');

		$order = $this->orders_model->get($code);

		if( ! empty($order))
		{
			if( ! empty($order->DocEntry) && !empty($order->DocNum) && $order->Status == 1)
			{
				$arr = array(
					'DocEntry' => $order->DocEntry,
					'DocNum' => $order->DocNum
				);

				$rs = $this->order_api->cancle_sap_order($arr);

				if(! $rs)
				{
					$sc = FALSE;
					$this->error = $this->order_api->error;
				}
				else
				{
					$arr = array(
						'Status' => -1,
						'DocEntry' => NULL,
						'DocNum' => NULL,
            'Approved' => 'P',
            'Approver' => NULL
					);

					$this->orders_model->update($code, $arr);
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid document status";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid document No.";
		}

		$this->_response($sc);
	}



	public function cancle_order()
	{
		$sc = TRUE;
		if($this->pm->can_delete)
		{
			$code = $this->input->post('code');

			$order = $this->orders_model->get($code);

			if( ! empty($order))
			{
				if($order->Status == -1 OR $order->Status == 0 OR $order->Status == 3)
				{
					$this->db->trans_begin();
					//--- set detail complete to 2 ** cancelled
					if(! $this->orders_model->cancle_details($code))
					{
						$sc = FALSE;
						$this->error = "Cannot change document line status";
					}
					else
					{
						if(! $this->orders_model->cancle_order($code))
						{
							$sc = FALSE;
							$this->error = "Cannot change Document status";
						}
					}

					if($sc === TRUE)
					{
						$this->db->trans_commit();
						$arr = array(
							'code' => $code,
							'action' => 'cancel',
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname
						);

						$this->orders_model->add_logs($arr);
					}
					else
					{
						$this->db->trans_rollback();
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Invalid Document Status";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid Document No.";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}


	public function send_to_sap()
	{
		$sc = TRUE;

		$code = $this->input->post('code');

		$order = $this->orders_model->get($code);
		if(!empty($order))
		{
			if(empty($order->DocEntry) && empty($order->DocNum))
			{
        $this->load->library('order_api');
				//--- check document
				$rs = $this->order_api->exportOrder($code);

				if( ! $rs)
				{
					$sc = FALSE;
          $this->error = $this->order_api->error;
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Sales Order : {$order->DocNum} already exists in SAP";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid document No.";
		}

		$this->_response($sc);
	}

	public function get_item_data()
	{
		$sc = TRUE;
		$itemCode = $this->input->get('ItemCode');
		$cardCode = $this->input->get('CardCode');
		$priceList = $this->input->get('PriceList');
		$docDate = db_date($this->input->get('DocDate'));
		$payment = $this->input->get('Payment');
		$channels = $this->input->get('Channels');
		$whsCode = $this->input->get('WhsCode');
		$whsCode = empty($whsCode) ? getConfig('DEFAULT_WAREHOUSE') : $whsCode;
		$quotaNo = $this->input->get('quotaNo');
		$qty = 1;

		$pd = $this->products_model->get($itemCode);

		if(! empty($pd))
		{
			$price = $pd->price; //$this->getPrice($itemCode, $priceList);
			$stock = $this->getStock($itemCode, $whsCode, $quotaNo, $pd->count_stock);
			$disc = $this->discount_model->get_item_discount($itemCode, $cardCode, $price, $qty, $payment, $channels, $docDate);

			if(!empty($disc))
			{
				$ds = array(
					'product_id' => $pd->id,
					'ItemCode' => $pd->code,
					'ItemName' => $pd->name,
					'whsCode' => $whsCode,
					'instock' => $stock['OnHand'],
					'team' => $stock['QuotaQty'],
					'commit' => $stock['Committed'],
					'available' => $stock['Available'],
					'Qty' => $qty,
					'UomCode' => $pd->uom_code,
					'UomName' => $pd->uom,
					'StdPrice' => $price,
					'Price' => $disc->type == 'N' ? $disc->sellPrice : $price,
					'SellPrice' => $disc->sellPrice,
					'sysSellPrice' => $disc->sellPrice,
					'sysDiscLabel' => discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'discLabel' => $disc->type == 'N' ? "" : discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'DiscPrcnt' => $disc->totalDiscPrecent,
					'discAmount' => $disc->discAmount,
					'totalDiscAmount' => $disc->totalDiscAmount,
					'VatGroup' => $pd->vat_group,
					'VatRate' => $pd->vat_rate,
					'VatAmount' => get_vat_amount($disc->sellPrice, $pd->vat_rate),
					'TotalVatAmount' => (get_vat_amount($disc->sellPrice, $pd->vat_rate) * $qty),
					'LineTotal' => ($disc->sellPrice * $qty),
					'image' => get_image_path($pd->id, 'mini'),
					'rule_id' => $disc->rule_id,
					'policy_id' => $disc->policy_id,
					'freeQty' => $disc->freeQty,
					'discType' => $disc->type,
					'count_stock' => $pd->count_stock,
					'allow_change_discount' => $pd->allow_change_discount
				);
			}
			else
			{
				$sc = FALSE;
				$this->error = "Discount not found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Item Not found";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}


	public function get_discount_data()
	{
		$sc = TRUE;
		$itemCode = $this->input->get('ItemCode');
		$cardCode = $this->input->get('CardCode');
		$docDate = db_date($this->input->get('DocDate'));
		$payment = $this->input->get('Payment');
		$channels = $this->input->get('Channels');
		$qty = $this->input->get('Qty');
		$pd = $this->products_model->get($itemCode);
		$price = $pd->price;

		if(! empty($pd))
		{
			$disc = $this->discount_model->get_item_discount($itemCode, $cardCode, $price, $qty, $payment, $channels, $docDate);

			if(!empty($disc))
			{
				$ds = array(
					"product_id" => $pd->id,
					'ItemCode' => $pd->code,
					'ItemName' => $pd->name,
					'Qty' => $qty,
					'UomCode' => $pd->uom_code,
					'UomName' => $pd->uom,
					'StdPrice' => $price,
					'Price' => $disc->type == 'N' ? $disc->sellPrice : $price,
					'SellPrice' => $disc->sellPrice,
					'sysSellPrice' => $disc->sellPrice,
					'sysDiscLabel' => discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'discLabel' => $disc->type == 'N' ? "" : discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
					'DiscPrcnt' => $disc->totalDiscPrecent,
					'discAmount' => $disc->discAmount,
					'totalDiscAmount' => $disc->totalDiscAmount,
					'VatGroup' => $pd->vat_group,
					'VatRate' => $pd->vat_rate,
					'VatAmount' => get_vat_amount($disc->sellPrice, $pd->vat_rate),
					'TotalVatAmount' => (get_vat_amount($disc->sellPrice, $pd->vat_rate) * $qty),
					'LineTotal' => ($disc->sellPrice * $qty),
					'rule_id' => $disc->rule_id,
					'policy_id' => $disc->policy_id,
					'freeQty' => $disc->freeQty,
					'discType' => $disc->type
				);
			}
			else
			{
				$sc = FALSE;
				$this->error = "Discount not found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Item Not found";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}


	public function get_free_item_rule()
	{
		$sc = TRUE;

		$ds = array();

		$json = json_decode($this->input->post('json'));

		if(!empty($json))
		{
			$date = db_date($json->DocDate);

			if(! empty($json->items))
			{
				$arr = array();

				foreach($json->items as $rs)
				{
					$rd = $this->discount_model->get_free_item_rule($rs->itemCode, $json->CardCode, $json->Payment, $json->Channels, $date, $rs->qty, $rs->amount);

					if(!empty($rd))
					{
						if($rd->freeQty > 0)
						{
							if(isset($ds[$rd->rule_id]))
							{
								$ds[$rd->rule_id]['freeQty'] += $rd->freeQty;
							}
							else
							{
								$ds[$rd->rule_id]['freeQty'] = $rd->freeQty;
								$ds[$rd->rule_id]['policy_id'] = $rd->policy_id;
								$ds[$rd->rule_id]['rule_id'] = $rd->rule_id;
								$ds[$rd->rule_id]['uid'] = uniqid(rand(1,100));
							}
						}
					}
				}
			}
		}

		echo json_encode($ds);
	}



	public function get_free_item()
	{
		$rule_id = $this->input->get('rule_id');
		$uid = $this->input->get('uid');
		$freeQty = $this->input->get('freeQty');
		$picked = $this->input->get('picked');
		$qty = $freeQty - $picked;

		$list = $this->discount_model->get_free_item_list($rule_id);

		$ds = "";

		if(!empty($list))
		{
			$ds .= "<tr><td colspan='5' class='text-center'>เลือก {$qty} ชิ้น จากรายการต่อไปนี้</td></tr>";
			$ds .= "<tr>";
			$ds .= "<td class='fix-width-60 middle'>Image</td>";
			$ds .= "<td class='fix-width-100 middle'>Code</td>";
			$ds .= "<td class='min-width-250 middle'>Description</td>";
			$ds .= "<td class='fix-width-80 middle'>Qty</td>";
			$ds .= "<td class='fix-width-80 middle'></td>";
			$ds .= "</tr>";

			foreach($list as $rs)
			{
				$uuid = uniqid(rand(1,100));
				$img = get_image_path($rs->product_id, 'mini');
				$pd = $this->products_model->get($rs->product_code);
				$price = $pd->price;

				$ds .= "<tr>";
				$ds .= "<td class='text-center'><img src='{$img}' width='40' height='40' /></td>";
				$ds .= "<td class='fix-width-100 middle'>{$pd->code}</td>";
				$ds .= "<td class='min-width-250 middle'>{$pd->name}</td>";
				$ds .= "<td class='fix-width-80 middle'>";
				$ds .= "<input type='number' class='form-control input-sm text-center auto-select' ";
				$ds .= "id='input-{$uuid}' data-item='{$pd->id}' ";
				$ds .= "data-uid='{$uid}' data-parent='{$uid}' ";
				$ds .= "data-pdcode='{$pd->code}' ";
				$ds .= "data-pdname='{$pd->name}' ";
				$ds .= "data-price='{$price}' ";
				$ds .= "data-uom='{$pd->uom}' data-uomcode='{$pd->uom_code}' ";
				$ds .= "data-rule='{$rs->rule_id}' data-policy='{$rs->id_policy}' ";
				$ds .= "data-vatcode='{$pd->vat_group}' data-vatrate='{$pd->vat_rate}' ";
				$ds .= "data-img='{$img}' data-qty='{$qty}' value='1'>";
				$ds .= "</td>";
				$ds .= "<td class='fix-width-80 middle'>";
				$ds .= "<button class='btn btn-primary btn-xs btn-block' id='btn-{$uuid}' onclick=\"addFreeRow('{$uuid}')\">Add</button>";
				$ds .= "</td>";
				$ds .= "</tr>";
			}
		}
		else
		{
			$ds .= "<tr><td colspan='5' class='text-center'>ไม่พบรายการสินค้า</td></tr>";
		}

		echo $ds;
	}


	public function getPrice($ItemCode, $priceList)
	{
		$this->load->library('api');
		return $this->api->getItemPrice($ItemCode, $priceList);
	}

	public function get_item_price()
	{
		$ItemCode = $this->input->get('itemCode');
		$priceList = $this->input->get('priceList');

		return $this->getPrice($ItemCode, $priceList);

	}



	public function getStock($ItemCode, $WhsCode, $QuotaNo, $count_stock = 1)
	{
		$test = getConfig('TEST') == 1 ? TRUE : FALSE;

		if($test OR $count_stock == 0)
		{
			$arr = array(
				'OnHand' => 0,
				'Committed' => 0,
				'QuotaQty' => 0,
				'Available' => 0
			);
		}
		else
		{
			$this->load->library('api');

			$commit = get_zero($this->orders_model->get_commit_qty($ItemCode, $QuotaNo));

			$stock = $this->api->getItemStock($ItemCode, $WhsCode, $QuotaNo);

			$arr = array(
				'OnHand' => 0,
				'Committed' => $commit,
				'QuotaQty' => 0,
				'Available' => 0
			);

			if(!empty($stock))
			{
				$OnHand = $stock['OnHand'];
				$Quota = $stock['QuotaQty'];
				$available = $Quota - $commit;

				$arr = array(
					'OnHand' => $OnHand,
					'Committed' => $commit,
					'QuotaQty' => $Quota,
					'Available' => $available > 0 ? $available : 0
				);
			}
		}

    return $arr;
	}


	public function get_stock()
	{
		$ItemCode = $this->input->get('itemCode');
		$whsCode = $this->input->get('whsCode');
		$quota = $this->input->get('quota');

		$arr = $this->getStock($ItemCode, $whsCode, $quota);

		echo json_encode($arr);
	}



	public function remove_row()
	{
		$sc = TRUE;

		$id = $this->input->post('id');
		$code = $this->input->post('order_code');

		if( ! $this->orders_model->delete_detail($id))
		{
			$sc = FALSE;
			set_eror('delete');
		}
		else
		{
			$this->orders_model->update_doc_total($code);
		}

		$this->_response($sc);
	}


	public function export_order($code)
	{
		return TRUE;
	}


	public function get_customer_order_data()
	{
		$code = $this->input->get('CardCode');

		$rs = $this->customers_model->get_customer_data($code);

		if(! empty($rs))
		{
			echo json_encode($rs);
		}
		else {
			echo "not found";
		}
	}

	public function get_address_ship_to_code()
	{
		$CardCode = $this->input->get('CardCode');
		$sc = array();
		$ds = $this->customer_address_model->get_address_ship_to_code($CardCode);

		if(!empty($ds))
		{
			foreach($ds as $rs)
			{
				$sc[] = $rs;
			}

			echo json_encode($sc);
		}
		else
		{
			echo "no data";
		}
	}

	public function get_address_bill_to_code()
	{
		$CardCode = $this->input->get('CardCode');
		$sc = array();
		$ds = $this->customer_address_model->get_address_bill_to_code($CardCode);

		if(!empty($ds))
		{
			foreach($ds as $rs)
			{
				$sc[] = $rs;
			}

			echo json_encode($sc);
		}
		else
		{
			echo "no data";
		}
	}



	public function get_address_ship_to()
	{
		$CardCode = $this->input->get('CardCode');
		$Address = $this->input->get('Address');
		$adr = $this->customer_address_model->get_address_ship_to($CardCode, $Address);

		if( ! empty($adr))
		{
			$arr = array(
				'code' => get_empty_text($adr->Address),
				'address' => get_empty_text($adr->Street),
				'sub_district' => get_empty_text($adr->Block),
				'district' => get_empty_text($adr->City),
				'province' => get_empty_text($adr->County),
				'country' => get_empty_text($adr->Country),
				'postcode' => get_empty_text($adr->ZipCode)
			);

			echo json_encode($arr);
		}
		else
		{
			echo "not found";
		}
	}


	public function get_address_bill_to()
	{
		$CardCode = $this->input->get('CardCode');
		$Address = $this->input->get('Address');

		$sc = array();
		$adr = $this->customer_address_model->get_address_bill_to($CardCode, $Address);

		if( ! empty($adr))
		{
			$arr = array(
				'code' => get_empty_text($adr->Address),
				'address' => get_empty_text($adr->Street),
				'sub_district' => get_empty_text($adr->Block),
				'district' => get_empty_text($adr->City),
				'province' => get_empty_text($adr->County),
				'country' => get_empty_text($adr->Country),
				'postcode' => get_empty_text($adr->ZipCode)
			);

			echo json_encode($arr);
		}
		else
		{
			echo "not found";
		}
	}



	public function get_order_message()
	{
		$code = $this->input->get('code');

		$order = $this->orders_model->get($code);

		if(!empty($order))
		{
			$arr = array(
				'U_WEBORDER' => $code,
				'CardCode' => $order->CardCode,
				'CardName' => $order->CardName,
				'date_upd' => thai_date($order->date_upd, TRUE),
				'Message' => $order->message
			);

			echo json_encode($arr);
		}
		else
		{
			echo "No data";
		}
	}


	public function clear_filter()
	{
		$filter = array(
			'order_code',
			'order_customer',
			'sqNo',
			'soNo',
			'order_role',
			'order_sale_id',
			'order_channels',
			'order_payment',
			'order_approval',
			'order_status',
			'order_from_date',
			'order_to_date',
			'onlyMe',
			'chk_sqNo',
			'chk_channels',
			'chk_payment',
			'chk_ship',
			'order_user_id'
		);

		return clear_filter($filter);
	}



	public function get_new_code($date = NULL)
  {
    $date = empty($date) ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_ORDER');
    $run_digit = getConfig('RUN_DIGIT_ORDER');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->orders_model->get_max_code($pre);

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
}
?>
