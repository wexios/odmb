<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites</title>
    <link rel="stylesheet" href="assets/favorites/css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="fav_container">
        <div class="header" id="user">
            <span id="email"></span>
            <i class="fa-solid fa-user"></i>
        </div>
        <div id="popover" class="popover">
            <p></p>
            <a href="#">Logout</a>
        </div>
        <h1>My Favorite Movies</h1>
        <hr />
        <div class="breadcrumb"><a href="/"><i class="fa-solid fa-house"></i></a><span><i class="fa-solid fa-arrow-right"></i></span><h5>Favorites</h5></div>
        
        <div id="content">
            <div id="loader" class="loader"></div>
            <div id="favoritesList" class="favorites-container"></div>
        </div>

    </div>

    <script src="assets/favorites/js/index.js"></script>

</body>

</html>