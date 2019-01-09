<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Own CSS -->
    <link rel="stylesheet" href="rooms/css/main.css">

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

    <title><?= $page_title ?></title>
</head>
<body>
<!-- Menu -->
<?= $navigation ?>

<!-- Content -->
<div class="container">
    <div class ="row"> <br></div>

    <div class="row">
        <h1><?= $page_title ?></h1>
        <!-- Left column -->
        <div class="col-md-6">
            <!-- Error message -->
            <?php if (isset($error_msg)){echo $error_msg;} ?>
            <h5><?= $page_subtitle ?></h5>
            <table class="table table-hover">
                <tr>
                    <th> Name </th>
                    <td> <?=$name ?> </td>
                </tr>
                <tr>
                    <th> Sex </th>
                    <td> <?=$sex ?> </td>
                </tr>
                <tr>
                    <th> E-mail </th>
                    <td> <?=$email ?> </td>
                </tr>
                <tr>
                    <th> Phonenumber </th>
                    <td> <?=$phone_number ?> </td>
                </tr>
                <tr>
                    <th> Birthdate </th>
                    <td> <?=$birth_date ?> </td>
                </tr>
                <tr>
                    <th> Profession </th>
                    <td> <?=$profession ?> </td>
                </tr>
                <tr>
                    <th> Study </th>
                    <td> <?=$studies ?> </td>
                </tr>
                <tr>
                    <th> Language </th>
                    <?php if(isset($language)) :?>
                    <td> <?=$language; ?> </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <th> Biography </th>
                    <td> <?=$biography ?> </td>
                </tr>
                <tr>
                    <th> Profile picture </th>
                    <td><img src="images/users/<?=$picture?>" alt="" width="30%"> </td>
                </tr>
            </table>
            <?php if ($display_buttons) { ?>
            <div class="row">
                <div class="col-sm-3">
                    <a href="/DDWT-Eindopdracht/rooms/account/edit" role="button" class="btn btn-info">Edit Account</a>
                </div>
                <div class="col-sm-3">
                    <form action="/DDWT-Eindopdracht/rooms/account/delete" method="POST">
                        <button type="submit" class="btn btn-danger">Delete account</button>
                    </form>
                </div>
            </div>
        <?php }?>
        </div>


        <!-- Right column -->
        <?php if ($right_column) { ?>
            <div class="col-md-6">
                <h5> Your messages </h5>
                <?php if(isset($optins[0]["message"])){
                    echo $optins_table;
                } else {
                    echo "You have no opt-ins.";
                }
                ?>
            </div>
        <?php }?>

            <?php if(isset($left_content)){echo $left_content;} ?>
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