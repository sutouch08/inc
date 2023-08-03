<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Customers extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/customers_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	 public function create_post()
  {
    $sc = TRUE;
		$result = array();

    $json = file_get_contents("php://input");

	  $ds = json_decode($json);

	  if(empty($ds))
	  {
		  $sc = FALSE;
		  $this->error = "Empty data";
	  }


		if($sc === TRUE && empty($ds->CardCode))
		{
			$sc = FALSE;
			$this->error = "Missing required parameter : CardCode";
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->customers_model->get($ds->CardCode);

		  if(empty($cr))
		  {
			  $arr = array(
				  'CardCode' => $ds->CardCode,
					'CardName' => empty($ds->CardName) ? NULL: get_null($ds->CardName),
					'LicTradNum' => empty($ds->LicTradNum) ? NULL : get_null($ds->LicTradNum),
					'CardType' => empty($ds->CardType) ? 'C' : $ds->CardType,
					'GroupCode' => empty($ds->GroupCode) ? NULL : get_null($ds->GroupCode),
					'GroupNum' => empty($ds->GroupNum) ? NULL : get_null($ds->GroupNum),
					'ListNum' => empty($ds->ListNum) ? NULL : $ds->ListNum,
					'SlpCode' => empty($ds->SlpCode) ? NULL : get_null($ds->SlpCode),
					'RegionCode' => empty($ds->RegionCode) ? NULL : get_null($ds->RegionCode),
					'AreaCode' => empty($ds->AreaCode) ? NULL : get_null($ds->AreaCode),
					'TypeCode' => empty($ds->TypeCode) ? NULL : get_null($ds->TypeCode),
					'GradeCode' => empty($ds->GradeCode) ? NULL : get_null($ds->GradeCode),
					'SaleTeam' => empty($ds->Sales_Team) ? NULL : get_null($ds->Sales_Team),
					'SaleTeamName' => empty($ds->Sales_Team_Name) ? NULL : get_null($ds->Sales_Team_Name),
					'CreditLine' => empty($ds->CreditLine) ? 0.00 : $ds->CreditLine,
					'Status' => empty($ds->validFor) ? 1 : ($ds->validFor == 'N' ? 0 : 1),
					'last_sync' => now()
			  );


			  if(! $this->customers_model->add($arr))
			  {
					$sc = FALSE;
				  set_error("insert");
			  }
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "{$ds->CardCode} already exists";
		  }
	  }


		if($sc === TRUE)
		{
			$logs = array(
				'action' => 'create',
				'status' => 'success',
				'message' => NULL,
				'json' => $this->log_json ? $json : NULL
			);

			$this->logs_model->logs_customers($logs);


			$result = array(
				'status' => 'success'
			);

			$this->response($result, 200);
		}
		else
		{
			$logs = array(
				'action' => 'create',
				'status' => 'error',
				'message' => $this->error,
				'json' => $this->log_json ? $json : NULL
			);

			$this->logs_model->logs_customers($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }



	public function update_post($CardCode)
  {
    $sc = TRUE;
		$result = array();

    $json = file_get_contents("php://input");

	  $ds = json_decode($json);

	  if(empty($ds))
	  {
		  $sc = FALSE;
		  $this->error = "Empty data";
	  }

		if($sc === TRUE && empty($CardCode))
		{
			$sc = FALSE;
			set_error('required', 'CardCode');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->customers_model->get($CardCode);

		  if( ! empty($cr))
		  {
				$arr = array(
					'CardName' => empty($ds->CardName) ? NULL: get_null($ds->CardName),
					'LicTradNum' => empty($ds->LicTradNum) ? NULL : get_null($ds->LicTradNum),
					'CardType' => empty($ds->CardType) ? 'C' : $ds->CardType,
					'GroupCode' => empty($ds->GroupCode) ? NULL : get_null($ds->GroupCode),
					'GroupNum' => empty($ds->GroupNum) ? NULL : get_null($ds->GroupNum),
					'ListNum' => empty($ds->ListNum) ? NULL : $ds->ListNum,
					'SlpCode' => empty($ds->SlpCode) ? NULL : get_null($ds->SlpCode),
					'RegionCode' => empty($ds->RegionCode) ? NULL : get_null($ds->RegionCode),
					'AreaCode' => empty($ds->AreaCode) ? NULL : get_null($ds->AreaCode),
					'TypeCode' => empty($ds->TypeCode) ? NULL : get_null($ds->TypeCode),
					'GradeCode' => empty($ds->GradeCode) ? NULL : get_null($ds->GradeCode),
					'SaleTeam' => empty($ds->Sales_Team) ? NULL : get_null($ds->Sales_Team),
					'SaleTeamName' => empty($ds->Sales_Team_Name) ? NULL : get_null($ds->Sales_Team_Name),
					'CreditLine' => empty($ds->CreditLine) ? 0.00 : $ds->CreditLine,
					'Status' => empty($ds->validFor) ? 1 : ($ds->validFor == 'N' ? 0 : 1),
					'last_sync' => now()
			  );

			  if(! $this->customers_model->update($CardCode, $arr))
			  {
					$sc = FALSE;
				  set_error("update");
			  }
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "{$CardCode} not exists";
		  }
	  }


		if($sc === TRUE)
		{
			$logs = array(
				'action' => 'update',
				'status' => 'success',
				'message' => NULL,
				'json' => $this->log_json ? $json : NULL
			);

			$this->logs_model->logs_customers($logs);


			$result = array(
				'status' => 'success'
			);

			$this->response($result, 200);
		}
		else
		{
			$logs = array(
				'action' => 'update',
				'status' => 'error',
				'message' => $this->error,
				'json' => $this->log_json ? $json : NULL
			);

			$this->logs_model->logs_customers($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}
  }
} //--- end class
