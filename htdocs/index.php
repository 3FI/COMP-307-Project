<?php
	$request = $_SERVER['REQUEST_URI'];
	$viewDir = '/cgi-bin/';

	switch ($request) {
	    case '':
	    case '/':
	        require __DIR__ . $viewDir . 'login-form.php';
	        break;
		case '/login-form':
			require __DIR__ . $viewDir . 'login-form.php';
			break;
		case '/login':
			require __DIR__ . $viewDir . 'login.php';
			break;
	    case '/register-form':
	        require __DIR__ . $viewDir . 'register-form.php';
	        break;
		case '/register':
			require __DIR__ . $viewDir . 'register.php';
			break;
	    case '/logout':
	        require __DIR__ . $viewDir . 'logout.php';
	        break;
		case '/select-discussion':
			require 'select-discussion.html';
			break;
		case '/validate-ticket':
			require __DIR__ . $viewDir . 'validate-ticket.php';
			break;
		case '/fetch-board-list':
			require __DIR__ . $viewDir . 'fetch-board-list.php';
			break;
		case '/create-board':
			require __DIR__ . $viewDir . 'create-board.php';
		case '/fetch-channels-list':
			require __DIR__ . $viewDir . 'fetch-channels-list.php';
		case '/create-channel':
			require __DIR__ . $viewDir . 'create-channel.php';
		case '/fetch-messages-list':
			require __DIR__ . $viewDir . 'fetch-messages-list.php';
		case '/create-message':
			require __DIR__ . $viewDir . 'create-message.php';





	    default:
	        http_response_code(404);
	        require __DIR__ . $viewDir . '404.php';
	}
?>

