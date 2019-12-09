// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

$(document).ready(function(){
  // Initialize Tooltip
  $('[data-toggle="tooltip"]').tooltip(); 
  
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {

      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 900, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
})

function openDay (evt, day) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(day).style.display = "block";
  evt.currentTarget.className += " active";
}

function d1schedule() {
  document.getElementById("d1p1n").innerHTML = "Someone";
  document.getElementById("d1p1t").innerHTML = "22:00" + " CET";
  document.getElementById("d1p1d").innerHTML = "Someone is good at blah blah";
  document.getElementById("d1p2n").innerHTML = "Someone 2";
  document.getElementById("d1p2t").innerHTML = "23:30" + " CET";
  document.getElementById("d1p2d").innerHTML = "Someone 2 is blah blah blah";
  document.getElementById("d1p3n").innerHTML = "Someone 3";
  document.getElementById("d1p3t").innerHTML = "01:10" + " CET";
  document.getElementById("d1p3d").innerHTML = "Someone 3 is ok aokakak";
}