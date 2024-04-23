<?php 
require_once 'HunterObfuscator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Get input values
	$phoneHK = $_POST['phoneHK'];
	$phoneCN = $_POST['phoneCN'];
	$plateID = $_POST['plateID'];

	$magicWord = $_POST['magicWord'];
	
	
	// Extract desired parts
	$telNumber_part1 = substr($phoneHK, 0, 4);
	$telNumber_part2 = substr($phoneHK, -4);
	
	$telNumber_part1_CN = substr($phoneCN, 0, 4);
	$telNumber_part2_CN = substr($phoneCN, -7);
	
	// Create the data.js content
	$dataContent = "var telNumber_part1 = '$telNumber_part1';\n";
	$dataContent .= "var telNumber_part2 = '$telNumber_part2';\n\n";
	
	$dataContent .= "var telNumber_part1_CN = '$telNumber_part1_CN';\n";
	$dataContent .= "var telNumber_part2_CN = '$telNumber_part2_CN';\n\n";
	
	$dataContent .= "var plate1_line = '$plateID';\n";
	$dataContent .= "var plate2_line = '';\n";
	
	$hunter = new HunterObfuscator($dataContent); //Initialize with JS code in parameter
	$obsfucated = $hunter->Obfuscate(); //Do obfuscate and get the obfuscated code
	
	$file = fopen("data.js", "w");
	fwrite($file, $obsfucated);
	fclose($file);

	$githubUsername = 'bigbigshop';
	$repositoryName = 'car';
	$accessToken = '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';
	$fileToUpload = "data.js";
	$commitMessage = 'Replace file'; // Change the commit message to indicate file replacement

	$fileContent = file_get_contents($fileToUpload);

	$url = "https://api.github.com/repos/{$githubUsername}/{$repositoryName}/contents/{$magicWord}/{$fileToUpload}";

	// First, check if the file exists
	$headers = array(
		'Authorization: token ' . $accessToken,
		'User-Agent: PHP'
	);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	if ($httpCode === 200) {
		// File exists, so retrieve its SHA value
		$responseJson = json_decode($response, true);
		$existingFileSha = $responseJson['sha'];

		$data = array(
			'message' => $commitMessage,
			'content' => base64_encode($fileContent),
			'sha' => $existingFileSha // Include the SHA value in the payload
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		header('Location: success.html');
		exit();
	} else {
		// File doesn't exist, so upload it as a new file
		$data = array(
			'message' => $commitMessage,
			'content' => base64_encode($fileContent)
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		header('Location: success.html');
		exit();
	}

	if ($httpCode === 201) {
		echo "<script>alert('未能更新')</script>";
	}		
}
?> 