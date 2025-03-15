$(document).ready(function(){
  


});
async function handleClick(){
    const key = $("#userInput").val();
    const res = await fetch(`/api/movies/search?k=${key}`).then(res=>res.json()).then(res=>res);
    let htmlToRender = `<div class="movie_container">`;
    res.forEach(movie=>{
        htmlToRender+=`<div class="movie">
        <img src="${movie.Poster}" alt="movie poster">
        <h3>${movie.Title}</h3>
        <p>${movie.Year}</p>
        <p>${movie.Type}</p>
        </div>`;
    });
    htmlToRender+=`</div>`;
    $("#movies").html(htmlToRender);
   }