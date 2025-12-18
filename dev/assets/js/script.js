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

  // 2. Back to Top
  // ----------------------------------------------------------------
  const backToTopButton = document.getElementById('backtotop');
  window.onscroll = () => {
    if (!backToTopButton) return;
    if (document.body.scrollTop > 600 || document.documentElement.scrollTop > 600) {
      backToTopButton.classList.add('active');
    } else {
      backToTopButton.classList.remove('active');
    }
  };

  // 3. Comments
  // ----------------------------------------------------------------
  if (document.body.classList.contains('single')) {
    const showOtherReplyButton = () => {
      const reply_button = document.getElementById('restore-reply-button');
      if (reply_button) {
        reply_button.classList.remove('visually-hidden');
        reply_button.removeAttribute('id');
      }
    };

    document.querySelectorAll('.comment-reply-link').forEach(link => {
      addMultiEventListener(link, (e) => {
        e.preventDefault();
        showOtherReplyButton();

        link.setAttribute('id', 'restore-reply-button');
        link.classList.add('visually-hidden');

        const replyTitle = document.getElementById('reply-title');
        if (!replyTitle.hasAttribute('data-original-title')) {
          replyTitle.setAttribute('data-original-title', replyTitle.textContent);
        }
        replyTitle.textContent = link.getAttribute('data-replyto');

        document.getElementById('comment_parent').value = link.getAttribute('data-commentid');

        const replyButton = document.getElementById('comment-submit');
        if (replyButton.hasAttribute('data-original-value')) {
          replyButton.value = replyButton.getAttribute('data-original-value');
        }

        const comment_field = document.getElementById('comment');
        comment_field.style.display = 'block';
        if (comment_field.value == '[##like##]') comment_field.value = '';

        document.getElementById('like-comment-reply').style.display = 'block';
        link.closest('li').appendChild(document.getElementById('comment-form').parentElement);

        document.getElementById('cancel-comment-reply').style.display = 'block';
        comment_field.focus();
      });
    });

    const cancel_comment_reply = document.getElementById('cancel-comment-reply');
    if (cancel_comment_reply) {
      addMultiEventListener(cancel_comment_reply, (e) => {
        e.preventDefault();
        showOtherReplyButton();

        cancel_comment_reply.style.display = 'none';

        const replyTitle = document.getElementById('reply-title');
        replyTitle.textContent = replyTitle.getAttribute('data-original-title');

        const replyButton = document.getElementById('comment-submit');
        if (replyButton.hasAttribute('data-original-value')) {
          replyButton.value = replyButton.getAttribute('data-original-value');
        }

        const comment_field = document.getElementById('comment');
        comment_field.style.display = 'block';
        if (comment_field.value == '[##like##]') comment_field.value = '';

        document.getElementById('like-section').style.display = 'block';
        document.getElementById('comment_parent').value = 0;
        document.getElementById('comments').appendChild(document.getElementById('comment-form').parentElement);
        comment_field.focus();
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
        const comment_field = document.getElementById('comment');
        comment_field.style.display = 'none';
        comment_field.value = '[##like##]';

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

    if (action === 'hide') {
      menuOverlay.classList.remove('active');
      document.body.style.paddingRight = 0;
    } else {
      menuOverlay.classList.add('active');
      bodyWidth = document.documentElement.clientWidth;
      document.body.style.paddingRight = (document.documentElement.clientWidth - bodyWidth) + 'px';
    }
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
      searchForm.style.zIndex = 475;
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
  document.querySelectorAll('#primary-menu .menu-item-has-children').forEach(item => {
    const parentLink = item.querySelector(':scope > a');
    parentLink.setAttribute('aria-expanded', 'false');
    const roomName = parentLink.textContent;

    item.querySelector('a').insertAdjacentHTML('afterend', `<a class="svg open-submenu" href="#" aria-expanded="false" aria-haspopup="true"><span class="visually-hidden"> apri il sottomenu per ${roomName}</span></a>`);

    addMultiEventListener(item.querySelector('.open-submenu'), (e) => {
      if (e.type !== 'touchstart') e.preventDefault();
      item.classList.add('active');
      item.querySelectorAll(':scope > a').forEach(link => link.setAttribute('aria-expanded', 'true'));
    });
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