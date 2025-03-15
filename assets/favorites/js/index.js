const authToken = localStorage.getItem("authToken");
if(authToken){
    $(document).ready(function() {
    
    if (authToken) {
        $("#user").css({
            display: "flex"
        });
    }
    $("#user").click(function(event) {
        event.stopPropagation();
        $("#popover").toggle();
    });

    $(document).click(function(event) {
        if (!$(event.target).closest("#user, #popover").length) {
            $("#popover").hide();
        }
    });

    // Verify the user's token before fetching favorites
    function verifyToken() {
        return $.ajax({
            url: "/api/auth/verify-token",
            method: "GET",
            headers: {
                Authorization: `Bearer ${authToken}`
            }
        });
    }

    function fetchFavorites() {
        $("#loader").show();
        $.ajax({
            url: "/api/favorites",
            method: "GET",
            headers: {
                Authorization: `Bearer ${authToken}`
            },
            success: function(favorites) {
                $("#loader").hide();
                renderFavorites(favorites);
            },
            error: function() {
                $("#loader").hide();
                alert("Failed to fetch favorites.");
            }
        });
    }

    function renderFavorites(favorites) {
        let html = "";
        if (favorites.length === 0) {
            html = "<p>No favorites added yet.</p>";
        } else {
            favorites.forEach(movie => {
                html += `
            <div class="favorite">
                <img src="${movie.poster}" alt="Movie Poster">
                <div class="info">
                <h3>${movie.title}</h3>
                <p>${movie.year} | ${movie.type}</p>
                </div>
                <button class="remove-btn" data-title="${movie.title}" data-year="${movie.year}">Remove</button>
            </div>
        `;
            });
        }
        $("#favoritesList").html(html);

        $(".remove-btn").on("click", function() {
            const title = $(this).data("title");
            const year = $(this).data("year");
            removeFavorite(title, year);
        });
    }

    function removeFavorite(title, year) {
        if (!confirm("Are you sure you want to remove this favorite?")) return;

        $.ajax({
            url: "/api/favorites",
            method: "DELETE",
            contentType: "application/json",
            headers: {
                Authorization: `Bearer ${authToken}`
            },
            data: JSON.stringify({
                title,
                year
            }),
            success: function() {
                fetchFavorites();
            },
            error: function() {
                alert("Failed to remove favorite.");
            }
        });
    }

    // First, verify the token before listing favorites
    verifyToken()
        .done(function(response) {
            console.log("Token verified:", response);
            $("#popover p").html(`Welcome, ${response.user.username}`);
            $("#email").css({
                'display': 'flex'
            }).html(response.user.email);
            fetchFavorites();
        })
        .fail(function() {
            alert("Session expired. Please log in again.");
            localStorage.removeItem("authToken");
            window.location.href = "/auth";
        });
});

}
else{
    window.location.href = "/auth";
}

function logout() {
    localStorage.clear();
    window.location.href = "/";
}