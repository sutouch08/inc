<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends PS_Controller
{
	public $menu_code = 'SOODSO';
	public $menu_group_code = 'SO';
  public $menu_sub_group_code = 'ORDER';
	public $title = 'Sales Order';
	public $docType = 'SO';
	public $segment = 4;
	public $readOnly = FALSE;

	public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'orders/orders';
		$this->load->model('orders/orders_model');
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
			'code' => get_filter('code', 'so_code', ''),
			'customer' => get_filter('customer', 'so_customer', ''),
			'doc_num' => get_filter('doc_num', 'so_doc_num', ''),
			'SQNO' => get_filter('SQNO', 'SQNO', ''),
			'project' => get_filter('project', 'project', ''),
			'sale_id' => get_filter('sale_id', 'so_sale_id', 'all'),
			'emp_id' => get_filter('emp_id', 'so_emp_id', 'all'),
			'review' => get_filter('review', 'so_review', 'all'),
			'approval' => get_filter('approval', 'so_approval', 'all'),
			'status' => get_filter('status', 'so_status', 'all'),
			'from_date' => get_filter('from_date', 'so_from_date', ''),
			'to_date' => get_filter('to_date', 'so_to_date', ''),
			'onlyMe' => get_filter('onlyMe', 'onlyMe', 0),
			'user_id' => get_filter('user_id', 'so_user_id', 'all')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			//--- แสดงผลกี่รายการต่อหน้า
			$perpage = get_rows();

			$rows = $this->orders_model->count_rows($filter);
			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$filter['data'] = $this->orders_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$filter['paymentName'] = $this->payment_term_model->get_all();
			$this->pagination->initialize($init);
			$this->load->view('orders/order_list', $filter);
		}
  }




  public function add_new()
  {
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/employee_model');
		$order = array(
			'code' => "",
			'CardCode' => "",
			'CardName' => "",
			'ContactPerson' => "",
			'CntctCode' => "",
			'NumAtCard' => "",
			'Payment' => -1,
			'Phone' => "",
			'PriceList' => "",
			'SlpCode' => "",
			'DocCur' => getConfig('DEFAULT_CURRENCY'),
			'DocRate' => 1.00,
			'DocTotal' => 0.00,
			'SysTotal' => 0.00,
			'DiscPrcnt' => 0.00,
			'DiscAmount' => 0.00,
			'RoundDif' => 0.00,
			'VatSum' => 0.00,
			'PayToCode' => "",
			'ShipToCode' => "",
			'Address' => "",
			'Address2' => "",
			'DocEntry' => NULL,
			'DocNum' => NULL,
			'Status' => NULL,
			'DocDate' => date('Y-m-d'),
			'DocDueDate' => date('Y-m-d'),
			'TextDate' => date('Y-m-d'),
			'SQNO' => "",
			'Owner' => $this->_user->emp_id,
			'Attn1' => "",
			'Attn2' => "",
			'Type' => "",
			'Project' => "",
			'Comments' => "",
			'Status' => -1,
			'Review' => 'P',
			'Approved' => 'P'
		);

		$ds = array(
			'mode' => 'Add',
			'sale_name' => $this->sales_person_model->get_name($this->_user->sale_id),
			'sale_id' => $this->_user->sale_id,
			'owner_name' => $this->employee_model->get_name($this->_user->emp_id),
			'whsCode' => getConfig('DEFAULT_WAREHOUSE'),
			'order' => (object) $order,
			'totalAmount' => 0.00,
			'totalVat' => 0.00
		);

    $this->load->view('orders/order_add', $ds);
  }


	public function create_from_sq($code)
  {
		$this->load->model('orders/quotation_model');
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/customer_address_model');
		$this->load->model('masters/employee_model');

		if($this->pm->can_edit OR $this->pm->can_add)
		{
			$totalAmount = 0;
			$totalVat = 0;

			$order = $this->quotation_model->get($code);

			if( ! empty($order) && $order->Status == 1 && ($order->Approved == 'A' OR $order->Approved == 'S'))
			{
				$order->DocDate = date('Y-m-d');
				$order->DocDueDate = date('Y-m-d');
				$order->TextDate = date('Y-m-d');

				$details = $this->quotation_model->get_details($order->code);

				if(!empty($details))
				{
					foreach($details as $rs)
					{
						$pd = $this->products_model->get($rs->ItemCode);

						$totalAmount += $rs->LineTotal;
						$totalVat += $rs->totalVatAmount;
						$rs->stdPrice = ! empty($pd) ? $pd->price : 0.00;
						$rs->WhsCode = empty($rs->WhsCode) ? $pd->dfWhsCode : $rs->WhsCode;
						$stock = $this->products_model->getItemStock($rs->ItemCode, $rs->WhsCode);
						$rs->BaseDocNum = $order->DocNum;
						$rs->OnHand = empty($stock) ? 0 : $stock->OnHand;
						$rs->Commited = empty($stock) ? 0 : $stock->IsCommited;
						$rs->OnOrder = empty($stock) ? 0 : $stock->OnOrder;
					}
				}

				$order->code = "";
				$order->Status = -1;
				$order->SQNO = $code;
				$ds = array(
					'mode' => 'Add',
					'order' => $order,
					'details' => $details,
					'totalAmount' => $totalAmount,
					'totalVat' => $totalVat,
					'sale_name' => $this->sales_person_model->get_name($order->SlpCode),
					'owner_name' => $this->employee_model->get_name($this->_user->emp_id),
					'whsCode' => getConfig('DEFAULT_WAREHOUSE'),
					'Address' => $this->quotation_model->get_quotation_address($code)
				);

				$this->load->view('orders/order_add', $ds);
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


	public function add()
	{
		$sc = TRUE;
		$ex = 0;

		if($this->pm->can_add)
		{
			$json = file_get_contents('php://input');

			$data = json_decode($json);

			// print_r($data);
			// exit();

			if(! empty($data))
			{
				$hd = $data->header;
				$adr = $hd->Address;
				$details = $data->details;

				$docDate = db_date($hd->DocDate, FALSE);
				$customer = $this->customers_model->get($hd->CardCode);
				$code = $this->get_new_code($docDate);
				$MustReview = getConfig('ORDER_REVIEW') == 1 ? TRUE : FALSE;
				$MustApprove = getConfig('ORDER_APPROVE') == 1 ? TRUE : FALSE;
				$must_approve = $hd->mustApprove == 1 ? 1 : 0;

				if( ! empty($customer))
				{
					$arr = array(
						'code' => $code,
						'CardCode' => $hd->CardCode,
						'CardName' => $hd->CardName,
						'CntctCode' => get_null($hd->CntctCode),
						'ContactPerson' => get_null($hd->ContactPerson),
						'NumAtCard' => get_null($hd->NumAtCard),
						'Attn1' => get_null($hd->Attn1),
						'Attn2' => get_null($hd->Attn2),
						'Type' => get_null($hd->Type),
						'Project' => get_null($hd->Project),
						'Phone' => trim($hd->Phone),
						'PriceList' => get_null($customer->ListNum),
						'SlpCode' => empty($hd->SlpCode) ? $customer->SlpCode : $hd->SlpCode,
						'Payment' => $hd->Payment,
						'DocCur' => getConfig('DEFAULT_CURRENCY'),
						'DocRate' => 1,
						'DocTotal' => $hd->docTotal,
						'SysTotal' => $hd->sysTotal,
						'DocDate' => $docDate,
						'DocDueDate' => db_date($hd->DocDueDate, FALSE),
						'TextDate' => db_date($hd->TextDate, FALSE),
						'SQNO' => get_null($hd->SQNO),
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
						'must_approve' => $must_approve,
						'disc_diff' => $hd->maxDiff,
						'VatGroup' => $hd->VatGroup,
						'VatRate' => $hd->VatRate,
						'Status' => $hd->isDraft == 1 ? -1 : 0,
						'Review' => $MustReview ? 'P' : 'S',
						'Approved' => $MustApprove ? ($must_approve == 1 ? 'P' : 'S') : 'S',
						'OwnerCode' => $hd->OwnerCode
					);

					$this->db->trans_begin();

					$id = $this->orders_model->add($arr);

					if(! $id)
					{
						$sc = FALSE;
						$this->error = "Failed to create sales order";
					}
					else
					{
						if($this->orders_model->drop_address($code))
						{
							$adr->order_code = $code;
							$address = (array) $adr;
							$this->orders_model->add_address($address);
						}

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
									$discLabel = discountLabel($rs->disc1, $rs->disc2, $rs->disc3);

									if($rs->ItemCode == 'FG-Dummy')
									{
										$uom = $this->products_model->get_uom($rs->UomEntry);

										if( ! empty($uom))
										{
											$pd->uom_code = $uom->UomCode;
											$pd->uom_id = $uom->UomEntry;
										}
									}

									$arr = array(
										'order_id' => $id,
										'order_code' => $code,
										'LineNum' => $rs->LineNum,
										'BaseRef' => get_null($rs->BaseRef),
										'BaseEntry' => get_null($rs->BaseEntry),
										'BaseLine' => $rs->BaseLine == "" ? NULL : $rs->BaseLine,
										'ItemCode' => $rs->ItemCode,
										'ItemName' => $rs->ItemName,
										'Description' => $rs->Description,
										'WhsCode' => empty($rs->whsCode) ? $pd->dfWhsCode : $rs->whsCode,
										'Qty' => $rs->Quantity,
										'UomCode' => $pd->uom_code,
										'UomEntry' => $pd->uom_id,
										'Cost' => empty($pd->cost) ? 0.00 : $pd->cost,
										'stdPrice' => empty($pd->price) ? 0.00 : $pd->price,
										'Price' => $rs->Price,
										'SellPrice' => $rs->SellPrice,
										'sysSellPrice' => $rs->sysSellPrice,
										'disc1' => $rs->disc1,
										'disc2' => $rs->disc2,
										'disc3' => $rs->disc3,
										'sysDisc' => $rs->sysDiscLabel,
										'discLabel' => $discLabel,
										'discDiff' => $rs->discDiff,
										'DiscPrcnt' => $rs->DiscPrcnt,
										'discAmount' => $rs->discAmount,
										'totalDiscAmount' => $rs->totalDiscAmount,
										'VatGroup' => $pd->vat_group,
										'VatRate' => $pd->vat_rate,
										'VatAmount' => $rs->VatAmount,
										'totalVatAmount' => $rs->totalVatAmount,
										'LineTotal' => $rs->LineTotal,
										'LineSysTotal' => $rs->LineSysTotal,
										'user_id' => $this->_user->id,
										'uname' => $this->_user->uname,
										'sale_team' => $rs->sale_team,
										'TreeType' => $rs->TreeType,
										'uid' => $rs->uid,
										'father_uid' => empty($rs->father_uid) ? NULL : $rs->father_uid,
										'LineText' => get_null(trim($rs->LineText))
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
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname,
							'docType' => 'SO',
							'docNum' => $code,
							'action' => 'add',
							'ip_address' => $_SERVER['REMOTE_ADDR']
						);

						$this->user_model->add_logs($arr);
					}
					else
					{
						$this->db->trans_rollback();
					}

					if(getConfig('ORDER_AUTO_INTERFACE'))
					{
						//-- 1 = Interface ทันที  0 = ต้องกดส่งทีหลัง

						if($hd->isDraft == 0 && ! $MustReview && ! $MustApprove)
						{
							$this->load->library('api');

							if( ! $this->api->exportSO($code))
							{
								$ex = 1;
								$this->error = "บันทึกเอกสารสำเร็จแต่ส่งข้อมูลไป SAP ไม่สำเร็จ กรุณากดส่งข้อมูลไป SAP อีกครั้งภายหลัง";
							}
						}
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

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'failed',
			'message' => $sc === TRUE ? ($ex == 1 ? $this->error : 'success') : $this->error,
			'code' => $sc === TRUE ? $code : NULL,
			'ex' => $ex
		);

		echo json_encode($arr);
	}


	public function is_document_avalible()
  {
    $code = $this->input->get('code');
    $uuid = $this->input->get('uuid');
    if( ! $this->orders_model->is_document_avalible($code, $uuid))
    {
      echo "not_available";
    }
    else
    {
      echo "available";
    }
  }


	public function update_uuid()
  {
    $sc = TRUE;
    $code = trim($this->input->post('code'));
    $uuid = trim($this->input->post('uuid'));

    if( ! empty($uuid))
    {
      return $this->orders_model->update_uuid($code, $uuid);
    }
  }


  public function edit($code, $uuid)
  {
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/customer_address_model');
		$this->load->model('masters/employee_model');

		if($this->pm->can_edit OR $this->pm->can_add)
		{
			$totalAmount = 0;
			$totalVat = 0;

			$order = $this->orders_model->get($code);

			if( ! empty($order) && $order->Status != 1)
			{
				$details = $this->orders_model->get_details($order->code);

				if(!empty($details))
				{
					foreach($details as $rs)
					{
						$pd = $this->products_model->get($rs->ItemCode);

						$totalAmount += $rs->LineTotal;
						$totalVat += $rs->totalVatAmount;
						$rs->stdPrice = ! empty($pd) ? $pd->price : 0.00;
						$rs->WhsCode = empty($rs->WhsCode) ? $pd->dfWhsCode : $rs->WhsCode;
						$stock = $this->products_model->getItemStock($rs->ItemCode, $rs->WhsCode);
						$rs->OnHand = empty($stock) ? 0 : $stock->OnHand;
						$rs->Commited = empty($stock) ? 0 : $stock->IsCommited;
						$rs->OnOrder = empty($stock) ? 0 : $stock->OnOrder;
					}
				}

				$ds = array(
					'mode' => 'Edit',
					'order' => $order,
					'details' => $details,
					'totalAmount' => $totalAmount,
					'totalVat' => $totalVat,
					'sale_name' => $this->sales_person_model->get_name($order->SlpCode),
					'owner_name' => $this->employee_model->get_name($this->_user->emp_id),
					'whsCode' => getConfig('DEFAULT_WAREHOUSE'),
					'Address' => $this->orders_model->get_order_address($code)
				);

				$this->orders_model->update_uuid($code, $uuid);

				$this->load->view('orders/order_edit', $ds);
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
		$ex = 0;

		if($this->pm->can_edit)
		{
			$json = file_get_contents('php://input');

			$data = json_decode($json);

			if(! empty($data))
			{
				$hd = $data->header;
				$adr = $hd->Address; //-- addres -> table order_address
				$details = $data->details;

				if(!empty($hd->code))
				{
					$docDate = db_date($hd->DocDate, FALSE);
					$customer = $this->customers_model->get($hd->CardCode);
					$code = $hd->code;
					$MustReview = getConfig('QUOTATION_REVIEW') == 1 ? TRUE : FALSE;
					$MustApprove = getConfig('QUOTATION_APPROVE') == 1 ? TRUE : FALSE;
					$must_approve = $hd->mustApprove == 1 ? 1 : 0;

					$order = $this->orders_model->get($code);

					if(! empty($order))
					{
						if($order->Status == -1 OR $order->Status == 0 OR $order->Status == 3)
						{
							if( ! empty($customer))
							{
								$arr = array(
									'code' => $code,
									'CardCode' => $hd->CardCode,
									'CardName' => $hd->CardName,
									'CntctCode' => get_null($hd->CntctCode),
									'ContactPerson' => get_null($hd->ContactPerson),
									'NumAtCard' => get_null($hd->NumAtCard),
									'Attn1' => get_null($hd->Attn1),
									'Attn2' => get_null($hd->Attn2),
									'Type' => get_null($hd->Type),
									'Project' => get_null($hd->Project),
									'Type' => get_null($hd->Type),
									'Phone' => trim($hd->Phone),
									'PriceList' => get_null($customer->ListNum),
									'SlpCode' => empty($hd->SlpCode) ? $customer->SlpCode : $hd->SlpCode,
									'Payment' => $hd->Payment,
									'DocCur' => getConfig('DEFAULT_CURRENCY'),
									'DocRate' => 1,
									'DocTotal' => $hd->docTotal,
									'SysTotal' => $hd->sysTotal,
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
									'must_approve' => $MustApprove ? $must_approve : 0,
									'disc_diff' => $hd->maxDiff,
									'VatGroup' => $hd->VatGroup,
									'VatRate' => $hd->VatRate,
									'Status' => $hd->isDraft == 1 ? -1 : 0,
									'Review' => $MustReview ? 'P' : 'S',
									'ReviewBy' => NULL,
									'Approved' => $MustApprove ? ($must_approve == 1 ? 'P' : 'S') : 'S',
									'Approver' => NULL,
									'OwnerCode' => $hd->OwnerCode
								);

								$this->db->trans_begin();

								if(! $this->orders_model->update($code, $arr))
								{
									$sc = FALSE;
									$this->error = "Update Order failed";
								}
								else
								{
									if($this->orders_model->drop_address($code))
									{
										$adr->order_code = $code;
										$address = (array) $adr;

										$this->orders_model->add_address($address);
									}

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
													$discLabel = discountLabel($rs->disc1, $rs->disc2, $rs->disc3);

													if($rs->ItemCode == 'FG-Dummy')
													{
														$uom = $this->products_model->get_uom($rs->UomEntry);

														if( ! empty($uom))
														{
															$pd->uom_code = $uom->UomCode;
															$pd->uom_id = $uom->UomEntry;
														}
													}

													$arr = array(
														'order_id' => $order->id,
														'order_code' => $code,
														'LineNum' => $rs->LineNum,
														'ItemCode' => $rs->ItemCode,
														'ItemName' => $rs->ItemName,
														'Description' => $rs->Description,
														'WhsCode' => empty($rs->whsCode) ? $pd->dfWhsCode : $rs->whsCode,
														'Qty' => $rs->Quantity,
														'UomCode' => $pd->uom_code,
														'UomEntry' => $pd->uom_id,
														'Cost' => empty($pd->cost) ? 0.00 : $pd->cost,
														'stdPrice' => empty($pd->price) ? 0.00 : $pd->price,
														'Price' => $rs->Price,
														'SellPrice' => $rs->SellPrice,
														'sysSellPrice' => $rs->sysSellPrice,
														'disc1' => $rs->disc1,
														'disc2' => $rs->disc2,
														'disc3' => $rs->disc3,
														'sysDisc' => $rs->sysDiscLabel,
														'discLabel' => $discLabel,
														'discDiff' => $rs->discDiff,
														'DiscPrcnt' => $rs->DiscPrcnt,
														'discAmount' => $rs->discAmount,
														'totalDiscAmount' => $rs->totalDiscAmount,
														'VatGroup' => $pd->vat_group,
														'VatRate' => $pd->vat_rate,
														'VatAmount' => $rs->VatAmount,
														'totalVatAmount' => $rs->totalVatAmount,
														'LineTotal' => $rs->LineTotal,
														'LineSysTotal' => $rs->LineSysTotal,
														'user_id' => $this->_user->id,
														'uname' => $this->_user->uname,
														'sale_team' => $rs->sale_team,
														'TreeType' => $rs->TreeType,
														'uid' => $rs->uid,
														'father_uid' => empty($rs->father_uid) ? NULL : $rs->father_uid,
														'LineText' => get_null(trim($rs->LineText))
													);

													if(! $this->orders_model->add_detail($arr))
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
										'user_id' => $this->_user->id,
										'uname' => $this->_user->uname,
										'docType' => 'SQ',
										'docNum' => $code,
										'action' => 'edit',
										'ip_address' => $_SERVER['REMOTE_ADDR']
									);

									$this->user_model->add_logs($arr);

								}
								else
								{
									$this->db->trans_rollback();
								}

								if(getConfig('QUOTATION_AUTO_INTERFACE'))
								{
									//-- 1 = Interface ทันที  0 = ต้องกดส่งทีหลัง

									if($hd->isDraft == 0 && $must_approve == 0 OR (! $MustReview && ! $MustApprove))
									{
										$this->load->library('api');

										if( ! $this->api->exportSQ($code))
										{
											$ex = 1;
											$this->error = "บันทึกเอกสารสำเร็จแต่ส่งข้อมูลไป SAP ไม่สำเร็จ กรุณากดส่งข้อมูลไป SAP อีกครั้งภายหลัง";
										}
									}
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

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'failed',
			'message' => $sc === TRUE ? ($ex == 1 ? $this->error : 'success') : $this->error,
			'code' => $sc === TRUE ? $code : NULL,
			'ex' => $ex
		);

		echo json_encode($arr);
	}


	public function duplicate_order()
	{
		$sc = TRUE;
		$sqCode = $this->input->post('code');

		if($this->pm->can_add)
		{
			$hd = $this->orders_model->get($sqCode);

			if( ! empty($hd))
			{
				$details = $this->orders_model->get_details($sqCode);
				$adr = $this->orders_model->get_order_address($sqCode);
				$MustReview = getConfig('QUOTATION_REVIEW') == 1 ? TRUE : FALSE;
				$MustApprove = getConfig('QUOTATION_APPROVE') == 1 ? TRUE : FALSE;

				$code = $this->get_new_code();
				$arr = array(
					'code' => $code,
					'CardCode' => $hd->CardCode,
					'CardName' => $hd->CardName,
					'CntctCode' => get_null($hd->CntctCode),
					'ContactPerson' => get_null($hd->ContactPerson),
					'NumAtCard' => get_null($hd->NumAtCard),
					'Attn1' => get_null($hd->Attn1),
					'Attn2' => get_null($hd->Attn2),
					'Type' => get_null($hd->Type),
					'Project' => get_null($hd->Project),
					'Type' => get_null($hd->Type),
					'Phone' => trim($hd->Phone),
					'PriceList' => get_null($hd->PriceList),
					'SlpCode' => $hd->SlpCode,
					'Payment' => $hd->Payment,
					'DocCur' => $hd->DocCur,
					'DocRate' => $hd->DocRate,
					'DocTotal' => $hd->DocTotal,
					'SysTotal' => $hd->SysTotal,
					'DocDate' => date('Y-m-d'),
					'DocDueDate' => date('Y-m-d'),
					'TextDate' => date('Y-m-d'),
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
					'Comments' => get_null($hd->Comments),
					'must_approve' => $hd->must_approve,
					'disc_diff' => $hd->disc_diff,
					'VatGroup' => $hd->VatGroup,
					'VatRate' => $hd->VatRate,
					'Status' => -1,
					'is_duplicate' => 1,
					'OriginalSQ' => $sqCode,
					'Review' => $MustReview ? 'P' : 'S',
					'Approved' => $MustApprove ? ($hd->must_approve == 1 ? 'P' : 'S') : 'S',
					'OwnerCode' => $this->_user->emp_id
				);

				$this->db->trans_begin();
				$id = $this->orders_model->add($arr);

				if( ! empty($id))
				{
					if( ! empty($adr))
					{
						$address = array(
							'order_code' => $code,
							'bAddress' => $adr->bAddress,
							'bAddress2' => $adr->bAddress2,
							'bStreet' => $adr->bStreet,
							'bStreetNo' => $adr->bStreetNo,
							'bBlock' => $adr->bBlock,
							'bCity' => $adr->bCity,
							'bCounty' => $adr->bCounty,
							'bZipCode' => $adr->bZipCode,
							'bCountry' => $adr->bCountry,
							'sAddress' => $adr->sAddress,
							'sAddress2' => $adr->sAddress2,
							'sStreet' => $adr->sStreet,
							'sStreetNo' => $adr->sStreetNo,
							'sBlock' => $adr->sBlock,
							'sCity' => $adr->sCity,
							'sCounty' => $adr->sCounty,
							'sZipCode' => $adr->sZipCode,
							'sCountry' => $adr->sCountry
						);

						$this->orders_model->add_address($address);
					}

					if( ! empty($details))
					{
						foreach($details as $rs)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr = array(
								'order_id' => $id,
								'order_code' => $code,
								'LineNum' => $rs->LineNum,
								'ItemCode' => $rs->ItemCode,
								'ItemName' => $rs->ItemName,
								'Description' => $rs->Description,
								'WhsCode' => $rs->WhsCode,
								'Qty' => $rs->Qty,
								'UomCode' => $rs->UomCode,
								'UomEntry' => $rs->UomEntry,
								'Cost' => $rs->Cost,
								'Price' => $rs->Price,
								'SellPrice' => $rs->SellPrice,
								'sysSellPrice' => $rs->sysSellPrice,
								'disc1' => $rs->disc1,
								'disc2' => $rs->disc2,
								'disc3' => $rs->disc3,
								'sysDisc' => $rs->sysDisc,
								'discLabel' => $rs->discLabel,
								'discDiff' => $rs->discDiff,
								'DiscPrcnt' => $rs->DiscPrcnt,
								'discAmount' => $rs->discAmount,
								'totalDiscAmount' => $rs->totalDiscAmount,
								'VatGroup' => $rs->VatGroup,
								'VatRate' => $rs->VatRate,
								'VatAmount' => $rs->VatAmount,
								'totalVatAmount' => $rs->totalVatAmount,
								'LineTotal' => $rs->LineTotal,
								'LineSysTotal' => $rs->LineSysTotal,
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'sale_team' => $rs->sale_team,
								'LineText' => $rs->LineText,
								'TreeType' => $rs->TreeType,
								'uid' => $rs->uid,
								'father_uid' => empty($rs->father_uid) ? NULL : $rs->father_uid
							);

							if(! $this->orders_model->add_detail($arr))
							{
								$sc = FALSE;
								$this->error = "Insert detail failed";
							}
						}
					}

					if($sc === TRUE)
					{
						$this->db->trans_commit();

						$arr = array(
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname,
							'docType' => 'SQ',
							'docNum' => $code,
							'action' => 'add',
							'ip_address' => $_SERVER['REMOTE_ADDR']
						);

						$this->user_model->add_logs($arr);
					}
					else
					{
						$this->db->trans_rollback();
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Duplicate Quotation failed";
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

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'failed',
			'message' => $sc === TRUE ? 'success' : $this->error,
			'code' => $sc === TRUE ? $code : NULL
		);

		echo json_encode($arr);
	}


	public function approve()
	{
		$sc = TRUE;
		$ex = 0;
		$code = trim($this->input->post('code'));

		if(!empty($code))
		{
			$order = $this->orders_model->get($code);

			if( ! empty($order))
			{
				if( $order->Status == 0 )
				{
					if($order->Approved == 'P' OR $order->Approved == 'S')
					{
						$this->load->model('users/approver_model');
						$ap = $this->approver_model->get_rule_by_user_id($this->_user->id, $this->docType);

						if( ! empty($ap))
						{
							if($ap->maxDisc < $order->disc_diff)
							{
								$sc = FALSE;
								$this->error = "ไม่สามารถอนุมัติได้ ต้องการสิทธิ์อนุมัติส่วนลดมากกว่า {$order->disc_diff} คุณสามารถอนุมัติได้ไม่เกิน {$ap->maxDisc}";
							}

							if($ap->maxAmount < $order->DocTotal)
							{
								$sc = FALSE;
								$this->error = "ไม่สามารถอนุมัติได้ ต้องการสิทธิ์อนุมัติมูลค่ามากกว่า ".number($order->DocTotal, 2)." คุณสามารถอนุมัติได้ไม่เกิน ".number($ap->maxAmount, 2);
							}

							if($sc === TRUE)
							{
								$arr = array(
									'Approved' => 'A',
									'Approver' => $this->_user->uname
								);

								if( ! $this->orders_model->update($code, $arr))
								{
									$sc = FALSE;
									set_error('update');
								}
							}

							if($sc === TRUE)
							{
								$arr = array(
									'user_id' => $this->_user->id,
									'uname' => $this->_user->uname,
									'docType' => 'SQ',
									'docNum' => $code,
									'action' => 'approve',
									'ip_address' => $_SERVER['REMOTE_ADDR']
								);

								$this->user_model->add_logs($arr);
							}

							if($sc === TRUE)
							{
								if(getConfig('QUOTATION_AUTO_INTERFACE'))
								{
									//-- 1 = Interface ทันที  0 = ต้องกดส่งทีหลัง

									$this->load->library('api');

									if( ! $this->api->exportSQ($code))
									{
										$ex = 1;
										$this->error = "บันทึกเอกสารสำเร็จแต่ส่งข้อมูลไป SAP ไม่สำเร็จ กรุณากดส่งข้อมูลไป SAP อีกครั้งภายหลัง";
									}
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
						if($order->Approved == 'A')
						{
							$this->error = "Document already approved by {$order->Approver}";
						}
						elseif($order->Approved == 'R')
						{
							$this->error = "Unable to approve. This document already rejected by {$order->Approver}";
						}
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
				$this->error = "Invalid document number";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'failed',
			'message' => $sc === TRUE ? ($ex == 1 ? $this->error : 'success') : $this->error,
			'ex' => $ex
		);

		echo json_encode($arr);
	}



	public function reject()
	{
		$sc = TRUE;
		$code = trim($this->input->post('code'));
		$reason = trim($this->input->post('reason'));

		if(!empty($code))
		{
			$order = $this->orders_model->get($code);

			if( ! empty($order))
			{
				if($order->Status == 0)
				{
					if($order->Approved == 'P' OR $order->Approved == 'S')
					{
						$arr = array(
							'Approved' => 'R',
							'Approver' => $this->_user->uname,
							'message' => $reason
						);

						if( ! $this->orders_model->update($code, $arr))
						{
							$sc = FALSE;
							$this->error = "Update failed";
						}

						if($sc === TRUE)
						{
							$arr = array(
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'docType' => $this->docType,
								'docNum' => $code,
								'action' => 'reject',
								'ip_address' => $_SERVER['REMOTE_ADDR']
							);

							$this->user_model->add_logs($arr);
						}
					}
					else
					{
						$sc = FALSE;
						if($order->Approved == 'A')
						{
							$this->error = "Unable to reject. This document already approved by {$order->Approver}";
						}

						if($order->Approved == 'R')
						{
							$this->error = "Document already rejected by {$order->Approver}";
						}
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
				$this->error = "Invalid document number";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}


	public function confirm_review()
	{
		$sc = TRUE;
		$ex = 0;
		$code = trim($this->input->post('code'));
		$MustApprove = getConfig('QUOTATION_APPROVE') == 1 ? TRUE : FALSE;

		if(!empty($code))
		{
			$order = $this->orders_model->get($code);

			if( ! empty($order))
			{
				if($order->Status == 0)
				{
					if($order->Review == 'P' OR $order->Review == 'S')
					{
						$arr = array(
							'Review' => 'A',
							'ReviewBy' => $this->_user->uname
						);

						if( ! $this->orders_model->update($code, $arr))
						{
							$sc = FALSE;
							set_eror('update');
						}

						if($sc === TRUE)
						{
							$arr = array(
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'docType' => 'SQ',
								'docNum' => $code,
								'action' => 'review',
								'ip_address' => $_SERVER['REMOTE_ADDR']
							);

							$this->user_model->add_logs($arr);
						}

						if(getConfig('QUOTATION_AUTO_INTERFACE'))
						{
							//-- 1 = Interface ทันที  0 = ต้องกดส่งทีหลัง

							if($sc === TRUE && (! $MustApprove OR $order->must_approve == 0))
							{
								$this->load->library('api');

								if( ! $this->api->exportSQ($code))
								{
									$ex = 1;
									$this->error = "บันทึกเอกสารสำเร็จแต่ส่งข้อมูลไป SAP ไม่สำเร็จ กรุณากดส่งข้อมูลไป SAP อีกครั้งภายหลัง";
								}
							}
						}
					}
					else
					{
						$sc = FALSE;

						if($order->Review == 'A')
						{
							$this->error = "This document already confirmed by {$order->ReviewBy}.";
						}
						elseif($order->Review == 'R')
						{
							$this->error = "Document already rejected by {$order->ReviewBy}";
						}
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
				$this->error = "Invalid Document Number";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$arr = array(
			'status' => $sc === TRUE ? 'success' : 'failed',
			'message' => $sc === TRUE ? ($ex == 1 ? $this->error : 'success') : $this->error,
			'ex' => $ex
		);

		echo json_encode($arr);
	}


	public function reject_review()
	{
		$sc = TRUE;
		$code = trim($this->input->post('code'));
		$reason = trim($this->input->post('reason'));

		if(!empty($code))
		{
			$order = $this->orders_model->get($code);

			if( ! empty($order))
			{
				if($order->Status == 0)
				{
					if($order->Review == 'P' OR $order->Review == 'S')
					{
						$arr = array(
							'Review' => 'R',
							'ReviewBy' => $this->_user->uname,
							'message' => $reason
						);

						if( ! $this->orders_model->update($code, $arr))
						{
							$sc = FALSE;
							set_eror('update');
						}

						if($sc === TRUE)
						{
							$arr = array(
								'user_id' => $this->_user->id,
								'uname' => $this->_user->uname,
								'docType' => 'SQ',
								'docNum' => $code,
								'action' => 'reject_review',
								'ip_address' => $_SERVER['REMOTE_ADDR']
							);

							$this->user_model->add_logs($arr);
						}
					}
					else
					{
						$sc = FALSE;

						if($order->Review == 'A')
						{
							$this->error = "Unable to reject. This document already confirmed.";
						}
						elseif($order->Review == 'R')
						{
							$this->error = "Document already rejected by {$order->ReviewBy}";
						}
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
				$this->error = "Invalid Document Number";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}



	public function view_detail($code)
	{
		$this->load->model('users/approver_model');
		$this->load->model('masters/sales_person_model');
		$this->load->model('masters/employee_model');

		$totalAmount = 0;
		$totalVat = 0;

		$order = $this->orders_model->get_header($code);

		if( ! empty($order))
		{
			$details = $this->orders_model->get_details($order->code);

			if(!empty($details))
			{
				foreach($details as $rs)
				{
					if( ! empty($rs->LineText))
					{
						$rs->Description .= empty($rs->Description) ? nl2br($rs->LineText) : "<br>".nl2br($rs->LineText);
					}

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

			$this->load->view('orders/order_view', $ds);
		}
		else
		{
			$this->page_error();
		}
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
				$exists = FALSE;

				if($order->Status == 1)
				{
					$docNum = $this->orders_model->getSapDocNum($code);

					if( ! empty($docNum))
					{
						$exists = TRUE;
						$sc = FALSE;
						$this->error = "กรุณายกเลิกใบเสนอราคาเลขที่ {$docNum} ใน SAP ก่อนทำการยกเลิกบน WEB";
					}
				}

				if($exists === FALSE && ($order->Status == -1 OR $order->Status == 0 OR $order->Status == 3))
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
							'user_id' => $this->_user->id,
							'uname' => $this->_user->uname,
							'docType' => 'SQ',
							'docNum' => $code,
							'action' => 'cancel',
							'ip_address' => $_SERVER['REMOTE_ADDR']
						);

						$this->user_model->add_logs($arr);
					}
					else
					{
						$this->db->trans_rollback();
					}
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
				$this->load->library('api');

				if( ! $this->api->exportSQ($code))
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
		$this->load->model('users/signature_model');

		$this->load->library('printer');
		$doc = $this->orders_model->get($code);
		$details = $this->orders_model->get_un_child_details($code);
		$doc->total_rows = 1;
		if( ! empty($details))
		{
			foreach($details as $rs)
			{
				$rs->use_rows = 1;

				if( ! empty($rs->LineText))
				{
					$lines = substr_count( $rs->LineText, "\n" );
					$rs->Description .= empty($rs->Description) ? nl2br($rs->LineText) : "<br>".nl2br($rs->LineText);
					$rs->use_rows += $lines;
				}

				if($rs->TreeType == 'S')
				{
					$childs = $this->orders_model->get_childs_row($code, $rs->uid);

					if( ! empty($childs))
					{
						$lineAmount = 0.00;
						$price = 0.00;
						$qty = number($rs->Qty, 2);

						foreach($childs as $ch)
						{
							$rs->Description .= "<br>".$ch->Description;
							if(! empty($ch->LineText))
							{
								$lines = substr_count( $ch->LineText, "\n" );
								$rs->Description .= empty($rs->Description) ? nl2br($ch->LineText) : "<br>".nl2br($ch->LineText);
								$rs->use_rows += $lines;
							}

							$price += $ch->SellPrice;
							$lineAmount += $ch->LineTotal;
							$qty .= "<br>".number($ch->Qty, 2);
							$rs->use_rows++;
						}

						$rs->Qty = $qty;
						$rs->Price = $price;
						$rs->SellPrice = $price;
						$rs->LineTotal = $lineAmount;
					}
				}

				$doc->total_rows += $rs->use_rows;
			}
		}

		$customer = $this->customers_model->get($doc->CardCode);
		$sale = $this->sales_person_model->get($doc->SlpCode);

		$owner = empty($doc->OwnerCode) ? NULL : $this->employee_model->get($doc->OwnerCode);
		if( ! empty($owner))
		{
			$owner->signature = $this->signature_model->get_signature($owner->id);
		}

		$user = $this->user_model->get_by_uname($doc->Approver);
		if( ! empty($user))
		{
			$doc->approver_signature = $this->signature_model->get_signature($user->emp_id);
		}

		$approv_emp = empty($user) ? NULL : $user->emp_id;

		$doc->approve_emp_id = $approv_emp;

		//$doc->term = empty($payment) ? 0 : $payment->term;
		$company = new stdClass();
		$company->name = getConfig('COMPANY_FULL_NAME');
		$company->address1 = getConfig('COMPANY_ADDRESS1');
		$company->address2 = getConfig('COMPANY_ADDRESS2');
		$company->postcode = getConfig('COMPANY_POST_CODE');
		$company->phone = getConfig('COMPANY_PHONE');
		$company->fax = getConfig('COMPANY_FAX');
		$company->taxId = getConfig('COMPANY_TAX_ID');
		$company->website = getConfig('COMPANY_WEBSITE');
		$company->line = getConfig('COMPANY_LINE');
		$company->facebook = getConfig('COMPANY_FACEBOOK');

		$ds = array(
			'doc' => $doc,
			'details' => $details,
			'customer' => $customer,
			'sale' => $sale,
			'owner' => $owner,
			'company' => $company
		);

		$this->load->view('print/print_order', $ds);
	}



	public function get_item_data()
	{

		$sc = TRUE;
		$itemCode = $this->input->get('ItemCode');
		$cardCode = $this->input->get('CardCode');
		$priceList = $this->input->get('PriceList');
		$docDate = db_date($this->input->get('DocDate'));
		$payment = $this->input->get('Payment');
		$qty = 1;

		$pd = $this->products_model->get($itemCode, $priceList);

		if(! empty($pd))
		{
			$price = $pd->price;

			$disc = $this->discount_model->getDiscountByManufacture($pd->FirmCode);

			if(empty($disc))
			{
				$disc = $this->discount_model->get_item_discount($itemCode);
			}

			$disAmount = ($disc * 0.01) * $price;
			$sellPrice = $price - $disAmount;
			$stock = $this->products_model->getItemStock($pd->code, $pd->dfWhsCode);

			$ds = array(
				'ItemCode' => $pd->code,
				'ItemName' => $pd->name,
				'dfWhsCode' => $pd->dfWhsCode,
				'OnHand' => empty($stock) ? number($pd->OnHand) : number($stock->OnHand),
				'Commited' => empty($stock) ? number($pd->IsCommited) : number($stock->IsCommited),
				'OnOrder' => empty($stock) ? number($pd->OnOrder) : number($stock->OnOrder),
				'Qty' => $qty,
				'UomCode' => $pd->uom_code,
				'UomName' => $pd->uom,
				'Price' => $price, //--- ราคาตาม price list
				'SellPrice' => $sellPrice, //--- ราคาหลังส่วนลด
				'sysDiscLabel' => discountLabel($disc),
				'disc1' => $disc,
				'disc2' => NULL,
				'disc3' => NULL,
				'DiscPrcnt' => $disc,
				'discAmount' => $disAmount,
				'totalDiscAmount' => $disAmount * $qty,
				'VatGroup' => $pd->vat_group,
				'VatRate' => $pd->vat_rate,
				'VatAmount' => get_vat_amount($sellPrice, $pd->vat_rate),
				'TotalVatAmount' => (get_vat_amount($sellPrice, $pd->vat_rate) * $qty),
				'LineTotal' => ($sellPrice * $qty)
			);

		}
		else
		{
			$sc = FALSE;
			$this->error = "Item Not found";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}


	public function get_stock()
	{
		$ItemCode = $this->input->get('ItemCode');
		$WhsCode = $this->input->get('WhsCode');

		$stock = $this->products_model->getItemStock($ItemCode, $WhsCode);

		$arr = array(
			'ItemCode' => $ItemCode,
			'WhsCode' => $WhsCode,
			'OnHand' => (empty($stock) ? 0 : number($stock->OnHand)),
			'Commited' => (empty($stock) ? 0 : number($stock->IsCommited)),
			'OnOrder' => (empty($stock) ? 0 : number($stock->OnOrder))
		);

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
			$cnt = $this->customers_model->get_contact($code);
			$rs->CntctPrsn = empty($cnt) ? $rs->CntctPrsn : $cnt->contactName;
			$rs->CntctCode = empty($cnt) ? NULL : $cnt->CntctCode;

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
				'street' => get_empty_text($adr->Street),
				'streetNo' => get_empty_text($adr->StreetNo),
				'block' => get_empty_text($adr->Block),
				'city' => get_empty_text($adr->City),
				'county' => get_empty_text($adr->County),
				'country' => get_empty_text($adr->Country),
				'zipCode' => get_empty_text($adr->ZipCode),
				'state' => get_empty_text($adr->State)
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
				'street' => get_empty_text($adr->Street),
				'streetNo' => get_empty_text($adr->StreetNo),
				'block' => get_empty_text($adr->Block),
				'city' => get_empty_text($adr->City),
				'county' => get_empty_text($adr->County),
				'country' => get_empty_text($adr->Country),
				'zipCode' => get_empty_text($adr->ZipCode),
				'state' => get_empty_text($adr->State)
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

	public function get_reject_message()
	{
		$code = $this->input->get('code');

		$order = $this->orders_model->get($code);

		if(!empty($order))
		{
			$uname = $order->Review == 'R' ? $order->ReviewBy : ($order->Approved == 'R' ? $order->Approver : NULL);

			$arr = array(
				'U_WEBORDER' => $code,
				'CardCode' => $order->CardCode,
				'CardName' => $order->CardName,
				'date_upd' => thai_date($order->date_upd, TRUE),
				'Message' => $order->message,
				'rejected_by' => $this->user_model->get_name($uname)
			);

			echo json_encode($arr);
		}
		else
		{
			echo "No data";
		}
	}

	public function get_logs()
	{
		$sc = TRUE;
		$ds = array();
		$code = $this->input->get('code');

		if( ! empty($code))
		{
			$logs = $this->orders_model->get_logs($code);

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

	public function get_quotation_list()
	{
		$filter = array(
			'SQNO' => get_null($this->input->get('SQNO')),
			'CardCode' => get_null($this->input->get('CardCode')),
			'fromDate' => $this->input->get('fromDate'),
			'toDate' => $this->input->get('toDate')
		);

		$list = $this->orders_model->get_quotation_list($filter, 50);

		if( ! empty($list))
		{
			foreach($list as $rs)
			{
				$rs->DocDate = thai_date($rs->DocDate);
			}

			echo json_encode($list);
		}
		else
		{
			echo "";
		}
	}


	public function get_sap_quotation_details()
	{
		$sc = TRUE;
		$ds = json_decode($this->input->post('sq_list'));

		if( ! empty($ds))
		{
			$details = $this->orders_model->get_sap_quotation_details($ds);

			if( ! empty($details))
			{
				$father_uid = 0;

				foreach($details as $rs)
				{
					$uid = genUid();
					$father_uid = $rs->TreeType == 'S' ? $uid : 0;
					$discAmount = $rs->stdPrice > $rs->SellPrice ? $rs->stdPrice - $rs->SellPrice : 0.00;
					$stock = $rs->TreeType == 'S' ? 0 : $this->products_model->getItemStock($rs->ItemCode, $rs->WhsCode);

					$rs->uid = $uid;
					$rs->father_uid = $father_uid;
					$rs->Rate = empty($rs->Rate) ? 1 : $rs->Rate;
					$rs->discAmount = $discAmount;
					$rs->totalDiscAmount = $discAmount * $rs->Qty;
					$rs->LineTotal = $rs->SellPrice * $rs->Qty;
					$rs->VatAmount = get_vat_amount($rs->SellPrice, $rs->VatRate);
					$rs->TotalVatAmount = (get_vat_amount($rs->SellPrice * $rs->VatRate) * $rs->Qty);
					$rs->OnHand = empty($stock) ? 0 : number($stock->OnHand); 
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Item not found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing Required Parameter";
		}

		echo $sc === TRUE ? json_encode($details) : $this->error;
	}


	public function clear_filter()
	{
		$filter = array(
			'so_code',
			'so_customer',
			'so_doc_num',
			'originalSQ',
			'project',
			'so_sale_id',
			'so_emp_id',
			'so_review',
			'so_approval',
			'so_status',
			'so_from_date',
			'so_to_date',
			'onlyMe',
			'so_user_id'
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

}//--- end class


 ?>
