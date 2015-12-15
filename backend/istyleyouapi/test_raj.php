<?php

		//$rid=$get_token_value['device_token'];
				// Replace with the real server API key from Google APIs
				$message ="Hello from server";
				$apiKey = "AIzaSyDRU5TJpogZL8v_nSVMoDN0QS3OS6jiJI0";

				// Replace with the real client registration IDs
				$registrationIDs = "c_sZPyBDMTo:APA91bFKA4g2WxASsz4Qj2W-tvGKbnOUD4qz7YAGbCBZoFekwJ_v-YNbSNlBZyrx6aA3QRzpFWj2PswKhhgueSgZq79IJYuDJxA_eif6jQ_i_mKqeRQ8vwImHtdfpc1dsh51ENLj0YVF";
				// Message to be sent
				#$message = $mess;
				// Set POST variables
				$url = 'https://android.googleapis.com/gcm/send';

				$fields = array(
					'registration_ids' => $registrationIDs,
					'data' => array( "message" => $message,
						),
				);
				$headers = array(
					'Authorization: key=' . $apiKey,
					'Content-Type: application/json'
				);

				// Open connection
				$ch = curl_init();

				// Set the URL, number of POST vars, POST data
				curl_setopt( $ch, CURLOPT_URL, $url);
				curl_setopt( $ch, CURLOPT_POST, true);
				curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
				//curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields));

				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				// curl_setopt($ch, CURLOPT_POST, true);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields));

				// Execute post
				$result = curl_exec($ch);

				// Close connection
				curl_close($ch);
				var_dump($result);
				$echo=1;
				if($echo==1)
					echo $result;
				print_r($result);
				var_dump($result);
			


?>