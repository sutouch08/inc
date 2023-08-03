<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Customer_area extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/customer_area_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	public function get_get($id = NULL)
	{
		if( ! empty($id))
		{
			$rs = $this->customer_area_model->get($id);

			if( ! empty($rs))
			{
				$result = array(
					'id' => $rs->id,
					'code' => $rs->code,
					'name' => $rs->name,
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
			$ds = $this->customer_area_model->get_all();

			if( ! empty($ds))
			{
				$result = array();

				foreach($ds as $rs)
				{
					$arr = array(
						'id' => $rs->id,
						'code' => $rs->code,
						'name' => $rs->name,
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


	  if($sc === TRUE)
	  {
		  $cr = $this->customer_area_model->get_by_code($ds->id);

		  if(empty($cr))
		  {
				$arr = array(
					'code' => $ds->id,
					'name' => $ds->name,
					'last_sync' => now()
				);

				if(! $this->customer_area_model->add($arr))
				{
					$sc = FALSE;
					$this->error = "Insert data failed";
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "({$ds->id}) already exists";
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

			$this->logs_model->logs_customer_area($logs);


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

			$this->logs_model->logs_customer_area($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }



	public function update_post($code)
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


	  if($sc === TRUE)
	  {
		  $cr = $this->customer_area_model->get_by_code($code);

		  if( ! empty($cr))
		  {
				$arr = array(
					'name' => $ds->name,
					'last_sync' => now()
				);

				if(! $this->customer_area_model->update($cr->id, $arr))
				{
					$sc = FALSE;
					set_error('update');
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "({$code}) not exists";
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

			$this->logs_model->logs_customer_area($logs);


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

			$this->logs_model->logs_customer_area($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }


	public function delete_post($id)
	{
		$sc = TRUE;

		if(! $this->customer_area_model->delete($id))
		{
			$sc = FALSE;
			$this->error = "Delete failed";

			$logs = array(
				'action' => 'delete',
				'status' => 'error',
				'message' => $this->error,
				'json' => '{"id" : '.$id.'}'
			);

			$this->logs_model->logs_customer_area($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}
		else
		{
			$logs = array(
				'action' => 'delete',
				'status' => 'success',
				'message' => Null,
				'json' => '{"id" : '.$id.'}'
			);

			$this->logs_model->logs_customer_area($logs);

			$result = array(
				'status' => 'success'
			);

			$this->response($result, 200);
		}
	}


} //--- end class
