(function( $ ) {
   'use strict';

   $(document).ready( function() {

      // Make page header sticky on scroll. Using https://github.com/AndrewHenderson/jSticky
      $('#amcust-header').sticky({
         topSpacing: 0, // Space between element and top of the viewport (in pixels)
         zIndex: 100, // z-index
         stopper: '', // Id, class, or number value
         stickyClass: 'amcust-sticky' // Class applied to element when it's stuck. Class name or false.
      })

      // Initialize sortable elements: https://api.jqueryui.com/sortable/
      $('#custom-admin-menu').sortable({
         placeholder: 'sortable-placeholder'
      });

      // ----- Custom Menu Order ------

      // Get the default/current menu order
      let menuOrder = $('#custom-admin-menu').sortable("toArray").toString();
      // console.log( menuOrder );

      // Set hidden input value for saving in options
      document.getElementById('admin_menu_customizer[custom_menu_order]').value = menuOrder;

      // Save custom order into a comma-separated string, triggerred after each drag and drop of menu item
      // https://api.jqueryui.com/sortable/#event-update
      // https://api.jqueryui.com/sortable/#method-toArray
      $('#custom-admin-menu').on( 'sortupdate', function( event, ui) {

         // Get the updated menu order
         let menuOrder = $('#custom-admin-menu').sortable("toArray").toString();
         // console.log( menuOrder );

         // Set hidden input value for saving in options
         document.getElementById('admin_menu_customizer[custom_menu_order]').value = menuOrder;

      });

      // ----- Menu Item Hiding -----

      // Prepare constant to store IDs of menu items that will be hidden
      if ( document.getElementById('admin_menu_customizer[custom_menu_hidden]').value ) {

         var hiddenMenuItems = document.getElementById('admin_menu_customizer[custom_menu_hidden]').value.split(","); // array

      } else {

         var hiddenMenuItems = []; // array

      }

      // console.log(hiddenMenuItems);

      // Detect which menu items are being checked. Ref: https://stackoverflow.com/a/3871602
      Array.from(document.getElementsByClassName('menu-item-checkbox')).forEach(function(item,index,array) {

         item.addEventListener('click', event => {

            if (event.target.checked) {

               // Add ID of menu item to array
               // alert(event.target.dataset.menuItemId + ' will be hidden');
               hiddenMenuItems.push(event.target.dataset.menuItemId);

            } else {

               // Remove ID of menu item from array
               // alert(event.target.dataset.menuItemId + ' will not be hidden');
               const start = hiddenMenuItems.indexOf(event.target.dataset.menuItemId);
               const deleteCount = 1;
               hiddenMenuItems.splice(start, deleteCount);

            }

            // console.log(hiddenMenuItems.toString());

            // Set hidden input value
            document.getElementById('admin_menu_customizer[custom_menu_hidden]').value = hiddenMenuItems;

         });

      });

      // ----- Toggle hidden menu items -----

      $('#toplevel_page_amcust_hide_hidden_menu').hide();

      // Show hidden menu items

      $('#toplevel_page_amcust_show_hidden_menu a').on( 'click', function(e) {
         e.preventDefault();
         $('#toplevel_page_amcust_show_hidden_menu').hide();
         $('#toplevel_page_amcust_hide_hidden_menu').show();
         $('.menu-top.amcust_hidden_menu').toggleClass('hidden');
         $('.wp-menu-separator.amcust_hidden_menu').toggleClass('hidden');
         $(document).trigger('wp-window-resized');         
      });

      // Hide menu items set for hiding

      $('#toplevel_page_amcust_hide_hidden_menu a').on( 'click', function(e) {
         e.preventDefault();
         $('#toplevel_page_amcust_show_hidden_menu').show();
         $('#toplevel_page_amcust_hide_hidden_menu').hide();
         $('.menu-top.amcust_hidden_menu').toggleClass('hidden');
         $('.wp-menu-separator.amcust_hidden_menu').toggleClass('hidden');
         $(document).trigger('wp-window-resized');         
      });

      // Clicking on header save button triggers click of the hidden form submit button
      $('.amcust-save-button').click( function(e) {

         e.preventDefault();

         // ----- Custom Menu Item Titles ------

         // Prepare variable to store ID-Title pairs of menu items
         var customMenuTitles = []; // empty array

         // Initialize other variables
         var menuItemId = '';
         var customTitle = '';

         // Save default/custom title values. Ref: https://stackoverflow.com/a/3871602
         Array.from(document.getElementsByClassName('menu-item-custom-title')).forEach(function(item,index,array) {

            menuItemId = item.dataset.menuItemId;
            customTitle = item.value;
            customMenuTitles.push(menuItemId + '__' + customTitle);            

         });

         // console.log(customMenuTitles.toString());

         // Set hidden input value
         document.getElementById('admin_menu_customizer[custom_menu_titles]').value = customMenuTitles;

         // Submit the settings form
         $('input[type="submit"]').click();

      });

   });

})( jQuery );