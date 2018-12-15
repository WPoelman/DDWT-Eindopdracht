<?php
/**
 * Controller
 * User: reinardvandalen
 * Date: 05-11-18
 * Time: 15:25
 */

/* Require composer autoloader */
require __DIR__ . '/vendor/autoload.php';

/* Include model.php */
include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'roomturbo', 'roomturbo', 'roomturbo');

/* Credentials */

/* Create Router instance */
$router = new \Bramus\Router\Router();

/* Routes */

/* GET route: Landing Page */
$router->get('/', function () {
    /* Hier json van functie die error ophaalt uit de POST route */
    printf('<h1>Landing page</h1>');
});

/* GET route: Contact Page */
$router->get('/contact', function () {
    printf('<h1>Contact info page</h1>');
});

/* GET & DELETE route: Room opt-ins*/
$router->match('GET|DELETE', '/opt-ins', function () use ($db) {
    /* ALLEEN BESCHIKBAAR VOOR TENANTS
    hier functies die
    - bestaande info kunnen ophalen
    - bestaande info kunnen verwijderen */
    printf('<h1>Opt-in overview page</h1>');
});

/* GET & POST route: Log In */
$router->match('GET|POST', '/login', function () use ($db){
    /* Hier json van functie die error ophaalt uit de POST route */
    printf('<h1>Log in page</h1>');

    /* Hier functie die probeert in te loggen en goede feedback meegeeft naar de GET*/
////        $feedback = log_in($db, $_POST);
////        echo json_encode($feedback);
});

/* GET & POST & DELETE route: Account Overview*/
$router->match('GET|POST|DELETE', '/account/([a-z0-9_-]+)', function ($username) use ($db) {
    /* Hier json van functie die error ophaalt uit de POST route */
    printf('<h1>Account Overview page</h1>');
    /* In de route moet een functie of variable mee die de username in de url zet */

    /* Hier functie die probeert in te loggen en goede feedback meegeeft naar de GET*/
//        $feedback = log_in($db, $_POST);
//        echo json_encode($feedback);
    /* functie die de bestaande info ophaalt en laat updaten -> update_series() :) */

});

/* GET & POST route: Register*/
$router->match('GET|POST', '/register', function () use ($db){
    /* Hier json van functie die error ophaalt uit de POST route */
    printf('<h1>Register page</h1>');
    /* In de route moet een functie of variable mee die de username in de url zet */

    /* Hier functie die probeert te registreren goede feedback meegeeft naar de GET*/
//        $feedback = log_in($db, $_POST);
//        echo json_encode($feedback);
});

/* Mount for single room views */
$router->mount('/rooms', function () use ($router, $db) {
    /* GET route: All Rooms Overview */
    $router->get('/', function () use ($db) {
        /* Hier json van functie die alle kamer info ophaalt*/
        printf('<h1>ALL Rooms overview page</h1>');
    });

    /* GET route: view single room */
    $router->get('/(\d+)', function ($room_id) use ($db) {
        printf('<h1>Single room page</h1>');
    });

    /* GET & POST & DELETE route: edit room */
    $router->match('GET|POST|DELETE', '/(\d+)/edit', function ($room_id) use ($db) {
        /* ALLEEN BESCHIKBAAR VOOR OWNERS
        hier functies die
        - nieuwe info kunnen posten
        - bestaande info kunnen ophalen
        - bestaande info kunnen updaten
        - bestaande info kunnen verwijderen */
        printf('<h1>Single room EDIT page</h1>');
    });
});

/* ERROR: route not found */
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    $feedback = [
       "http-code" => 401,
       "error-message" => "The route you tried to access does not exist.",
       ];
    echo json_encode($feedback);
});

/* Run the router */
$router->run();
//
//include 'model.php';
//
///* Connect to DB */
//$db = connect_db('localhost', 'roomturbo', 'roomturbo', 'roomturbo');
//
//$template = Array(
//    1 => Array('name' => 'Home','url' => '/DDWT-Eindopdracht/rooms/'),
//    2 => Array('name' => 'Overview','url' => '/DDWT-Eindopdracht/rooms/overview/')
//);
//
///* Landing page */
//if (new_route('/DDWT-Eindopdracht/rooms/', 'get')) {
//    /* Page info */
//    $page_title = 'Home';
//    $breadcrumbs = get_breadcrumbs([
//        'Home' => na('/DDWT-Eindopdracht/rooms/', True)
//    ]);
//    $active_id = 1;
//    $navigation = get_navigation($template, $active_id);
//
//    /* Page content */
//    $page_subtitle = 'Subtitle Home-page';
//    $page_content = 'Content';
//
//    /* Choose Template */
//    include use_template('main');
//}
//
///* Overview*/
//elseif (new_route('/DDWT-Eindopdracht/rooms/overview/', 'get')) {
//    /* Page info */
//    $page_title = 'Overview';
//    $breadcrumbs = get_breadcrumbs([
//        'Home' => na('/DDWT-Eindopdracht/rooms/', True),
//        'Overview' => na('/DDWT-Eindopdracht/rooms/overview/', True)
//    ]);
//    $active_id = 2;
//    $navigation = get_navigation($template, $active_id);
//
//    /* Page content */
//    $page_subtitle = 'Subtitle Overview';
//    $page_content = 'Content Overview';
//
//    /* Choose Template */
//    include use_template('main');
//}
/* Check if the user has the right credentials */
//$router->before('GET|POST|PUT|DELETE', '/api/.*', function () use ($cred) {
//    if (!check_cred($cred)) {
//        $feedback = [
//            'type' => 'danger',
//            'message' => 'Authentication failed. Please check the credentials.'
//        ];
//        echo json_encode($feedback);
//        exit();
//    }
//});

