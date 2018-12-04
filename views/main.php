<?php
/**
 * main
 * Date: 4-12-2018
 * Time: 15:50
 */
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWT-Eindopdracht/css/main.css">

        <!-- TODO: Add global variable title -->
        <!-- <title><?php //$page_title ?></title> -->
</head>
<body>
<!-- Menu -->
<!-- TODO: add navigation -->
<?php //$navigation ?>

<!-- Content -->
<div class="container">
    <!-- Breadcrumbs -->
    <div class="pd-15">&nbsp</div>
    <!-- TODO add breadcrumbs-->
    <?php //$breadcrumbs ?>

    <div class="row">

        <!-- Left column -->
        <div class="col-md-8">
            <!-- Error message -->
            <?php //if (isset($error_msg)){echo $error_msg;} ?>
            <!-- TODO: Fill in table -->
            <h1>Title</h1>
            <h5>Subtitle</h5>
            <p>Page Conent</p>
            <?php //if(isset($left_content)){echo $left_content;} ?>
        </div>

        <!-- Right column -->
        <div class="col-md-4">

            <!-- TODO: add variable right_column -->
            <?php //include $right_column ?>

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