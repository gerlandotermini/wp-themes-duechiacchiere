// Table of Contents
//
// 1. Utilities
// 2. Back to Top
// 3. Comments
// 4. Menu
// 5. User Experience

window.addEventListener( 'load', ( event ) => {
  // WordPress COOKIEHASH (replaced when script is enqueued)
  const duechiacchiere = { 'COOKIEHASH': 'COOKIEHASHVALUE' };

  // // Keyboard-friendly Navigation
  // let getSiblings = function( e ) {
  //   let siblings = []; 

  //   // If no parent, return no sibling
  //   if( !e.parentNode ) {
  //       return siblings;
  //   }
    
  //   // First child of the parent node
  //   let sibling  = e.parentNode.firstChild;
    
  //   // Find siblings
  //   while ( sibling ) {
  //       if ( sibling.nodeType === 1 && sibling !== e ) {
  //           siblings.push( sibling );
  //       }
  //       sibling = sibling.nextSibling;
  //   }
  //   return siblings;
  // }

  // document.querySelectorAll( '#header-container ul.menu > .menu-item > a' ).forEach( link => {
  //     // Add a 'focus' event handler to each top level link
  //     link.addEventListener( 'focus', function() {
  //       // Append a class 'focus' to the parent li
  //       this.parentElement.classList.add( 'focus' );

  //       // Change the aria label
  //       this.setAttribute( 'aria-expanded', 'true' );

  //       // Remove class and aria label from all the siblings
  //       getSiblings( this.parentElement ).forEach( sibling => {
  //         sibling.classList.remove( 'focus' );
  //         sibling.firstChild.setAttribute( 'aria-expanded', 'false' );
  //       });
  //     });
  // });

  // 1. Utilities
  // ----------------------------------------------------------------

  // Function to attach multiple event listeners to an element
  let addMultiEventListener = function( element, listener ) {
    element.addEventListener( 'click', listener, false );
    element.addEventListener( 'touchstart', listener, {passive: true} ); // Passive listeners: https://web.dev/uses-passive-event-listeners/

    // Prevent touch event from triggering a fake 'click' event
    element.addEventListener('touchend', event => { event.preventDefault(); });
  }
  let getCookie = function( name ) {
    const value = '; ' + document.cookie;
    const parts = value.split( '; ' + name + '=' );

    if ( parts.length === 2 ) {
      return decodeURIComponent( parts.pop().split( ';' ).shift() );
    }

    return '';
  }

  // 2. Back to Top
  // ----------------------------------------------------------------

  // Show/hide back to top button
  window.onscroll = function() {
    const backToTopButton = document.getElementById( 'backtotop' );

    if ( document.body.scrollTop > 300 || document.documentElement.scrollTop > 300 ) {
      backToTopButton.style.opacity = 1;
      backToTopButton.style.cursor = 'pointer';
    } else {
      backToTopButton.style.opacity = 0;
      backToTopButton.style.cursor = 'initial';
    }
  }

  // 3. Comments
  // ----------------------------------------------------------------

  // Show/Hide the "Rispondi" button after it's been clicked
  if ( document.body.classList.contains( 'single' ) ) {
    let showReplyButton = function( e ) {
      // e.preventDefault();

      let reply_button = document.getElementById( 'restore-reply-link' );
      if ( reply_button !== null ) {
        reply_button.classList.remove( 'visually-hidden' );
        reply_button.removeAttribute( 'id' );
      }
    }

    let hideReplyButton = function( e ) {
      showReplyButton();

      e.currentTarget.setAttribute( 'id', 'restore-reply-link' );
      e.currentTarget.classList.add( 'visually-hidden' );
    }

    document.querySelectorAll( '.comment-reply-link' ).forEach( link => {
      addMultiEventListener( link, hideReplyButton );
    } );

    const cancel_comment_reply_link = document.getElementById( 'cancel-comment-reply-link' );
    if ( cancel_comment_reply_link !== null ) {
      addMultiEventListener( cancel_comment_reply_link, showReplyButton );
    }
  }

  // Populate comment fields with cookie values, if available
  
  if ( typeof( duechiacchiere.COOKIEHASH ) != 'undefined' ) {
    if ( document.getElementById( 'author' ) !== null ) {
      document.getElementById( 'author' ).value = getCookie( 'comment_author_' + duechiacchiere.COOKIEHASH );
    }
    if ( document.getElementById( 'email' ) !== null ) {
      document.getElementById( 'email' ).value = getCookie( 'comment_author_email_' + duechiacchiere.COOKIEHASH );
    }
    if ( document.getElementById( 'url' ) !== null ) {
      document.getElementById( 'url' ).value = getCookie( 'comment_author_url_' + duechiacchiere.COOKIEHASH );
    }
  }

  // 4. Menu
  // ----------------------------------------------------------------

  // Enable the trigger to open and close the menu
  const toolbarMenuButton = document.getElementById( 'mobile-nav-button' );
  const menuOverlay = document.getElementById( 'menu-overlay' );
  let bodyWidth = 0;

  let toggleMenu = function ( e, action ) {
    const menu = document.getElementById( 'primary-menu' );  

    e.preventDefault();

    if ( menu === null || menuOverlay === null || toolbarMenuButton === null ) {
      return false;
    }

    if ( action == 'close' ||  toolbarMenuButton.classList.contains( 'active' ) ) {
      menu.classList.remove( 'active' );
      menuOverlay.classList.remove( 'active' );
      toolbarMenuButton.classList.remove( 'active' );

      document.body.style.overflowY = 'visible';
      document.body.style.paddingRight = 0;
    }
    else if ( action == 'open' || !toolbarMenuButton.classList.contains( 'active' ) ) {
      menu.classList.add( 'active' );
      menuOverlay.classList.add( 'active' );
      toolbarMenuButton.classList.add( 'active' );

      bodyWidth = document.documentElement.clientWidth; // Prevent copy reflow issues when removing overflow-y from body
      document.body.style.overflowY = 'hidden';
      document.body.style.paddingRight = ( document.documentElement.clientWidth - bodyWidth) + 'px';
    }
  }

  // Attach the appropriate event handler to the mobile menu button
  addMultiEventListener( toolbarMenuButton, function( e ) {
    toggleMenu( e, 'toggle' );
  } );

  // Hide the menu when tapping on the overlay. We had to use an actual DIV because we cannot attach event handlers to pseudo elements
  addMultiEventListener( menuOverlay, function( e ) {
    toggleMenu( e, 'close' );
  } );

  // When tapping the search button, let's make sure the navigation is closed
  addMultiEventListener( document.getElementById( 'mobile-search-button' ), function( e ) {
    toggleMenu( e, 'close' );
    document.getElementById( 'search-field' ).focus();
    document.getElementById( 'search-field' ).closest( '.widget' ).scrollIntoView();
  } );

  // Add elements to open and close the submenus
  document.querySelectorAll( '#primary-menu .menu-item-has-children' ).forEach( item => {
    // Use Italian grammar to determine which preposition to use
    let room_name = item.childNodes[0].textContent;

    let preposition = 'dal';
    if ( [ 'a', 'e', 'i', 'o', 'u' ].indexOf( room_name.charAt(0) ) != -1 ) { // word starts with a vowel
      preposition += "l'";
    }
    else if ( room_name.slice(-1) == 'a' ) { // word ends with 'a'
      preposition += 'la ';
    }
    else {
      preposition += ' ';
    }

    item.querySelector( 'a' ).insertAdjacentHTML( 'afterend', '<a class="open-submenu" href="#"><span class="visually-hidden">entra in ' + room_name + '</span></a>' );
    item.querySelector( '.sub-menu' ).insertAdjacentHTML( 'afterbegin', '<li class="menu-item"><a class="close-submenu" href="#">esci ' + preposition + room_name + '</a></li>' );
    
    addMultiEventListener( item.querySelector( '.open-submenu' ), function( e ) {
      e.preventDefault();
      item.classList.add( 'active' );
    } );

    addMultiEventListener( item.querySelector( '.close-submenu' ), function( e ) {
      e.preventDefault();
      item.classList.remove( 'active' );
    } );
  } );

  // 5. User Experience
  // ----------------------------------------------------------------

  // Open external links in a new tab/window
  document.querySelectorAll( 'a' ).forEach( link => {
    if ( link.getAttribute( 'href' ) && link.hostname !== location.hostname ) {
      link.target = '_blank';
      link.rel = 'noopener noreferrer';
    }
  });
} );