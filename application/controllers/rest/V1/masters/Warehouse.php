<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Warehouse extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/warehouse_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	public function get_get($id = NULL)
	{
		if( ! empty($id))
		{
			$rs = $this->warehouse_model->get($id);

			if( ! empty($rs))
			{
				$result = array(
					'WhsCode' => $rs->code,
					'WhsName' => $rs->name,
					'date_upd' => $rs->date_upd
				);

				$this->response($result, 200);
			}
			else
			{
				$result = array(
					'status' => FALSE,
					'error' => 'No data found'
				);

				$this->response($result, 400);
			}
		}
		else
		{
			$ds = $this->warehouse_model->get_all();

			if( ! empty($ds))
			{
				$result = array();

				foreach($ds as $rs)
				{
					$arr = array(
						'WhsCode' => $rs->code,
						'WhsName' => $rs->name,
						'date_upd' => $rs->date_upd
					);

					array_push($result, $arr);
				}

				$this->response($result, 200);
			}
			else
			{
				$result = array(
					'status' => FALSE,
					'error' => 'No data found'
				);

				$this->response($result, 400);
			}
		}



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

		if($sc === TRUE && (empty($ds->WhsCode) OR empty($ds->WhsName)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->warehouse_model->get($ds->WhsCode);

		  if(empty($cr))
		  {
				$type = $ds->WhsCode[-1];

				$arr = array(
					'code' => $ds->WhsCode,
					'name' => $ds->WhsName,
					'type' => $type,
					'last_sync' => now()
				);

				if(! $this->warehouse_model->add($arr))
				{
					$sc = FALSE;
					$this->error = "Insert data failed";
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "WhsCode ({$ds->WhsCode}) already exists";
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

			$this->logs_model->logs_warehouse($logs);


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

			$this->logs_model->logs_warehouse($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }



	public function update_post($code = NULL)
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

		if($sc === TRUE && (empty($code) OR empty($ds->WhsName)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->warehouse_model->get($code);

		  if( ! empty($cr))
		  {
				$arr = array(
					'name' => $ds->WhsName
				);

				if(! $this->warehouse_model->update($code, $arr))
				{
					$sc = FALSE;
					set_error('update');
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "WhsCode ({$code}) not exists";
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

			$this->logs_model->logs_warehouse($logs);


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

			$this->logs_model->logs_warehouse($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}
  }

} //--- end class
