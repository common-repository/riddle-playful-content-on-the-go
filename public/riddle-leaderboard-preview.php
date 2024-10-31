<?php

require '../src/init.php';
authenticate();

$type = \src\classes\Landingpage\RiddleLandingpageManager::getTypeInstance('leaderboard');
$type->injectValuesFromArray($_GET);

echo $type->render(false, true); // do not accept data ($acceptData => false) and it's a preview (true)

function dieWithAccessDeniedError($msg = 'Access denied')
{
    http_response_code(403);
    die($msg);
}

function authenticate()
{
    // check if the request is an AJAX request
    if (!$_SERVER['HTTP_X_REQUESTED_WITH'] || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest' ) {
        dieWithAccessDeniedError('You have no permission to view this page.');
    }

    // check if the user has access to this riddle ID - a potential hacker could get the leaderboard leads that way if this page wouldn't be restricted
    $handler = new \src\classes\Landingpage\RiddleLeaderboardPreviewHandler(true); // start session
    $id = isset($_GET['riddle_type_id']) ? urldecode($_GET['riddle_type_id']) : -1;

    if (!$handler->isAllowed(intval($id))) {
        dieWithAccessDeniedError('Access denied.');
    }
}