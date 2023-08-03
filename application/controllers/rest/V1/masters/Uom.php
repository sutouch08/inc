<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Uom extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/uom_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	public function get_get($id = NULL)
	{
		if( ! empty($id))
		{
			$rs = $this->uom_model->get($id);

			if( ! empty($rs))
			{
				$result = array(
					'UomEntry' => $rs->id,
					'UomCode' => $rs->code,
					'UomName' => $rs->name,
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
			$ds = $this->uom_model->get_all();

			if( ! empty($ds))
			{
				$result = array();

				foreach($ds as $rs)
				{
					$arr = array(
						'UomEntry' => $rs->id,
						'UomCode' => $rs->code,
						'UomName' => $rs->name,
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

		if($sc === TRUE && (empty($ds->UomEntry) OR empty($ds->UomCode)))
		{
			$sc = FALSE;
			$this->error = "Missing required parameter : UomEntry OR UomCode";
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->uom_model->get($ds->UomEntry);

		  if(empty($cr))
		  {
			  $arr = array(
				  'id' => $ds->UomEntry,
				  'code' => $ds->UomCode,
					'name' => $ds->UomName,
					'last_sync' => now()
			  );

			  if(! $this->uom_model->add($arr))
			  {
					$sc = FALSE;
				  $this->error = "Insert data failed";
			  }
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "UomEntry ({$ds->UomEntry}) already exists";
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

			$this->logs_model->logs_uom($logs);


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

			$this->logs_model->logs_uom($logs);

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

		if($sc === TRUE && empty($id))
		{
			$sc = FALSE;
			$this->error = "Missing required parater: UomEntry";
		}

		if($sc === TRUE && empty($ds->UomCode))
		{
			$sc = FALSE;
			$this->error = "Missing required parameter : UomCode";
		}

	  if($sc === TRUE)
	  {
		  $cr = $this->uom_model->get($id);

		  if( ! empty($cr))
		  {
				if( ! $this->uom_model->is_exists_code($ds->UomCode, $id))
				{
					$arr = array(
						'code' => $ds->UomCode,
						'name' => $ds->UomName,
						'last_sync' => now()
					);

					if(! $this->uom_model->update($id, $arr))
					{
						$sc = FALSE;
						set_error('update');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $ds->UomCode);
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "UomEntry ({$id}) not exists";
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

			$this->logs_model->logs_uom($logs);


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

			$this->logs_model->logs_uom($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }

} //--- end class
