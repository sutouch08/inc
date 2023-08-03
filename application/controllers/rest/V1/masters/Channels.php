<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Channels extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/channels_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	public function get_get($id = NULL)
	{
		if( ! empty($id))
		{
			$rs = $this->channels_model->get($id);

			if( ! empty($rs))
			{
				$result = array(
					'id' => $rs->id,
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
			$ds = $this->channels_model->get_all();

			if( ! empty($ds))
			{
				$result = array();

				foreach($ds as $rs)
				{
					$arr = array(
						'id' => $rs->id,
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

		if($sc === TRUE && (empty($ds->id) OR empty($ds->name)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->channels_model->get($ds->id);

		  if(empty($cr))
		  {
				if( ! $this->channels_model->is_exists($ds->name))
				{
					$arr = array(
						'id' => $ds->id,
						'name' => $ds->name
					);

					if(! $this->channels_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "Insert data failed";
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $ds->name);
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "id ({$ds->id}) already exists";
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

			$this->logs_model->logs_channels($logs);


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

			$this->logs_model->logs_channels($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }



	public function update_post($id)
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

		if($sc === TRUE && (empty($ds->id) OR empty($ds->name)))
		{
			$sc = FALSE;
			set_error('required');
		}


	  if($sc === TRUE)
	  {
		  $cr = $this->channels_model->get($id);

		  if( ! empty($cr))
		  {
				if( ! $this->channels_model->is_exists_name($ds->name, $id))
				{
					$arr = array(
						'name' => $ds->name
					);

					if(! $this->channels_model->update($id, $arr))
					{
						$sc = FALSE;
						set_error('update');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $ds->name);
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

			$this->logs_model->logs_channels($logs);


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

			$this->logs_model->logs_channels($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}
  }

} //--- end class
