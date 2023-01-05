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
    <script src="assets/js/pages/shops.js" type="module" defer></script>
</head>


<body>
    <header class="site-header sticky-top py-1">
        <nav class="container d-flex flex-column flex-md-row justify-content-between shadow-sm">
            <a class="py-2" href="#" aria-label="Product">
                <svg class="text-grey" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 512 512">
                    <title>order.io</title>
                    <path d="M160 265.2c0 8.5-3.4 16.6-9.4 22.6l-26.8 26.8c-12.3 12.3-32.5 11.4-49.4 7.2C69.8 320.6 65 320 60 320c-33.1 0-60 26.9-60 60s26.9 60 60 60c6.3 0 12 5.7 12 12c0 33.1 26.9 60 60 60s60-26.9 60-60c0-5-.6-9.8-1.8-14.5c-4.2-16.9-5.2-37.1 7.2-49.4l26.8-26.8c6-6 14.1-9.4 22.6-9.4H336c6.3 0 12.4-.3 18.5-1c11.9-1.2 16.4-15.5 10.8-26c-8.5-15.8-13.3-33.8-13.3-53c0-61.9 50.1-112 112-112c8 0 15.7 .8 23.2 2.4c11.7 2.5 24.1-5.9 22-17.6C494.5 62.5 422.5 0 336 0C238.8 0 160 78.8 160 176v89.2z" />
                </svg>
            </a>
            <a class="px-2 d-none d-md-inline-block"></a>
            <a class="px-2 d-none d-md-inline-block"></a>
            <a class="px-2 d-none d-md-inline-block"></a>
            <div class="dropdown py-2 d-none d-md-inline-block text-decoration-none">

                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-circle-user">&nbsp;</i>
                    <span class="d-none d-sm-inline mx-1"><?= Session::get('user')['name'] ?></span>
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
        </nav>
    </header>
    <main>
        <div class="container">
            <div class="row my-4">
                <div class="col-12 col-md-3">
                    <div class="d-md-none container my-3">
                        <div class="row row-cols-3 px-0">
                            <div class="col d-flex mx-auto px-3">
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSort" aria-expanded="false" aria-controls="collapseFilters">
                                    Sort
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-down" viewBox="0 0 16 16">
                                        <path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="col d-flex mx-auto px-0">
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
                                    Filters
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="col">
                            </div>
                        </div>

                    </div>

                    <div class="collapse" id="collapseSort">
                        <div class="container my-3">
                            <form>
                                <div class="form-group">
                                    <legend>Sorting</legend>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sorting" id="default" value="default" checked>
                                        <label class="form-check-label" for="default">
                                            Default
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sorting" id="distance" value="distance">
                                        <label class="form-check-label" for="distance">
                                            Distance
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sorting" id="delivery-time" value="delivery-time">
                                        <label class="form-check-label" for="delivery-time">
                                            Delivery Time
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sorting" id="delivery-cost" value="delivery-cost">
                                        <label class="form-check-label" for="delivery-cost">
                                            Delivery Cost
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sorting" id="minimum-order" value="minimum-order">
                                        <label class="form-check-label" for="minimum-order">
                                            Minimum Order
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sorting" id="ranking" value="ranking">
                                        <label class="form-check-label" for="ranking">
                                            Ranking
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="collapse" id="collapseFilters">
                        <div class="container my-3">
                            <form>
                                <div class="form-group">
                                    <legend>Cuisines</legend>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="grill" id="grill">
                                        <label class="form-check-label" for="grill">
                                            Grill
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="coffee" id="coffee">
                                        <label class="form-check-label" for="coffee">
                                            Coffee
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="pizza" id="pizza">
                                        <label class="form-check-label" for="pizza">
                                            Pizza
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="salad" id="salad">
                                        <label class="form-check-label" for="salad">
                                            Salad
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="burgers" id="burgers">
                                        <label class="form-check-label" for="burgers">
                                            Burgers
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="crepes" id="crepes">
                                        <label class="form-check-label" for="crepes">
                                            Crepes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="sandwich" id="sandwich">
                                        <label class="form-check-label" for="sandwich">
                                            Sandwich
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="asian" id="asian">
                                        <label class="form-check-label" for="asian">
                                            Asian
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="american" id="american">
                                        <label class="form-check-label" for="american">
                                            American
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="international" id="international">
                                        <label class="form-check-label" for="international">
                                            International
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="container my-3">
                            <form>
                                <div class="form-group">
                                    <legend>Filter</legend>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="credit-card" id="credit-card">
                                        <label class="form-check-label" for="credit-card">
                                            Accepts credit card
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="order-io" id="order-io">
                                        <label class="form-check-label" for="order-io">
                                            Delivered by order.io
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="discount" id="discount">
                                        <label class="form-check-label" for="discount">
                                            With discount
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="offers" id="offers">
                                        <label class="form-check-label" for="offers">
                                            With offers
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="free-delivery" id="free-delivery">
                                        <label class="form-check-label" for="free-delivery">
                                            Free delivery
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="shop-cards" class="col-12 col-md-9">
                </div>
            </div>
        </div>
    </main>

</body>