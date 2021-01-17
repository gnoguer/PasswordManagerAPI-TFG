<?php 

require_once 'DbConnect.php';

$response = array();

if(isset($_GET['apicall'])){

	switch($_GET['apicall']){

		case 'signup':

 		//checking the parameters required are available or not 
		if(isTheseParametersAvailable(array('email','password'))){

			$response = signup($conn);

		}else{
			$response['error'] = true; 
			$response['message'] = 'required parameters are not available'; 
		}


		break; 

		case 'login':

 		//for login we need the username and password 
		if(isTheseParametersAvailable(array('email', 'password'))){

			$response = login($conn);

		}else{

 				//if the user not found 
			$response['error'] = false; 
			$response['message'] = 'Invalid username or password';
		}

		break; 

		case 'savePass':
		if(isTheseParametersAvailable(array('userId','name','password','note'))){

			$response = savePass($conn);

		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;


		case 'getServices':
		if(isTheseParametersAvailable(array('userId'))){

			$response = getServices($conn);

		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'updateService':
		if(isTheseParametersAvailable(array('code', 'name', 'username', 'password', 'note'))){

			$response = updateService($conn);

		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'getPasswords':

		if(isTheseParametersAvailable(array('userId'))){

			$response = getPasswords($conn);

		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'getLeaks':

		$response = getPasswordsFromFile();

		break;

		case 'deleteService':

		if(isTheseParametersAvailable(array('code'))){

			$response = deleteService($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'saveNote':

		if(isTheseParametersAvailable(array('userId', 'name', 'note'))){

			$response = saveNote($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'getNotes':

		if(isTheseParametersAvailable(array('userId'))){

			$response = getNotes($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'deleteNote':

		if(isTheseParametersAvailable(array('code'))){

			$response = deleteNote($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'updateNote':

		if(isTheseParametersAvailable(array('code', 'name', 'note'))){

			$response = updateNote($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'savePaymentCard':

		if(isTheseParametersAvailable(array('userId', 'name', 'nameOnCard', 'number', 'securityCode', 'expirationDate'))){

			$response = savePaymentCard($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'getPaymentCards':

		if(isTheseParametersAvailable(array('userId'))){

			$response = getPaymentCards($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'deletePaymentCard':

		if(isTheseParametersAvailable(array('code'))){

			$response = deletePaymentCard($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'updatePaymentCard':

		if(isTheseParametersAvailable(array('code', 'name', 'nameOnCard', 'number', 'securityCode', 'expirationDate'))){

			$response = updatePaymentCard($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'saveBankAcc':

		if(isTheseParametersAvailable(array('userId', 'name', 'IBAN', 'PIN'))){

			$response = saveBankAcc($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'getBankAccs':

		if(isTheseParametersAvailable(array('userId'))){

			$response = getBankAccs($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'deleteBankAcc':

		if(isTheseParametersAvailable(array('code'))){

			$response = deleteBankAcc($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;

		case 'updateBankAcc':

		if(isTheseParametersAvailable(array('code', 'name', 'IBAN', 'PIN'))){

			$response = updateBankAcc($conn);
			
		}else{

			$response['error'] = true; 
			$response['message'] = 'Required parameters are not available'; 
		}

		break;



		default: 
		$response['error'] = true; 
		$response['message'] = 'Invalid Operation Called';
	}

}else{

	$response['error'] = true; 
	$response['message'] = 'Invalid API Call';
}

echo json_encode($response);



//function validating all the paramters are available
//we will pass the required parameters to this function 

function isTheseParametersAvailable($params){

	//traversing through all the parameters 
	foreach($params as $param){

		//if the paramter is not available
		if(!isset($_POST[$param]) && !isset($_GET[$param])){

			return false; 
		}
	}
 //return true if every param is available 
	return true; 
}

function deleteService($conn){

	$code = $_POST["code"];

	$stmt = $conn->prepare("DELETE FROM SERVICES WHERE SERVICES.CODE = ?");
	$stmt->bind_param("i", $code);

	if($stmt->execute()){

		$response['error'] = false; 
		$response['message'] = 'Service deleted successfully'; 

	}else{
		$response['error'] = true; 
		$response['message'] = 'Service could not be deleted'; 
	}

	return $response;

}

function signup($conn){

			//getting the values 
	$email = $_POST['email']; 
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


 			//checking if the user is already exist with this username or email
 			//as the email and username should be unique for every user 
	$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$stmt->store_result();

			//if the user already exist in the database 
	if($stmt->num_rows > 0){

		$response['error'] = true;
		$response['message'] = 'User already registered';
		$stmt->close();

	}else{

				 //if user is new creating an insert query 
		$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
		$stmt->bind_param("ss", $email, $password);

				 //if the user is successfully added to the database 
		if($stmt->execute()){

 					//fetching the user back 
			$stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?"); 
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->bind_result($id, $email,);
			$stmt->fetch();

			$user = array(
				'id'=>$id, 
				'email'=>$email,
			);

			$stmt->close();

 					//adding the user data in response 
			$response['error'] = false; 
			$response['message'] = 'User registered successfully'; 
			$response['user'] = $user; 
		}
	}

	return $response;
}

function login($conn){

			//getting values 
	$email = $_POST['email'];
	$password = $_POST['password']; 

 			//creating the query 
	$stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
	$stmt->bind_param("s",$email);

	$stmt->execute();
	$stmt->store_result();

 			//if the user exist with given credentials 
	if($stmt->num_rows > 0){

		$stmt->bind_result($id, $email, $dbPass);
		$stmt->fetch();


		if(password_verify($password, $dbPass)){

			$user = array(
				'id'=>$id, 
				'email'=>$email,
			);

			$response['error'] = false; 
			$response['message'] = 'Login successfull'; 
			$response['user'] = $user; 

		}else{

					//if the password is incorrect
			$response['error'] = false; 
			$response['message'] = 'Invalid username or password';
		}
	}

	return $response;
}


function savePass($conn){

			//getting values

	$struserId = $_POST['userId'];
	$name = $_POST['name'];  
	$username = $_POST['username'];
	$password = $_POST['password'];
	$note = $_POST['note'];  
	$expirationDate = $_POST['expirationDate'];

	$userId = (int) $struserId;
 			//creating the query 
	$stmt = $conn->prepare("INSERT INTO SERVICES (userId, name, username, password, note, expiration_date) VALUES (?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("isssss", $userId, $name, $username, $password, $note, $expirationDate);

	if($stmt->execute()){

		$stmt = $conn->prepare("SELECT LAST_INSERT_ID()"); 
		$stmt->execute();
		$stmt->bind_result($code);
		$stmt->fetch();

		$stmt->close();

		$response['error'] = false; 
		$response['message'] = 'Password saved successfully';
		$response['code'] = $code;

	}else{
		$response['error'] = true; 
		$response['message'] = 'We could not save your password right now';
	}

	return $response;
}

function updateService($conn){

	//getting values

	$code = $_POST['code'];
	$name = $_POST['name'];  
	$username = $_POST['username'];
	$password = $_POST['password'];
	$note = $_POST['note'];  


	$stmt = $conn->prepare("UPDATE SERVICES SET name = ?, username = ?, password = ?, note = ? WHERE SERVICES.CODE = ?");

	$stmt->bind_param("ssssi", $name, $username, $password, $note, $code);

	if($stmt->execute()){

		$stmt->close();

		$response['error'] = false; 
		$response['message'] = 'Password updated successfully';
		$response['code'] = $code;

	}else{
		$response['error'] = true; 
		$response['message'] = 'We could not update your password right now';
	}

	return $response;
}

function getServices($conn){

			//getting values
	$userId = $_GET["userId"];

 			//creating the query 
	$stmt = $conn->prepare("SELECT * FROM SERVICES WHERE USERID = ?");
	$stmt->bind_param("i", $userId);

	$stmt->execute();
	$stmt->store_result();

	if($stmt->num_rows > 0){

		$stmt->bind_result($code, $userId, $name, $username, $password, $note, $expirationDate);

		$passwords = array();

		while($stmt->fetch()){

			$row = array(
				'code'=>$code,
				'userId'=>$userId,
				'name'=>$name,
				'username'=>$username,
				'password'=>$password,
				'note'=>$note
			);
			array_push($passwords, $row);
		}

		$response['error'] = false;

		$response['message'] = 'List loaded successfully'; 
		$response['passwords'] = $passwords; 
	}

	return $response;
}


function getPasswords($conn){

	$userId = $_GET["userId"];

	$stmt = $conn->prepare("SELECT PASSWORD FROM SERVICES WHERE USERID = ?");
	$stmt->bind_param("i", $userId);

	$stmt->execute();
	$stmt->store_result();

	if($stmt->num_rows > 0){

		$stmt->bind_result($password);

		$passwords = array();

		while($stmt->fetch()){

			$row = array(
				'password'=>$password,
			);
			array_push($passwords, $password);
		}

		$response['error'] = false; 
		$response['message'] = 'List loaded successfully'; 
		$response['passwords'] = $passwords; 
	}

	return $response;


}

function saveNote($conn){

	$struserId = $_POST['userId'];
	$name = $_POST['name'];  
	$note = $_POST['note'];  

	$userId = (int) $struserId;
 			//creating the query 

	$stmt = $conn->prepare("INSERT INTO NOTES (userId, name, note) VALUES (?, ?, ?)");
	$stmt->bind_param("iss", $userId,$name, $note);

	if($stmt->execute()){

		$stmt = $conn->prepare("SELECT LAST_INSERT_ID()"); 
		$stmt->execute();
		$stmt->bind_result($code);
		$stmt->fetch();

		$stmt->close();

		$response['error'] = false; 
		$response['message'] = 'Note saved successfully';
		$response['code'] = $code;

	}else{
		$response['error'] = true; 
		$response['message'] = 'We could not save your note right now';
	}

	return $response;

}

function updateNote($conn){

	//getting values

	$code = $_POST['code'];
	$name = $_POST['name'];  
	$note = $_POST['note'];  


	$stmt = $conn->prepare("UPDATE NOTES SET name = ?, note = ? WHERE NOTES.CODE = ?");

	$stmt->bind_param("ssi", $name, $note, $code);

	if($stmt->execute()){

		$stmt->close();

		$response['error'] = false; 
		$response['message'] = 'Note updated successfully';
		$response['code'] = $code;

	}else{
		$response['error'] = true; 
		$response['message'] = 'We could not update your note right now';
	}

	return $response;
}

function getNotes($conn){

	$userId = $_GET["userId"];

 			//creating the query 
	$stmt = $conn->prepare("SELECT * FROM NOTES WHERE USERID = ?");
	$stmt->bind_param("i", $userId);

	$stmt->execute();
	$stmt->store_result();

	if($stmt->num_rows > 0){

		$stmt->bind_result($code, $userId, $name, $note);

		$notes = array();

		while($stmt->fetch()){

			$row = array(
				'code'=>$code,
				'userId'=>$userId,
				'name'=>$name,
				'note'=>$note
			);
			array_push($notes, $row);
		}

		$response['error'] = false;

		$response['message'] = 'List loaded successfully'; 
		$response['notes'] = $notes; 
	}

	return $response;

}

function deleteNote($conn){

	$code = $_POST["code"];

	$stmt = $conn->prepare("DELETE FROM NOTES WHERE NOTES.CODE = ?");
	$stmt->bind_param("i", $code);

	if($stmt->execute()){

		$response['error'] = false; 
		$response['message'] = 'Note deleted successfully'; 

	}else{
		$response['error'] = true; 
		$response['message'] = 'Note could not be deleted'; 
	}

	return $response;
}

function savePaymentCard($conn){

	$struserId = $_POST['userId'];
	$name = $_POST['name'];  
	$nameOnCard = $_POST['nameOnCard'];
	$number = $_POST['number'];
	$securityCode = $_POST['securityCode'];
	$expirationDate = $_POST['expirationDate'];

	$userId = (int) $struserId;
 			//creating the query 

	$stmt = $conn->prepare("INSERT INTO PAYMENT_CARDS (userId, name, name_on_card, number, security_code, expiration_date) VALUES (?, ?, ?, ?, ?, ?)");

	$stmt->bind_param("isssss", $userId, $name, $nameOnCard, $number, $securityCode, $expirationDate);

	if($stmt->execute()){

		$stmt = $conn->prepare("SELECT LAST_INSERT_ID()"); 
		$stmt->execute();
		$stmt->bind_result($code);
		$stmt->fetch();

		$stmt->close();

		$response['error'] = false; 
		$response['message'] = 'Payment card saved successfully';
		$response['code'] = $code;

	}else{
		$response['error'] = true; 
		$response['message'] = 'We could not save your payment card right now';
	}

	return $response;

}

function getPaymentCards($conn){

	$userId = $_GET["userId"];

 			//creating the query 
	$stmt = $conn->prepare("SELECT * FROM PAYMENT_CARDS WHERE USERID = ?");
	$stmt->bind_param("i", $userId);

	$stmt->execute();
	$stmt->store_result();

	if($stmt->num_rows > 0){

		$stmt->bind_result($code, $userId, $name, $nameOnCard, $number, $securityCode, $expirationDate);

		$paymentCards = array();

		while($stmt->fetch()){

			$row = array(
				'code'=>$code,
				'userId'=>$userId,
				'name'=>$name,
				'nameOnCard'=>$nameOnCard,
				'number'=>$number,
				'securityCode'=>$securityCode,
				'expirationDate'=>$expirationDate

			);
			array_push($paymentCards, $row);
		}

		$response['error'] = false;

		$response['message'] = 'List loaded successfully'; 
		$response['paymentCards'] = $paymentCards; 
	}

	return $response;

}

function updatePaymentCard($conn){

	//getting values

	$code = $_POST['code'];
	$name = $_POST['name'];  
	$nameOnCard = $_POST['nameOnCard'];
	$number = $_POST['number'];  
	$securityCode = $_POST['securityCode'];  
	$expirationDate = $_POST['expirationDate'];  
  
	$stmt = $conn->prepare("UPDATE PAYMENT_CARDS SET name = ?, name_on_card = ?, number = ?, security_code = ?, expiration_date = ? WHERE PAYMENT_CARDS.CODE = ?");

	$stmt->bind_param("sssssi", $name, $nameOnCard, $number, $securityCode, $expirationDate, $code);

	if($stmt->execute()){

		$stmt->close();

		$response['error'] = false; 
		$response['message'] = 'Note updated successfully';
		$response['code'] = $code;

	}else{
		$response['error'] = true; 
		$response['message'] = 'We could not update your note right now';
	}

	return $response;
}

function deletePaymentCard($conn){

	$code = $_POST["code"];

	$stmt = $conn->prepare("DELETE FROM PAYMENT_CARDS WHERE PAYMENT_CARDS.CODE = ?");
	$stmt->bind_param("i", $code);

	if($stmt->execute()){

		$response['error'] = false; 
		$response['message'] = 'Payment card deleted successfully'; 

	}else{
		$response['error'] = true; 
		$response['message'] = 'Payment card could not be deleted'; 
	}

	return $response;
}

function saveBankAcc($conn){

	$struserId = $_POST['userId'];
	$name = $_POST['name'];  
	$IBAN = $_POST['IBAN'];
	$PIN = $_POST['PIN'];

	$userId = (int) $struserId;
 			//creating the query 

	$stmt = $conn->prepare("INSERT INTO BANK_ACCOUNTS (userId, name, IBAN, PIN) VALUES (?, ?, ?, ?)");

	$stmt->bind_param("isss", $userId, $name, $IBAN, $PIN);

	if($stmt->execute()){

		$stmt = $conn->prepare("SELECT LAST_INSERT_ID()"); 
		$stmt->execute();
		$stmt->bind_result($code);
		$stmt->fetch();

		$stmt->close();

		$response['error'] = false; 
		$response['message'] = 'Bank account saved successfully';
		$response['code'] = $code;

	}else{
		$response['error'] = true; 
		$response['message'] = 'We could not save your payment card right now';
	}

	return $response;

}

function getBankAccs($conn){

	$userId = $_GET["userId"];

 			//creating the query 
	$stmt = $conn->prepare("SELECT * FROM BANK_ACCOUNTS WHERE USERID = ?");
	$stmt->bind_param("i", $userId);

	$stmt->execute();
	$stmt->store_result();

	if($stmt->num_rows > 0){

		$stmt->bind_result($code, $userId, $name, $IBAN, $PIN);

		$bankAccounts = array();

		while($stmt->fetch()){

			$row = array(
				'code'=>$code,
				'userId'=>$userId,
				'name'=>$name,
				'IBAN'=>$IBAN,
				'PIN'=>$PIN,
			);
			array_push($bankAccounts, $row);
		}

		$response['error'] = false;

		$response['message'] = 'List loaded successfully'; 
		$response['bankAccounts'] = $bankAccounts; 
	}

	return $response;

}

function updateBankAcc($conn){

	//getting values

	$code = $_POST['code'];
	$name = $_POST['name'];    
	$IBAN = $_POST['IBAN'];  
	$PIN = $_POST['PIN'];  
  
	$stmt = $conn->prepare("UPDATE BANK_ACCOUNTS SET name = ?, IBAN = ?, PIN = ? WHERE BANK_ACCOUNTS.CODE = ?");

	$stmt->bind_param("sssi", $name, $IBAN, $PIN, $code);

	if($stmt->execute()){

		$stmt->close();

		$response['error'] = false; 
		$response['message'] = 'Bank account updated successfully';
		$response['code'] = $code;

	}else{
		$response['error'] = true; 
		$response['message'] = 'We could not update your bank account right now';
	}

	return $response;
}

function deleteBankAcc($conn){

	$code = $_POST["code"];

	$stmt = $conn->prepare("DELETE FROM BANK_ACCOUNTS WHERE BANK_ACCOUNTS.CODE = ?");
	$stmt->bind_param("i", $code);

	if($stmt->execute()){

		$response['error'] = false; 
		$response['message'] = 'Bank account deleted successfully'; 

	}else{
		$response['error'] = true; 
		$response['message'] = 'Bank account could not be deleted'; 
	}

	return $response;
}

function getPasswordsFromFile(){

	$words = file('./files/words2.txt', FILE_IGNORE_NEW_LINES);

	$response['error'] = false;
	$respones['message'] = "Leak passwords loaded successfully";
	$response['leaked_passwords'] = $words;


	return $response;
}



?>