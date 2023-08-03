<?php
class Order_api
{
  private $url;
  protected $ci;
	public $error;
  private $timeout = 0; //--- timeout in seconds;

  public function __construct()
  {
		$this->ci =& get_instance();

    $this->url = getConfig('SAP_API_HOST');
		if($this->url[-1] != '/')
		{
			$this->url .'/';
		}
  }



	public function getCreditBalance($CardCode)
	{
		$arr = array(
			'CardCode' => $CardCode
		);

		$url = $this->url .'GetCreditBalance';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, $this->timeout);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);

		curl_close($curl);

		$rs = json_decode($response);

		if(! empty($rs) && ! empty($rs->status))
		{
			if($rs->status == 'success')
			{
				return $rs->Balance;
			}
		}
    else
    {
      return FALSE;
    }
	}



	public function cancle_sap_order($arr)
	{
		$url = $this->url .'SalesOrder';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
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
				return TRUE;
			}
			else
			{
				$this->error = $rs->error;
			}
		}
    else
    {
      $this->error = "no data";
    }

    return FALSE;
	}


	public function exportOrder($code)
	{
		$testMode = getConfig('TEST') ? TRUE : FALSE;

		if($testMode)
		{
			$arr = array(
				'Status' => 1,
				'DocEntry' => 1,
				'DocNum' => "22000001"
			);

			$this->ci->orders_model->update($code, $arr);
			return TRUE;
		}



		$logJson = getConfig('LOGS_JSON') == 1 ? TRUE : FALSE;

		$sc = TRUE;
		$order = $this->ci->orders_model->get($code);
		$details = $this->ci->orders_model->get_details($code);

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
					"DiscPrcnt" => $rs->is_free == 1 ? 100 : NULL, //round($rs->DiscPrcnt, 2),
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


			$url = getConfig('SAP_API_HOST');
			if($url[-1] != '/')
			{
				$url .'/';
			}

			$url = $url."SalesOrder";

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($curl, CURLOPT_TIMEOUT, 0);
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

					$this->ci->orders_model->update($code, $arr);

				}
				else
				{
					$arr = array(
						'Status' => 3,
						'message' => $rs->error
					);

					$this->ci->orders_model->update($code, $arr);

					$sc = FALSE;
					$this->error = $rs->error;

					if($logJson)
					{
						$this->ci->load->model('rest/logs_model');

						$logs = array(
							'code' => $code,
							'status' => 'error',
							'json' => json_encode($ds)
						);

						$this->ci->logs_model->order_logs($logs);
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Export failed : {$response}";

				$arr = array(
					'Status' => 3,
					'message' => $response
				);

				$this->ci->orders_model->update($code, $arr);
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "No data found";
		}

		return $sc;
	}




	public function syncOrderStatus($OrderCode, $DocEntry, $DocNum)
	{
		$sc = TRUE;
		$this->ci->load->model('orders/orders_model');
		$this->ci->load->model('sync_logs_model');

		$arr = array(
			"DocEntry" => $DocEntry,
			"DocNum" => $DocNum
		);

		$url = $this->url .'SalesOrder';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);

		curl_close($curl);

		$res = json_decode($response);

		if(! empty($res))
		{
			if( ! empty($res->status))
			{
				if($res->status == 'success')
				{
					if(! empty($res->DocStatus))
					{
						if( ! empty($res->Line))
						{
							foreach($res->Line as $rs)
							{
								$row = $this->ci->orders_model->get_detail_by_item_line($OrderCode, $rs->ItemCode, $rs->LineNum);

								if(!empty($row))
								{
									$arr = array(
										'OpenQty' => $rs->OpenQty,
										'LineStatus' => $rs->LineStatus
									);

									$this->ci->orders_model->update_detail($row->id, $arr);
								}
							}
						}

						$arr = array(
							'so_status' => $res->DocStatus,
							'last_sync' => now()
						);

						$this->ci->orders_model->update($OrderCode, $arr);

						$arr = array(
							'code' => $OrderCode,
							'DocStatus' => $res->DocStatus,
							'status' => 'S'
						);

						$this->ci->sync_logs_model->add_status_logs($arr);

            if($res->DocStatus == 'C')
            {
              $this->ci->orders_model->update_all_line_status($OrderCode, 'C');
            }
					}
					else
					{
						$sc = FALSE;
						$this->error = "Empty Document Status";
						$arr = array(
							'code' => $OrderCode,
							'DocStatus' => NULL,
							'status' => 'E',
							'Message' => $response
						);

						$this->ci->sync_logs_model->add_status_logs($arr);
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Empty response. Please sell order sync logs";
				$arr = array(
					'code' => $OrderCode,
					'DocStatus' => NULL,
					'status' => 'E',
					'Message' => $response
				);

				$this->ci->sync_logs_model->add_status_logs($arr);
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Empty response. Please sell order sync logs";
			$arr = array(
				'code' => $OrderCode,
				'DocStatus' => NULL,
				'status' => 'E',
				'Message' => $response
			);

			$this->ci->sync_logs_model->add_status_logs($arr);
		}

		return $sc;
	}



} //--- end class
?>
