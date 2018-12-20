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
// TODO: Room id veranderen test variables weghalen
$room_id = 1;
$room_info = get_room_details($db, $room_id);
$username = 'Iris';

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
$router->get('/', function () use ($nav) {
    /* todo Hier functie die eventuele error ophaalt uit de POST route */
    /*Set page content */
    $page_title = "Homepage";
    $page_subtitle = "RoomTurbo: Not related to new kids.";
    $page_content = "See nice rooms, meet new owners.";
    $navigation = get_navigation($nav, 0);

    /* Choose Template */
    include use_template('main');
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

/* GET & POST route: Log In */
$router->match('GET|POST', '/login', function () use ($db, $nav){

    /* todo Hier functie die probeert in te loggen en goede feedback meegeeft naar de GET*/

    /*Set page content */
    $page_title = "Log In";
    $page_subtitle = "Log in with your username and password.";
    $page_content = "No account yet? You can register.";
    $submit_btn = "Submit";
    $navigation = get_navigation($nav, 1);
    $form_action = '/DDWT-Eindopdracht/rooms/login';

    /* Choose Template */
    include use_template('login');

    if (isset($_POST["Submit"])) {
        $feedback = login_user($db, $_POST);
        $error_msg = get_error($feedback);
    }

});

/* GET & POST route: Account Overview*/
$router->match('GET|POST', '/account', function () use ($db, $nav, $username) {

    /* todo functie die probeert in te loggen en goede feedback meegeeft naar de GET*/

    /* todo functie die de bestaande info ophaalt en laat updaten -> update_series() :) */

    /*Set page content */
    $page_title = "Account Overview. Hallo $username !";
    $page_subtitle = "View and edit your account information";
    $page_content = "Hier komt het straks, let maar op.";
    $submit_btn = "Submit";
    $navigation = get_navigation($nav, 2);
    $form_action = '/DDWT-Eindopdracht/rooms/account';

    /* Choose Template */
    include use_template('account');

//    if (isset($_POST["Submit"])) {
//        $feedback = edit_user($db, $_POST);
//        $error_msg = get_error($feedback);
//    }
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
        $error_msg = get_error($feedback);
    }

});

/* Mount for single room views */
$router->mount('/rooms', function () use ($router, $db, $nav, $room_info, $username) {
    /* GET route: All Rooms Overview */
    $router->get('/', function () use ($db, $nav) {
        $page_title = "Rooms overview";
        $page_subtitle = "Overview of all the rooms";
        $page_content = get_rooms_table(get_rooms($db));
        $navigation = get_navigation($nav, 5);

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
    $router->match('GET|POST', '/add', function () use ($db, $nav, $username) {
        /* ALLEEN BESCHIKBAAR VOOR OWNERS */

        /*Set page content */
        $page_title = "Add a room";
        $page_subtitle = "Please fill out the form";
        $page_content = "Add your room";
        $submit_btn = "Submit";
        $navigation = get_navigation($nav, 6);
        $form_action = '/DDWT-Eindopdracht/rooms/rooms/add';



        if (isset($_POST["Submit"])){
            $feedback = add_room($db, $_POST, $username);
            $error_msg = get_error($feedback);
        }
        /* Choose Template */
        include use_template('add');
    });

    /* GET & POST route: edit room */
    $router->match('GET|POST', '/edit/(\d+)', function ($room_id) use ($db, $nav, $username) {

        // todo $feedback = edit_room($db, $POST, $username);
        /*Set page content */
        $page_title = "Edit a room";
        $page_subtitle = "Please fill out the form";
        $page_content = "Edit your room";
        $submit_btn = "Submit";
        $navigation = get_navigation($nav, 7);
        $form_action = '/DDWT-Eindopdracht/rooms/rooms/edit';

        /* Choose Template */
        include use_template('add');

        if (isset($_POST["Submit"])) {
            $feedback = edit_room($db, $_POST, $username);
            $error_msg = get_error($feedback);
        }
    });

    $router->delete('/(\d+)', function($id) use($db, $nav) {
        $room_details = get_room_details($db, $id);
        $username = $room_details['owner'];
        $feedback = remove_room($db, $id, $username);
//        get_error($feedback);
    });
});

/* ERROR route: route not found */
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    $feedback = [
       "http-code" => 404,
       "error-message" => "The route you tried to access does not exist.",
       ];
    printf(get_error($feedback));
});

/* Run the router */
$router->run();