<?php

use App\Session;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>order.io</title>
    <link href="assets/css/shops/index.css" rel="stylesheet" />
    <script src="assets/js/pages/dashboard.js" type="module" defer></script>
</head>


<body>
    <header class="site-header sticky-top py-1">
        <nav class="container d-md-flex d-sm-inline-flex flex-column flex-md-row justify-content-between shadow-sm navbar-dark">
            <a class="navbar-brand py-2" href="#" id="branding" aria-label="Product">
                <svg class="text-grey" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 512 512">
                    <title>order.io</title>
                    <path d="M160 265.2c0 8.5-3.4 16.6-9.4 22.6l-26.8 26.8c-12.3 12.3-32.5 11.4-49.4 7.2C69.8 320.6 65 320 60 320c-33.1 0-60 26.9-60 60s26.9 60 60 60c6.3 0 12 5.7 12 12c0 33.1 26.9 60 60 60s60-26.9 60-60c0-5-.6-9.8-1.8-14.5c-4.2-16.9-5.2-37.1 7.2-49.4l26.8-26.8c6-6 14.1-9.4 22.6-9.4H336c6.3 0 12.4-.3 18.5-1c11.9-1.2 16.4-15.5 10.8-26c-8.5-15.8-13.3-33.8-13.3-53c0-61.9 50.1-112 112-112c8 0 15.7 .8 23.2 2.4c11.7 2.5 24.1-5.9 22-17.6C494.5 62.5 422.5 0 336 0C238.8 0 160 78.8 160 176v89.2z" />
                </svg>
            </a>
            <button class="navbar-toggler float-end d-md-none d-sm-block" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="px-2 d-none d-md-inline-block"></a>
            <a class="px-2 d-none d-md-inline-block"></a>
            <a class="px-2 d-none d-md-inline-block"></a>
            <div class="d-md-flex collapse navbar-collapse justify-content-md-end" id="navbarNav">
                <div class="d-flex justify-content-between">
                    <div class="dropdown py-2 d-md-inline-block text-decoration-none mx-2">
                        <a href="#" class="d-md-flex float-left align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user">&nbsp;</i>
                            <span class="d-sm-inline mx-1"><?= Session::get('user')['name'] ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow p-3">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#" onclick="App.methods.logout()">Sign out</a></li>
                        </ul>
                    </div>
                    <div id="teams-dropdown" class="dropdown my-auto d-md-inline-block text-decoration-none mx-2"></div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
            <div class="row my-4">
                <div class="col-12 col-md-3 d-md-flex px-4 aside d-none">
                    <div id="sidebar-container" class="d-flex flex-column flex-grow-1 p-3 text-white bg-dark shadow sidemenu"></div>
                </div>
                <div id="spa-container" class="col-12 col-md-9">
                </div>
            </div>
        </div>
    </main>

</body>