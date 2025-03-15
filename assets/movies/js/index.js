$(document).ready(function () {
  const userData = localStorage.getItem("authToken");
  if (userData) {
    $("#user").css({
      display: "flex",
    });
  }
  $("#user").click(function (event) {
    event.stopPropagation(); // Prevents event from reaching document
    $("#popover").toggle(); // Toggle the popover
  });

  // Hide popover when clicking outside
  $(document).click(function (event) {
    if (!$(event.target).closest("#user, #popover").length) {
      $("#popover").hide();
    }
  });
});
async function handleClick() {
  // $("#movies").html("");
  $("#search button").off("click").html("<div class='loader'></div>");
  const key = $("#userInput").val();
  const res = await $.ajax({
    url: `/api/movies/search?k=${key}`,
    method: "GET",
    dataType: "json",
  });

  let htmlToRender = `<div class="movie_container">`;
  res.forEach((movie) => {
    htmlToRender += `
        
        <div class="movie">
        <img src="${movie.Poster}" alt="movie poster">
        <h3>${movie.Title}</h3>
        <p>${movie.Year}</p>
        <p>${movie.Type}</p>
        </div>`;
  });
  htmlToRender += `</div>`;
  $("#movies").css("height", "80vh");
  $("#movies").html(htmlToRender);
  $("#search button").html("Search").on("click", handleClick);
}
