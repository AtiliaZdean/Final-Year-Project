(function($) {
  'use strict'; // Enforce strict mode for better error checking

  // Document ready function
  $(function() {
    var body = $('body'); // Cache the body element
    var contentWrapper = $('.content-wrapper'); // Cache the content wrapper
    var scroller = $('.container-scroller'); // Cache the main scroller container
    var footer = $('.footer'); // Cache the footer element
    var sidebar = $('.sidebar'); // Cache the sidebar element
    var navbar = $('.navbar');

    // Function to add 'active' class to navigation links based on the current URL
    function addActiveClass(element) {
      if (current === "") {
        // Check for root URL (e.g., index.html)
        if (element.attr('href').indexOf("index.html") !== -1) {
          element.parents('.nav-item').last().addClass('active'); // Add active class to the last parent nav item
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show'); // Show the submenu if it exists
            element.addClass('active'); // Add active class to the element
          }
        }
      } else {
        // For other URLs
        if (element.attr('href').indexOf(current) !== -1) {
          element.parents('.nav-item').last().addClass('active'); // Add active class to the last parent nav item
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show'); // Show the submenu if it exists
            element.addClass('active'); // Add active class to the element
          }
          if (element.parents('.submenu-item').length) {
            element.addClass('active'); // Add active class to submenu items
          }
        }
      }
    }

    // Get the current page name from the URL
    var current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');

    $('.navbar-nav li a', navbar).each(function() {
      var $this = $(this);
      addActiveClass($this); // Call the function to add active class
    });
    
    // Apply active class to sidebar navigation links
    $('.nav li a', sidebar).each(function() {
      var $this = $(this);
      addActiveClass($this); // Call the function to add active class
    });

    // Apply active class to horizontal menu navigation links
    $('.horizontal-menu .nav li a').each(function() {
      var $this = $(this);
      addActiveClass($this); // Call the function to add active class
    });

    // Close other submenus in the sidebar when one is opened
    sidebar.on('show.bs.collapse', '.collapse', function() {
      sidebar.find('.collapse.show').collapse('hide'); // Hide any currently open submenu
    });

    // Function to apply styles and initialize scrollbars
    applyStyles();

    function applyStyles() {
      // Applying perfect scrollbar to specific elements
      if (!body.hasClass("rtl")) { // Check if the body does not have 'rtl' class
        if ($('.settings-panel .tab-content .tab-pane.scroll-wrapper').length) {
          const settingsPanelScroll = new PerfectScrollbar('.settings-panel .tab-content .tab-pane.scroll-wrapper'); // Initialize scrollbar for settings panel
        }
        if ($('.chats').length) {
          const chatsScroll = new PerfectScrollbar('.chats'); // Initialize scrollbar for chats
        }
        if (body.hasClass("sidebar-fixed")) {
          if($('#sidebar').length) {
            var fixedSidebarScroll = new PerfectScrollbar('#sidebar .nav'); // Initialize scrollbar for fixed sidebar
          }
        }
      }
    }

    // Toggle sidebar visibility on minimize button click
    $('[data-toggle="minimize"]').on("click", function() {
      if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
        body.toggleClass('sidebar-hidden'); // Hide the sidebar
      } else {
        body.toggleClass('sidebar-icon-only'); // Change to icon-only mode
      }
    });

    // Append input helper for checkboxes and radios
    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    // Toggle horizontal menu in mobile view
    $('[data-toggle="horizontal-menu-toggle"]').on("click", function() {
      $(".horizontal-menu .bottom-navbar").toggleClass("header-toggled"); // Toggle the header class
    });

    // Handle horizontal menu navigation in mobile
    var navItemClicked = $('.horizontal-menu .page-navigation >.nav-item');
    navItemClicked.on("click", function(event) {
      if(window.matchMedia('(max-width: 991px)').matches) { // Check if the screen width is less than 991px
        if(!($(this).hasClass('show-submenu'))) {
          navItemClicked.removeClass('show-submenu'); // Remove show-submenu class from all items
        }
        $(this).toggleClass('show-submenu'); // Toggle show-submenu class on the clicked item
      }        
    });

    // Change header style on scroll for larger screens
    $(window).scroll(function() {
      if(window.matchMedia('(min-width: 992px)').matches) { // Check if the screen width is greater than or equal to 992px
        var header = $('.horizontal-menu');
        if ($(window).scrollTop() >= 70) {
          $(header).addClass('fixed-on-scroll'); // Add fixed class when scrolled down
        } else {
          $(header).removeClass('fixed-on-scroll'); // Remove fixed class when scrolled back up
        }
      }
    });
  });

  // Focus input when clicking on search icon
  $('#navbar-search-icon').click(function() {
    $("#navbar-search-input").focus(); // Set focus on the search input field
  });
  
})(jQuery); // Immediately invoked function expression (IIFE) to avoid polluting the global namespace