// Table of Contents
//
// 1. Back to Top
// 2. Comments
// 3. Menu
// 4. User Experience

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

  // 1. Back to Top
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

  // 2. Comments
  // ----------------------------------------------------------------

  // Show/Hide the "Rispondi" button after it's been clicked
  document.querySelectorAll( '.comment-reply-link' ).forEach( link => {
    link.addEventListener( 'click', function( e ) {
      this.setAttribute( 'id', 'restore-reply-link' );
      this.classList.add( 'visually-hidden' );
    });
  });
  document.getElementById( 'cancel-comment-reply-link' ).addEventListener( 'click', function( e ) {
    let reply_button = document.getElementById( 'restore-reply-link' )
    reply_button.classList.remove( 'visually-hidden' );
    reply_button.removeAttribute( 'id' );
  } );

  // Populate comment fields with cookie values, if available
  let getCookie = function( name ) {
    const value = '; ' + document.cookie;
    const parts = value.split( '; ' + name + '=' );

    if ( parts.length === 2 ) {
      return decodeURIComponent( parts.pop().split( ';' ).shift() );
    }

    return '';
  }
  if ( typeof( duechiacchiere.COOKIEHASH ) != 'undefined' && document.querySelector( '#commentform #author' ) != null ) {
    document.querySelector( '#commentform #author' ).value = getCookie( 'comment_author_' + duechiacchiere.COOKIEHASH );
    document.querySelector( '#commentform #email' ).value = getCookie( 'comment_author_email_' + duechiacchiere.COOKIEHASH );
    document.querySelector( '#commentform #url' ).value = getCookie( 'comment_author_url_' + duechiacchiere.COOKIEHASH );
  }

  // 3. Menu
  // ----------------------------------------------------------------

  // Enable the trigger to open and close the menu
  const toolbarMenuButton = document.getElementById( 'mobile-nav-button' );
  const menuOverlay = document.getElementById( 'menu-overlay' );
  let bodyWidth = 0;

  let toggleMenu = function ( e, action ) {
    const menu = document.getElementById( 'primary-menu' );  

    e.preventDefault();

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
  toolbarMenuButton.addEventListener( 'click', function( e ) {
    toggleMenu( e, 'toggle' );
  } );

  // Hide the menu when tapping on the overlay. We had to use an actual DIV because we cannot attach event handlers to pseudo elements
  menuOverlay.addEventListener( 'click', function( e ) {
    toggleMenu( e, 'close' );
  } );

  // When tapping the search button, let's make sure the navigation is closed
  document.getElementById( 'mobile-search-button' ).addEventListener( 'click', function( e ) {
    toggleMenu( e, 'close' );
    document.getElementById( 'search-field' ).focus();
    document.getElementById( 'search-field' ).closest( '.widget' ).scrollIntoView();
  } );

  // Add elements to open and close the submenus
  document.querySelectorAll( '#primary-menu .menu-item-has-children' ).forEach( item => {
    item.querySelector( 'a' ).insertAdjacentHTML( 'afterend', '<a class="open-submenu" href="javascript:;"><span class="visually-hidden">entra in questa stanza</span></a>' );
    item.querySelector( '.sub-menu' ).insertAdjacentHTML( 'afterbegin', '<li class="menu-item"><a class="close-submenu" href="javascript:;">esci dalla stanza</a></li>' );
    
    item.querySelector( '.open-submenu' ).addEventListener( 'click', function( e ) { 
      e.preventDefault();
      item.classList.add( 'active' );
    } );

    item.querySelector( '.close-submenu' ).addEventListener( 'click', function( e ) {
      e.preventDefault();
      item.classList.remove( 'active' );
    } );
  } );

  // 4. User Experience
  // ----------------------------------------------------------------

  // Open external links in a new tab/window
  document.querySelectorAll( 'a' ).forEach( link => {
    if ( link.getAttribute( 'href' ) && link.hostname !== location.hostname ) {
      link.target = '_blank';
      link.rel = 'noopener noreferrer';
    }
  });
} );