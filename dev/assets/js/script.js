// WordPress COOKIEHASH (replaced when script is enqueued)
const duechiacchiere = { 'COOKIEHASH': 'COOKIEHASHVALUE' };

// Keyboard-friendly Navigation
let getSiblings = function( e ) {
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
document.querySelectorAll( '#header-container ul.menu > .menu-item > a' ).forEach( link => {
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

// Mobile Menu
let menuElement = document.getElementById( 'primary-menu' );
const navExpand = document.querySelectorAll('#primary-menu .menu-item-has-children');
const openSubmenuButton = '<a class="open-submenu" href="javascript:;"><span class="visually-hidden">visualizza il menu per questa stanza</span></a>';
const closeSubmenuButton = '<li class="menu-item"><a class="close-submenu" href="javascript:;">esci dalla stanza</a></li>';
const openMenuButton = document.getElementById( 'mobile-nav-button' );

navExpand.forEach( item => {
	item.querySelector( 'a' ).insertAdjacentHTML( 'afterend', openSubmenuButton );
  item.querySelector( '.sub-menu' ).insertAdjacentHTML( 'afterbegin', closeSubmenuButton );
	
  item.querySelector( '.open-submenu' ).addEventListener( 'click', function( e ){ 
    e.preventDefault();
    item.classList.add( 'active' );
  } );

	item.querySelector( '.close-submenu' ).addEventListener( 'click', function( e ){
    e.preventDefault();
    item.classList.remove( 'active' );
  } );
} );
openMenuButton.addEventListener( 'click', function( e ) {
  e.preventDefault();
  menuElement.classList.toggle( 'active' );
  openMenuButton.classList.toggle( 'active' );
});

// Display the comment form under the comment for replies
form_container = document.querySelector( '#respond' );
document.querySelectorAll( '.comment-reply-link' ).forEach( link => {
    link.addEventListener( 'click', function( e ) {
        e.preventDefault();
        comment_container = this.closest( '.comment-body' );
        form_container.querySelector( '#reply-title' ).classList.add( 'visually-hidden' );
        form_container.querySelector( '#comment_parent' ).setAttribute( 'value', comment_container.getAttribute( 'id' ).replace(/\D/g, '') );
        comment_container.append( form_container );
        form_container.querySelector( '#comment' ).focus();
    });
});

// Open external links in a new tab/window
document.querySelectorAll( 'a' ).forEach( link => {
  if ( link.getAttribute( 'href' ) && link.hostname !== location.hostname ) {
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
  }
});

// Show/hide back to top button
window.onscroll = function() {
  if ( document.body.scrollTop > 300 || document.documentElement.scrollTop > 300 ) {
    document.getElementById( 'backtotop' ).style.opacity = 1;
    document.getElementById( 'backtotop' ).style.cursor = 'pointer';
  } else {
    document.getElementById( 'backtotop' ).style.opacity = 0;
    document.getElementById( 'backtotop' ).style.cursor = 'initial';
  }
}

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