/* 
Trogy.NZ Delta Self Hosted Edition
(C) Marc Anderson | All Rights Reserved 
File Last Updated : 9/28/2021
*/
console.log('%cWelcome to Delta by Trogy.NZ', 'color: red; font-size: 30px;');
console.log('%c----', 'color: black; font-size: 30px;');
console.log('%cWhat are you doing in my swamp!', 'color: Green; font-size: 15px;');
console.log('%c----', 'color: black; font-size: 15px;');
console.log('%cBegin Actual Log', 'color: black; font-size: 15px;');
console.log('%c-------------', 'color: black; font-size: 15px;');
$('#locations').load('csv.php');
console.log('JQuery Load csv.php');
$('#CASE_NUMBERS').load('stats.php');
console.log('JQuery Load stats.php');

var map = L.map('map').setView([-40.71395084817901, 174.01979895371952], 6);

L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
  subdomains: 'abcd',
  maxZoom: 19
}).addTo(map);
console.log('Currently Using CARTO Maps!');

fetch('https://raw.githubusercontent.com/minhealthnz/nz-covid-data/main/locations-of-interest/august-2021/locations-of-interest.geojson')
.then(function (response){
  return response.json();
})
.then(function (data){
  var layer = L.geoJSON(data, {
   onEachFeature: function (f, l) {
     l.bindPopup('<div>'+JSON.stringify(f.properties,null,'<br>').replace(/[\{\}"]/g,'')+'</div>');
   }
  }).addTo(map);
  console.log('Finished Mapping!');
});

function ZoomMap(lat, lng){
  if (typeof marker == 'undefined') {
    // the variable is defined
  }
  else{
    map.removeLayer(marker);
  }
    map.setView([lat, lng], 25);
    marker = new L.circleMarker([lat, lng], {color: '#fa3737'});
    map.addLayer(marker);
}
function setcookie(cookieName,cookieValue) {
    var today = new Date();
    var expire = new Date();
    expire.setTime(today.getTime() + 3600000*24*14);
    document.cookie = cookieName+"="+encodeURI(cookieValue) + ";expires="+expire.toGMTString();
}
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
function checkCookie() {
  let splash = getCookie("Splash");
  if (splash == 1) {
    return true;
  }
  else {
    return false;
  }
}
if(checkCookie() === true){
  $(".Splash").toggle();
  console.log("Splash Toggled - Welcome Back User!");
}
else{
  console.log("Showing Splash - Welcome New User!");
}

$(document).ready(function() {



  $("#SPLASH_BUTTON").click(function(){
    $(".Splash").toggle();
    setcookie("Splash", 1);
  });

  $("#AKL_BUTTON").click(function(){
    $(".Auckland").toggle();
    console.log("Toggled Auckland");
    if($('#AKL_BUTTON').hasClass('btn-secondary')){
      map.setView([-36.84076690326373, 174.7534336828429], 12);
    }
    $("#AKL_BUTTON").toggleClass("btn-secondary");
    $("#AKL_BUTTON").toggleClass("btn-primary");
    if ($(this).text() == "Show Auckland"){
      $(this).text("Hide Auckland");
    }
    else{
      $(this).text("Show Auckland");
    }
  });

  $("#COR_BUTTON").click(function(){
    $(".Coromandel").toggle();
    console.log("Toggled Coromandel");
    if($('#COR_BUTTON').hasClass('btn-secondary')){
      map.setView([-36.752199932392585, 175.46502340692425], 12);
    }
    $("#COR_BUTTON").toggleClass("btn-secondary");
    $("#COR_BUTTON").toggleClass("btn-primary");
    if ($(this).text() == "Show Coromandel"){
      $(this).text("Hide Coromandel");
    }
    else{
      $(this).text("Show Coromandel");
    }
  });

  $("#WEL_BUTTON").click(function(){
    $(".Wellington").toggle();
    console.log("Toggled Wellington");
    if($('#WEL_BUTTON').hasClass('btn-secondary')){
      map.setView([-41.238863827107686, 174.8208189147652], 12);
    }
    $("#WEL_BUTTON").toggleClass("btn-secondary");
    $("#WEL_BUTTON").toggleClass("btn-primary");
    if ($(this).text() == "Show Wellington"){
      $(this).text("Hide Wellington");
    }
    else{
      $(this).text("Show Wellington");
    }
  });

  $("#OTH_BUTTON").click(function(){
    $(".Other").toggle();
    console.log("Toggled Other Locations");
    if($('#OTH_BUTTON').hasClass('btn-secondary')){
      map.setView([-40.71395084817901, 174.01979895371952], 6);
    }
    $("#OTH_BUTTON").toggleClass("btn-secondary");
    $("#OTH_BUTTON").toggleClass("btn-primary");
    if ($(this).text() == "Show Other"){
      $(this).text("Hide Other");
    }
    else{
      $(this).text("Show Other");
    }
  });

  $("#NEW_BUTTON").click(function(){
    $(".New").toggle();
    console.log("Toggled New Locations");
    $("#NEW_BUTTON").toggleClass("btn-secondary");
    $("#NEW_BUTTON").toggleClass("btn-primary");
    if ($(this).text() == "Show New Locations"){
      $(this).text("Hide New Locations");
    }
    else{
      $(this).text("Show New Locations");
    }
  });

  $("#OLD_BUTTON").click(function(){
    $(".Old").toggle();
    console.log("Toggled Old Locations");
    $("#OLD_BUTTON").toggleClass("btn-secondary");
    $("#OLD_BUTTON").toggleClass("btn-primary");
    if ($(this).text() == "Show Old Locations"){
      $(this).text("Hide Old Locations");
    }
    else{
      $(this).text("Show Old Locations");
    }
  });

  $("#CENTR_MAP").click(function(){
    map.setView([-40.71395084817901, 174.01979895371952], 6);
    console.log("Centered the map! Did you get lost?");
  });

  $("#COVID_SITE_BUTTON").click(function(){
    $("#COVID_SITE").toggle();
    console.log("Toggled the thing that used to show the covid site but now is just the options panel and I never bothered to changed the button name from COVID_SITE");
  });

  $('#CASE_NUMBERS').click(function(){
  $('#popup').toggle();
  console.log("Toggled Case Chart");
  });

//  $('#CASE_NUMBERS').click(function(){
//    $('#popup').hide();
//  });
});
