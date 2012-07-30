jQuery(document).ready(function() {

    function add_a_guest($clicked) {
        /* Check current number of guests input#rsvp_num_guests */
        var num_guests = parseInt(jQuery('input#rsvp_num_guests').val());
        
        /* Find table row where this was clicked and add a guest entry above it */
        $newRow = jQuery('<tr>');
        $newRow.append('<td><label>Guest name<span class="small">Add next guest name</span></label></td>');
        $newRow.append('<td><input type="text" class="rsvp_guest required" /><a href="#" class="remove_a_guest"><span class="hide">Remove</span></a></td>');
        $clicked.closest('tr').before($newRow);

        /* TODO: Make this a plugin variable -- max_guests */
        var MAX_GUESTS = 6;
        if (MAX_GUESTS <= (num_guests + 1)) {
            /* Hide the 'Add a guest' link */
            $clicked.closest('tr').css('display', 'none');
        }
        
        /* Increment number of guests input#rsvp_num_guests */
        num_guests++;
        jQuery('span#rsvp_num_guests').text(num_guests);
        jQuery('input#rsvp_num_guests').val(num_guests);
    }
    
    function remove_a_guest($clicked) {
        /* Check current number of guests input#rsvp_num_guests */
        var num_guests = parseInt(jQuery('input#rsvp_num_guests').val());
        
        /* Find table row where this was clicked and remove it */
        $clicked.closest('tr').remove();
        
        /* Show the 'Add a guest' link */
        jQuery('#add_a_guest').closest('tr').css('display', 'table-row');

        /* Increment number of guests input#rsvp_num_guests */
        num_guests--;
        jQuery('span#rsvp_num_guests').text(num_guests);
        jQuery('input#rsvp_num_guests').val(num_guests);
    }
    
    function prepare_input() {
        /* Loop through rsvp_guest inputs and put them into an array */
        $guests = jQuery('input.rsvp_guest');
        guests = new Array();
        jQuery.each($guests, function() {
            guests.push(jQuery(this).val());
        });
        
        /* Stringify the array and pass it as a form input */
        jQuery('input#rsvp_guests').val(JSON.stringify(guests));
    }
    
    jQuery('#add_a_guest').on('click', function() {
        add_a_guest(jQuery(this));
        return false;
    });
    
    jQuery('#rsvp_form').on('click', '.remove_a_guest', function() {
        remove_a_guest(jQuery(this));
        return false;
    });
    
    jQuery('#rsvp_form').on('submit', function() {
        prepare_input();
    });
    
    jQuery('#rsvp_form').validate({
        messages: {
            rsvp_name: {
                required: ''
            },
            rsvp_email: {
                required: '',
                email: 'Please enter a valid email address.'
            }
        }
    });
});
