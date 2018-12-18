<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWT18-Eindopdracht/rooms/css/main.css">

        <title><?= $page_title ?></title>
</head>
<body>
<!-- Menu -->
<?= $navigation ?>

<!-- Content -->
<div class="container">
    <!-- Breadcrumbs -->
    <div class="pd-15">&nbsp</div>
    <?= $breadcrumbs ?>

    <div class="row">

        <!-- Left column -->
        <div class="col-md-8">
            <!-- Error message -->
            <?php if (isset($error_msg)){echo $error_msg;} ?>

            <h1><?= $page_title ?></h1>
            <h5><?= $page_subtitle ?></h5>
            <p><?= $page_content ?></p>
            <table class="table">
                <tbody>
                <tr>
                    <th scope="row">Title</th>
                    <td><?= $type ?> <?= $street ?> <?= $city ?></td>
                </tr>
                <tr>
                    <th scope="row">Picture</th>
                    <td><?= $picture ?></td>
                </tr>
                <tr>
                    <th scope="row">Description</th>
                    <td><?= $description ?></td>
                </tr>
                <tr>
                    <th scope="row">Size</th>
                    <td><?= $size ?></td>
                </tr>
                <tr>
                    <th scope="row">Price</th>
                    <td><?= $price ?></td>
                </tr>
                <tr>
                    <th scope="row">Type of room</th>
                    <td><?= $type ?></td>
                </tr>
                <tr>
                    <th scope="row">Owner</th>
                    <td><?= $owner ?></td>
                </tr>
                <tr>
                    <th scope="row"> Address</th>
                    <td><?= $street ?><?= $city ?></td>
                </tr>
                </tbody>
            </table>
            <?php if($display_buttons) { ?>
                <div class="row">
                    <div class="col-sm-2">
                        <a href="/DDWT-Eindopdracht/rooms/rooms/?room_id=<?= $room_id ?>/edit/" role="button" class="btn btn-warning">Edit</a>
                    </div>
                    <div class="col-sm-2">
                        <form action="/DDWT-Eindopdracht/rooms/rooms/remove/" method="POST">
                            <input type="hidden" value="<?= $room_id ?>" name="room_id">
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            <?php }?>
        </div>


    </div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>