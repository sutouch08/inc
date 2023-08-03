<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Zone extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/zone_model');
		$this->load->model('masters/warehouse_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	public function get_get($id = NULL)
	{
		if( ! empty($id))
		{
			$rs = $this->zone_model->get($id);

			if( ! empty($rs))
			{
				$result = array(
					"AbsEntry" => $rs->id,
					"BinCode" => $rs->code,
					"Descr" => $rs->name,
					"WhsCode" => $rs->warehouse_code,
					"SysBin" => $rs->sysBin == 1 ? 'Y' : 'N',
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
			$ds = $this->zone_model->get_all();

			if( ! empty($ds))
			{
				$result = array();

				foreach($ds as $rs)
				{
					$arr = array(
						"AbsEntry" => $rs->id,
						"BinCode" => $rs->code,
						"Descr" => $rs->name,
						"WhsCode" => $rs->warehouse_code,
						"SysBin" => $rs->sysBin == 1 ? 'Y' : 'N',
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

		if($sc === TRUE && (empty($ds->AbsEntry) OR empty($ds->BinCode)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->zone_model->get($ds->AbsEntry);

		  if(empty($cr))
		  {
				if(! $this->zone_model->is_exists_code($ds->BinCode))
				{
					$whs = $this->warehouse_model->get($ds->WhsCode);

					$arr = array(
						'id' => $ds->AbsEntry,
						'code' => $ds->BinCode,
						'name' => $ds->Descr,
						'warehouse_id' => empty($whs) ? NULL : $whs->id,
						'warehouse_code' => $ds->WhsCode,
						'sysBin' => $ds->SysBin == 'Y' ? 1 : 0
					);

					if(! $this->zone_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "Insert data failed";
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $ds->BinCode);
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "BinAbs ({$ds->AbsEntry}) already exists";
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

			$this->logs_model->logs_zone($logs);


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

			$this->logs_model->logs_zone($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }



	public function update_post($id = NULL)
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

		if($sc === TRUE && (empty($id) OR empty($ds->BinCode)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->zone_model->get($id);

		  if( ! empty($cr))
		  {
				if( ! $this->zone_model->is_exists_code($ds->BinCode, $id))
				{
					$whs = $this->warehouse_model->get($ds->WhsCode);
					$arr = array(
						'code' => $ds->BinCode,
						'name' => $ds->Descr,
						'warehouse_id' => empty($whs) ? NULL : $whs->id,
						'warehouse_code' => $ds->WhsCode,
						'sysBin' => $ds->SysBin == 'Y' ? 1 : 0
					);

					if(! $this->zone_model->update($id, $arr))
					{
						$sc = FALSE;
						set_error('update');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $ds->BinCode);
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "BinAbs ({$id}) not exists";
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

			$this->logs_model->logs_zone($logs);


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

			$this->logs_model->logs_zone($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}
  }

} //--- end class
