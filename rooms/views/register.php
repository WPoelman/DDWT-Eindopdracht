<!doctype html>
<html lang="english">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWTT-Eindopdracht/rooms/css/main.css">

        <title><?= $page_title ?></title>
    </head>


    <body>
        <!-- navigation -->
        <?= $navigation ?>

        <!-- content -->
        <div class="container">
            <!-- breadcrumps -->
            <div class="pd-15">&nbsp</div>
            <?= $breadcrumbs ?>

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
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="J.deboer" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="firstname">First name</label>
                            <input type="text" class="form-control" id="firstname" placeholder="Johan" name="firstname" required>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last name</label>
                            <input type="text" class="form-control" id="lastname" placeholder="de Boer" name="lastname" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Date of birth</label>
                            <input type="date" class="form-control" id="birthdate" placeholder="1990-01-01" name="birthdate" required>
                        </div>
                        <div class="form-group">
                            <label for="sex">Gender</label>
                            <input type="radio" class="form-control" id="sex" value="male" name="sex">Male<br>
                            <input type="radio" class="form-control" id="sex" value="female" name="sex">Female<br>
                            <input type="radio" class="form-control" id="sex" value="other" name="sex">Other
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" placeholder="j.deboer@deboer.nl" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="*********" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="radio" class="form-control" id="role" value="owner" name="role">Owner<br>
                            <input type="radio" class="form-control" id="role" value="tenant" name="role">Tenant
                        </div>
                        <div class="form-group">
                            <label for="phonenumber">Phonenumber</label>
                            <input type="tel" class="form-control" id="phonenumber" placeholder="0610012002" name="phonenumber" pattern="[0]{1}[6]{1}[0-9]{8}" required>
                        </div>
                        <div class="form-group">
                            <label for="studies">Enter your study</label>
                            <input type="text" class="form-control" id="studies" placeholder="Informatiekunde" name="studies">
                        </div>
                        <div class="form-group">
                            <label for="profession">Enter your profession</label>
                            <input type="text" class="form-control" id="profession" placeholder="Student-assistant" name="profession">
                        </div>
                        <div class="form-group">
                            <label for="biography">Tell us something about you</label>
                            <input type="text" class="form-control" id="biography" placeholder="Hi, my name is..." name="biography">
                        </div>
                        <div class="form-group">
                            <label for="picture">Upload a profile picture</label>
                            <input type="file" class="form-control" id="picture" name="picture">
                        </div>
                        <button type="submit" name="Submit" class="btn btn-primary">Register</button>
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