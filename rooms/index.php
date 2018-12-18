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


/* Set the default routes for the navigation bar */
$nav = Array(
    0 => Array(
        'name' => 'Home',
        'url' => '/DDWT-Eindopdracht/rooms'
    ),
    1 => Array(
        'name' => 'Login',
        'url' => '/DDWT-Eindopdracht/rooms/login'
    ),
    2 => Array(
        'name' => 'My Account',
        'url' => '/DDWT-Eindopdracht/rooms/account'
    ),
    4 => Array(
        'name' => 'Contact',
        'url' => '/DDWT-Eindopdracht/rooms/contact'
    ),
    5 => Array(
        'name' => 'Register',
        'url' => '/DDWT-Eindopdracht/rooms/register'
    ),
    6 => Array(
        'name' => 'Overview',
        'url' => '/DDWT-Eindopdracht/rooms/rooms'
    ),
    7 => Array(
        'name' => 'Add',
        'url' => '/DDWT-Eindopdracht/rooms/rooms/add'
    ),
    8 => Array(
        'name' => 'Edit',
        'url' => '/DDWT-Eindopdracht/rooms/rooms/edit'
    ),
);
/* Create Router instance */
$router = new \Bramus\Router\Router();

/* Routes */

/* GET route: Landing Page */
$router->get('/', function () {
    /* Hier json van functie die error ophaalt uit de POST route */
    printf('<h1>Landing page</h1>');
});

/* GET route: Contact Page */
$router->get('/contact', function () use ($nav) {

    $page_title = "Contact info";
    $page_subtitle = "Contact us";
    $page_content = "To contact us, mail wessel@roomturbo.nl";
    $breadcrumbs = get_breadcrumbs([
        'DDWT-Eindopdracht' => na('/DDWT-Eindopdracht/', False),
        'rooms' => na('/DDWT18/rooms/', False),
        'contact' => na('/DDWT18/rooms/contact', True)
    ]);
    //todo navigatiebar instellen
    $navigation = get_navigation($nav, 4);

    /* Choose Template */
    include use_template('main');

});

/* GET & POST route: Room opt-ins*/

/* GET & POST route: Log In */
$router->match('GET|POST', '/login', function () use ($db, $nav){
    /* Hier json van functie die error ophaalt uit de POST route */
    printf('<h1>Log in page</h1>');

    /* Hier functie die probeert in te loggen en goede feedback meegeeft naar de GET*/
////        $feedback = log_in($db, $_POST);
////        echo json_encode($feedback);
});

/* GET & POST route: Account Overview*/
$router->match('GET|POST', '/account/([a-z0-9_-]+)', function ($username) use ($db, $nav) {
    /* Hier json van functie die error ophaalt uit de POST route */
    printf('<h1>Account Overview page</h1>');
    /* In de route moet een variable mee die de username in de url zet */

    /* Hier functie die probeert in te loggen en goede feedback meegeeft naar de GET*/
//        $feedback = log_in($db, $_POST);
//        echo json_encode($feedback);
    /* functie die de bestaande info ophaalt en laat updaten -> update_series() :) */

});

/* GET & POST route: Register*/
$router->match('GET|POST', '/register', function () use ($db, $nav) {

    /*Set page content */
    $page_title = "Register";
    $page_subtitle = "Please fill out the form";
    $page_content = "Register your account";
    $submit_btn = "Submit";
    $navigation = get_navigation($nav, 5);
    $form_action = '/DDWT-Eindopdracht/rooms/register';

    /* Choose Template */
    include use_template('register');

    if (isset($_POST["Submit"])) {
        $feedback = register_user($db, $_POST);
        echo json_encode($feedback);
    }

});

/* Mount for single room views */
$router->mount('/rooms', function () use ($router, $db, $nav) {
    /* GET route: All Rooms Overview */
    $router->get('/', function () use ($db) {
        /* Hier json van functie die alle kamer info ophaalt*/
        printf('<h1>ALL Rooms overview page</h1>');
        $feedback = get_rooms($db);
        echo json_encode($feedback);
    });

    /* GET route: view single room */
    $router->get('/(\d+)', function ($room_id) use ($db) {
        printf('<h1>Single room page</h1>');
        $feedback = get_room_details($db, $room_id);
        echo json_encode($feedback);
    });

    /* GET & POST route: add room */
    $router->match('GET|POST', '/add', function () use ($db, $nav) {
        /* ALLEEN BESCHIKBAAR VOOR OWNERS */

        /*Set page content */
        $page_title = "Add a room";
        $page_subtitle = "Please fill out the form";
        $page_content = "Add your room";
        $submit_btn = "Submit";
        $navigation = get_navigation($nav, 7);
        $form_action = '/DDWT-Eindopdracht/rooms/rooms/add';

        /* Choose Template */
        include use_template('add');

        if (isset($_POST["Submit"])){
            $feedback = add_room($db, $_POST, $username);
            echo json_encode($feedback);
        }
    });

    /* GET & POST route: edit room */
    $router->match('GET|POST', '/edit/(\d+)', function ($room_id) use ($db, $nav) {
        /* ALLEEN BESCHIKBAAR VOOR OWNERS */

        /* hier functies die
        - nieuwe info kunnen posten */
        //$feedback = edit_room($db, $POST, $username);


        /*- bestaande info kunnen ophalen
        - bestaande info kunnen updaten
        - bestaande info kunnen verwijderen */
        //$feedback = remove_room($db, $room_id, $username);

        printf('<h1>Single room EDIT page</h1>');
    });

    $router->delete('/(\d+)', function($id) use($db, $nav) {
        $room_details = get_room_details($db, $id);
        $username = $room_details['owner'];
        $feedback = remove_room($db, $id, $username);
        echo json_encode($feedback);
    });
});

/* ERROR route: route not found */
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    $feedback = [
       "http-code" => 404,
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

