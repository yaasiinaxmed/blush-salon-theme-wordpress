/**
 * Blush Salon front-end behavior. Plain JavaScript, no libraries.
 *
 * - Sticky header shadow
 * - Mobile menu and accessible submenus
 * - Testimonials carousel (dots, swipe, keyboard)
 * - FAQ accordion
 * - Reveal on scroll
 * - Video modal
 * - Form notices
 */
( function () {
	'use strict';

	var i18n = window.blushSalon || {};
	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var desktop = window.matchMedia ? window.matchMedia( '(min-width: 1024px)' ) : { matches: true };

	function each( list, fn ) {
		Array.prototype.forEach.call( list, fn );
	}

	/* Sticky header shadow ------------------------------------------------ */
	function initHeader() {
		var header = document.getElementById( 'site-header' );
		if ( ! header ) {
			return;
		}
		var ticking = false;
		function update() {
			header.classList.toggle( 'is-scrolled', window.scrollY > 8 );
			ticking = false;
		}
		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( update );
			}
		}, { passive: true } );
		update();
	}

	/* Menu ---------------------------------------------------------------- */
	function initMenu() {
		var toggle = document.querySelector( '.nav-toggle' );
		var nav = document.getElementById( 'site-navigation' );
		if ( ! nav ) {
			return;
		}
		var label = toggle ? toggle.querySelector( '.nav-toggle__label' ) : null;

		function setMenu( open ) {
			if ( ! toggle ) {
				return;
			}
			nav.classList.toggle( 'is-open', open );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			if ( label ) {
				label.textContent = open ? ( i18n.closeMenu || 'Close menu' ) : ( i18n.openMenu || 'Open menu' );
			}
		}

		function setSubmenu( item, open ) {
			var button = item.querySelector( ':scope > .submenu-toggle' );
			item.classList.toggle( 'is-open', open );
			if ( button ) {
				button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			}
		}

		function closeSubmenus( except ) {
			each( nav.querySelectorAll( '.menu-item-has-children.is-open' ), function ( item ) {
				if ( item !== except ) {
					setSubmenu( item, false );
				}
			} );
		}

		if ( toggle ) {
			toggle.addEventListener( 'click', function () {
				setMenu( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
			} );
		}

		each( nav.querySelectorAll( '.submenu-toggle' ), function ( button ) {
			button.addEventListener( 'click', function () {
				var item = button.parentNode;
				var open = ! item.classList.contains( 'is-open' );
				if ( desktop.matches ) {
					closeSubmenus( item );
				}
				setSubmenu( item, open );
			} );
		} );

		// Parent links that only hold a "#" open their submenu instead of jumping to the top.
		each( nav.querySelectorAll( '.menu-item-has-children > a' ), function ( link ) {
			var href = link.getAttribute( 'href' );
			if ( href === '#' || href === '' || href === null ) {
				link.setAttribute( 'role', 'button' );
				link.addEventListener( 'click', function ( event ) {
					event.preventDefault();
					var button = link.parentNode.querySelector( ':scope > .submenu-toggle' );
					if ( button ) {
						button.click();
					}
				} );
			}
		} );

		// Keep aria-expanded right when a submenu opens by mouse hover on large screens.
		each( nav.querySelectorAll( '.menu-item-has-children' ), function ( item ) {
			item.addEventListener( 'mouseenter', function () {
				if ( desktop.matches ) {
					closeSubmenus( item );
					setSubmenu( item, true );
				}
			} );
			item.addEventListener( 'mouseleave', function () {
				if ( desktop.matches ) {
					setSubmenu( item, false );
				}
			} );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'Escape' ) {
				return;
			}
			var openItem = nav.querySelector( '.menu-item-has-children.is-open' );
			if ( openItem ) {
				setSubmenu( openItem, false );
				var button = openItem.querySelector( ':scope > .submenu-toggle' );
				if ( button && nav.contains( document.activeElement ) ) {
					button.focus();
				}
				return;
			}
			if ( toggle && nav.classList.contains( 'is-open' ) ) {
				setMenu( false );
				toggle.focus();
			}
		} );

		// Close when focus or a click moves outside.
		document.addEventListener( 'click', function ( event ) {
			if ( nav.contains( event.target ) || ( toggle && toggle.contains( event.target ) ) ) {
				return;
			}
			closeSubmenus();
			setMenu( false );
		} );
		nav.addEventListener( 'focusout', function ( event ) {
			if ( desktop.matches && event.relatedTarget && ! nav.contains( event.relatedTarget ) ) {
				closeSubmenus();
			}
		} );

		// Close the mobile panel after choosing a link to a section on the same page.
		each( nav.querySelectorAll( 'a[href*="#"]' ), function ( link ) {
			link.addEventListener( 'click', function () {
				if ( link.getAttribute( 'role' ) !== 'button' ) {
					setMenu( false );
				}
			} );
		} );

		function onBreakpoint() {
			setMenu( false );
			closeSubmenus();
		}
		if ( desktop.addEventListener ) {
			desktop.addEventListener( 'change', onBreakpoint );
		} else if ( desktop.addListener ) {
			desktop.addListener( onBreakpoint );
		}
	}

	/* Carousel ------------------------------------------------------------- */
	function initCarousel( root ) {
		var track = root.querySelector( '[data-carousel-track]' );
		var dotsWrap = root.querySelector( '[data-carousel-dots]' );
		if ( ! track || ! dotsWrap ) {
			return;
		}
		var slides = track.children;
		var dots = [];
		var pages = 1;
		var current = 0;
		var timer = null;
		var paused = false;

		function perView() {
			if ( ! slides.length ) {
				return 1;
			}
			return Math.max( 1, Math.round( track.clientWidth / slides[ 0 ].getBoundingClientRect().width ) );
		}

		function pageLeft( page ) {
			var index = Math.min( page * perView(), slides.length - 1 );
			return slides[ index ].offsetLeft - slides[ 0 ].offsetLeft;
		}

		function goTo( page, instant ) {
			current = ( page + pages ) % pages;
			var left = Math.min( pageLeft( current ), track.scrollWidth - track.clientWidth );
			if ( track.scrollTo ) {
				track.scrollTo( { left: left, behavior: instant || reduceMotion ? 'auto' : 'smooth' } );
			} else {
				track.scrollLeft = left;
			}
			mark();
		}

		function mark() {
			dots.forEach( function ( dot, index ) {
				if ( index === current ) {
					dot.setAttribute( 'aria-current', 'true' );
				} else {
					dot.removeAttribute( 'aria-current' );
				}
			} );
		}

		function build() {
			pages = Math.max( 1, Math.ceil( slides.length / perView() ) );
			dotsWrap.innerHTML = '';
			dots = [];
			if ( pages < 2 ) {
				return;
			}
			for ( var p = 0; p < pages; p++ ) {
				var dot = document.createElement( 'button' );
				dot.type = 'button';
				dot.className = 'carousel__dot';
				dot.setAttribute( 'aria-label', ( i18n.goToSlide || 'Go to page %d' ).replace( '%d', p + 1 ) );
				dot.addEventListener( 'click', ( function ( page ) {
					return function () {
						goTo( page );
						restart();
					};
				}( p ) ) );
				dotsWrap.appendChild( dot );
				dots.push( dot );
			}
			current = Math.min( current, pages - 1 );
			mark();
		}

		// Follow native scrolling and touch swipes (scroll snap does the moving).
		var scrollTimer = null;
		track.addEventListener( 'scroll', function () {
			window.clearTimeout( scrollTimer );
			scrollTimer = window.setTimeout( function () {
				var atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
				var best = 0;
				var bestDistance = Infinity;
				for ( var p = 0; p < pages; p++ ) {
					var distance = Math.abs( pageLeft( p ) - track.scrollLeft );
					if ( distance < bestDistance ) {
						bestDistance = distance;
						best = p;
					}
				}
				current = atEnd ? pages - 1 : best;
				mark();
			}, 90 );
		}, { passive: true } );

		track.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'ArrowRight' ) {
				event.preventDefault();
				goTo( Math.min( current + 1, pages - 1 ) );
			} else if ( event.key === 'ArrowLeft' ) {
				event.preventDefault();
				goTo( Math.max( current - 1, 0 ) );
			}
		} );

		// Gentle auto-play. It stops on hover, focus, touch and for people who prefer less motion.
		function stop() {
			window.clearInterval( timer );
			timer = null;
		}
		function start() {
			if ( reduceMotion || paused || pages < 2 || timer ) {
				return;
			}
			timer = window.setInterval( function () {
				if ( ! document.hidden ) {
					goTo( current + 1 );
				}
			}, 6000 );
		}
		function restart() {
			stop();
			start();
		}
		function pause() {
			paused = true;
			stop();
		}
		function resume() {
			paused = false;
			start();
		}
		root.addEventListener( 'mouseenter', pause );
		root.addEventListener( 'mouseleave', resume );
		root.addEventListener( 'focusin', pause );
		root.addEventListener( 'focusout', resume );
		root.addEventListener( 'touchstart', pause, { passive: true } );

		var resizeTimer = null;
		window.addEventListener( 'resize', function () {
			window.clearTimeout( resizeTimer );
			resizeTimer = window.setTimeout( function () {
				build();
				goTo( current, true );
				restart();
			}, 150 );
		} );

		build();
		start();
	}

	/* FAQ accordion ----------------------------------------------------------- */
	function initAccordion( root ) {
		each( root.querySelectorAll( '.faq__col' ), function ( column ) {
			each( column.querySelectorAll( '.faq-item__btn' ), function ( button, index ) {
				var panel = document.getElementById( button.getAttribute( 'aria-controls' ) );
				if ( ! panel ) {
					return;
				}
				function set( open ) {
					button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
					panel.hidden = ! open;
				}
				// Without JavaScript every answer is visible. With it, only the first one stays open.
				set( index === 0 );
				button.addEventListener( 'click', function () {
					set( button.getAttribute( 'aria-expanded' ) !== 'true' );
				} );
			} );
		} );
	}

	/* Reveal on scroll ---------------------------------------------------------- */
	function initReveal() {
		var items = document.querySelectorAll( '.reveal' );
		if ( ! items.length ) {
			return;
		}
		if ( reduceMotion || ! ( 'IntersectionObserver' in window ) ) {
			each( items, function ( item ) {
				item.classList.add( 'is-visible' );
			} );
			return;
		}
		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					observer.unobserve( entry.target );
				}
			} );
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 } );
		each( items, function ( item ) {
			observer.observe( item );
		} );
	}

	/* Video modal ------------------------------------------------------------------ */
	function initVideo() {
		var modal = document.querySelector( '[data-video-modal]' );
		var openers = document.querySelectorAll( '[data-video-open]' );
		if ( ! modal || ! openers.length ) {
			return;
		}
		var frame = modal.querySelector( '[data-video-frame]' );
		var closeButton = modal.querySelector( '[data-video-close]' );
		var lastOpener = null;

		function open( button ) {
			var src = button.getAttribute( 'data-video-src' );
			var type = button.getAttribute( 'data-video-type' );
			var player;
			if ( ! src ) {
				return;
			}
			if ( type === 'file' ) {
				player = document.createElement( 'video' );
				player.controls = true;
				player.autoplay = true;
				player.setAttribute( 'playsinline', '' );
			} else {
				player = document.createElement( 'iframe' );
				player.title = i18n.videoTitle || 'Video player';
				player.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
				player.setAttribute( 'allowfullscreen', '' );
			}
			player.src = src;
			frame.innerHTML = '';
			frame.appendChild( player );
			lastOpener = button;
			if ( typeof modal.showModal === 'function' ) {
				modal.showModal();
			} else {
				modal.setAttribute( 'open', '' );
			}
			closeButton.focus();
		}

		function close() {
			if ( typeof modal.close === 'function' && modal.open ) {
				modal.close();
			} else {
				modal.removeAttribute( 'open' );
				cleanup();
			}
		}

		function cleanup() {
			frame.innerHTML = '';
			if ( lastOpener ) {
				lastOpener.focus();
			}
		}

		each( openers, function ( button ) {
			button.addEventListener( 'click', function () {
				open( button );
			} );
		} );
		closeButton.addEventListener( 'click', close );
		modal.addEventListener( 'close', cleanup );
		modal.addEventListener( 'click', function ( event ) {
			if ( event.target === modal ) {
				close();
			}
		} );
	}

	/* Form notices -------------------------------------------------------------------- */
	function initNotices() {
		var notice = document.querySelector( '.form-notice' );
		if ( ! notice ) {
			return;
		}
		// Make sure the notice is seen and announced, then clean the address bar.
		var parentReveal = notice.closest( '.reveal' );
		if ( parentReveal ) {
			parentReveal.classList.add( 'is-visible' );
		}
		window.setTimeout( function () {
			notice.scrollIntoView( { block: 'center', behavior: reduceMotion ? 'auto' : 'smooth' } );
			notice.focus( { preventScroll: true } );
		}, 150 );
		if ( window.history && window.history.replaceState && window.URL ) {
			var url = new URL( window.location.href );
			url.searchParams.delete( 'blush_form' );
			url.searchParams.delete( 'blush_status' );
			window.history.replaceState( null, '', url.pathname + url.search + url.hash );
		}
	}

	function init() {
		initHeader();
		initMenu();
		each( document.querySelectorAll( '[data-carousel]' ), initCarousel );
		each( document.querySelectorAll( '[data-accordion]' ), initAccordion );
		initReveal();
		initVideo();
		initNotices();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
