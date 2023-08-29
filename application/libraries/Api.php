<?php
class Api
{
  private $url;
  protected $ci;
	public $error;
  protected $timeout = 0; //-- time out in seconds;

  public function __construct()
  {
		$this->ci =& get_instance();


    $this->url = getConfig('SAP_API_HOST');
		if($this->url[-1] != '/')
		{
			$this->url .'/';
		}
  }


  public function exportSQ($code)
	{
    $this->ci->load->model('rest/logs_model');
    $logJson = getConfig('LOGS_JSON') == 1 ? TRUE : FALSE;
    $testMode = getConfig('TEST') ? TRUE : FALSE;

		$sc = TRUE;
		$order = $this->ci->quotation_model->get($code);
		$details = $this->ci->quotation_model->get_details($code);
    $dfWhsCode = getConfig('DEFAULT_WAREHOUSE');

		if(! empty($order) && ! empty($details))
		{
      $ds = array();
			$sq = array(
				"WEBNumber" => $order->code,
				"CardCode" => $order->CardCode,
				"CardName" => $order->CardName,
				"SlpCode" => intval($order->SlpCode),
        "SalesEmployee" => intval($order->SlpCode),
				"GroupNum" => intval($order->Payment),
				"DocCur" => $order->DocCur,
				"DocRate" => round($order->DocRate, 2),
				"DocTotal" => round($order->DocTotal, 2),
				"DocDate" => $order->DocDate,
				"DocDueDate" => $order->DocDueDate,
				"TaxDate" => $order->TextDate,
        "NumAtCard" => $order->NumAtCard,
        "CntctCode" => $order->CntctCode,
        "Seires" => NULL,
        "SeriesName" => NULL,
				"PayToCode" => $order->PayToCode,
				"ShipToCode" => $order->ShipToCode,
				"Address" => $order->Address,
				"Address2" =>$order->Address2,
				"DiscPrcnt" => round($order->DiscPrcnt, 2),
        "DiscSum" => $order->DiscAmount,
				"RoundDif" => round($order->RoundDif, 2),
				"Comments" => $order->Comments,
				"OwnerCode" => intval($order->OwnerCode)
			);


			$orderLine = array();

			foreach($details AS $rs)
			{
				$line = array(
					"LineNum" => intval($rs->LineNum),
					"ItemCode" => $rs->ItemCode,
					"ItemDescription" => $rs->ItemName,
          "FreeText" => NULL,
					"Quantity" => round($rs->Qty, 2),
					//"UomEntry" => intval($rs->UomEntry),
          "UnitPrice" => round($rs->Price, 2),
          "DiscPrcnt" => round($rs->DiscPrcnt, 2),
          "VatGroup" => $rs->VatGroup,
          "ShipDate" => NULL,
          "UomCode" => intval($rs->UomEntry),
          "OcrCode" => NULL,
          "AcctCode" => NULL,
          "WhsCode" => empty($rs->WhsCode) ? $dfWhsCode : $rs->WhsCode,
					"LineTotal" => round($rs->LineTotal, 2),
					"PriceBefDi" => round($rs->Price, 2),
					"Currency" => $order->DocCur,
					"Rate" => round($order->DocRate, 2),
					"VatPrcnt" => round($rs->VatRate, 2),
					"PriceAfVAT" => round(add_vat($rs->SellPrice, $rs->VatRate), 2),
					"VatSum" => round($rs->totalVatAmount, 2),
					"SlpCode" => intval($order->SlpCode),
          "NoInvTryMv" => NULL,
          "CoGsOcrCode" => NULL
				);

				array_push($orderLine, $line);
			}

			$sq['Line'] = $orderLine;

      array_push($ds, $sq);

      if($testMode)
  		{
  			$arr = array(
  				'Status' => 1,
  				'DocEntry' => 1,
  				'DocNum' => "22000001"
  			);

  			$this->ci->quotation_model->update($code, $arr);

        $logs = array(
          'code' => $code,
          'status' => 'success',
          'json' => json_encode($ds)
        );

        $this->ci->logs_model->order_logs($logs);
  			return TRUE;
  		}

      $json = json_encode($ds, JSON_UNESCAPED_UNICODE);
      // echo $json; exit();

      $logs = array(
        'code' => $code,
        'status' => 'send',
        'json' => $json
      );

      $this->ci->logs_model->order_logs($logs);


			$url = getConfig('SAP_API_HOST');

			if($url[-1] != '/')
			{
				$url .'/';
			}

			$url = $url."Quotation";

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$response = curl_exec($curl);

      if($response === FALSE)
      {
        $response = curl_error($curl);
      }

			curl_close($curl);

			$ra = json_decode($response);

			if(! empty($ra) && ! empty($ra[0]->Status))
			{
        $rs = $ra[0];

				if($rs->Status == 'success' OR $rs->Status == 'Success')
				{
					$arr = array(
						'Status' => 1,
						'DocEntry' => $rs->DocEntry,
						'DocNum' => $rs->DocNum
					);

					$this->ci->quotation_model->update($code, $arr);


          if($logJson)
					{
						$logs = array(
							'code' => $code,
							'status' => 'success',
							'json' => $response
						);

						$this->ci->logs_model->order_logs($logs);
					}

				}
				else
				{
					$arr = array(
						'Status' => 3,
						'message' => $rs->errMsg
					);

					$this->ci->quotation_model->update($code, $arr);

					$sc = FALSE;
					$this->ci->error = $rs->errMsg;

          $logs = array(
            'code' => $code,
            'status' => 'error',
            'json' => $rs->errMsg
          );

          $this->ci->logs_model->order_logs($logs);
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

				$this->ci->quotation_model->update($code, $arr);

        $logs = array(
          'code' => $code,
          'status' => 'response',
          'json' => $response
        );

        $this->ci->logs_model->order_logs($logs);
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "No data found";
		}

		return $sc;
	}

} //-- end class

 ?>
