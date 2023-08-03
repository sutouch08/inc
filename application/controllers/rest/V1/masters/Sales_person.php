<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Sales_person extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/sales_person_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	public function get_get($id = NULL)
	{
		if( ! empty($id))
		{
			$rs = $this->sales_person_model->get($id);

			if( ! empty($rs))
			{
				$result = array(
					'SlpCode' => $rs->id,
					'SlpName' => $rs->name,
					'EmpID' => $rs->emp_id,
					'Active' => $rs->active == 1 ? 'Y' : 'N',
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
			$ds = $this->sales_person_model->get_all();

			if( ! empty($ds))
			{
				$result = array();

				foreach($ds as $rs)
				{
					$arr = array(
						'SlpCode' => $rs->id,
						'SlpName' => $rs->name,
						'EmpID' => $rs->emp_id,
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

		if($sc === TRUE && (empty($ds->SlpCode) OR empty($ds->SlpName)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->sales_person_model->get($ds->SlpCode);

		  if(empty($cr))
		  {
				$arr = array(
					'id' => $ds->SlpCode,
					'name' => $ds->SlpName,
					'emp_id' => empty($ds->EmpID) ? NULL : $ds->EmpID,
					'active' => empty($ds->Active) ? 1 : ($ds->Active === 'Y' ? 1 : 0),
					'last_sync' => now()
				);

				if(! $this->sales_person_model->add($arr))
				{
					$sc = FALSE;
					$this->error = "Insert data failed";
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "SlpCode ({$ds->SlpCode}) already exists";
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

			$this->logs_model->logs_sales_person($logs);


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

			$this->logs_model->logs_sales_person($logs);

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

		if($sc === TRUE && (empty($id) OR empty($ds->SlpName)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->sales_person_model->get($id);

		  if( ! empty($cr))
		  {
				$arr = array(
					'name' => $ds->SlpName,
					'emp_id' => empty($ds->EmpID) ? NULL : $ds->EmpID,
					'active' => empty($ds->Active) ? 1 :($ds->Active == 'Y' ? 1 : 0),
					'last_sync' => now()
				);

				if(! $this->sales_person_model->update($id, $arr))
				{
					$sc = FALSE;
					set_error('update');
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "id ({$id}) not exists";
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

			$this->logs_model->logs_sales_person($logs);


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

			$this->logs_model->logs_sales_person($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}
  }

} //--- end class
