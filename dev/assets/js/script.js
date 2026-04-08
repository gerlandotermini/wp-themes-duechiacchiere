// Table of Contents
//
// 1. Utilities
// 2. Back to Top
// 3. Comments
// 4. Menu
// 5. Live Search
// 6. Miscellaneous

window.addEventListener('DOMContentLoaded', () => {

  // 1. Utilities
  // ----------------------------------------------------------------
  const getSiblings = (el, withClass) => {
    let siblings = [];
    if (!el.parentNode) return siblings;
    let sibling = el.parentNode.firstChild;
    while (sibling) {
      if (sibling.nodeType === 1 && sibling !== el && (!withClass || sibling.classList.contains(withClass))) {
        siblings.push(sibling);
      }
      sibling = sibling.nextSibling;
    }
    return siblings;
  };

  const addMultiEventListener = (element, listener) => {
    element.addEventListener('click', listener, false);
    element.addEventListener('touchstart', listener, { passive: true });
    element.addEventListener('touchend', (e) => e.preventDefault());
  };

  const getCookie = (name) => {
    const value = '; ' + document.cookie;
    const parts = value.split('; ' + name + '=');
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
    return '';
  };

  const isVisible = (el) => {
    if (!el) return false;

    const style = getComputedStyle(el);
    const rect = el.getBoundingClientRect();

    return (
      style.display !== 'none' &&
      style.visibility !== 'hidden' &&
      style.opacity !== '0' &&
      rect.width > 0 &&
      rect.height > 0
    );
  };

  const cssVar = (name) =>
    getComputedStyle(document.documentElement)
      .getPropertyValue(name)
      .trim();

  const mq = {
    mobile: window.matchMedia(`(max-width: ${cssVar('--bp-mobile-max')})`),
    tablet: window.matchMedia(`(min-width: ${cssVar('--bp-tablet-min')})`),
    large: window.matchMedia(`(min-width: ${cssVar('--bp-large-min')})`)
  };

  const stripTags = (html) => {
    const tmpTagCleaner = document.createElement('div');
    tmpTagCleaner.innerHTML = html;
    return tmpTagCleaner.textContent || tmpTagCleaner.innerText || '';
  };

  // 2. Back to Top
  // ----------------------------------------------------------------
  const backToTopButton = document.getElementById('backtotop');
  window.addEventListener('scroll', () => {
    if (!backToTopButton) return;
    backToTopButton.classList.toggle(
      'active',
      document.documentElement.scrollTop > 600
    );
  });

  // 3. Comments
  // ----------------------------------------------------------------
  if (document.body.classList.contains('single')) {

    if (typeof tinymce !== 'undefined') {
      const fontFamily = getComputedStyle(document.documentElement).getPropertyValue('--body-font-family').trim();

      tinymce.init({
        selector: '#comment-editor',
        inline: true,
        menubar: false,
        plugins: 'link lists',
        toolbar: 'link blockquote',
        link_quicklink: true,
        language_url: '/content/themes/duechiacchiere/assets/js/tinymce-it.js',
        height: 200,
        fixed_toolbar_container: '#comment-editor-toolbar',

        link_context_toolbar: false,
        link_title: false,
        target_list: false,
        anchor_top: false,
        anchor_bottom: false,

        setup: function(editor) {
          // --- Sync content on submit ---
          const form = document.getElementById('comment-form');
          form.addEventListener('submit', function(e) {
            const content = editor.getContent({format:'text'}).trim();
            if (!content) {
              e.preventDefault();
              alert('Per favore, inserisci un commento.');
              editor.focus();
            } else {
              document.getElementById('comment').value = editor.getContent();
            }
          });

        }
      });
    }

    const showOtherReplyButton = () => {
      const reply_button = document.getElementById('restore-reply-button');
      if (reply_button) {
        reply_button.classList.remove('visually-hidden');
        reply_button.removeAttribute('id');
      }
    };

    // Reply links
    document.querySelectorAll('.comment-reply-link').forEach(link => {
      addMultiEventListener(link, (e) => {
        e.preventDefault();
        showOtherReplyButton();

        // Hide clicked reply link
        link.setAttribute('id', 'restore-reply-button');
        link.classList.add('visually-hidden');

        // Update reply title
        const replyTitle = document.getElementById('reply-title');
        if (!replyTitle.hasAttribute('data-original-title')) {
          replyTitle.setAttribute('data-original-title', replyTitle.textContent);
        }
        replyTitle.textContent = link.getAttribute('data-replyto');

        // Set parent comment ID
        document.getElementById('comment_parent').value = link.getAttribute('data-commentid');

        // Reset reply button value if needed
        const replyButton = document.getElementById('comment-submit');
        if (replyButton.hasAttribute('data-original-value')) {
          replyButton.value = replyButton.getAttribute('data-original-value');
        }

        // Show like button
        document.getElementById('like-comment-reply').style.display = 'block';

        // MOVE the entire respond wrapper after the comment
        const respondWrapper = document.getElementById('respond');
        const commentLi = link.closest('li');
        commentLi.appendChild(respondWrapper);

        // Re-sync TinyMCE content to hidden textarea
        const editor = tinymce.get('comment-editor');
        if (editor) {
          editor.setContent(document.getElementById('comment').value);
          editor.focus();
        }

        // Show cancel reply button
        document.getElementById('cancel-comment-reply').style.display = 'block';
      });
    });

    // Cancel reply button
    const cancel_comment_reply = document.getElementById('cancel-comment-reply');
    if (cancel_comment_reply) {
      addMultiEventListener(cancel_comment_reply, (e) => {
        e.preventDefault();
        showOtherReplyButton();

        cancel_comment_reply.style.display = 'none';

        // Restore reply title
        const replyTitle = document.getElementById('reply-title');
        replyTitle.textContent = replyTitle.getAttribute('data-original-title');

        // Reset reply button value
        const replyButton = document.getElementById('comment-submit');
        if (replyButton.hasAttribute('data-original-value')) {
          replyButton.value = replyButton.getAttribute('data-original-value');
        }

        // Show hidden textarea
        const comment_field = document.getElementById('comment-editor');
        comment_field.style.display = 'block';

        document.getElementById('like-section').style.display = 'block';
        document.getElementById('comment_parent').value = 0;

        // Move respond wrapper back to original position at the bottom of comments
        const commentsSection = document.getElementById('comments');
        const respondWrapper = document.getElementById('respond');
        commentsSection.appendChild(respondWrapper);

        // Re-sync TinyMCE content
        const editor = tinymce.get('comment-editor');
        if (editor) {
          editor.setContent('');
          editor.focus();
        }
      });
    }

    // Sync editor content on submit
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
      commentForm.addEventListener('submit', function(e) {
        const editor = tinymce.get('comment-editor');
        if (editor) {
          const content = editor.getContent({format:'text'}).trim();
          if (content) {
            // Sync to hidden textarea
            document.getElementById('comment').value = editor.getContent();
          }
        }
      });
    }

    // Form validation
    document.getElementById('comment-form').addEventListener('submit', (e) => {
      e.preventDefault();
      let submitForm = true;

      e.target.querySelectorAll('[required]').forEach(node => {
        submitForm = submitForm && (node.value.trim() != '');
      });

      const emailField = e.target.querySelector('#email');
      if (emailField && !emailField.value.toLowerCase().match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
        submitForm = false;
      }

      if (!submitForm) {
        document.getElementById('comment-submit').classList.add('shake');
        setTimeout(() => document.getElementById('comment-submit').classList.remove('shake'), 500);
      } else e.target.submit();
    });

    // Like button
    const like_comment_reply = document.getElementById('like-comment-reply');
    if (like_comment_reply) {
      addMultiEventListener(like_comment_reply, (e) => {
        e.preventDefault();
        const comment_field_container = document.getElementById('comment-editor');
        if (comment_field_container) {
          comment_field_container.style.display = 'none';
        }

        const editor = tinymce.get('comment-editor');
        editor.setContent('[##like##]');

        const replyTitle = document.getElementById('reply-title');
        if (!replyTitle.hasAttribute('data-original-title')) {
          replyTitle.setAttribute('data-original-title', replyTitle.textContent);
        }
        replyTitle.textContent = "Ti è piaciuto questo post? Dimmi chi sei.";

        const replyButton = document.getElementById('comment-submit');
        if (!replyButton.hasAttribute('data-original-value')) {
          replyButton.setAttribute('data-original-value', replyButton.value);
        }
        replyButton.value = 'Mi piace';

        document.getElementById('cancel-comment-reply').style.display = 'block';
        document.getElementById('like-section').style.display = 'none';
        document.getElementById('like-section').after(document.getElementById('comment-form').parentElement);
        document.getElementById('author').focus();
      });
    }
  }

  // Populate comment fields from cookies
  if (typeof(duechiacchiere.COOKIEHASH) !== 'undefined') {
    if (document.getElementById('author')) document.getElementById('author').value = getCookie('comment_author_' + duechiacchiere.COOKIEHASH);
    if (document.getElementById('email')) document.getElementById('email').value = getCookie('comment_author_email_' + duechiacchiere.COOKIEHASH);
    if (document.getElementById('url')) document.getElementById('url').value = getCookie('comment_author_url_' + duechiacchiere.COOKIEHASH);
  }

  // 4. Menu (mobile-first, breakpoint-aware)
  // ----------------------------------------------------------------
  const toolbarMenuButton = document.getElementById('mobile-nav-button');
  const toolbarSearchButton = document.getElementById('mobile-search-button');
  const menuOverlay = document.getElementById('menu-overlay');
  const primaryMenu = document.getElementById('primary-menu');

  let bodyWidth = 0;
  let mobileListeners = [];
  let searchForm = document.getElementById('search-form-widget');

  const addMobileListener = (el, type, fn, options) => {
    el.addEventListener(type, fn, options);
    mobileListeners.push({ el, type, fn, options });
  };

  const removeMobileListeners = () => {
    mobileListeners.forEach(({ el, type, fn, options }) => el.removeEventListener(type, fn, options));
    mobileListeners = [];
  };

  // Overlay toggle
  const toggleOverlay = (action) => {
    if (!menuOverlay) return;

    const isActive = action !== 'hide';

    if (isActive) {
      const scrollbarWidth =
        window.innerWidth - document.documentElement.clientWidth;

      document.body.style.setProperty(
        '--scrollbar-compensation',
        `${scrollbarWidth}px`
      );
    } else {
      document.body.style.removeProperty('--scrollbar-compensation');
    }

    menuOverlay.classList.toggle('active', isActive);
    document.body.classList.toggle('overlay-active', isActive);
  };

  // Menu toggle
  const toggleMenu = (action) => {
    if (!primaryMenu || !toolbarMenuButton) return;

    // Close search if opening menu
    if (action !== 'close' && searchForm?.classList.contains('active')) {
      toggleSearch('close');
    }

    if (action === 'close' || toolbarMenuButton.classList.contains('active')) {
      primaryMenu.classList.remove('active');
      toolbarMenuButton.classList.remove('active');
      toggleOverlay('hide');
    } else {
      primaryMenu.classList.add('active');
      toolbarMenuButton.classList.add('active');
      toggleOverlay('show');
    }
  };

  // Search toggle
  const toggleSearch = (action) => {
    if (!searchForm || !toolbarSearchButton) return;

    // Close menu if opening search
    if (action !== 'close' && toolbarMenuButton?.classList.contains('active')) {
      toggleMenu('close');
    }

    if (action === 'close' || toolbarSearchButton.classList.contains('active')) {
      searchForm.classList.remove('active');
      toolbarSearchButton.classList.remove('active');
      toggleOverlay('hide');
      searchForm.addEventListener('transitionend', function onEnd() {
        searchForm.removeEventListener('transitionend', onEnd);
      });
    } else {
      searchForm.classList.add('active');
      toolbarSearchButton.classList.add('active');
      document.getElementById('search-field').focus();
      toggleOverlay('show');
    }
  };

  // Mobile setup
  const onEnterMobile = () => {
    if (toolbarMenuButton) {
      addMobileListener(toolbarMenuButton, 'click', () => toggleMenu('toggle'));
      addMobileListener(
        toolbarMenuButton,
        'touchstart',
        (e) => {
          e.preventDefault();
          toggleMenu('toggle');
        },
        { passive: false }
      );
    }

    if (toolbarSearchButton) {
      addMobileListener(toolbarSearchButton, 'click', () => toggleSearch('toggle'));
      addMobileListener(
        toolbarSearchButton,
        'touchstart',
        (e) => {
          e.preventDefault();
          toggleSearch('toggle');
        },
        { passive: false }
      );
    }

    if (menuOverlay) {
      addMobileListener(menuOverlay, 'click', () => { toggleOverlay('hide'); toggleMenu('close'); toggleSearch('close'); });
      addMobileListener(
        menuOverlay,
        'touchstart',
        () => {
          toggleOverlay('hide');
          toggleMenu('close');
          toggleSearch('close');
        }
      );
    }

    // Reset menu/search state
    toggleMenu('close');
    toggleSearch('close');
  };

  // Mobile teardown
  const onExitMobile = () => {
    console.log('Exited mobile mode');

    // Remove listeners
    removeMobileListeners();

    // Reset menu/search state
    toggleMenu('close');
    toggleSearch('close');
  };

  // Attach breakpoint listener
  mq.mobile.addEventListener('change', (e) => {
    if (e.matches) onEnterMobile();
    else onExitMobile();
  });

  // Initialize
  if (mq.mobile.matches) onEnterMobile();

  // Submenus
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

    item.querySelector( 'a' ).setAttribute( 'aria-expanded', 'false' );
    item.querySelector( 'a' ).insertAdjacentHTML( 'afterend', '<a class="svg open-submenu" href="#" aria-expanded="false" aria-haspopup="true"><span class="visually-hidden" style="left:-10000vh;position:absolute"> apri il sottomenu per ' + enter_preposition + room_name + '</span></a>' );
    
    if (mq.mobile.matches) {
      item.querySelector('.sub-menu')
        .insertAdjacentHTML(
          'afterbegin',
          `<li class="menu-item">
            <a class="svg close-submenu" href="#">
              esci ${exit_preposition}${room_name}
            </a>
          </li>`
        );
    }

    item.addEventListener( 'mouseover', ( e ) => {
      item.querySelectorAll( ':scope > a' ).forEach( link => {
        link.setAttribute( 'aria-expanded', 'true' );
      } );
    } );

    item.addEventListener( 'mouseout', ( e ) => {
      item.querySelectorAll( ':scope > a' ).forEach( link => {
        link.setAttribute( 'aria-expanded', 'false' );
      } );
    } );

    addMultiEventListener( item.querySelector( '.open-submenu' ), ( e ) => {
      if ( e.type != 'touchstart' ) {
        e.preventDefault();
      }

      item.classList.add( 'active' );
      item.querySelectorAll( ':scope > a' ).forEach( link => {
        link.setAttribute( 'aria-expanded', 'true' );
      } );
    } );

    item.querySelectorAll( '.close-submenu' ).forEach( link => {
      addMultiEventListener( link, ( e ) => {
        if ( e.type != 'touchstart' ) {
          e.preventDefault();
        }
        
        item.classList.remove( 'active' );

        item.querySelectorAll( ':scope > a' ).forEach( link => {
          link.setAttribute( 'aria-expanded', 'false' );
        } );

        // On desktop, focus the parent
        if ( parseInt( window.getComputedStyle( document.body, ':before' ).getPropertyValue( 'padding' ) ) === 1 ) {
          item.querySelector( 'a' ).focus();
        }
      } );
    } );

    // This only applies to the desktop version on the menu (we use a pseudoelement to determine which layout is being displayed)
    if ( parseInt( window.getComputedStyle( document.body, '::before' ).getPropertyValue( 'padding' ) ) === 1 ) {
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
            item.querySelectorAll( ':scope > a' ).forEach( link => {
              link.setAttribute( 'aria-expanded', 'false' );
            } );
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

  // 5. Live Search
  // ----------------------------------------------------------------
  const searchDropdown = document.getElementById('live-results');
  const searchFieldInput = document.getElementById('search-field');
  const decoderElem = document.createElement('textarea');
  const searchEndpoint = '//' + window.location.hostname + '/wp-json/wp/v2/posts?per_page=20&search=';
  let searchTimeout = 0;
  let activeIndex = -1;

  if (searchFieldInput && searchDropdown) {
    searchDropdown.setAttribute('role', 'listbox');
    searchDropdown.setAttribute('aria-live', 'polite');
    searchFieldInput.setAttribute('aria-expanded', 'false');
    searchFieldInput.setAttribute('aria-owns', 'live-results');

    const getItems = () => Array.from(searchDropdown.querySelectorAll('li[role="option"]'));

    const setActiveItem = (index) => {
      const items = getItems();
      items.forEach((item, i) => item.classList.toggle('active', i === index));
      activeIndex = index;

      if (index > -1) {
        searchFieldInput.setAttribute('aria-activedescendant', items[index].id);
        // DON'T focus the <a> anymore
        items[index].scrollIntoView({ block: 'nearest' });
      } else {
        searchFieldInput.removeAttribute('aria-activedescendant');
      }
    };

    const populateSearchDropdown = (data) => {
      searchDropdown.innerHTML = '';
      activeIndex = -1;

      if (!data.length) {
        const item = document.createElement('li');
        item.textContent = 'Nessun risultato trovato';
        searchDropdown.appendChild(item);
      } else {
        data.forEach((post, index) => {
          decoderElem.innerHTML = post.title.rendered;
          const item = document.createElement('li');
          item.setAttribute('role', 'option');
          item.setAttribute('id', `live-result-${index}`);
          const link = document.createElement('a');
          link.href = post.link;
          link.textContent = decoderElem.value;
          item.appendChild(link);
          searchDropdown.appendChild(item);
        });
      }

      searchDropdown.style.display = 'block';
      searchFieldInput.setAttribute('aria-expanded', 'true');
    };

    const fetchSearchResults = async () => {
      if (searchFieldInput.value.length < 3) {
        searchDropdown.innerHTML = '';
        searchDropdown.style.display = 'none';
        searchFieldInput.setAttribute('aria-expanded', 'false');
        activeIndex = -1;
        return;
      }

      try {
        const categoryFilter = document.getElementById('search-category');
        const url = searchEndpoint + encodeURIComponent(searchFieldInput.value) +
                    (categoryFilter ? '&categories=' + categoryFilter.value : '');
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network error');
        const data = await response.json();
        populateSearchDropdown(data);
      } catch (err) {
        console.error(err);
      }
    };

    searchFieldInput.addEventListener('input', () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(fetchSearchResults, 500);
    });

    // Keyboard navigation
    searchFieldInput.addEventListener('keydown', (e) => {
      const items = getItems();
      if (!items.length) return;

      switch (e.key) {
        case 'ArrowDown':
          e.preventDefault();
          setActiveItem((activeIndex + 1) % items.length);
          break;
        case 'ArrowUp':
          e.preventDefault();
          setActiveItem((activeIndex - 1 + items.length) % items.length);
          break;
        case 'Enter':
          e.preventDefault();
          if (activeIndex > -1) {
            items[activeIndex].querySelector('a').click();
          } else {
            searchFieldInput.form.submit();
          }
          break;
        case 'Escape':
          searchDropdown.style.display = 'none';
          searchFieldInput.setAttribute('aria-expanded', 'false');
          setActiveItem(-1);
          break;
        default:
          break;
      }
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', (e) => {
      if (!searchDropdown.contains(e.target) && !searchFieldInput.contains(e.target)) {
        searchDropdown.style.display = 'none';
        searchFieldInput.setAttribute('aria-expanded', 'false');
        setActiveItem(-1);
      }
    });
  }

  // 6. Miscellaneous
  // ----------------------------------------------------------------
  // Open external links in a new tab
  document.querySelectorAll('#main-wrapper a').forEach(link => {
    if (link.hostname !== location.hostname.replace('www.', '')) {
      link.target = '_blank';
      if (!link.querySelector('.visually-hidden')) {
        link.insertAdjacentHTML('beforeend', '<span class="visually-hidden"> (apre una nuova finestra)</span>');
      }
      let linkTypes = (link.getAttribute('rel') || '').split(' ');
      if (!linkTypes.includes('noopener')) linkTypes.push('noopener');
      link.setAttribute('rel', linkTypes.join(' ').trim());
    }
  });

  // Add link to read today's posts in the past
  const today = new Date();
  const back_in_time = document.querySelector('#widget-back-in-time ul');
  if (back_in_time) {
    back_in_time.insertAdjacentHTML('beforeend', `<li><a href="/?day=${today.getDate()}&amp;monthnum=${today.getMonth()+1}&amp;year=0" rel="nofollow">Oggi nel passato</a></li>`);
  }
});