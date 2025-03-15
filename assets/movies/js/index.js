let allFavorites = [];
let key = "";

$(document).ready(function () {
    const authToken = localStorage.getItem("authToken");

    if (authToken) {
        verifyToken(authToken)
            .then((response) => {
                $(".header").css({ display: "flex" });
                $("#popover p").html(`Welcome, ${response.user.username}`);
                $("#email").css({ display: "flex" }).html(response.user.email);
                fetchFavorites(authToken);
            })
            .catch(() => {
                alert("Session expired. Please log in again.");
                localStorage.removeItem("authToken");
                window.location.href = "/auth"; // Redirect to login page
            });
    }

    // Toggle popover (Prevent it from opening when clicking on links inside)
    $("#user").click(function (event) {
        if (!$(event.target).closest("#popover a").length) {
            event.stopPropagation();
            $("#popover").toggle();
        }
    });

    $("#favLink").click(function(){
        $("#popover").hide();
    })
    // Clicking outside should hide popover
    $(document).click(function (event) {
        if (!$(event.target).closest("#user, #popover").length) {
            $("#popover").hide();
        }
    });

    // Prevent popover from showing when clicking links inside
    $("#popover a").click(function () {
        $("#popover").hide();
    });

    // Handle search
    $("#search button").on("click", handleClick);
});

// Function to verify the auth token
async function verifyToken(token) {
    return $.ajax({
        url: "/api/auth/verify-token",
        method: "GET",
        headers: { Authorization: `Bearer ${token}` },
    });
}

// Function to fetch favorite movies
function fetchFavorites(token) {
    $.ajax({
        url: "/api/favorites",
        method: "GET",
        headers: { Authorization: `Bearer ${token}` },
        success: function (favorites) {
            allFavorites = favorites;
        },
        error: function (error) {
            console.error("Error fetching favorites:", error);
        },
    });
}

// Function to handle movie search
async function handleClick() {
    $("#search button").off("click").html("<div class='loader'></div>");
    key = $("#userInput").val();

    renderMovies(key);
}

// Function to render movies
async function renderMovies(key) {
    const movies = await $.ajax({
        url: `/api/movies/search?k=${key}`,
        method: "GET",
        dataType: "json",
    });

    let htmlToRender = `<div class="movie_container">`;

    movies.forEach((movie) => {
        const isFavorite = allFavorites.some((f) => f.title === movie.Title);
        htmlToRender += `
          <div class="movie">
            <img src="${movie.Poster}" alt="movie poster">
            <div class="info">
              <h3>${movie.Title}</h3>
              <p>${movie.Year}</p>
              <p>${movie.Type}</p>
            </div>
            <button class="fav-btn" data-title="${movie.Title}" data-year="${movie.Year}" data-type="${movie.Type}" data-poster="${movie.Poster}">
              ${isFavorite ? `<i class="fa-solid fa-heart"></i>` : `<i class="fa-regular fa-heart"></i>`}
            </button>
          </div>`;
    });

    htmlToRender += `</div>`;
    $("#movies").css("height", "60vh").html(htmlToRender);
    $("#search button").html("Search").on("click", handleClick);

    // Attach event listener to favorite buttons
    $(".fav-btn").on("click", toggleFavorite);
}

// Function to toggle favorite with token verification
function toggleFavorite() {
    const authToken = localStorage.getItem("authToken");

    if (!authToken) {
        alert("You need to log in to add favorites.");
        window.location.href = "/auth"; // Redirect to login page
        return;
    }

    verifyToken(authToken)
        .then(() => {
            const button = $(this);
            const title = button.data("title");
            const year = button.data("year");
            const type = button.data("type");
            const poster = button.data("poster");

            const isFavorite = allFavorites.some((f) => f.title === title);

            // Show loader
            button.html(`<div class='loader_green' style='width:15px; height:15px; border-width:2px;'></div>`);

            if (isFavorite) {
                // Remove from favorites
                $.ajax({
                    url: "/api/favorites",
                    method: "DELETE",
                    contentType: "application/json",
                    headers: { Authorization: `Bearer ${authToken}` },
                    data: JSON.stringify({ title, year }),
                    success: function () {
                        allFavorites = allFavorites.filter((f) => f.title !== title);
                        renderMovies(key);
                    },
                    error: function (error) {
                        console.error("Error removing favorite:", error);
                    },
                });
            } else {
                // Add to favorites
                $.ajax({
                    url: "/api/favorites",
                    method: "POST",
                    contentType: "application/json",
                    headers: { Authorization: `Bearer ${authToken}` },
                    data: JSON.stringify({ title, year, type, poster }),
                    success: function () {
                        allFavorites.push({ title, year, type, poster });
                        renderMovies(key);
                    },
                    error: function (error) {
                        console.error("Error adding favorite:", error);
                    },
                });
            }
        })
        .catch(() => {
            alert("Session expired. Please log in again.");
            localStorage.removeItem("authToken");
            window.location.href = "/auth"; // Redirect to login page
        });
}

function logout(){
    localStorage.clear();
}