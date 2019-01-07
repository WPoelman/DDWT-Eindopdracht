<?php
/**
 * Controller
 * Date: 05-12-2018
 * Time: 15:25
 */

/* Require composer autoloader */
require __DIR__ . '/vendor/autoload.php';

/* Include model.php */
include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'roomturbo', 'roomturbo', 'roomturbo');

/* Credentials */
$username = get_username();
$role = get_user_role();

/* Set the default routes for the navigation bar */
$nav = Array(
    0 => Array(
        'name' => 'Home',
        'url' => '/DDWT-Eindopdracht/rooms'
    ),
    1 => Array(
        'name' => 'My Account',
        'url' => '/DDWT-Eindopdracht/rooms/account'
    ),
    2 => Array(
        'name' => 'Contact',
        'url' => '/DDWT-Eindopdracht/rooms/contact'
    ),
    3 => Array(
        'name' => 'Overview',
        'url' => '/DDWT-Eindopdracht/rooms/rooms'
    ),
    4 => Array(
        'name' => 'Add',
        'url' => '/DDWT-Eindopdracht/rooms/rooms/add'
    ),
    6 => Array(
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
    $page_title = "RoomTurbo";
    $page_subtitle = "Not related to new kids.";
    $page_content = "See nice rooms, meet new owners.";
    $navigation = get_navigation($nav, 0);
    if (check_login()) {
        $login_button = False;
    } else {$login_button = "You don't have an account? ";
    }


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
    $page_title = "Contact info RoomTurbo";
    $page_subtitle = "Contact us";
    $page_content = "To contact us, mail wessel@roomturbo.nl";
    $logo = "/DDWT-Eindopdracht/rooms/images/logo.png";
    $navigation = get_navigation($nav, 2);
    $login_button = False;

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
    $page_title = "Log in";
    $page_subtitle = "Log in with your username and password.";
    $page_content = "No account yet? You can register.";
    $navigation = get_navigation($nav, 1);

    /* Choose Template */
    include use_template('login');
});

/* POST route: Opt In*/
$router->post('/optin', function () use ($db){
    /* Try to login */
    $feedback = add_optin($db, $_POST);
    /* Redirect to log in GET route */
    redirect(sprintf('/DDWT-Eindopdracht/rooms/rooms/?error_msg=%s',json_encode($feedback)));
});

/* POST route: Log In */
$router->post('/login', function () use ($db){
    /* Try to login */
    $feedback = log_in($db, $_POST);
    /* Redirect to log in GET route */
    redirect(sprintf('/DDWT-Eindopdracht/rooms/login/?error_msg=%s',json_encode($feedback)));
});

/* GET route: Account Overview*/
$router->get('/account', function () use ($db, $nav, $username) {

    /* Check if the user is logged in */
     if (!check_login()) {
         redirect(sprintf('/DDWT-Eindopdracht/rooms/login/?error_msg=%s',
             json_encode([
                 'type' => 'danger',
                 'message' => "You don't have an account yet, please log in or register"
             ])));
     };

    /* Get error msg from POST route */
    if (isset($_GET['error_msg'])) {
        $error_msg = get_error($_GET['error_msg']);
    }

    $user_info = get_user_info($db, $username);
    $full_name = get_fullname($db, $username);
    $optins = get_optins($db, $username);
    $optins_table = get_optins_table($optins);
    /*Set page content */
    $page_title = "Account overview. Hello $full_name!";
    $page_subtitle = "View and edit your account information";

    /* Page content */
    $page_content = "Your info";
    $name = $user_info['username'];
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
    $navigation = get_navigation($nav, 1);
    $form_action = '/DDWT-Eindopdracht/rooms/account';

    /* Choose Template */
    include use_template('account');
});

/* GET route: Edit account */
$router->get('/account/edit', function() use ($nav, $db, $username){
    if (isset($_GET['feedback'])) {
        $feedback = get_error($_GET['feedback']);
    }
    $user_info = get_user_info($db, $username);
    $full_name = get_fullname($db, $username);
    /*Set page content */
    $page_title = "Edit your Account";
    $page_subtitle = "$full_name";
    $page_content = "Edit the information below";
    $submit_btn = "Edit";
    $navigation = get_navigation($nav, null);
    $form_action = '/DDWT-Eindopdracht/rooms/account/edit';

    include use_template('register');
});


//    /* POST route: Edit Account */
//    $router->post('/account', function () use ($db, $username) {
//        $feedback = edit_user($db, $_POST, $username);
//        /* Redirect to account GET route */
//        redirect(sprintf('/DDWT-Eindopdracht/rooms/account/?error_msg=%s',
//            json_encode($feedback)));
//    });

/* DELETE route: optin*/
$router->post('/optin/delete', function () use ($db, $username){
    $feedback = remove_optin($db, $_POST["room"], $username);
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
    $navigation = get_navigation($nav, null);
    $form_action = '/DDWT-Eindopdracht/rooms/register';

    /* Choose Template */
    include use_template('register');
});

/* POST route: Register */
$router->post('/register', function () use ($db) {
    /* Try to register user */
    $feedback = register_user($db, $_POST, $_FILES);

    /* Redirect to register GET route */
    redirect(sprintf('/DDWT-Eindopdracht/rooms/register/?error_msg=%s',
        json_encode($feedback)));
});

//* MOUNT FOR ROOM VIEWS *//
$router->mount('/rooms', function () use ($router, $db, $nav, $username) {

    /* GET route: All Rooms Overview */

    $router->get('/', function () use ($db, $nav) {
        if (isset($_GET['error_msg'])) {
            $error_msg = get_error($_GET['error_msg']);
        }
        /* Page content */
        $page_title = "Rooms overview";
        $page_subtitle = "Overview of all the rooms";
        $page_content = get_rooms_table(get_rooms($db));
        $navigation = get_navigation($nav, 3);
        $login_button = False;

        /* Choose Template */
        include use_template('main');
    });

    /* GET route: View Single Room */
    $router->get('/room/', function () use ($db, $nav, $username) {
        /* Check if the user is logged in */
        if (!check_login()) {
            redirect(sprintf('/DDWT-Eindopdracht/rooms/login/?error_msg=%s',
                json_encode([
                    'type' => 'danger',
                    'message' => "You don't have an account yet, please log in or register"
                ])));
        };
        // todo: check if logged in user is the same as editor
        // todo: TEST
        // $display_buttons = False;
        // if ($_SESSION['username'] == $room_info['owner']) {
        //   $display_buttons = True;
        //}
        $room_id = $_GET['room_id'];
        $room_info = get_room_details($db, $room_id);

        // todo: display buttons check toevoegen
        $display_buttons = True;

        /* Page info */
        $page_title = "Information about:";
        $page_subtitle = sprintf($room_info['title']);
        $navigation = get_navigation($nav, 3);
        $right_column = use_template('optin');

        /* Page content */
        $page_content = "Room info";
        $description = $room_info['description'];
        $number = $room_info['number'];
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
        // todo: TESTEN if (check_role()) {

        /* Check if the user is logged in */
        if (!check_login()) {
            redirect(sprintf('/DDWT-Eindopdracht/rooms/login/?error_msg=%s',
                json_encode([
                    'type' => 'danger',
                    'message' => "You don't have an account yet, please log in or register"
                ])));
        };

        /* Get error msg from POST route */
        if (isset($_GET['error_msg'])) {
            $error_msg = get_error($_GET['error_msg']);
        }

        /*Set page content */
        $page_title = "Add a room";
        $page_subtitle = "Please fill out the form";
        $page_content = "Add your room";
        $submit_btn = "Submit";
        $navigation = get_navigation($nav, 4);
        $form_action = '/DDWT-Eindopdracht/rooms/rooms/add';

        /* Choose Template */
        include use_template('add');
    });

    /* POST route: Add Room */
    $router->post('/add', function () use ($db, $username) {
        /* Add room to database */
        // todo: TESTEN if (check_role()) {
        $feedback = add_room($db, $_POST, $username);

        /* Redirect to room GET route */
        redirect(sprintf('/DDWT-Eindopdracht/rooms/rooms/add?error_msg=%s',
            json_encode($feedback)));
    });

    /* GET route: Edit Room */
    $router->get('/edit', function () use ($db, $nav, $username) {
        /*Set page content */
        // todo: TESTEN if (check_role()){
        /* Check if the user is logged in */
        if (!check_login()) {
            redirect(sprintf('/DDWT-Eindopdracht/rooms/login/?error_msg=%s',
                json_encode([
                    'type' => 'danger',
                    'message' => "You don't have an account yet, please log in or register"
                ])));
        };
        /* Retrieve existing room info */
        $room_id = $_GET['room_id'];
        $room_info = get_room_details($db, $room_id);

        $page_title = "Edit a room";
        $page_subtitle = "Please fill out the form";
        $page_content = "Edit your room";
        $submit_btn = "Submit";
        $navigation = get_navigation($nav, 5);
        $form_action = '/DDWT-Eindopdracht/rooms/rooms/edit';

        /* Choose Template */
        include use_template('add');
    });

    /* POST route: Edit Room */
    $router->post('/edit', function () use ($db, $username) {
        // todo: TESTEN if (check_role()){
        /* Edit room */
        $room_id = $_POST['room_id'];
        $room_info_old = get_room_details($db, $room_id);
        $feedback = edit_room($db, $_POST, $room_info_old, $username);

        /* Redirect to rooms overview GET route */
        redirect(sprintf('/DDWT-Eindopdracht/rooms/rooms/?error_msg=%s',
            json_encode($feedback)));
    });

    /* DELETE route: Delete Room */
    $router->post('/delete', function() use($db, $nav, $username) {
        /* Try to delete room */
        // todo: if (check_role())
        $room_id = $_POST["room_id"];
        $feedback = remove_room($db, $room_id, $username);
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