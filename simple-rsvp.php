<?php
/**
 *     ---------------       DO NOT DELETE!!!     ---------------
 * 
 *     Plugin Name:  Simple RSVP
 *     Plugin URI:   http://woofenbark.com/wordpress-plugins/simple-rsvp
 *     Description:  Accept and manage RSVPs for events.
 *     Version:      0.1.0
 *     Author:       Woofenbark Web Technology
 *     Author URI:   http://woofenbark.com
 *
 *     ---------------       DO NOT DELETE!!!     ---------------
 *
 *    This is the required license information for a Wordpress plugin.
 *
 *    Copyright 2011  Ben Burleson  (email : ben@woofenbark.com)
 *
 *    This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *     ---------------       DO NOT DELETE!!!     ---------------
 */

require_once("wordpress-plugin-framework.php");

class SimpleRsvp extends SimpleRsvp_WordpressPluginFramework
{
    function create_rsvp_post_type() {
        register_post_type('simple-rsvp',
            array(
                'labels' => array(
                    'name' => __('RSVPs'),
                    'singular_name' => __('RSVP'),
                    'add_new' => _x('Add New', 'rsvp'),
                    'add_new_item' => __('Add New RSVP'),
                    'edit_item' => __('Edit RSVP'),
                    'new_item' => __('New RSVP'),
                    'all_items' => __('All RSVPs'),
                    'view_item' => __('View RSVP'),
                    'search_items' => __('Search RSVPs'),
                    'not_found' =>  __('No RSVPs found'),
                    'not_found_in_trash' => __('No RSVPs found in Trash'), 
                    'parent_item_colon' => '',
                    'menu_name' => 'RSVPs'
                ),
            'public' => true,
            'publicly_queryable' => false,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields'),
            )
        );
    }

    function create_rsvp_columns($columns) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            //'id' => 'RSVP ID',
            'name' => 'Name',
            'email' => 'Email',
            'attending' => 'Attending',
            'num_guests' => 'Number of Guests',
            'guests' => 'Other Guests',
            'note' => 'Note'
        );

        return $columns;
    }

    function rsvp_sortable_columns($columns) {
        $columns['name'] = 'name';
        $columns['email'] = 'email';
        $columns['attending'] = 'attending';
        $columns['num_guests'] = 'num_guests';

        return $columns;
    }

    function rsvp_columns($column) {
        global $post;
        $id = $post->ID;

        switch($column) {
            case 'id':
                echo $id;
                break;
            case 'name':
                echo '<strong>'.stripslashes($post->post_title).'</strong>';
                echo '<div class="row-actions">';
                echo '<span class="edit"><a href="';
                echo site_url('/wp-admin/post.php?').'post='.$id.'&action=edit';
                echo '" title="Edit this RSVP">Edit</a> | </span>';
                echo '<span class="trash"><a class="submitdelete" href="';
                echo wp_nonce_url( get_bloginfo('wpurl') . "/wp-admin/post.php?action=trash&post=" . $id, 'trash-' . $post->post_type . '_' . $id);
                echo '" title="Move this RSVP to the Trash">Trash</a></span>';
                echo '</div>';
                break;
            case 'email':
                $email = get_post_meta($id, 'rsvp_email', true);
                echo '<a href="mailto:'.$email.'">'.$email.'</a>';
                break;
            case 'attending':
                echo get_post_meta($id, 'rsvp_attending', true);
                break;
            case 'num_guests':
                echo get_post_meta($id, 'rsvp_num_guests', true);
                break;
            case 'guests':
                $guests = get_post_meta($id, 'rsvp_guest', false);
                foreach ($guests as $guest) {
                    echo stripslashes($guest).'<br/>';
                }
                break;
            case 'note':
                echo stripslashes($post->post_content);
                break;
        }

    }

    function add_rsvp_style() {
        $styleUrl = plugins_url('simple-rsvp.css', __FILE__);
        $styleFile = WP_PLUGIN_DIR . '/simple-rsvp/simple-rsvp.css';
        if (file_exists($styleFile)) {
            wp_register_style('simple-rsvp-style',
                              $styleUrl,
                              false,
                              '0.1.0');
            wp_enqueue_style('simple-rsvp-style');
        }
    }
    
    function add_rsvp_script() {
        $scriptUrl = plugins_url('jquery-validate.min.js', __FILE__);
        $scriptFile = WP_PLUGIN_DIR . '/simple-rsvp/jquery-validate.min.js';
        if (file_exists($scriptFile)) {
            wp_register_script('simple-rsvp-validation',
                               $scriptUrl,
                               array('jquery'),
                               '0.1.0');
            wp_enqueue_script('simple-rsvp-validation');
        }
        
        $scriptUrl = plugins_url('simple-rsvp.js', __FILE__);
        $scriptFile = WP_PLUGIN_DIR . '/simple-rsvp/simple-rsvp.js';
        if (file_exists($scriptFile)) {
            wp_register_script('simple-rsvp-script',
                               $scriptUrl,
                               array('jquery'),
                               '0.1.0');
            wp_enqueue_script('simple-rsvp-script');
        }
    }
    
    function rsvp_send_confirmation($name, $email, $attending, $num_guests, $guests) {
        $to = $email;
        $subject = 'RSVP confirmation - Thank you!';
        $headers = 'From: '.'us@heddyandben.com'."\r\n";
        $headers = 'Cc: '.'us@heddyandben.com'."\r\n";
        $headers .= 'Reply-To: '.'us@heddyandben.com'."\r\n";
        $headers .= 'X-Mailer: PHP/'.phpversion();
        
        $message = 'Thanks for responding to our wedding invitation!'."\n";
        if ('yes' == $attending) {
            $message .= 'We\'re so glad you will be able to make it and we look forward to seeing you!'."\n\n";
            $message .= 'We have marked you down as ' . $num_guests . ' guest';
            $message .= (1 < $num_guests) ? 's ' : ' ';
            $message .= 'attending:'."\n";
            $message .= ' * ' . $name ."\n";
            if (1 <= count($guests)) {
                foreach ($guests as $guest) {
                    $message .= ' * ' . $guest ."\n";
                }
            }
        } else {
            $message .= 'We\'re so sad you won\'t be able to make it, but we look forward to seeing you soon!'."\n";
        }
        $message .= "\n".'Love,'."\n".'Heather and Ben'."\n";
        $message = wordwrap($message, 70);
        
        mail($to, $subject, $message, $headers);
    }
    
    function rsvp_ack() {
        $name = $_POST['rsvp_name'] ? $_POST['rsvp_name'] : '';
        $email = $_POST['rsvp_email'] ? $_POST['rsvp_email'] : '';
        $note = $_POST['rsvp_note'] ? $_POST['rsvp_note'] : '';
        $attending = $_POST['rsvp_attending'] ? $_POST['rsvp_attending'] : '';
        $num_guests = $_POST['rsvp_num_guests'] ? intval($_POST['rsvp_num_guests']) : 1;
        $guests = $_POST['rsvp_guests'] ? $_POST['rsvp_guests'] : '[]';

        /* Sanitize input */        
        $name = mysql_real_escape_string(filter_var($name, FILTER_SANITIZE_STRING));
        $email = mysql_real_escape_string(filter_var($email, FILTER_SANITIZE_EMAIL));
        $note = mysql_real_escape_string(filter_var($note, FILTER_SANITIZE_STRING));
        $attending = mysql_real_escape_string(filter_var($attending, FILTER_SANITIZE_STRING));
        $num_guests = mysql_real_escape_string(filter_var($num_guests, FILTER_SANITIZE_NUMBER_INT));
        
        $guests = json_decode(stripslashes($guests));
        if (NULL == $guests) {
            $guests = array();
        }
        
        global $user_ID;
        $new_post = array(
            'post_type' => 'simple-rsvp',
            'post_title' => $name,
            'post_content' => $note,
            'post_date' => date('Y-m-d H:i:s'),
            'post_author' => $user_ID,
            'post_status' => 'publish');
        $post_id = wp_insert_post($new_post, true);
        add_post_meta($post_id, 'rsvp_email', $email, true);
        add_post_meta($post_id, 'rsvp_attending', $attending, true);
        add_post_meta($post_id, 'rsvp_num_guests', $num_guests, true);
        if (1 <= count($guests)) {
            foreach ($guests as $guest) {
                add_post_meta($post_id, 'rsvp_guest', $guest, false);
            }
        }

        if (is_wp_error($post_id)) {
            $response = '<h2>Uh-oh, it\'s broken!</h2>';
            $response .= '<p>Ben just got an email and he\'ll fix it asap!</p>';
            $response .= '<p>'. $post_id->get_error_message() .'</p>';
        } else {
            $response = '<h2>Thank you!</h2>';
            if ('yes' == $attending) {
                $response .= '<p>'.'We\'re super exited you\'ll be joining us!'.'</p>';
            } else {
                $response .= '<p>'.'We\'re so sad you won\'t be able to make it, but we look forward to seeing you soon!'.'</p>';
            }
            
            /* Send an email */
            $this->rsvp_send_confirmation($name, $email, $attending, $num_guests, $guests);
        }

        return $response;
    }

    function rsvp_form() {
        $form = '';
        $form .= '<div id="stylized" class="myform">';
        $form .= '  <form id="rsvp_form" name="form" method="post" action="'.$_SERVER['REQUEST_URI'].'">';
        $form .= '    <h1>'.'Help us celebrate!'.'</h1>';
        $form .= '    <p>'.'Let us know if you\'ll be able to join us or not. If you have any questions, please call or <a href="mailto:us@heddyandben.com">email</a>!'.'</p>';
        $form .= '    <table>';
        $form .= '      <tr>';
        $form .= '        <td>';
        $form .= '          <label>Attending';
        $form .= '            <span class="small">Will you be able to join us?</span>';
        $form .= '          </label>';
        $form .= '        </td>';
        $form .= '        <td>';
        $form .= '          <span class="radio_option">';
        $form .= '            <input type="radio" name="rsvp_attending" id="rsvp_attending" value="yes" checked="true" />';
        $form .= '            Yes';
        $form .= '          </span><br/>';
        $form .= '          <span class="radio_option">';
        $form .= '            <input type="radio" name="rsvp_attending" id="rsvp_attending" value="no" />';
        $form .= '            No';
        $form .= '          </span>';
        $form .= '        </td>';
        $form .= '      </tr>';
        $form .= '      <tr>';
        $form .= '        <td>';
        $form .= '          <label>Name';
        $form .= '            <span class="small">Add your name</span>';
        $form .= '          </label>';
        $form .= '        </td>';
        $form .= '        <td>';
        $form .= '          <input type="text" name="rsvp_name" id="rsvp_name" class="required" />';
        $form .= '        </td>';
        $form .= '      </tr>';
        $form .= '      <tr>';
        $form .= '        <td></td>';
        $form .= '        <td><a id="add_a_guest" href="#">Add a guest</a></td>';
        $form .= '      </tr>';
        $form .= '      <tr>';
        $form .= '        <td>';
        $form .= '          <label>Email';
        $form .= '            <span class="small">Add a valid address</span>';
        $form .= '           </label>';
        $form .= '        </td>';
        $form .= '        <td>';
        $form .= '          <input type="text" name="rsvp_email" id="rsvp_email" class="required email" />';
        $form .= '        </td>';
        $form .= '      </tr>';
        $form .= '      <tr>';
        $form .= '        <td>';
        $form .= '          <label>Note';
        $form .= '            <span class="small">Comments or questions?</span>';
        $form .= '          </label>';
        $form .= '        </td>';
        $form .= '        <td>';
        $form .= '          <textarea name="rsvp_note" id="rsvp_note"></textarea>';
        $form .= '        <td>';
        $form .= '      </tr>';
        $form .= '      <tr>';
        $form .= '        <td>';
        $form .= '          <label>Number of guests';
        $form .= '            <span class="small">If needed, add guests above</span>';
        $form .= '          </label>';
        $form .= '        </td>';
        $form .= '        <td>';
        $form .= '          <span id="rsvp_num_guests">1</span>';
        $form .= '          <input type="hidden" name="rsvp_num_guests" id="rsvp_num_guests" value="1" />';
        $form .= '          <input type="hidden" name="rsvp_guests" id="rsvp_guests" value="[]" />';
        $form .= '        </td>';
        $form .= '      </tr>';
        $form .= '      <tr>';
        $form .= '        <td></td>';
        $form .= '        <td>';
        $form .= '          <button type="submit">RSVP</button>';
        $form .= '        </td>';
        $form .= '      </tr>';
        $form .= '    </table>';
        $form .= '  </form>';
        $form .= '</div>';
        
        return $form;
    }
    
    function rsvp_response() {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            return $this->rsvp_ack();
        } else {
            return $this->rsvp_form();
        }
    }
    
    function HTML_DisplayPluginRsvpFormBlock() {
        $this->DisplayPluginOption( 'form_title' );
        ?>
        <br/>
        <?php
        $this->DisplayPluginOption( 'form_subtitle' );
    }
    
    function HTML_DisplayPluginRsvpAckBlock() {
        $this->DisplayPluginOption( 'ack_response_yes' );
        ?>
        <br/>
        <?php
        $this->DisplayPluginOption( 'ack_response_no' );
    }
    
    function HTML_DisplayPluginRsvpEmailBlock() {
        $this->DisplayPluginOption( 'confirmation_email_from' );
        ?>
        <?php
        $this->DisplayPluginOption( 'confirmation_email_subject' );
        ?>
        <?php
        $this->DisplayPluginOption( 'confirmation_email_yes_message' );
        ?>
        <?php
        $this->DisplayPluginOption( 'confirmation_email_no_message' );
    }
}

function rsvp_edit_rsvp_load() {
    add_filter('request', 'rsvp_sort_rsvps');
}

function rsvp_sort_rsvps($vars) {
    /* Check if we're viewing the RSVP post type */
    if (isset($vars['post_type']) && ('simple-rsvp' == $vars['post_type'])) {
        /* Check if 'orderby' is set */
        if (isset($vars['orderby'])) {
            switch($vars['orderby']) {
                case 'name':
                    /* Merge the query vars with our custom variables */
                    $vars = array_merge($vars,
                        array('orderby'=>'title'));
                    break;
                case 'email':
                    $vars = array_merge($vars,
                        array('meta_key'=>'rsvp_email', 'orderby'=>'meta_value'));
                    break;
                case 'attending':
                    $vars = array_merge($vars,
                        array('meta_key'=>'rsvp_attending', 'orderby'=>'meta_value'));
                    break;
                case 'num_guests':
                    $vars = array_merge($vars,
                        array('meta_key'=>'rsvp_num_guests', 'orderby'=>'meta_value'));
                    break;
            }
        }
    }
    
    return $vars;
}

function rsvp_print_total_guests($vars) {
    global $typenow;
    /* Check if we're viewing the RSVP post type */
    if ('simple-rsvp' == $typenow) {
        /* Calculate a total number of guests that have RSVP'd */
        $query = new WP_Query('post_type=simple-rsvp&posts_per_page=-1');
        $total_guests_yes = 0;
        $total_guests_no = 0;
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $attending = get_post_meta($id, 'rsvp_attending', true);
            $num_guests = intval(get_post_meta(get_the_ID(), 'rsvp_num_guests', true));
            if ('yes' == $attending) {
                $total_guests_yes += $num_guests;
            } else {
                $total_guests_no += $num_guests;
            }
        }
        $total_guests = $total_guests_yes + $total_guests_no;
        wp_reset_postdata();
        echo '<div class="updated">';
        echo '<p>Total number of <strong>Yes</strong> guests: '.$total_guests_yes.'</p>';
        echo '<p>Total number of <strong>No</strong> guests: '.$total_guests_no.'</p>';
        echo '<p>Total number of guest responses: '.$total_guests.'</p>';
        echo '</div>';
    }
}

if (!$simpleRsvp)
{
    $simpleRsvp = new SimpleRsvp();
    $simpleRsvp->Initialize('Simple RSVP Plugin', '0.1.0', 'simple-rsvp', 'simple-rsvp', true);
    
    /* RSVP Form Variables */
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTBOX, 'form_title', 'Help us celebrate!', 'Choose a title for your RSVP form. (Text only)');
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTAREA, 'form_subtitle', 'Let us know if you\'ll be able to join us or not.', 'Choose a subtitle for your RSVP form. (Text only)');
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTBOX, 'max_guests', '6', 'Maximum number of guests for one RSVP. (Number only)');
    /* RSVP Acknowledge Variables */
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTAREA, 'ack_response_yes', 'We\'re super exited you\'ll be joining us!', 'Choose the message to be displayed to your guests when they RSVP "Yes". (Text only)');
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTAREA, 'ack_response_no', 'We\'re so sad you won\'t be able to make it, but we look forward to seeing you soon!', 'Choose the message to be displayed to your guests when they RSVP "No". (Text only)');
    /* RSVP Confirmation E-mail Variables */
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTBOX, 'confirmation_email_from', 'me@example.com', 'Choose an email address your confirmation email should be sent from. (E-mail address only)');
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTBOX, 'confirmation_email_subject', 'RSVP confirmation - Thank you!', 'Choose a subject for the email sent to guests confirming their RSVP. (Text only)');
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTAREA, 'confirmation_email_yes_message', 'We\'re so glad you will be able to make it and we look forward to seeing you!', 'Choose the message to be emailed to your guests when they RSVP "Yes". (Text only)');
    $simpleRsvp->AddOption($simpleRsvp->OPTION_TYPE_TEXTAREA, 'confirmation_email_no_message', 'We\'re so sad you won\'t be able to make it, but we look forward to seeing you soon!', 'Choose the message to be emailed to your guests when they RSVP "No". (Text only)');
    
    $simpleRsvp->RegisterOptions(__FILE__);
    $simpleRsvp->AddAdministrationPageBlock('block-rsvp-form', 'RSVP Form Options', $simpleRsvp->CONTENT_BLOCK_TYPE_SIDEBAR, array($simpleRsvp, 'HTML_DisplayPluginRsvpFormBlock'));
    $simpleRsvp->AddAdministrationPageBlock('block-rsvp-ack', 'RSVP Acknowledge Page Options', $simpleRsvp->CONTENT_BLOCK_TYPE_SIDEBAR, array($simpleRsvp, 'HTML_DisplayPluginRsvpAckBlock'));
    $simpleRsvp->AddAdministrationPageBlock('block-rsvp-email', 'RSVP E-mail Options', $simpleRsvp->CONTENT_BLOCK_TYPE_SIDEBAR, array($simpleRsvp, 'HTML_DisplayPluginRsvpEmailBlock'));
    
    $donateLink = 'http://woofenbark.com/donate';
    $homepageLink = 'http://woofenbark.com/wordpress-plugins/simple-rsvp';
    $supportForumLink = 'http://woofenbark.com/support';
    //$simpleRsvp->AddAboutThisPluginLinks($donateLink, $homepageLink, $supportForumLink);
    
    // Register the plugin administration page with the Wordpress core.
    $simpleRsvp->RegisterAdministrationPage($simpleRsvp->PARENT_MENU_OPTIONS, $simpleRsvp->ACCESS_LEVEL_ADMINISTRATOR, 'Simple RSVP', 'Simple RSVP Plugin Options Page', 'simple-rsvp-plugin-options');
    
    add_action('init', array($simpleRsvp, 'create_rsvp_post_type'));

    add_action('manage_posts_custom_column', array($simpleRsvp, 'rsvp_columns'));
    add_filter('manage_edit-simple-rsvp_columns', array($simpleRsvp, 'create_rsvp_columns'));
    add_filter('manage_edit-simple-rsvp_sortable_columns', array($simpleRsvp, 'rsvp_sortable_columns'));
    add_action('load-edit.php', 'rsvp_edit_rsvp_load');
    add_action('admin_notices', 'rsvp_print_total_guests');

    add_action('wp_print_styles', array($simpleRsvp, 'add_rsvp_style'));
    add_action('wp_enqueue_scripts', array($simpleRsvp, 'add_rsvp_script'));
    add_shortcode('simple-rsvp', array($simpleRsvp, 'rsvp_response'));
}
   
?>
