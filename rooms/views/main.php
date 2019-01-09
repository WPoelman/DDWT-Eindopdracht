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

        <!-- Left column -->
        <div class="col-md-8">
            <!-- Error message -->
            <?php if (isset($error_msg)){echo $error_msg;} ?>

            <h1><?= $page_title ?></h1>
            <h5><?= $page_subtitle ?></h5>
            <?php if (isset($logo)) :?>
                <img src="<?php echo $logo ?> " height="100" ">
            <?php endif; ?>
            <p></p>
            <p><?= $page_content ?></p>
            <?php if(isset($contact)) :?>
            <ul>
                <li><pre>Marieke Visscher  - s2985012</pre></li>
                <li><pre>Rimmert Sijtsma   - s3220176</pre></li>
                <li><pre>Lisa Stuifzand    - s2997851</pre></li>
                <li><pre>Wessel Poelman    - s2976129</pre></li>
                <li><pre>Iris Meijer       - s3761304</pre></li>

            </ul>
            <?php endif; ?>
            <?php if($login_button) : ?>
                <a href="/DDWT-Eindopdracht/rooms/login" role="button" class="btn btn-info">Login</a><br><br>
                <?php echo $login_button; ?><a href="/DDWT-Eindopdracht/rooms/register">Register here</a>
            <?php endif; ?>
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