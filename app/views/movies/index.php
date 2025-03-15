<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/movies/css/index.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div id="page">
        <div class="header" >
            <a id="favLink" href="/favorites"><i class="fa-solid fa-heart"></i>&nbsp;Favorites</a>
            <span id="email"></span>
            <i class="fa-solid fa-user" id="user"></i>
        </div>
        <div id="popover" class="popover">
            <p>Welcome, User!</p>
            <a href="javascript:logout();">Logout</a>
        </div>
        <div id="search">
            <input type="text" id="userInput" name="search" placeholder="Search movie ...">
            <button onclick="handleClick();">Search</button>
        </div>
        <div id="movies"></div>
    </div>
    <script src="assets/movies/js/index.js">
    </script>
</body>

</html>