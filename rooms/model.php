<?php
/**
 * Model
 * Date: 4-12-2018
 * Time: 15:47
 */

/* Enable error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Connects to the database using PDO
 * @param string $host database host
 * @param string $db database name
 * @param string $user database user
 * @param string $pass database password
 * @return pdo object
 */
function connect_db($host, $db, $user, $pass){
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        echo sprintf("Failed to connect. %s",$e->getMessage());
    }
    return $pdo;
}

/**
 * Check if the route exist
 * @param string $route_uri URI to be matched
 * @param string $request_type request method
 * @return bool
 *
 */
function new_route($route_uri, $request_type){
    $route_uri_expl = array_filter(explode('/', $route_uri));
    $current_path_expl = array_filter(explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($route_uri_expl == $current_path_expl && $_SERVER['REQUEST_METHOD'] == strtoupper($request_type)) {
        return True;
    }
}

/**
 * Creates a new navigation array item using url and active status
 * @param string $url The url of the navigation item
 * @param bool $active Set the navigation item to active or inactive
 * @return array
 */
function na($url, $active){
    return [$url, $active];
}

/**
 * Creates filename to the template
 * @param string $template filename of the template without extension
 * @return string
 */
function use_template($template){
    $template_doc = sprintf("views/%s.php", $template);
    return $template_doc;
}

/**
 * Creates breadcrumb HTML code using given array
 * @param array $breadcrumbs Array with as Key the page name and as Value the corresponding url
 * @return string html code that represents the breadcrumbs
 */
function get_breadcrumbs($breadcrumbs) {
    $breadcrumbs_exp = '<nav aria-label="breadcrumb">';
    $breadcrumbs_exp .= '<ol class="breadcrumb" >';
    foreach ($breadcrumbs as $name => $info) {
        if ($info[1]){
            $breadcrumbs_exp .= '<li class="breadcrumb-item active " aria-current="page">'.$name.'</li>';
        }else{
            $breadcrumbs_exp .= '<li class="breadcrumb-item"><a href="'.$info[0].'">'.$name.'</a></li>';
        }
    }
    $breadcrumbs_exp .= '</ol>';
    $breadcrumbs_exp .= '</nav>';
    return $breadcrumbs_exp;
}

/**
 * Creates navigation HTML code using given array
 * @param $template
 * @param $active_id
 * @return string html code that represents the navigation
 */
function get_navigation($template, $active_id){
    $navigation_exp = '
    <nav class="navbar navbar-expand-lg navbar-light bg-info">
    <a class="navbar-brand">RoomTurbo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">';
    foreach ($template as $id => $info) {
        if ($id == $active_id){
            $navigation_exp .= '<li class="nav-item active">';
            $navigation_exp .= '<a class="nav-link" href="'.$info['url'].'">'.$info['name'].'</a>';
        }else{
            $navigation_exp .= '<li class="nav-item">';
            $navigation_exp .= '<a class="nav-link" href="'.$info['url'].'">'.$info['name'].'</a>';
        }

        $navigation_exp .= '</li>';
    }
    $navigation_exp .= '
    </ul>
    </div>
    </nav>';
    return $navigation_exp;
}

/**
 * Register new users and assign the values to database
 * @param $pdo
 * @param $form_data
 * @return array
 */
function register_user($pdo, $form_data){
    /* Check if there are no empty values */
    if (
        empty($form_data['username']) or
        empty($form_data['password']) or
        empty($form_data['firstname']) or
        empty($form_data['lastname']) or
        empty($form_data['birthdate']) or
        empty($form_data['email']) or
        empty($form_data['phonenumber'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'You should fill in all required fields.'
        ];
    }

    /* Check if user already exists*/
    try {
        $stmt = $pdo->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->execute([$form_data['username']]);
        $user_exists = $stmt->rowCount();
    } catch (PDOException $e){
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Return error message if user already exists */
    if (!empty($user_exists)){
        return [
            'type' => 'danger',
            'message' => 'The username you entered already exists!'
        ];
    }

    /* Hash password */
    $password = password_hash($form_data['password'], PASSWORD_DEFAULT);

    /* Save user to database */
    try {
        $stmt = $pdo->prepare('INSERT INTO user (username, password, firstname, lastname, birthdate, sex, email, role, phonenumber, studies, profession, biography, picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$form_data['username'], $password, $form_data['firstname'], $form_data['lastname'], $form_data['birthdate'], $form_data['sex'], $form_data['email'], $form_data['role'], $form_data['phonenumber'], $form_data['studies'], $form_data['profession'], $form_data['biography'], $form_data['picture']]);
        $username = $pdo->lastInsertusername();
    } catch (PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Login user and redirect */
    session_start();
    $_SESSION['username'] = $username;
    $feedback = [
        'type' => 'success',
        'message' => sprintf('%s, your account was successfully created!', get_user($pdo, $_SESSION['username']))
    ];
    redirect(sprintf('/DDWT18/week2/myaccount/?error_msg=%s', json_encode($feedback)));
}

/**
 * Add room to the database
 * @param object $pdo db object
 * @param array $room_info post array
 * @param integer $username user that adds the series
 * @return array with message feedback
 */
function add_room($pdo, $room_info, $username)
{
    /* Check if all required fields are set */
    if (
        empty($room_info['size']) or
        empty($room_info['price']) or
        empty($room_info['type']) or
        empty($room_info['zip_code']) or
        empty($room_info['number'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all required fields were filled in.'
        ];
    }

    /* Check data type */
    if (!is_numeric($room_info['size'] and $room_info['price'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the size and price fields.'
        ];
    }

    /* niet zeker hier, hoe checken we of de room al bestaat? id wordt pas later toegevoegd.... ook de dingen die mogen
    zoals de foto en description nog even checken
//    /* Check if room already exists */
//    $stmt = $pdo->prepare('SELECT * FROM room WHERE id = ?');
//    $stmt->execute([$room_info['Name']]);
//    $serie = $stmt->rowCount();
//    if ($serie) {
//        return [
//            'type' => 'danger',
//            'message' => 'This series was already added.'
//        ];
//    }

    /* Add Room */
    $stmt = $pdo->prepare("INSERT INTO room (owner, size, price, type, zip_code, number) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $username,
        $room_info['size'],
        $room_info['price'],
        $room_info['type'],
        $room_info['zip_code'],
        $room_info['number']
    ]);
    $inserted = $stmt->rowCount();
    if ($inserted == 1) {
        return [
            'type' => 'success',
            'message' => sprintf("Room is successfully added!")
        ];
    } else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. The room was not added. Try it again.'
        ];
    }
}

/**
 * Removes a room with a specific room_id
 * @param object $pdo db object
 * @param int $room_id id of the to be deleted room
 * @param string $username username of the owner
 * @return array
 */
function remove_room($pdo, $room_id, $username)
{
    /* Get room info */
    $room_info = get_roominfo($pdo, $room_id);

    /* Check if the user is allowed to edit the serie */
    if ($room_info['username'] =! $username){
        return [
            'type' => 'danger',
            'message' => 'There was an error. You are not allowed to remove this room.'];
    }

    /* Delete room */
    $stmt = $pdo->prepare("DELETE FROM room WHERE id = ?");
    $stmt->execute([$room_id]);
    $deleted = $stmt->rowCount();
    if ($deleted == 1) {
        return [
            'type' => 'success',
            'message' => sprintf("Room was successfully removed")
        ];
    } else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. The room was not removed.'
        ];
    }
}

/**
 * Creats HTML alert code with information about the success or failure
 * @param bool $feedback True if success, False if failure
 * @return string
 */
function get_error($feedback){
    $error_exp = '
        <div class="alert alert-'.$feedback['type'].'" role="alert">
            '.$feedback['message'].'
        </div>';
    return $error_exp;
}