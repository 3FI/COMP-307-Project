<?php
	$request = strtok($_SERVER['REQUEST_URI'], '?');
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
		case '/fetch-ticket':
			require __DIR__ . $viewDir . 'fetch-ticket.php';
			break;
		case '/fetch-board-list':
			require __DIR__ . $viewDir . 'fetch-board-list.php';
			break;








	    default:
	        http_response_code(404);
	        require __DIR__ . $viewDir . '404.php';
}
?>
