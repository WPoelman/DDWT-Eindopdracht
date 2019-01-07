<!doctype html>
<html lang="english">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="rooms/css/main.css">

        <title><?= $page_title ?></title>
    </head>


    <body>
        <!-- navigation -->
        <?= $navigation ?>

        <!-- content -->
        <div class="container">
            <div class ="row"> <br></div>


            <div class="row">

                <!-- Left column -->
                <div class="col-md-12">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>
                    <p><?= $page_content ?></p>
                    <form action="<?= $form_action ?>" method="POST">

                    <div class="pd-15">&nbsp;</div>
                        <div class="form-group row">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="inputUsername" placeholder="J.deboer" name="username" required>
                            </div>

                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-3">
                                <input type="password" class="form-control" id="password" placeholder="*********" name="password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="firstname" class="col-sm-2 col-form-label">First name</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="first_name" placeholder="Johan" name="first_name" required>
                            </div>

                            <label for="lastname" class="col-sm-2 col-form-label">Last name</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="last_name" placeholder="de Boer" name="last_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="birthdate" class="col-sm-2 col-form-label">Date of birth</label>
                            <div class="col-sm-3">
                            <input type="date" class="form-control" id="birthdate" placeholder="1990-01-01" name="birthdate" required>
                            </div>

                            <label for="inputType" class="col-sm-2 col-form-label">Gender</label>
                            <div class="col-sm-3">
                                <select class="custom-select" name="sex">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">E-mail</label>
                            <div class="col-sm-3">
                            <input type="email" class="form-control" id="email" placeholder="j.deboer@deboer.nl" name="email" required>
                            </div>

                            <label for="inputType" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-3">
                                <select class="custom-select" name="role">
                                    <option value="owner">Owner</option>
                                    <option value="tenant">Tenant</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phonenumber" class="col-sm-2 col-form-label">Phonenumber</label>
                            <div class="col-sm-3">
                            <input type="tel" class="form-control" id="phonenumber" placeholder="0610012002" name="phonenumber" pattern="[0]{1}[6]{1}[0-9]{8}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="studies" class="col-sm-2 col-form-label">Enter your study</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="studies" placeholder="Informatiekunde" name="studies">
                            </div>

                            <label for="profession" class="col-sm-2 col-form-label">Enter your profession</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="profession" placeholder="Student-assistant" name="profession">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="biography" class="col-sm-2 col-form-label">Tell us something about you</label>
                            <div class="col-sm-3">
                            <textarea type="text" class="form-control" id="biography" placeholder="Hi, my name is..." name="biography"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="picture" class="col-sm-2 col-form-label">Upload a profile picture</label>
                            <div class="col-sm-4">
                                <input type="file" class="form-control" id="picture" name="picture">
                            </div>
                        </div>
                        <button type="submit" name="Submit" class="btn btn-info">Register</button>
                    </form>

            </div>

        </div>

            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>

</html>