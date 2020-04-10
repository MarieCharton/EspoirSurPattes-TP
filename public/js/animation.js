/*Diaporama Fonction */ 

$('.slider').each(function() {
  var $this = $(this);
  var $group = $this.find('.slide_group');
  var $slides = $this.find('.slide');
  var bulletArray = [];
  var currentIndex = 0;
  var timeout;
  
  function move(newIndex) {
    var animateLeft, slideLeft;
    
    advance();
    
    if ($group.is(':animated') || currentIndex === newIndex) {
      return;
    }
    
    bulletArray[currentIndex].removeClass('active');
    bulletArray[newIndex].addClass('active');
    
    if (newIndex > currentIndex) {
      slideLeft = '100%';
      animateLeft = '-100%';
    } else {
      slideLeft = '-100%';
      animateLeft = '100%';
    }
    
    $slides.eq(newIndex).css({
      display: 'block',
      left: slideLeft
    });
    $group.animate({
      left: animateLeft
    }, function() {
      $slides.eq(currentIndex).css({
        display: 'none'
      });
      $slides.eq(newIndex).css({
        left: 0
      });
      $group.css({
        left: 0
      });
      currentIndex = newIndex;
    });
  }
  
  function advance() {
    clearTimeout(timeout);
    timeout = setTimeout(function() {
      if (currentIndex < ($slides.length - 1)) {
        move(currentIndex + 1);
      } else {
        move(0);
      }
    }, 10000);
  }
  
  $('.next_btn').on('click', function() {
    if (currentIndex < ($slides.length - 1)) {
      move(currentIndex + 1);
    } else {
      move(0);
    }
  });
  
  $('.previous_btn').on('click', function() {
    if (currentIndex !== 0) {
      move(currentIndex - 1);
    } else {
      move(3);
    }
  });
  
  $.each($slides, function(index) {
    var $button = $('<a class="slide_btn">&bull;</a>');
    
    if (index === currentIndex) {
      $button.addClass('active');
    }
    $button.on('click', function() {
      move(index);
    }).appendTo('.slide_buttons');
    bulletArray.push($button);
  });
  
  advance();
});


/*Fonction responsive Header */
var headerMenu = document.querySelector("#responsive-nav");
var navButton = document.querySelector("#homeButton");

// window.addEventListener("click",showMenu);

navButton.addEventListener("click",showMenu);


function showMenu(){

  if (window.matchMedia("(max-width: 780px)").matches) {


    if(headerMenu.classList.contains("closeMenu")) {
      headerMenu.classList.remove("closeMenu");
      headerMenu.classList.add("openMenu");
      headerMenu.style.display = "inline"
      console.log("menu ouvert ok");
      }


    else if(headerMenu.classList.contains("openMenu")) {
      headerMenu.classList.remove("openMenu");
      headerMenu.classList.add("closeMenu");
      headerMenu.style.display = "none"
      console.log("menu fermé ok");
      }
  }
}

/*Owl Carousel*/ 
$(document).ready(function(){
  $('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    dot: true,
    responsive:{
        0:{
            items:1
        },
        768:{
            items:2
        },
        1280:{
            items:5
        }
    }
})
});


/*lIGHTBOx*/ 

/*
 * Name: Dead simple lightbox
 * URL: http://kozmaflorin.com/demo/lightbox/
 * Author: Kozma Florin
 * Author URI: http://kozmaflorin.com
 * Description: Dead simple lightbox tries to keep the concept simple enough for implementation of for further development.
 * Version: 1.0
 * License: General Public License
 * License URI: http://www.gnu.org/licenses/gpl.html
 *
 * Additional info: This template uses boostrap for the grid system.
 * Usage: Add the id #deadSimpleLightbox in whatever HTML element,
 * and the script will automatically take every image from that parent id and display it in a lightbox (when clicked).
 * Note: If you add alt attributes to the images, it will be shown as a caption below the image
 */


// Set up HTML elements into variables
var $overlay	= $('<div id="lightboxOverlay"></div>');
var $image		= $('<img>');
var $caption	= $('<h3></h3>');
var $close		= $('<i class="fa fa-times"></i>');

// Just more variables
var imageUrl;
var imageAlt;

// Adding HTML stuff
$('body').append($overlay);		// Add the overlay to the document
$overlay.hide();				// Hide the overlay

// When a user clicks on an image
$('#deadSimpleLightbox img').click(function(){
	imageUrl = $(this).attr('src');		// Find the image URL
	imageAlt = $(this).attr('alt');		// Find the image Alt text

	$overlay.append($image);			// Add the image to the overlay
	$overlay.append($caption);			// Add the image caption to the overlay
	$overlay.append($close);			// Add the close button to the overlay
	$image.attr('src', imageUrl);		// Add the link to the image attribute
	$caption.text(imageAlt);			// Add text to the <p> tag
	$overlay.fadeIn('1000');			// Show the overlay
	$image.fadeIn('1000');
});

// If the users clicks anywhere on the click iccon, hide  the overlay.
$close.click( function() {
	$overlay.fadeOut('1000');
} );