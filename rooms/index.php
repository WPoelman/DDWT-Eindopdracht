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
// TODO: Room id veranderen
$room_id = 1;
$room_info = get_room_details($db, $room_id);

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
    3 => Array(
        'name' => 'Contact',
        'url' => '/DDWT-Eindopdracht/rooms/contact'
    ),
    4 => Array(
        'name' => 'Register',
        'url' => '/DDWT-Eindopdracht/rooms/register'
    ),
    5 => Array(
        'name' => 'Overview',
        'url' => '/DDWT-Eindopdracht/rooms/rooms'
    ),
    6 => Array(
        'name' => 'Add',
        'url' => '/DDWT-Eindopdracht/rooms/rooms/add'
    ),
    7 => Array(
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

    $navigation = get_navigation($nav, 3);

    /* Choose Template */
    include use_template('main');

});

/* GET & POST route: Room opt-ins*/

/* GET & POST route: Log In */
$router->match('GET|POST', '/login', function () use ($db, $nav){
    printf('<h1>Log in page</h1>');

    /* Hier functie die probeert in te loggen en goede feedback meegeeft naar de GET*/

});

/* GET & POST route: Account Overview*/
$router->match('GET|POST', '/account/([a-z0-9_-]+)', function ($username) use ($db, $nav) {
    printf('<h1>Account Overview page</h1>');

    /* todo functie die probeert in te loggen en goede feedback meegeeft naar de GET*/

    /* todo functie die de bestaande info ophaalt en laat updaten -> update_series() :) */

});

/* GET & POST route: Register*/
$router->match('GET|POST', '/register', function () use ($db, $nav) {

    /*Set page content */
    $page_title = "Register";
    $page_subtitle = "Please fill out the form";
    $page_content = "Register your account";
    $submit_btn = "Submit";
    $navigation = get_navigation($nav, 4);
    $form_action = '/DDWT-Eindopdracht/rooms/register';

    /* Choose Template */
    include use_template('register');

    if (isset($_POST["Submit"])) {
        $feedback = register_user($db, $_POST);
        echo json_encode($feedback);
    }

});

/* Mount for single room views */
$router->mount('/rooms', function () use ($router, $db, $nav, $room_info) {
    /* GET route: All Rooms Overview */
    $router->get('/', function () use ($db) {
        $page_title = "Rooms overview";
        $page_subtitle = "Overview of all the rooms";
        $page_content = get_rooms_table(get_rooms($db));

        include use_template('main');
    });

    /* GET route: view single room */
    $router->get('/(\d+)', function ($room_id) use ($db, $room_info, $nav) {

        /* Page info */
        $page_title = sprintf("Information about %s", $room_info['title']);
        $navigation = get_navigation($nav, 5);

        /* Page content */
        $title = $room_info['title'];
        $type = $room_info['type'];
        $owner = $room_info['owner'];
        $street = $room_info['street'];
        $city = $room_info['city'];
        $picture = $room_info['picture'];
        $size = $room_info['size'];
        $price = $room_info['price'];


        /* Choose Template */

        include use_template('room');
    });

    /* GET & POST route: add room */
    $router->match('GET|POST', '/add', function () use ($db, $nav) {
        /* ALLEEN BESCHIKBAAR VOOR OWNERS */

        /*Set page content */
        $page_title = "Add a room";
        $page_subtitle = "Please fill out the form";
        $page_content = "Add your room";
        $submit_btn = "Submit";
        $navigation = get_navigation($nav, 6);
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

        // todo $feedback = edit_room($db, $POST, $username);

        printf('<h1>Single room EDIT page</h1>');
    });

    $router->delete('/(\d+)', function($id) use($db, $nav) {
        $room_details = get_room_details($db, $id);
        $username = $room_details['owner'];
        $feedback = remove_room($db, $id, $username);
        // todo get error echo json_encode($feedback);
    });
});

/* ERROR route: route not found */
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    $feedback = [
       "http-code" => 404,
       "error-message" => "The route you tried to access does not exist.",
       ];
    /// todo get error echo json_encode($feedback);
});

/* Run the router */
$router->run();