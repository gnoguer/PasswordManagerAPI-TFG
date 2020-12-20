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


		case 'getPasswords':
		if(isTheseParametersAvailable(array('userId'))){

			$response = getPasswords($conn);

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
			$password = $_POST['password'];
			$note = $_POST['note'];  

			$userId = (int) $struserId;
 			//creating the query 
			$stmt = $conn->prepare("INSERT INTO SERVICES (userId, name, password, note) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("isss", $userId, $name, $password, $note);

			if($stmt->execute()){

				$response['error'] = false; 
				$response['message'] = 'Password saved successfully';

			}else{
				$response['error'] = true; 
				$response['message'] = 'We could not save your password right now';
			}

		return $response;
	}

	function getPasswords($conn){

			//getting values
			$userId = $_GET["userId"];
			
 			//creating the query 
 			$stmt = $conn->prepare("SELECT * FROM SERVICES WHERE USERID = ?");
			$stmt->bind_param("i", $userId);
			
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows > 0){

				$stmt->bind_result($code, $userId, $name, $username, $password, $note);

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

?>