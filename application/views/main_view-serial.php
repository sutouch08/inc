<?php $this->load->view('include/header'); ?>
	<div class="row">
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-center">
	    <h1>Hello! <?php echo get_cookie('displayName'); ?></h1>
	    <h5>Good to see you here</h5>
	  </div>
	  <div class="divider-hidden"></div>
		<div class="divider"></div>
	  <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 padding-5 text-center">
			<input type="text" id="result" />
			<button type="button" class="btn btn-sm btn-success" onclick="readData()">Read</button>
	  </div>

		<button type="button" class="btn btn-sm btn-success" onclick="getConnect()">getConnect</button>
	</div>
	<ul id="list2"></ul>
	<script>

		var ports = null;
		var port = null;

		var reader;
		var keepReading = true;
		//var usbProductId = 8200;
		//var usbVendorId = 1367;
		var filter = {usbVendorId:8200, usbProductId:1367};
		// window.addEventListener('load', () => {
		// 	connect();
		// });

		async function connect() {
			ports = await navigator.serial.getPorts();

			if(ports) {
				port = ports[0];
			}
		}


		$('#result').focus(function() {
			readData();
		});

		$('#result').focusout(function() {
			$(this).val('');
			closeReader();
		})

		async function closeReader() {
			try {
				await reader.releaseLock();
			}
			catch(err) {

			}
			if(port.readable) {
				await port.close();
			}
		}

		async function readData() {
			ports = await navigator.serial.getPorts();
			if(ports) {
				port = ports[0];
			}

			await port.open({baudRate:300});
			const decoder = new TextDecoder();
			if(port.readable) {
				reader = port.readable.getReader();
				let txt = "";
				while(true) {
					const { value, done } = await reader.read();
					var num = decoder.decode(value);
					if(num != "") {
						if(num == "g") {
							break;
						}

						txt = txt + num;
					}
				}

				$('#result').val(parseFloat(txt));
				reader.releaseLock();

				await port.close();
			}
		}


		function getConnect() {
			port = navigator.serial.requestPort({ filter })
		}

	</script>

<?php $this->load->view('include/footer'); ?>
