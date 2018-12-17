<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWT18/week2/css/main.css">

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
                    <form action="<?= $form_action ?>" method="POST">
                        <div class="form-group row">
                            <label for="inputSize" class="col-sm-2 col-form-label">Size</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="inputSize" name="size" value="<?php if (isset($room_info)){echo $room_info['size'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPicture" class="col-sm-2 col-form-label">Add a picture</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="inputPicture" name="picture">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPrice" class="col-sm-2 col-form-label">Price per month (including utilities)</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="inputPrice" name="price" value="<?php if (isset($room_info)){echo $room_info['price'];} ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputDescription" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="inputDescription" name="description"><?php if (isset($room_info)){echo $room_info['description'];} ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputType" class="radio-inline">Type</label>
                            <div class="form-group-inline">
                                <input type="radio" class="form-control" id="inputType" name="type">Room<br>
                                <input type="radio" class="form-control" id="inputType" name="type" value="<?php if (isset($room_info)){echo $room_info['type'];} ?>">Apartment<br>
                                <input type="radio" class="form-control" id="inputType" name="type" value="<?php if (isset($room_info)){echo $room_info['type'];} ?>">Room_in<br>
                                <input type="radio" class="form-control" id="inputType" name="type" value="<?php if (isset($room_info)){echo $room_info['type'];} ?>">Studio<br>
                                <input type="radio" class="form-control" id="inputType" name="type" value="<?php if (isset($room_info)){echo $room_info['type'];} ?>">Houseboat<br>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputZip_code" class="col-sm-2 col-form-label">Zip code</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputZip_code" name="zip_code" value="<?php if (isset($room_info)){echo $room_info['zip_code'];} ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputNumber" class="col-sm-2 col-form-label">Number</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="inputNumber" name="number" value="<?php if (isset($room_info)){echo $room_info['number'];} ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputStreet" class="col-sm-2 col-form-label">Street</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputStreet" name="street" value="<?php if (isset($room_info)){echo $room_info['street'];} ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputCity" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputCity" name="city" value="<?php if (isset($room_info)){echo $room_info['city'];} ?>">
                            </div>
                        </div>
                        <?php if(isset($room_id)){ ?><input type="hidden" name="room_id" value="<?php echo $room_id ?>"><?php } ?>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="Submit" class="btn btn-primary"><?= $submit_btn ?></button>
                            </div>
                        </div>
                    </form>
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