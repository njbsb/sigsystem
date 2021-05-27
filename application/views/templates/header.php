<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>VIC System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/flatly.bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery.dataTables.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dataTables.bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sticky-footer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sigcustom.css') ?>">
    <!-- <link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.min.css"> -->
    <script src="https://kit.fontawesome.com/dff01397e8.js" crossorigin="anonymous"></script>
    <script src="http://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/custom.js') ?>"></script>
    <script src="<?= base_url('assets/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.breadcrumbs-generator.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/list.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
        crossorigin="anonymous"></script>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
    body {
        font: 14px Montserrat, sans-serif;
        line-height: 1.8;
        /* background-color: #ffb6c1; */
    }

    table {
        max-width: 100%;
    }

    /* .btn {
            font: 12px Montserrat, sans-serif;
            text-transform: none;
        } */

    input {
        font: 14px Montserrat, sans-serif;
    }

    p.activitydesc {
        text-align: justify;
    }

    .post-date {
        background: #f4f4f4;
        padding: 4px;
        margin: 3px 0;
        display: block;
    }

    .margin {
        margin-bottom: 35px;
    }

    .container-fluid {
        /* padding-top: 25px;
            padding-bottom: 25px; */
        margin-top: 25px;
        margin-bottom: 25px;
    }
    </style>


</head>

<body>
    <?php $usertype =  $this->session->userdata('user_type') ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <img class="" style="object-fit:cover;" height="40px" width="40px" src="<?= base_url('assets/images/logo.png') ?>" alt="">
            <?php if ($this->session->userdata('logged_in')) : ?>
            <a class="navbar-brand" href="<?= site_url() ?>">VIC System</a>
            <?php endif ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <?php if ($this->session->userdata('logged_in')) : ?>
            <div class="collapse navbar-collapse" id="navbarColor03">
                <ul class="navbar-nav mr-auto">
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('profile') ?>">Profile <span class="sr-only">(current)</span></a>
                    </li> -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">SIG</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= site_url('organization') ?>">Organization</a>
                            <?php if ($usertype == 'student') : ?>
                            <a class="dropdown-item" href="<?= site_url('mentor') ?>">Mentors</a>
                            <?php else : ?>
                            <a class="dropdown-item" href="<?= site_url('student') ?>">Students</a>
                            <a class="dropdown-item" href="<?= site_url('activity/external') ?>">External Activity</a>
                            <?php endif ?>
                            <a class="dropdown-item" href="<?= site_url('activity') ?>">Activities</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Academic</a>
                        <div class="dropdown-menu">
                            <?php if ($usertype == 'mentor') : ?>
                            <a class="dropdown-item" href="<?= site_url('academic') ?>">Academic Control</a>
                            <a class="dropdown-item" href="<?= site_url('academicplan/mentor') ?>">Academic Plan</a>
                            <a class="dropdown-item" href="<?= site_url('enroll') ?>">Enroll Students</a>
                            <?php else : ?>
                            <a class="dropdown-item" href="<?= site_url('academicplan/student') ?>">Academic Plan</a>
                            <?php endif ?>
                        </div>
                    </li>
                    <?php if ($usertype == 'mentor' or $usertype == 'admin') : ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Score</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= site_url('scoreboard') ?>">Score Board</a>
                            <a class="dropdown-item" href="<?= site_url('scoreplan') ?>">Score Plan</a>
                            <a class="dropdown-item" href="<?= site_url('score') ?>">Submit Score</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Admin</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= site_url('user') ?>">Manage Users</a>

                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Others</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= site_url('citra') ?>">Citra</a>
                            <a class="dropdown-item" href="<?= site_url('collaborator') ?>">Collaborator</a>
                            <a class="dropdown-item" href="<?= site_url('template') ?>">Upload Templates</a>
                        </div>
                    </li>
                    <?php endif ?>
                </ul>
            </div>
            <?php endif ?>
        </div>

        <ul class="navbar-nav mr-auto">
            <?php if ($this->session->userdata('logged_in')) : ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?= $this->session->userdata('username') ?></a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= site_url('profile') ?>">Profile <span class="sr-only">(current)</span></a>
                    <a class="dropdown-item" href="<?= site_url('profile/update') ?>">Update Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= site_url('logout') ?>">Log Out</a>
                </div>
            </li>
            <li>
                <img class="rounded-circle" style="object-fit:cover;" height="40px" width="40px" src="<?= $this->session->userdata('userphoto') ?>" alt="">
            </li>
            <?php else : ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('login') ?>">Login<span class="sr-only">(current)</span></a>
            </li>
            <?php endif ?>

        </ul>

    </nav>

    <div class="container">
        <br>
        <!-- <?php if ($this->session->flashdata('login_failed')) : ?>
        <div class="alert alert-dismissible alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Oh snap!</strong> <?= $this->session->flashdata('login_failed') ?>
        </div>
        <?php endif ?>
        <?php if ($this->session->flashdata('user_loggedin')) : ?>
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Welcome!</strong> <?= $this->session->flashdata('user_loggedin') ?>
        </div>
        <?php endif ?>
        <?php if ($this->session->flashdata('logged_out')) : ?>
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong></strong> <?= $this->session->flashdata('logged_out') ?>
        </div>
        <?php endif ?> -->