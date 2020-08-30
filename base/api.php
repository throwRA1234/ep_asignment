<?php
	$route 	= $_SERVER['REQUEST_URI'];
	$method = $_SERVER['REQUEST_METHOD'];
	$route = substr($route, 1);
	$route = explode("?", $route);
        if(preg_match('/\.(js|css)/', $route[0])) {
           exit();
        }
        if(in_array($method, ['GET', 'DELETE']) && count($route) > 1) {
            $paramStr = $route[1];
            parse_str($paramStr, $params);
            if(!array_key_exists('controller', $params)) {
                ob_clean();
                header_remove();
                header('Content-Type: application/json');
                $response = ['status' => 'failure', 'message' => 'missing controller...'];
                echo json_encode($response);
                exit();
            }
        }
        $route = explode("/", $route[0]);
	$route = array_diff($route, array('bootstrap_proj', 'api'));
	$route = array_values($route);
        
        if(in_array($method, ['POST', 'PUT'])) {
            if(isJSON(array_keys($_POST)[0])) {
                $params = json_decode(array_keys($_POST)[0], true);
            } else {
                $params = $_POST;
            }
            if(!array_key_exists('controller', $params)) {
                ob_clean();
                header_remove();
                header('Content-Type: application/json');
                $response = ['status' => 'failure', 'message' => 'missing controller...'];
                echo json_encode($response);
                exit();
            }
        }

	$arr = null;

	if (count($route) <= 2) {
		switch ($route[0]) {
			case 'client':
				$arr = verifyMethod($method, $route, $params);
                                //try and find an api class and execute the relevant method in it
                                   $controller = ucfirst($arr[1]['controller']) . 'Api';
                                   if (class_exists($controller)) {
                                       $class = new $controller;
                                       $method = strtolower($arr[0][1]);
                                       //in case function is not found in API class file
                                       if(!method_exists($class, $method)) {
                                           ob_clean();
                                           header_remove();
                                           header('Content-Type: application/json');
                                           echo json_encode(['status' => 'failure', 'message' => 'Requested function not found...']);
                                           exit();
                                       }
                                       $response = call_user_func_array(array($class, $method), [$arr[1]]);
                                       ob_clean();
                                       header_remove();
                                       header('Content-Type: application/json');
                                       echo $response;
                                       exit();
                                   } else {
                                     ob_clean();
                                     header_remove();
                                     header('Content-Type: application/json');
                                     echo json_encode(['status' => 'failure', 'message' => 'Requested API Class not found...']);
                                     exit();
                                   }
				break;
                        case 'index.php':
                            break;
                        case 'app':
                            break;
                        case '':
                            break;
			default:
                               ob_clean();
                               header_remove();
                               header('Content-Type: application/json');                            
			       $arr = ['status' => 'failure', 'message' => 'Unknown path...'];
                               echo json_encode($arr);
                               exit();
			break;
		}

	} else {
                ob_clean();
                header_remove();
                header('Content-Type: application/json');  
		$arr = ['status' => 'failure', 'message' => 'Such a route does not exist...'];
                echo json_encode($arr);
                exit();
	}


function isJSON($input) {
   return json_decode($input, true) != null;
}

function verifyMethod($method, $route, $params = []) {
    if (in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
        return [$route, $params];
    } else {
        return ['status' => 'failure', 'message' => 'Invalid Api Request Type...'];
    }
}

?>