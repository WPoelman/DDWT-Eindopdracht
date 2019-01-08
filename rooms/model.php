<?php
/**
 * Model
 * User: DDWT-18 Group 12
 * Date: 21-12-2018
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
 * @return object $pdo
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
    <a class="navbar-brand" href="/DDWT-Eindopdracht/rooms/"><img src="/DDWT-Eindopdracht/rooms/images/logo.png" height=""50 width="50" ></a>
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
 * @return string $table_exp
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
            <th scope="row"><img src="images/rooms/'.$value['picture'].'" class="img-thumbnail" alt="room photo"</th>
            <td><a href="/DDWT-Eindopdracht/rooms/rooms/room/?room_id='.$value['id'].'" role="button" class="btn btn-info">Show details</a></td>
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
 * Get current username
 * @return bool current user id or False if not logged in
 */
function get_username(){
    session_start();
    if (isset($_SESSION['username'])){
        return $_SESSION['username'];
    } else {
        return False;
    }
}

/**
 * Checks if user is logged in
 * @return bool
 */
function check_login(){
    if (isset($_SESSION['username'])) {
        return True;
    } else {
        return False;
    }
}

/**
 * Gives the users role
 * @return String role or False if not set
 */

function get_user_role(){
    if (isset($_SESSION['role'])){
        return $_SESSION['role'];
    } else {
        return False;
    }
};


/**
 * Register new users and assign the values to database
 * @param $pdo database object
 * @param $form_data $_POST form data
 * @param $file $_FILES post data (profile picture)
 * @return array
 */
function register_user($pdo, $form_data, $file){
    /* Check if there are no empty values */
    if (
        empty($form_data['username']) or
        empty($form_data['password']) or
        empty($form_data['first_name']) or
        empty($form_data['last_name']) or
        empty($form_data['birthdate']) or
        empty($form_data['email'])
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

    /* Save image to the server */
    if ($file != Null) {
        $image_name = basename($file["picture"]["name"]);
        $target_dir = "images/users/";
        $target_file = $target_dir . basename($file["picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        /* Check if image file is a actual image or fake image */
        if($check = getimagesize($file["picture"]["tmp_name"]) == false) {
            return [
            'type' => 'danger',
            'message' => 'The profile picture is not a supported file format!'
            ];
        }
    /* Check if file already exists */
        if (file_exists($target_file)) {
            return [
                'type' => 'danger',
                'message' => 'The profile picture a;ready exists, try a different name!'
            ];
        }
    /* Check file size */
        if ($file["picture"]["size"] > 500000) {
            return [
                'type' => 'danger',
                'message' => 'The profile picture is too big!'
            ];
        }
    /* Allow certain file formats */
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            return [
                'type' => 'danger',
                'message' => 'Only jpg, jpeg, png and gif files are allowed as profile picture. '
            ];
        }
    /* if everything is ok, try to upload file */
        $target_dir = "images/users/";
        $target_file = $target_dir . basename($file["picture"]["name"]);
        move_uploaded_file($file["picture"]["tmp_name"], $target_file);
    } else {
        $image_name = Null;
    }

    /* Save user to database */
    try {
        $stmt = $pdo->prepare('INSERT INTO user (username, password, first_name, last_name, birth_date, sex, e_mail, role, phone_number, studies, profession, biography, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $form_data['username'],
            $password,
            $form_data['first_name'],
            $form_data['last_name'],
            $form_data['birthdate'],
            $form_data['sex'],
            $form_data['email'],
            $form_data['role'],
            $form_data['phonenumber'],
            $form_data['studies'],
            $form_data['profession'],
            $form_data['biography'],
            $image_name
        ]);

    } catch (PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Login user and redirect */
    session_start();
    $_SESSION['username'] = $form_data['username'];
    $_SESSION['role'] = $form_data['role'];
    $feedback = [
        'type' => 'success',
        'message' => sprintf('%s, your account was successfully created!', get_fullname($pdo, $_SESSION['username']))
    ];
    redirect(sprintf('/DDWT-Eindopdracht/rooms/?error_msg=%s', json_encode($feedback)));
}

/**
 * Get full name of users
 * @param $pdo
 * @param $username
 * @return array
 */
function get_fullname($pdo, $username){
    $stmt = $pdo->prepare('SELECT first_name, last_name FROM user where username = ?');
    $stmt->execute([$username]);
    $user_name = $stmt->fetch();
    return sprintf("%s %s", htmlspecialchars($user_name['first_name']), htmlspecialchars($user_name['last_name']));
}

/**
 * Gets all info from users
 * @param $pdo
 * @param $username
 * @return array
 */
function get_user_info($pdo, $username){
    $stmt = $pdo->prepare('SELECT * FROM user WHERE username = ?');
    $stmt->execute([$username]);
    $user_info = $stmt->fetch();
    $user_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($user_info as $key => $value) {
        $user_info_exp[$key] = htmlspecialchars($value);
    }
    return $user_info_exp;
}

/**
 * Changes the password of the user
 * @param $pdo
 * @param $username
 * @param $form_data
 * @return array
 */
function change_password($pdo, $username, $form_data){
    /* Check if there are no empty values */
    if (
        empty($form_data['oldpassword']) or
        empty($form_data['newpassword']) or
        empty($form_data['newpassword2'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'You should enter your old and new password.'
        ];
    }

    $user_info = get_user_info($pdo, $username);
    /* Check password */
    if ( !password_verify($form_data['oldpassword'], $user_info['password']) ){
        return [
            'type' => 'danger',
            'message' => 'The old password you entered is incorrect!'
        ];
    }

    if ($form_data['newpassword'] != $form_data['newpassword2']){
        return [
            'type' => 'danger',
            'message' => 'You did not enter the same new password twice.'
        ];
    }

    /* Hash password */
    $password = password_hash($form_data['newpassword'], PASSWORD_DEFAULT);

    /* Saving password */
    $stmt = $pdo->prepare("UPDATE user SET password = ? WHERE username = ?");
    $stmt->execute([
        $password,
        $username
    ]);
    /* Check if it worked */
    $updated = $stmt->rowCount();
    if ($updated == 1) {
        return [
            'type' => 'success',
            'message' => 'Your passsword is successfully edited!'
        ];
    } else {
        return [
            'type' => 'warning',
            'message' => 'No changes detected or something went wrong. Please try again.'
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
        $_SESSION['username'] = $user_info['username'];
        $_SESSION['role'] = $form_data['role'];
        $feedback = [
            'type' => 'success',
            'message' => sprintf('%s, you were logged in successfully!',
                get_fullname($pdo, $_SESSION['username']))
        ];
        redirect(sprintf('/DDWT-Eindopdracht/rooms/?error_msg=%s',
            json_encode($feedback)));
    }
}

function add_optin($pdo, $optin_info){
    if (
        empty($optin_info['username']) or
        empty($optin_info['id'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error.'
        ];
    }

    $stmt = $pdo->prepare("INSERT INTO opt_in (message, username, id) VALUES (?, ?, ?)");
    $stmt->execute([
        $optin_info['message'],
        $optin_info['username'],
        $optin_info['id']
    ]);
    $inserted = $stmt->rowCount();
    if ($inserted == 1) {
        return [
            'type' => 'success',
            'message' => 'You have send a message!'
        ];
    } else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. The message was not send. Try it again.'
        ];
    }
}

/**
 * Add room to the database
 * @param object $pdo db object
 * @param array $room_info post array
 * @param integer $username user that adds the room
 * @param mixed $file $_FILES array if there is an image, NULL if not
 * @return array with message feedback
 */
function add_room($pdo, $room_info, $username, $file)
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

    /* Save image to the server */
    if ($file != Null) {
        $image_name = basename($file["picture"]["name"]);
        $target_dir = "images/rooms/";
        $target_file = $target_dir . basename($file["picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        /* Check if image file is a actual image or fake image */
        if($check = getimagesize($file["picture"]["tmp_name"]) == false) {
            return [
                'type' => 'danger',
                'message' => 'The picture was not a supported file format!'
            ];
        }
        /* Check if file already exists */
        if (file_exists($target_file)) {
            return [
                'type' => 'danger',
                'message' => 'The picture already exists, try a different name!'
            ];
        }
        /* Check file size */
        if ($file["picture"]["size"] > 500000) {
            return [
                'type' => 'danger',
                'message' => 'The picture is too big!'
            ];
        }
        /* Allow certain file formats */
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            return [
                'type' => 'danger',
                'message' => 'Only jpg, jpeg, png and gif files are allowed as picture. '
            ];
        }
        /* if everything is ok, try to upload file */
        $target_dir = "images/rooms/";
        $target_file = $target_dir . basename($file["picture"]["name"]);
        move_uploaded_file($file["picture"]["tmp_name"], $target_file);
    } else {
        $image_name = Null;
    }

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
        /* Add room to room table */
        $stmt2 = $pdo->prepare("INSERT INTO room (owner, title, size, picture, price, description, type, zip_code, number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->execute([
            $username,
            $room_info['title'],
            $room_info['size'],
            $image_name,
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
                'message' => 'Room is successfully added!'
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
 * @param $pdo
 * @param $form_data
 * @param $file
 * @param $username
 * @return array
 */
function edit_user($pdo, $form_data, $username){
    /* check if all required fields are filled in */
    if (
        empty($form_data['username']) or
        empty($form_data['first_name']) or
        empty($form_data['last_name']) or
        empty($form_data['birthdate']) or
        empty($form_data['email'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'You should fill in all required fields.'
        ];
    }

    $stmt = $pdo->prepare("UPDATE user SET username = ?, first_name = ?, last_name = ?, birth_date = ?, sex = ?, e_mail = ?, role = ?, phone_number = ?, studies = ?, profession = ?, biography = ?  WHERE username = ?");
    $stmt->execute([
        $form_data['username'],
        $form_data['first_name'],
        $form_data['last_name'],
        $form_data['birthdate'],
        $form_data['sex'],
        $form_data['email'],
        $form_data['role'],
        $form_data['phonenumber'],
        $form_data['studies'],
        $form_data['profession'],
        $form_data['biography'],
        $username
    ]);
    /* Check if it worked */
    $updated = $stmt->rowCount();
    if ($updated == 1) {
        return [
            'type' => 'success',
            'message' => 'Your account is successfully edited!'
        ];
    } else {
        return [
            'type' => 'warning',
            'message' => 'No changes detected or something went wrong. Please try again.'
        ];
    }
}


/**
 * Updates a room in the database using post array
 * @param object $pdo db object
 * @param array $room_info post array
 * @param array $room_info_old old room info to look up the address
 * @param integer $username username from the session info
 * @return array
 */
function edit_room($pdo, $room_info, $room_info_old, $username)
{
    /* Check if the user is allowed to edit the room */
    if ($room_info['owner'] = !$username) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You are not allowed to edit this room.'];
    }

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

    /* Update address table*/
    $stmt = $pdo->prepare("UPDATE room_address SET zip_code = ?, number = ?, street = ?, city = ? WHERE zip_code = ? AND number = ?");
    $stmt->execute([
        $room_info['zip_code'],
        $room_info['number'],
        $room_info['street'],
        $room_info['city'],
        $room_info_old['zip_code'],
        $room_info_old['number']
    ]);

    /* Update room table */
    $stmt2 = $pdo->prepare("UPDATE room SET title = ?, size = ?, price = ?, description = ?, type = ?, zip_code = ?, number = ? WHERE id = ?");
    $stmt2->execute([
        $room_info['title'],
        $room_info['size'],
        $room_info['price'],
        $room_info['description'],
        $room_info['type'],
        $room_info['zip_code'],
        $room_info['number'],
        $room_info['room_id']
    ]);

    /* Check if it worked */
    $updated_1 = $stmt->rowCount();
    $updated_2 = $stmt2->rowCount();
    if (($updated_1 == 1) or ($updated_2 == 1)) {
        return [
            'type' => 'success',
            'message' => 'Room is successfully edited!'
        ];
    } else {
        return [
            'type' => 'warning',
            'message' => 'No changes detected or something went wrong. Please try again.'
        ];
    }
}

/**
 * Get all the opt-ins of a specific user
 * @param $pdo
 * @param $username
 * @return array
 */
function get_optins($pdo, $username){
    $stmt = $pdo->prepare('SELECT * FROM opt_in WHERE username = ? ');
    $stmt->execute([$username]);
    $opt_ins = $stmt->fetchAll();
    $opt_ins_exp = Array();
    /* Create array with htmlspecialchars */
    foreach ($opt_ins as $key => $optins_array){
        foreach ($optins_array as $optins_key => $value){
            $opt_ins_exp[$key][$optins_key] = htmlspecialchars($value);
        }
    }
    return $opt_ins_exp;
}

/**
 * Makes a table for all given opt-ins
 * @param $opt_ins
 * @param $user_role
 * @return string table
 */
function get_optins_table($opt_ins, $user_role){
    if ($user_role == 'tenant') {
        $table_exp_tenant = '
            <table class = "table table-hover">
            <thead>
            <tr>
                <th scope="col" class="col-sm-6"> Message </th>
                <th scope="col" class="col-sm-1"> Room </th>
                <th scope="col" class="col-sm-1"> Delete</th>
             </tr>
            </thead>
            <tbody>';
        foreach ($opt_ins as $key => $value) {
            $table_exp_tenant .= '
            <tr>
                <td scope="row">' . $value['message'] . '</td>
                <td scope="row">' . $value['id'] . '</td>
                <td><form action="/DDWT-Eindopdracht/rooms/optin/delete" method="post">
                    <a href="/DDWT-Eindopdracht/rooms/account"></a>
                    <input type="hidden" name="room" value=' . $value['id'] . '/>
                    <button type="submit" class="btn btn-primary"> Delete </button>
                </form></td>
            </tr>
        ';
        }
        $table_exp_tenant .= '
    </tbody>
    </table>
    ';
        return $table_exp_tenant;
    } else {
        $table_exp_owner = '
            <table class = "table table-hover">
            <thead>
            <tr>
                <th scope="col" class="col-sm-5"> Message </th>
                <th scope="col" class="col-sm-1"> Room </th>
                <th scope="col" class="col-sm-1"> User</th>
                <th scope="col" class="col-sm-1"></th>
                <th scope="col" class="col-sm-1"></th>
             </tr>
            </thead>
            <tbody>';
        foreach ($opt_ins as $key => $value) {
            $table_exp_owner .= '
            <tr>
                <td scope="row">' . $value['message'] . '</td>
                <td scope="row">' . $value['id'] . '</td>
                <td scope="row">' . $value['username'].'</td>
                <td><a href="/DDWT-Eindopdracht/rooms/account-view/?username=' . $value['username'] . '" role="button" class="btn btn-info"> Show User Details</a></td>
                <td><form action="/DDWT-Eindopdracht/rooms/optin/delete" method="post">
                    <a href="/DDWT-Eindopdracht/rooms/account"></a>
                    <input type="hidden" name="room" value=' . $value['id'] . '/>
                    <button type="submit" class="btn btn-primary"> Delete </button>
                </form></td>
            </tr>
        ';
        }
        $table_exp_owner .= '
    </tbody>
    </table>
    ';
        return $table_exp_owner;
    }
}

/**
 * Removes a user account
 * @param $pdo
 * @param $username
 * @return array
 */
function remove_user($pdo, $username){
    /* Get user info to check if there is a picture */
    $user_info = get_user_info($pdo, $username);

    /* Remove picture */
    if ($user_info['profile_picture'] != Null) {
        if (!unlink("images/users/".$user_info['profile_picture'])){
            return [
                'type' => 'danger',
                'message' => 'Something went wrong with deleting the profile picture.'
            ];
        }
    }

    /* Delete user from db */
    $stmt = $pdo->prepare("DELETE FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $deleted = $stmt->rowCount();
    if ($deleted == 1) {
        return [
            'type' => 'success',
            'message' => sprintf("Your account was successfully removed")
        ];
    } else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. Your account was not removed.'
        ];
    }

}

/**
 * Removes an optin
 * @param object $pdo db object
 * @param int $id of room
 * @param string $username username of the owner
 * @return array message
 */
function remove_optin($pdo, $id, $username)
{
    /* Delete room */
    $stmt = $pdo->prepare("DELETE FROM opt_in WHERE id = ? AND username = ?");
    $stmt->execute([$id, $username]);
    $deleted = $stmt->rowCount();
    if ($deleted == 1) {
        return [
            'type' => 'success',
            'message' => sprintf("Opt-in was successfully removed")
        ];
    } else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. The opt-in was not removed.'
        ];
    }
}

/**
 * Removes a room with a specific room_id
 * @param object $pdo db object
 * @param int $room_id id of the to be deleted room
 * @param string $username username of the owner
 * @return array message
 */
function remove_room($pdo, $room_id, $username)
{
    /* Get room info */
    $room_info = get_room_details($pdo, $room_id);

    /* Check if the user is allowed to edit the room */
    if ($room_info['username'] =! $username){
        return [
            'type' => 'danger',
            'message' => 'There was an error. You are not allowed to remove this room.'];
    }

    /* Remove picture */
    if ($room_info['picture'] != Null) {
        if (!unlink("images/rooms/".$room_info['picture'])){
            return [
                'type' => 'danger',
                'message' => 'Something went wrong with deleting the picture.'
            ];
        }
    }

    /* Delete room */
    $stmt2 = $pdo->prepare("DELETE FROM room_address WHERE zip_code =? AND number = ? ");
    $stmt2->execute([
        $room_info["zip_code"],
        $room_info["number"]
    ]);
    $stmt = $pdo->prepare("DELETE FROM room WHERE id = ?");
    $stmt->execute([$room_id]);

    $deleted1 = $stmt->rowCount();
    $deleted2 = $stmt2->rowCount();
    if ($deleted1 == 0 or $deleted2 == 0) {
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
 * Destroys a session of a user
 * @return array message
 */
function logout_user() {
    session_start();
    if (session_destroy()) {
        return [
            'type' => 'success',
            'message' => sprintf('You are succesfully logged out')
        ];
    } else {
        return [
            'type' => 'danger',
            'message' => sprintf('Logout Failed')
        ];
    }
}

/**
 * Creats HTML alert code with information about the success or failure
 * @param bool $feedback True if success, False if failure
 * @return string
 */
function get_error($feedback)
{
    $feedback = json_decode($feedback, True);
    $error_exp = '
        <div class="alert alert-' . $feedback['type'] . '" role="alert">
            ' . $feedback['message'] . '
        </div>';
    return $error_exp;
}

/**
 * Changes the HTTP Header to a given location
 * @param string $location location to be redirected to
 */
//TODO Session removed after redirect.
function redirect($location)
{
    header(sprintf('Location: %s', $location));
    exit();
}

