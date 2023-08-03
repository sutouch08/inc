<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends PS_Controller
{
	public $menu_code = 'SOODSQ';
	public $menu_group_code = 'SO';
  public $menu_sub_group_code = 'ORDER';
	public $title = 'Quotations';
	public $segment = 4;
	public $readOnly = FALSE;

	public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'orders/quotation';
		$this->load->model('orders/quotation_model');
		$this->load->model('masters/customers_model');
		$this->load->model('masters/customer_address_model');
		$this->load->model('masters/products_model');
		$this->load->model('masters/payment_term_model');
		$this->load->model('masters/channels_model');
		$this->load->model('orders/discount_model');
		$this->load->model('masters/warehouse_model');
		$this->load->model('masters/cost_center_model');
		$this->load->helper('channels');
		$this->load->helper('order');
		$this->load->helper('customer');
		$this->load->helper('product_images');
		$this->load->helper('discount');
		$this->load->helper('warehouse');

		$this->readOnly = getConfig('CLOSE_SYSTEM') == 2 ? TRUE : FALSE;
  }


  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'sq_code', ''),
			'customer' => get_filter('customer', 'sq_customer', ''),
			'sqNo' => get_filter('sqNo', 'sqNo', ''),
			'soNo' => get_filter('soNo', 'soNo', ''),
			'role' => get_filter('role', 'sq_role', 'all'),
			'sale_id' => get_filter('sale_id', 'sq_sale_id', 'all'),
			'channels' => get_filter('channels', 'sq_channels', 'all'),
			'payment' => get_filter('payment', 'sq_payment', 'all'),
			'approval' => get_filter('approval', 'sq_approval', 'all'),
			'status' => get_filter('status', 'sq_status', 'all'),
			'from_date' => get_filter('from_date', 'sq_from_date', ''),
			'to_date' => get_filter('to_dte', 'sq_to_date', ''),
			'onlyMe' => get_filter('onlyMe', 'onlyMe', 0),
			'user_id' => get_filter('user_id', 'sq_user_id', 'all'),
			'chk_channels' => get_filter('chk_channels', 'chk_channels', 0),
			'chk_payment' => get_filter('chk_payment', 'chk_payment', 0)
		);

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();

		$rows = $this->quotation_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
    $filter['data'] = $this->quotation_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$this->pagination->initialize($init);
    $this->load->view('quotation/quotation_list', $filter);
  }




  public function add_new()
  {
		$this->load->model('masters/sales_person_model');
		$ds = array(
			'sale_name' => $this->sales_person_model->get_name($this->_user->sale_id),
			'default_channels' => $this->channels_model->get_default()
		);

    $this->load->view('quotation/quotation_add', $ds);
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
						'CardCode' => $hd->CardCode,
						'CardName' => $hd->CardName,
						'ContactPerson' => $hd->ContactPerson,
						'Phone' => trim($hd->Phone),
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

					if(! $this->quotation_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "Create Quotation failed";
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

								if($rs->type == 0)
								{
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
											'UomCode' => $pd->uom_code,
											'UomEntry' => $pd->uom_id,
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
											'free_item' => $rs->free_item,
											'uid' => $rs->uid,
											'parent_uid' => $rs->parent_uid,
											'is_free' => $rs->is_free,
											'discType' => $rs->discType,
											'picked' => $rs->picked,
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
											'customer_region_id' => $customer->RegionCode,
											'customer_area_id' => $customer->AreaCode,
											'customer_grade_id' => $customer->GradeCode,
											'user_id' => $this->_user->id,
											'uname' => $this->_user->uname,
											'sale_team' => $rs->sale_team
										);

										if(! $this->quotation_model->add_detail($arr))
										{
											$sc = FALSE;
											$this->error = "Insert detail failed";
										}
									}
								}
								else
								{
									$arr = array(
										'order_code' => $code,
										'type' => $rs->type,
										'LineText' => $rs->LineText,
										'AfLineNum' => $rs->AfLineNum
									);

									if(! $this->quotation_model->add_detail($arr))
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

						$this->quotation_model->add_logs($arr);

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

			$order = $this->quotation_model->get($code);

			if( ! empty($order))
			{
				$details = $this->quotation_model->get_details($order->code);

				if(!empty($details))
				{
					foreach($details as $rs)
					{
						if($rs->type == 0)
						{
							$totalAmount += $rs->LineTotal;
							$totalVat += $rs->totalVatAmount;
							$stock = $this->getStock($rs->ItemCode, $rs->WhsCode, $rs->QuotaNo);
							$rs->instock = !empty($stock) ? $stock['OnHand'] : 0;
							$rs->team = !empty($stock) ? $stock['QuotaQty'] : 0;
							$rs->commit = !empty($stock) ? ($stock['Committed'] > 0 ? $stock['Committed'] - $rs->Qty : 0) : 0;
							$rs->available = !empty($stock) ? $stock['Available'] : 0;
							$rs->image = get_image_path($rs->product_id, 'mini');
						}
					}
				}

				$ds = array(
					'order' => $order,
					'details' => $details,
					'totalAmount' => $totalAmount,
					'totalVat' => $totalVat,
					'whsList' => $this->warehouse_model->get_listed(),
					'quotaList' => $this->quota_model->get_all_listed(),
					'logs' => $this->quotation_model->get_logs($code)
				);

				$this->load->view('quotation/quotation_edit', $ds);
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

					$order = $this->quotation_model->get($code);

					if(! empty($order))
					{
						if($order->Status == -1 OR $order->Status == 0 OR $order->Status == 3)
						{
							if( ! empty($customer))
							{
								$arr = array(
									'CardCode' => $hd->CardCode,
									'CardName' => $hd->CardName,
									'ContactPerson' => $hd->ContactPerson,
									'Phone' => trim($hd->Phone),
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

								if(! $this->quotation_model->update($code, $arr))
								{
									$sc = FALSE;
									$this->error = "Update Order failed";
								}
								else
								{
									if($this->quotation_model->drop_details($code))
									{
										if( ! empty($details))
										{
											foreach($details as $rs)
											{
												if($sc === FALSE)
												{
													break;
												}

												if($rs->type == 0)
												{
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
															'UomCode' => $pd->uom_code,
															'UomEntry' => $pd->uom_id,
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
															'customer_region_id' => $customer->RegionCode,
															'customer_area_id' => $customer->AreaCode,
															'customer_grade_id' => $customer->GradeCode,
															'user_id' => $this->_user->id,
															'uname' => $this->_user->uname,
															'sale_team' => $rs->sale_team
														);

														if(! $this->quotation_model->add_detail($arr))
														{
															$sc = FALSE;
															$this->error = "Insert detail failed";
														}
													}
												}
												else
												{
													$arr = array(
														'order_code' => $code,
														'type' => $rs->type,
														'LineText' => $rs->LineText,
														'AfLineNum' => $rs->AfLineNum
													);

													if(! $this->quotation_model->add_detail($arr))
													{
														$sc = FALSE;
														$this->error = "Insert detail failed";
													}
												}
											}
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

									$this->quotation_model->add_logs($arr);

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
				if(! $this->do_export($code))
				{
					$sc = FALSE;
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
		$doc = $this->quotation_model->get($code);

		if(!empty($doc))
		{
			//--- ตัองยังไม่ได้อนุมัติ และ ยังไม่เข้า SAP และ ยังไม่มีเลขที่เอกสารใน SAP
			if($doc->Approved === 'P' && $doc->Status == 0 && $doc->DocNum === NULL)
			{
				//--- ตรวจสอบสิทธิ์ในการอนุาัติ
				$approver = $this->approver_model->get_approve_right($this->_user->id, $doc->sale_team);

				if(! empty($approver))
				{
					if($doc->disc_diff <= $approver->max_disc)
					{
						$arr = array(
							'Approved' => 'A',
							'Approver' => $this->_user->uname
						);

						if(! $this->quotation_model->update($code, $arr))
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

							$this->quotation_model->add_logs($arr);
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
					set_error('permission');
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
		$order = $this->quotation_model->get($code);

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

						if( ! $this->quotation_model->update($code, $arr))
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

							$this->quotation_model->add_logs($arr);
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
					$rs->image = get_image_path($rs->product_id, 'mini');
				}
			}

			$ds = array(
				'order' => $order,
				'details' => $details,
				'totalAmount' => $totalAmount,
				'totalVat' => $totalVat,
				'sale_name' => $this->sales_person_model->get_name($order->SlpCode),
				'owner' => $this->employee_model->get_name($order->OwnerCode),
				'dimCode' => $this->parseDimCode($order->dimCode1, $order->dimCode2, $order->dimCode3, $order->dimCode4, $order->dimCode5),
				'logs' => $this->quotation_model->get_logs($code)
			);

			$this->load->view('quotation/quotation_view', $ds);
		}
		else
		{
			$this->page_error();
		}
	}


	public function parseDimCode($d1, $d2, $d3, $d4, $d5)
	{
		$name = "";

		$name = empty($d1) ? $name : $this->cost_center_model->get_name($d1);
		$name = empty($d2) ? $name : $this->cost_center_model->get_name($d2);
		$name = empty($d3) ? $name : $this->cost_center_model->get_name($d3);
		$name = empty($d4) ? $name : $this->cost_center_model->get_name($d4);
		$name = empty($d5) ? $name : $this->cost_center_model->get_name($d5);

		return $name;
	}


	public function duplicate_quotation()
	{
		$sc = TRUE;

		if($this->pm->can_add)
		{
			$original = $this->input->post('code');

			$hd = $this->quotation_model->get($original);

			if(! empty($hd))
			{
				$details = $this->quotation_model->get_details($original);

				$docDate = db_date($hd->DocDate, FALSE);
				$code = $this->get_new_code($docDate);

				$arr = array(
					'code' => $code,
					'CardCode' => $hd->CardCode,
					'CardName' => $hd->CardName,
					'ContactPerson' => $hd->ContactPerson,
					'Phone' => $hd->Phone,
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
					'OriginalSQ' => $original
				);

				$this->db->trans_begin();

				if(! $this->quotation_model->add($arr))
				{
					$sc = FALSE;
					$this->error = "Create Quotation failed";
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
								'type' => $rs->type,
								'ItemCode' => $rs->ItemCode,
								'ItemName' => $rs->ItemName,
								'Qty' => $rs->Qty,
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
								'sale_team' => $rs->sale_team,
								'LineText' => $rs->LineText,
								'AfLineNum' => $rs->AfLineNum
							);

							if(! $this->quotation_model->add_detail($arr))
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

					$this->quotation_model->add_logs($arr);
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
		$sc = TRUE;
		$order = $this->quotation_model->get($code);
		$details = $this->quotation_model->get_order_line($code);
		$text_line = $this->quotation_model->get_order_line_text($code);

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
				"Address" => $order->Address,
				"Address2" => $order->Address2,
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
					"LineTotal" => NULL, //round($rs->LineTotal, 2),
					"DiscPrcnt" => round($rs->DiscPrcnt, 2),
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

			$lineText = array();

			if(!empty($text_line))
			{
				$LineSeq = 0;
				foreach($text_line as $rs)
				{
					$arr = array(
						'LineSeq' => $LineSeq,
						'AfLineNum' => $rs->AfLineNum,
						'LineText' => $rs->LineText
					);

					array($lineText, $arr);
					$LineSeq++;
				}
			}

			$ds['TextLine'] = $lineText;


			$url = getConfig('SAP_API_HOST');
			if($url[-1] != '/')
			{
				$url .'/';
			}

			$url = $url."SalesQuotation";

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($ds));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$response = curl_exec($curl);

			if($response === FALSE)
			{
				$response = curl_error($curl);
			}
			
			curl_close($curl);

			$rs = json_decode($response);

			if(! empty($rs) && ! empty($rs->status))
			{
				if($rs->status == 'success')
				{
					$arr = array(
						'Status' => 1,
						'DocEntry' => $rs->DocEntry,
						'DocNum' => $rs->DocNum
					);

					$this->quotation_model->update($code, $arr);
				}
				else
				{
					$arr = array(
						'Status' => 3,
						'message' => $rs->error
					);

					$this->quotation_model->update($code, $arr);

					$sc = FALSE;
					$this->error = $rs->error;
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Export order failed";

				$arr = array(
					'Status' => 3,
					'message' => $response //$this->error
				);

				$this->quotation_model->update($code, $arr);
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "No data found";
		}

		return $sc;
	}




	public function cancle_sap_order()
	{
		$sc = TRUE;
		$code = $this->input->post('code');
		$this->load->library('order_api');

		$order = $this->quotation_model->get($code);

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
						'Status' => 0,
						'DocEntry' => NULL,
						'DocNum' => NULL
					);

					$this->quotation_model->update($code, $arr);
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

			$order = $this->quotation_model->get($code);

			if( ! empty($order))
			{
				if($order->Status == -1 OR $order->Status == 0 OR $order->Status == 3)
				{
					$this->db->trans_begin();
					//--- set detail complete to 2 ** cancelled
					if(! $this->quotation_model->cancle_details($code))
					{
						$sc = FALSE;
						$this->error = "Cannot change document line status";
					}
					else
					{
						if(! $this->quotation_model->cancle_order($code))
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

						$this->quotation_model->add_logs($arr);
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

		$order = $this->quotation_model->get($code);
		if(!empty($order))
		{
			if(empty($order->DocEntry) && empty($order->DocNum))
			{
				//--- check document
				$rs = $this->do_export($code);

				if( ! $rs)
				{
					$sc = FALSE;
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




	public function print_sq($code)
	{
		$this->load->model('masters/employee_model');
		$this->load->model('masters/sales_person_model');
		$this->load->library('pdf_printer');
		$this->load->library('printer');
		$doc = $this->quotation_model->get($code);
		$detail = $this->quotation_model->get_details($code);

		$details = array();


		if(!empty($detail))
		{
			$no = 0;
			foreach($detail as $rs)
			{
				if($rs->type == 1 && $no > 0)
				{
					$noo = $no -1;
					$details[$noo]->ItemName .= PHP_EOL.$rs->LineText;
				}
				else
				{
					$details[$no] = $rs;
					$no++;
				}
			}
		}


		$customer = $this->customers_model->get($doc->CardCode);
		$sale = $this->sales_person_model->get($doc->SlpCode);
		$doc->OwnerName = empty($doc->OwnerCode) ? "" : $this->employee_model->get_name($doc->OwnerCode);
		$company = new stdClass();
		$company->name = getConfig('COMPANY_FULL_NAME');
		$company->address1 = getConfig('COMPANY_ADDRESS1');
		$company->address2 = getConfig('COMPANY_ADDRESS2');
		$company->postcode = getConfig('COMPANY_POST_CODE');
		$company->phone = getConfig('COMPANY_PHONE');
		$company->fax = getConfig('COMPANY_FAX');
		$company->taxId = getConfig('COMPANY_TAX_ID');

		$ds = array(
			'doc' => $doc,
			'details' => $details,
			'customer' => $customer,
			'sale' => $sale,
			'company' => $company
		);

		$this->load->view('print/print_quotation', $ds);
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
			$stock = $this->getStock($itemCode, $whsCode, $quotaNo);

			$ds = array(
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
				'Price' => $price,
				'SellPrice' => $price, //$disc->sellPrice,
				'sysSellPrice' => $price, //$disc->sellPrice,
				'sysDiscLabel' => 0, //discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
				'discLabel' => 0, //discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
				'DiscPrcnt' => 0, //$disc->totalDiscPrecent,
				'discAmount' => 0,//$disc->discAmount,
				'totalDiscAmount' => 0, //$disc->totalDiscAmount,
				'VatGroup' => $pd->vat_group,
				'VatRate' => $pd->vat_rate,
				'VatAmount' => get_vat_amount($price, $pd->vat_rate), //get_vat_amount($disc->sellPrice, $pd->vat_rate),
				'TotalVatAmount' => (get_vat_amount($price, $pd->vat_rate) * $qty), //(get_vat_amount($disc->sellPrice, $pd->vat_rate) * $qty),
				'LineTotal' => ($price * $qty),
				'image' => get_image_path($pd->id, 'mini'),
				'rule_id' => NULL,
				'policy_id' => NULL,
				'freeQty' => 0,
				'discType' => 'P'
			);

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
		$price = $this->input->get('Price');

		$pd = $this->products_model->get($itemCode);

		if(! empty($pd))
		{
			//$disc = $this->discount_model->get_item_discount($itemCode, $cardCode, $price, $qty, $payment, $channels, $docDate);

			$ds = array(
				'ItemCode' => $pd->code,
				'ItemName' => $pd->name,
				'Qty' => $qty,
				'UomCode' => $pd->uom_code,
				'UomName' => $pd->uom,
				'Price' => $price,
				'SellPrice' => $price, //$disc->sellPrice,
				'sysSellPrice' => $price, //$disc->sellPrice,
				'sysDiscLabel' => 0, //discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
				'discLabel' => 0, //discountLabel($disc->disc1, $disc->disc2, $disc->disc3, $disc->disc4, $disc->disc5),
				'DiscPrcnt' => 0, //$disc->totalDiscPrecent,
				'discAmount' => 0, //$disc->discAmount,
				'totalDiscAmount' => 0, //$disc->totalDiscAmount,
				'VatGroup' => $pd->vat_group,
				'VatRate' => $pd->vat_rate,
				'VatAmount' => get_vat_amount($price, $pd->vat_rate), //($disc->sellPrice, $pd->vat_rate),
				'TotalVatAmount' => get_vat_amount($price, $pd->vat_rate) * $qty, //(get_vat_amount($disc->sellPrice, $pd->vat_rate) * $qty),
				'LineTotal' => $price * $qty, //($disc->sellPrice * $qty),
				'rule_id' => 0, //$disc->rule_id,
				'policy_id' => NULL, //$disc->policy_id,
				'freeQty' => 0, //$disc->freeQty,
				'discType' => 'P', //$disc->type
			);

		}
		else
		{
			$sc = FALSE;
			$this->error = "Item Not found";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}


	public function get_free_item()
	{
		$rule_id = $this->input->get('rule_id');
		$uid = $this->input->get('uid');
		$freeQty = $this->input->get('freeQty');
		$picked = $this->input->get('picked');
		$priceList = $this->input->get('priceList');
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
				$price = $pd->price; //$this->getPrice($pd->code, $priceList);

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


	public function getStock($ItemCode, $WhsCode, $QuotaNo)
	{
		$this->load->library('api');
		$commit = get_zero($this->quotation_model->get_commit_qty($ItemCode, $QuotaNo));

    $arr = array(
      'OnHand' => 0,
      'Committed' => $commit,
      'QuotaQty' => 0,
      'Available' => 0
    );

		$stock = $this->api->getItemStock($ItemCode, $WhsCode, $QuotaNo);

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

		if( ! $this->quotation_model->delete_detail($id))
		{
			$sc = FALSE;
			set_eror('delete');
		}
		else
		{
			$this->quotation_model->update_doc_total($code);
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

		$order = $this->quotation_model->get($code);

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
			'sq_code',
			'sq_customer',
			'sqNo',
			'soNo',
			'sq_role',
			'sq_sale_id',
			'sq_channels',
			'sq_payment',
			'sq_approval',
			'sq_status',
			'sq_from_date',
			'sq_to_date',
			'onlyMe',
			'sq_user_id',
			'chk_payment',
			'chk_channels'
		);

		return clear_filter($filter);
	}



	public function get_new_code($date = NULL)
  {
    $date = empty($date) ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_QUOTATION');
    $run_digit = getConfig('RUN_DIGIT_QUOTATION');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->quotation_model->get_max_code($pre);

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

}//--- end class


 ?>
