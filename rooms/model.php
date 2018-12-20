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
 * Creates filename to the template
 * @param string $template filename of the template without extension
 * @return string
 */
function use_template($template){
    $template_doc = sprintf("views/%s.php", $template);
    return $template_doc;
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
 * Get rooms from database
 * @param $pdo
 * @return array
 */

function get_rooms($pdo){
    $stmt = $pdo->prepare('SELECT * FROM room');
    $stmt->execute();
    $room_info = $stmt->fetchAll();

    $stmt2 = $pdo->prepare('SELECT * FROM room_address ');
    $stmt2->execute();
    $room_info += ($stmt2->fetchAll());
    $room_info_exp = Array();


    /* Create array with htmlspecialchars */
    foreach ($room_info as $key => $room_array){
        foreach ($room_array as $room_key => $value) {
            $room_info_exp[$key][$room_key] = htmlspecialchars($value);
        }
    }
    return $room_info_exp;
}

/** Make room overview table
 * @param $rooms
 * @return $table_exp
 */


function get_rooms_table($rooms){
    $table_exp = '
        <table class = "table table-hover">
        <thead>
        <tr>
            <th scope="col" class="col-sm-8"> Title </th>
            <th scope="col" class="col-sm-1"> Size </th>
            <th scope="col" class="col-sm-1"> Price </th>
            <th scope="col" class="col-sm-2"> Photo </th>
        </tr>
        </thead>
        <tbody>';
         foreach($rooms as $key => $value){
             $table_exp .= '
        <tr>
            <th scope="row">'.$value['title'].'</th>
            <th scope="row">'.$value['size'].'</th>
            <th scope="row">'.$value['price'].'</th>
            <th scope="row"><img src="'.$value['picture'].'" class="img-thumbnail" alt="room photo"</th>
            <td><a href="/DDWT-Eindopdracht/rooms/rooms/?room_id='.$value['id'].'" role="button" class="btn btn-primary">Show details</a></td>
        </tr>
        ';
         }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}
/**
 * Generates an array with room details
 * @param $pdo
 * @param $room_id
 * @return $room_id_exp
 */
function get_room_details($pdo, $room_id){
    $stmt = $pdo->prepare('SELECT * FROM room WHERE id = ?');
    $stmt->execute([$room_id]);
    $room_info = $stmt->fetch();

    $stmt2 = $pdo->prepare('SELECT * FROM room_address WHERE zip_code = ? AND number = ? ');
    $stmt2->execute([$room_info['zip_code'], $room_info['number']]);
    $room_info += ($stmt2->fetch());
    $room_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($room_info as $key => $value){
        $room_info_exp[$key] = htmlspecialchars($value);
    }
    return $room_info_exp;
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

}

/**
 * Allow an existing user to login using their credentials
 * @param $pdo
 * @param $form_data
 * @return array
 */
function log_in($pdo, $form_data){
    /* Check if there are no empty values */
    if (
        empty($form_data['username']) or
        empty($form_data['password'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'You should enter a username and password to login.'
        ];
    }

    /* Check if user exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->execute([$form_data['username']]);
        $user_info = $stmt->fetch();
    } catch (PDOException $e){
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Return error message for wrong username */
    if (empty($user_info)){
        return [
            'type' => 'danger',
            'message' => 'The username you entered does not exist!'
        ];
    }

    /* Check password */
    if ( !password_verify($form_data['password'], $user_info['password']) ){
        return [
            'type' => 'danger',
            'message' => 'The password you entered is incorrect!'
        ];
    } else {
        session_start();
        $_SESSION['user_id'] = $user_info['ID'];
        $feedback = [
            'type' => 'success',
            'message' => sprintf('%s, you were logged in successfully!',
                get_user($pdo, $_SESSION['user_id']))
        ];
        redirect(sprintf('/DDWT18/week2/myaccount/?error_msg=%s',
            json_encode($feedback)));
    }
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
        empty($room_info['title']) or
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
    if (!is_numeric($room_info['size']) and !is_numeric($room_info['price'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the size and price fields.'
        ];
    }

    /* niet zeker hier, hoe checken we of de room al bestaat? id wordt pas later toegevoegd

    /* Add to room_adress */
    $stmt = $pdo->prepare("INSERT INTO room_address (zip_code, number, street, city) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $room_info['zip_code'],
        $room_info['number'],
        $room_info['street'],
        $room_info['city']
    ]);
    $inserted = $stmt->rowCount();
    if ($inserted == 1) {
        $stmt2 = $pdo->prepare("INSERT INTO room (owner, title, size, picture, price, description, type, zip_code, number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->execute([
            $username,
            $room_info['title'],
            $room_info['size'],
            $room_info['picture'],
            $room_info['price'],
            $room_info['description'],
            $room_info['type'],
            $room_info['zip_code'],
            $room_info['number']
        ]);
        $inserted = $stmt2->rowCount();
        if ($inserted == 1) {
            return [
                'type' => 'success',
                'message' => sprintf("Room is successfully added!")
            ];
        } else {
            return [
                'type' => 'danger',
                'message' => 'There was an error. The room was not added to the room table. Try it again.'
            ];
        }
    }else{
        return [
            'type' => 'danger',
            'message' => 'There was an error. The room was not added to the address table. Try it again.'
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