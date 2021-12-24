// Keyboard-friendly Navigation
let getSiblings = function ( e ) {
  let siblings = []; 

  // If no parent, return no sibling
  if( !e.parentNode ) {
      return siblings;
  }
  
  // First child of the parent node
  let sibling  = e.parentNode.firstChild;
  
  // Find siblings
  while ( sibling ) {
      if ( sibling.nodeType === 1 && sibling !== e ) {
          siblings.push( sibling );
      }
      sibling = sibling.nextSibling;
  }
  return siblings;
}

document.querySelectorAll( '#header-container ul.menu > .menu-item a' ).forEach( link => {
    // Add a 'focus' event handler to each top level link
    link.addEventListener( 'focus', function() {
      // Append a class 'focus' to the parent li
      this.parentElement.classList.add( 'focus' );

      // Change the aria label
      this.setAttribute( 'aria-expanded', 'true' );

      // Remove class and aria label from all the siblings
      getSiblings( this.parentElement ).forEach( sibling => {
        sibling.classList.remove( 'focus' );
        sibling.firstChild.setAttribute( 'aria-expanded', 'false' );
      });
    });
});

// Hamburger Menu and Overlay
document.querySelector( '#mobile-menu-trigger' ).addEventListener( 'click', function() {
  this.classList.toggle( 'is-active');
  document.querySelector( '#primary-menu .menu' ).classList.toggle( 'open' );
  
});

// Back to Top Button
window.onscroll = function() {
  if ( document.body.scrollTop > 300 || document.documentElement.scrollTop > 300 ) {
    document.getElementById( 'backtotop' ).style.opacity = 1;
  } else {
    document.getElementById( 'backtotop' ).style.opacity = 0;
  }
}

// When the user clicks on the button, scroll to the top of the document
// function topFunction() {
//   document.body.scrollTop = 0; // For Safari
//   document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
// }

// Media Queries
// var previousStatus = false;
// viewport = window.matchMedia( "(max-width: 992px)" );
// function adjustLayout( viewport ) {
//   if ( viewport.matches ) {
//     // Mobile layout
//     siteName = document.querySelector( '#branding #name' );
//     if ( typeof siteName != 'undefined' && siteName != null ) {
//       document.querySelector( '#primary-menu' ).prepend( document.querySelector( '#branding #name' ) );
//     }
//   }
//   else {
//     // Desktop Layout
//     siteName = document.querySelector( '#primary-menu #name' );
//     if ( typeof siteName != 'undefined' && siteName != null ) {
//       document.querySelector( '#branding' ).append( document.querySelector( '#primary-menu #name' ) );
//     }
//   }
// }

// Check resolution on load and on resize
// adjustLayout( viewport );
// viewport.addEventListener( 'change', adjustLayout );