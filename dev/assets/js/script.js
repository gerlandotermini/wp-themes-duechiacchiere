// Table of Contents
//
// 1. Utilities
// 2. Back to Top
// 3. Comments
// 4. Menu
// 5. User Experience

window.addEventListener( 'DOMContentLoaded', ( event ) => {
  // WordPress COOKIEHASH (replaced when script is enqueued)
  const duechiacchiere = { 'COOKIEHASH': 'COOKIEHASHVALUE' };

  // 1. Utilities
  // ----------------------------------------------------------------

  // Find all the siblings of a given node
  let getSiblings = ( el, withClass ) => {
    let siblings = []; 

    // If no parent, return no sibling
    if( !el.parentNode ) {
        return siblings;
    }
    
    // First child of the parent node
    let sibling  = el.parentNode.firstChild;
    
    // Find siblings
    while ( sibling ) {
        if ( sibling.nodeType === 1 && sibling !== el && ( !withClass || sibling.classList.contains( withClass ) ) ) {
          siblings.push( sibling );
        }

        sibling = sibling.nextSibling;
    }

    return siblings;
  }

  // Function to attach multiple event listeners to an element
  let addMultiEventListener = ( element, listener ) => {
    element.addEventListener( 'click', listener, false );
    element.addEventListener( 'touchstart', listener, { passive: true } ); // Passive listeners: https://web.dev/uses-passive-event-listeners/

    // Prevent touch event from triggering a fake 'click' event
    element.addEventListener( 'touchend', ( event ) => { event.preventDefault(); } );
  }

  // Function to retrieve a cookie's value
  let getCookie = ( name ) => {
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
  window.onscroll = () => {
    const backToTopButton = document.getElementById( 'backtotop' );

    if ( document.body.scrollTop > 900 || document.documentElement.scrollTop > 900 ) {
      backToTopButton.style.opacity = 1;
    } else {
      backToTopButton.style.opacity = 0;
    }
  }

  // 3. Comments
  // ----------------------------------------------------------------

  // Move the comment form closer to the comment someone is replying to
  if ( document.body.classList.contains( 'single' ) ) {
    let showOtherReplyButton = () => {
      let reply_button = document.getElementById( 'restore-reply-button' );
      if ( reply_button !== null ) {
        reply_button.classList.remove( 'visually-hidden' );
        reply_button.removeAttribute( 'id' );
      }
    }

    let commentReply = ( e ) => {
      e.preventDefault();

      // If the user had clicked on another reply button, restore its original state
      showOtherReplyButton();

      // Hide the reply button
      e.currentTarget.setAttribute( 'id', 'restore-reply-button' );
      e.currentTarget.classList.add( 'visually-hidden' );
      
      // Set aside the original reply title
      const replyTitle = document.getElementById( 'reply-title' );
      if ( !replyTitle.hasAttribute( 'data-original-title' ) ) {
        replyTitle.setAttribute( 'data-original-title', replyTitle.textContent );
      }

      // Update the heading
      replyTitle.textContent = e.currentTarget.getAttribute( 'data-replyto' );

      // Set the value of the hidden field for the parent comment
      document.getElementById( 'comment_parent' ).value = e.currentTarget.getAttribute( 'data-commentid' );

      // Move the comment form
      e.currentTarget.closest( 'li' ).appendChild( document.getElementById( 'comment-form' ).parentElement );

      // Show the 'cancel' button
      document.getElementById( 'cancel-comment-reply' ).style.display = 'block';

      // Focus on the comment field
      document.getElementById( 'comment' ).focus();
    }

    let commentCancelReply = ( e ) => {
      e.preventDefault();

      // If the user had clicked on another reply button, restore its original state
      showOtherReplyButton();

      // Hide the 'cancel' button
      document.getElementById( 'cancel-comment-reply' ).style.display = 'none';

      // Reset the heading
      const replyTitle = document.getElementById( 'reply-title' );
      replyTitle.textContent = replyTitle.getAttribute( 'data-original-title' );

      // Reset the value of the hidden field for the parent comment
      document.getElementById( 'comment_parent' ).value = 0;

      // Move the form back
      document.getElementById( 'comments' ).appendChild( document.getElementById( 'comment-form' ).parentElement );

      // Focus on the comment field
      document.getElementById( 'comment' ).focus();
    }

    document.querySelectorAll( '.comment-reply-link' ).forEach( link => {
      addMultiEventListener( link, commentReply );
    } );

    const cancel_comment_reply = document.getElementById( 'cancel-comment-reply' );
    if ( cancel_comment_reply !== null ) {
      addMultiEventListener( cancel_comment_reply, commentCancelReply );
    }

    // Make sure that the comment is not empty
    document.getElementById( 'comment-form' ).addEventListener( 'submit', ( e ) => {
      e.preventDefault();
      
      let submitForm = true;
      e.target.querySelectorAll( '[required]' ).forEach( node => {
        submitForm = submitForm && ( node.value != '' );
      } );

      if ( !submitForm ) {
        document.getElementById( 'comment-submit' ).classList.add( 'shake' );
        setTimeout( () => {
          document.getElementById( 'comment-submit' ).classList.remove( 'shake' );
        }, 500 );
      }
      else {
        e.target.submit();
      }
    } );
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

  let toggleMenu = ( e, action ) => {
    const menu = document.getElementById( 'primary-menu' );  

    if ( e.type != 'touchstart' ) {
      e.preventDefault();
    }

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
  addMultiEventListener( toolbarMenuButton, ( e ) => {
    toggleMenu( e, 'toggle' );
  } );

  // Hide the menu when tapping on the overlay. We had to use an actual DIV because we cannot attach event handlers to pseudo elements
  addMultiEventListener( menuOverlay, ( e ) => {
    toggleMenu( e, 'close' );
  } );

  // When tapping the search button, let's make sure the navigation is closed
  addMultiEventListener( document.getElementById( 'mobile-search-button' ), ( e ) => {
    toggleMenu( e, 'close' );
    document.getElementById( 'search-field' ).focus();
    document.getElementById( 'search-field' ).closest( '.widget' ).scrollIntoView();
  } );

  // Add elements to open and close the submenus
  document.querySelectorAll( '#primary-menu .menu-item-has-children' ).forEach( item => {
    // Use Italian grammar to determine which preposition to use
    let room_name = item.childNodes[0].textContent;

    let enter_preposition = 'il ';
    let exit_preposition = 'dal';

    if ( [ 'a', 'e', 'i', 'o', 'u' ].indexOf( room_name.charAt(0) ) != -1 ) { // word starts with a vowel
      enter_preposition = "l'";
      exit_preposition += "l'";
    }
    else if ( room_name.slice(-1) == 'a' ) { // word ends with 'a'
      enter_preposition = 'la ';
      exit_preposition += 'la ';
    }
    else {
      exit_preposition += ' ';
    }

    item.querySelector( 'a' ).insertAdjacentHTML( 'afterend', '<a class="open-submenu svg icon-chevron-right" href="#" aria-expanded="false"><span class="visually-hidden">apri il sottomenu per ' + enter_preposition + room_name + '</span></a>' );
    item.querySelector( '.sub-menu' ).insertAdjacentHTML( 'afterbegin', '<li class="menu-item"><a href="#" class="close-submenu svg icon-chevron-left"></a> <a class="close-submenu" href="#">esci ' + exit_preposition + room_name + '</a></li>' );
    
    addMultiEventListener( item.querySelector( '.open-submenu' ), ( e ) => {
      if ( e.type != 'touchstart' ) {
        e.preventDefault();
      }

      item.classList.add( 'active' );
      item.querySelector( '.open-submenu' ).setAttribute( 'aria-expanded', 'true' );
    } );

    item.querySelectorAll( '.close-submenu' ).forEach( link => {
      addMultiEventListener( link, ( e ) => {
        if ( e.type != 'touchstart' ) {
          e.preventDefault();
        }
        
        item.classList.remove( 'active' );
        item.querySelector( '.open-submenu' ).setAttribute( 'aria-expanded', 'false' );
      } );
    } );

    // This only applies to the desktop version on the menu (menu is 'flex')
    if ( window.getComputedStyle( document.getElementById( 'menu-primary-menu' ) ).getPropertyValue( 'display' ) === 'flex' ) {
      let is_sibling_selected = false;
      item.querySelector( '.sub-menu' ).addEventListener( 'focusout', ( e ) => {
        // We need the setTimeout to give time to the browser to focus the next element
        setTimeout( () => {
          is_sibling_selected = false;
          getSiblings( e.target.parentElement ).forEach( ( sibling ) => {
            is_sibling_selected = is_sibling_selected || ( document.activeElement.parentElement === sibling );
          });
  
          if ( !is_sibling_selected ) {
            item.classList.remove( 'active' );
            item.querySelector( '.open-submenu' ).setAttribute( 'aria-expanded', 'false' );
          }
        }, 10);
      } );
    };
  } );

  // Close all the flyouts on Esc key
  document.body.addEventListener( 'keyup', ( e ) => {
    if ( e.key == "Escape" ) {
      document.querySelectorAll( '.menu-item-has-children' ).forEach( item => {
        item.classList.remove( 'active' );
        item.querySelector( '.open-submenu' ).setAttribute( 'aria-expanded', 'false' );
      } );
    }
  });

  // 5. User Experience
  // ----------------------------------------------------------------

  // Open external links in a new tab/window
  document.querySelectorAll( 'a' ).forEach( link => {
    if ( link.getAttribute( 'href' ) && link.hostname.indexOf( location.hostname ) == -1 ) {
      link.target = '_blank';

      if ( !link.querySelector( '.visually-hidden' ) ) {
        link.insertAdjacentHTML( 'beforeend', '<span class="visually-hidden"> (apri link in una nuova tab)</span>');
      }

      // See https://codersblock.com/blog/external-links-new-tabs-and-accessibility/
      let linkTypes = ( link.getAttribute( 'rel' ) || '' ).split(' ');
      if ( !linkTypes.includes( 'noopener' ) ) {
        linkTypes.push( 'noopener' );
      }
      link.setAttribute('rel', linkTypes.join(' ').trim());
    }
  });
} );