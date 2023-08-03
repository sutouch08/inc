<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Employee extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/employee_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	public function get_get($id = NULL)
	{
		if( ! empty($id))
		{
			$rs = $this->employee_model->get($id);

			if( ! empty($rs))
			{
				$result = array(
					"EmpID" => $rs->id,
					"firstName" => $rs->firstName,
					"lastName" => $rs->lastName,
					"middleName" => $rs->middleName,
					"Active" => $rs->active == 1 ? 'Y' : 'N',
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
			$ds = $this->employee_model->get_all();

			if( ! empty($ds))
			{
				$result = array();

				foreach($ds as $rs)
				{
					$arr = array(
						"EmpID" => $rs->id,
						"firstName" => $rs->firstName,
						"lastName" => $rs->lastName,
						"middleName" => $rs->middleName,
						"Active" => $rs->active == 1 ? 'Y' : 'N',
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

		if($sc === TRUE && (empty($ds->EmpID) OR empty($ds->firstName)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->employee_model->get($ds->EmpID);

		  if(empty($cr))
		  {
				$arr = array(
					'id' => $ds->EmpID,
					'firstName' => $ds->firstName,
					'lastName' => $ds->lastName,
					'middleName' => $ds->middleName,
					'active' => $ds->Active == 'Y' ? 1 : 0,
					'last_sync' => now()
				);

				$this->employee_model->add($arr);
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "EmpID ({$ds->EmpID}) already exists";
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

			$this->logs_model->logs_employee($logs);


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

			$this->logs_model->logs_employee($logs);

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

		if($sc === TRUE && (empty($id) OR empty($ds->firstName)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->employee_model->get($id);

		  if( ! empty($cr))
		  {
				$arr = array(
					'firstName' => $ds->firstName,
					'lastName' => $ds->lastName,
					'middleName' => $ds->middleName,
					'active' => $ds->Active == 'Y' ? 1 : 0,
					'last_sync' => now()
				);

				if(! $this->employee_model->update($id, $arr))
				{
					$sc = FALSE;
					set_error('update');
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "EmpID ({$id}) not exists";
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

			$this->logs_model->logs_employee($logs);


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

			$this->logs_model->logs_employee($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}
  }

} //--- end class
