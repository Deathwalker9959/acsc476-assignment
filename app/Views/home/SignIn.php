<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="assets/css/home/signin.css" rel="stylesheet" />
    <script src="assets/js/pages/signin.js" defer></script>
</head>

<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form id="signin-form" method="POST" <?php if (isset($data["register"])) { ?> action="/api/register" <?php } else { ?> action="/api/login" <?php } ?>>
            <svg class="text-grey my-2 text-black-50 rounded-4 border bg-secondary" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="48" height="48" viewBox="0 0 512 512">
                <title>order.io</title>
                <path d="M160 265.2c0 8.5-3.4 16.6-9.4 22.6l-26.8 26.8c-12.3 12.3-32.5 11.4-49.4 7.2C69.8 320.6 65 320 60 320c-33.1 0-60 26.9-60 60s26.9 60 60 60c6.3 0 12 5.7 12 12c0 33.1 26.9 60 60 60s60-26.9 60-60c0-5-.6-9.8-1.8-14.5c-4.2-16.9-5.2-37.1 7.2-49.4l26.8-26.8c6-6 14.1-9.4 22.6-9.4H336c6.3 0 12.4-.3 18.5-1c11.9-1.2 16.4-15.5 10.8-26c-8.5-15.8-13.3-33.8-13.3-53c0-61.9 50.1-112 112-112c8 0 15.7 .8 23.2 2.4c11.7 2.5 24.1-5.9 22-17.6C494.5 62.5 422.5 0 336 0C238.8 0 160 78.8 160 176v89.2z" />
            </svg>
            <h1 class="h3 mb-3 fw-normal"> <?php if (isset($data["register"])) { ?> Register <?php } else { ?> Please sign in<?php } ?></h1>

            <div class="form-group row row-cols-3 d-flex align-items-center">
                <label for="floatingPartner" class="col-form-label text-center">User</label>
                <div class="col d-flex align-items-center">
                    <div class="form-check form-switch d-flex w-100">
                        <input type="checkbox" class="form-check-input float-none" id="floatingPartner" role="switch" name="partner" />
                    </div>
                </div>
                <label for="floatingPartner" class="col-form-label text-center">Partner</label>
            </div>


            <?php if (isset($data["register"])) { ?>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingName" placeholder="John Smith" name="name" data-style="top" />
                    <label for="floatingName">Full name</label>
                </div>
            <?php } ?>
            <div class="form-floating">
                <input type="email" class="form-control" id="floatingInput" placeholder="hello@order.io" name="email" <?php if (!isset($data['register'])) { ?> data-style="top" <?php } ?> />
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" data-style="bottom" />
                <label for="floatingPassword">Password</label>
            </div>
            <div class="checkbox my-3">
                <label>
                    <input type="checkbox" value="true" name="remember-me" /> Remember me
                </label>
            </div>
            <a class="w-100 btn btn-lg btn-success" onclick="handleFormSubmit()">
                <?php if (isset($data["register"])) { ?>
                    Register
                <?php } else { ?>
                    Sign in
                <?php } ?>
            </a>
            <p class="mt-5 mb-3 text-muted">&copy; order.io 2022</p>
        </form>
    </main>
</body>

</html>