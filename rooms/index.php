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
$room_id = $_GET('room_id');
$room_info = get_room_details($db, $room_id);
$user_id = get_user_id();
$username = get_user($db, $user_id);
$user_info = get_user_info($db, $user_id);

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
    8 => Array(
        'name' => 'Logout',
        'url' => '/DDWT-Eindopdracht/rooms/logout'
    ),
);

/* Create Router instance */
$router = new \Bramus\Router\Router();

//* ROUTES *//

/* GET route: Landing Page */
$router->get('/', function () use ($nav) {
    /* Get error msg from POST route */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /*Set page content */
    $page_title = "Homepage";
    $page_subtitle = "RoomTurbo: Not related to new kids.";
    $page_content = "See nice rooms, meet new owners.";
    $navigation = get_navigation($nav, 0);

    /* Choose Template */
    include use_template('main');
});
/* GET route: Log out*/
$router->get('/logout', function () {
    $feedback = logout_user();
    redirect(sprintf('/DDWT-Eindopdracht/rooms/?error_msg=%s', json_encode($feedback)));
});
/* GET route: Contact Page */
$router->get('/contact', function () use ($nav) {
    /*Set page content */
    $page_title = "Contact info";
    $page_subtitle = "Contact us";
    $page_content = "To contact us, mail wessel@roomturbo.nl";
    $navigation = get_navigation($nav, 3);

    /* Choose Template */
    include use_template('main');
});



/* GET route: Log In */
$router->get('/login', function () use ($db, $nav){
    /* Get error msg from POST route */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /*Set page content */
    $page_title = "Log In";
    $page_subtitle = "Log in with your username and password.";
    $page_content = "No account yet? You can register.";
    $submit_btn = "Submit";
    $navigation = get_navigation($nav, 1);
    $form_action = '/DDWT-Eindopdracht/rooms/login';

    /* Choose Template */
    include use_template('login');
});

/* POST route: Log In */
$router->post('/login', function () use ($db){
    /* Try to login */
    /* todo Hier functie die probeert in te loggen */
    $feedback = login_user($db, $_POST);

    /* Redirect to log in GET route */
        redirect(sprintf('/DDWT-Eindopdracht/rooms/login/?error_msg=%s',
            json_encode($feedback)));
});

/* GET route: Account Overview*/
$router->get('/account', function () use ($db, $nav, $user_id, $user_info, $username) {

    /* Get error msg from POST route */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }
    $feedback = get_user_info($db, $user_id);

    /*Set page content */
    $page_title = "Account Overview. Hallo $username]!";
    $page_subtitle = "View and edit your account information";

    /* Page content */
    $name = $user_info['name'];
    $sex = $user_info['sex'];
    $email = $user_info['e_mail'];
    $phone_number = $user_info['phone_number'];
    $birth_date = $user_info['birth_date'];
    $role = $user_info['role'];
    $profession = $user_info['profession'];
    $studies = $user_info['studies'];
    $biography = $user_info['biography'];
    $picture = $user_info['profile_picture'];

    $submit_btn = "Submit";
    $navigation = get_navigation($nav, 2);
    $form_action = '/DDWT-Eindopdracht/rooms/account';

    /* Choose Template */
    include use_template('account');
});

/* POST route: Edit Account */
$router->post('/account', function () use ($db, $username) {
    /* Try to edit account */
    /* todo Hier functie die probeert account info ophaalt en laat updaten -> update_series() :) */
    $feedback = edit_user($db, $_POST, $username);

    /* Redirect to account GET route */
    redirect(sprintf('/DDWT-Eindopdracht/rooms/account/?error_msg=%s',
        json_encode($feedback)));
});

/* GET route: Register */
$router->get('/register', function () use ($db, $nav) {
    /* Get error msg from POST route */
    if (isset($_GET['feedback'])) {
        $feedback = get_error($_GET['feedback']);
    }

    /*Set page content */
    $page_title = "Register";
    $page_subtitle = "Please fill out the form";
    $page_content = "Register your account";
    $submit_btn = "Submit";
    $navigation = get_navigation($nav, 4);
    $form_action = '/DDWT-Eindopdracht/rooms/register';

    /* Choose Template */
    include use_template('register');
});

/* POST route: Register */
$router->post('/register', function () use ($db) {
    /* Try to register user */
    $feedback = register_user($db, $_POST);

    // todo er komt een check in register get die deze redirect naar de homepage,
    // todo dus user registered = user ingelogd dus naar homepage
    /* Redirect to register GET route */
    redirect(sprintf('/DDWT-Eindopdracht/rooms/register/?error_msg=%s',
        json_encode($feedback)));
});

//* MOUNT FOR ROOM VIEWS *//
$router->mount('/rooms', function () use ($router, $db, $nav, $room_info, $username) {

    /* GET route: All Rooms Overview */
    $router->get('/', function () use ($db, $nav) {
        /* Page content */
        $page_title = "Rooms overview";
        $page_subtitle = "Overview of all the rooms";
        $page_content = get_rooms_table(get_rooms($db));
        $navigation = get_navigation($nav, 5);

        /* Choose Template */
        include use_template('main');
    });

    /* GET route: View Single Room */
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

    /* GET route: Add Room */
    $router->get('/add', function () use ($db, $nav, $username) {
        /* Get error msg from POST route */
        if (isset($_GET['error_msg'])) {
            $error_msg = get_error($_GET['error_msg']);
        }

        /*Set page content */
        $page_title = "Add a room";
        $page_subtitle = "Please fill out the form";
        $page_content = "Add your room";
        $submit_btn = "Submit";
        $navigation = get_navigation($nav, 6);
        $form_action = '/DDWT-Eindopdracht/rooms/rooms/add';

        /* Choose Template */
        include use_template('add');
    });

    /* GET route: Add Room */
    $router->post('/add', function () use ($db, $username) {
        /* Add room to database */
        $feedback = add_room($db, $_POST, $username);

        /* Redirect to room GET route */
        redirect(sprintf('/DDWT-Eindopdracht/rooms/rooms/add?error_msg=%s',
            json_encode($feedback)));
    });

    /* GET route: Edit Room */
    $router->get('/edit/(\d+)', function ($room_id) use ($db, $nav, $username) {
        /*Set page content */
        $page_title = "Edit a room";
        $page_subtitle = "Please fill out the form";
        $page_content = "Edit your room";
        $submit_btn = "Submit";
        $navigation = get_navigation($nav, 7);
        $form_action = '/DDWT-Eindopdracht/rooms/rooms/edit';

        /* Choose Template */
        include use_template('add');
    });

    /* POST route: Edit Room */
    $router->post('/edit', function () use ($db, $username) {
        /* Edit room */
        // todo $feedback = edit_room($db, $POST, $username);
        $feedback = edit_room($db, $_POST, $username);

        // todo misschien andere redirect, even testen wat fijn werkt als gebruiker
        /* Redirect to rooms overview GET route */
        redirect(sprintf('/DDWT-Eindopdracht/rooms/rooms/?error_msg=%s',
            json_encode($feedback)));
    });

    /* DELETE route: Delete Room */
    $router->delete('/(\d+)', function($id) use($db, $nav, $username) {
        /* Try to delete room */
        $room_info = get_room_details($db, $id);
        $username = $room_info['owner'];
        //todo check if username from db is same as session username
        $feedback = remove_room($db, $id, $username);

        /* Redirect to rooms overview GET route */
        redirect(sprintf('/DDWT-Eindopdracht/rooms/rooms/?error_msg=%s',
            json_encode($feedback)));
    });
});

/* ERROR route: Route not Found */
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    $feedback = [
       'type' => "Danger",
       "message" => "404 : The route you tried to access does not exist.",
       ];
    printf(get_error(json_encode($feedback)));
});

/* Run the router */
$router->run();