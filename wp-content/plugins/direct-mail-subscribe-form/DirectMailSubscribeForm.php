<?php
/**
 * @package Direct Mail
 * @author e3 Software
 * @version 1.5
 */
/*
Plugin Name: Direct Mail Subscribe Form
Plugin URI: https://wordpress.org/plugins/direct-mail-subscribe-form/
Description: This plugin adds a mailing list subscribe form to your blog that syncs with Direct Mail for macOS. Add the widget to your sidebar by navigating to Appearance > Widgets.
Author: e3 Software
Version: 1.5
Author URI: https://www.directmailmac.com
*/

/*

Copyright 2019 e3 Software

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

*/

add_action( 'wp_head', 'directmail_sf_client_css' ); 
add_action( 'wp_head', 'directmail_sf_placeholder_fallback_script' ); 
add_action( 'admin_head', 'directmail_sf_admin_css' );

class Direct_Mail_Subscribe_Form_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'directmail_sg', // Base ID
			'Direct Mail Subscribe Form', // Name
			array( 'description' => __( 'Add a mailing list subscribe form that syncs with Direct Mail for Mac.', 'directmail_sf_admin_form' ), ) // Args
		);
	}
	
	private function ls( $key, $language ) {
		static $strings = null;
		
		if ( !$strings ) {
			$strings = array(
				"en" => array(
					"fn" => "First Name",
					"ln" => "Last Name",
					"email" => "Email",
					"subscribe" => "Subscribe"
				),
				"de" => array(
					"fn" => "Vorname",
					"ln" => "Nachname",
					"email" => "E-Mail",
					"subscribe" => "Anmelden"
				),
				"es" => array(
					"fn" => "Nombre",
					"ln" => "Apellidos",
					"email" => "Correo electrónico",
					"subscribe" => "Suscribirse"
				),
				"fr" => array(
					"fn" => "Prénom",
					"ln" => "Nom de famille",
					"email" => "Adresse électronique",
					"subscribe" => "S'abonner"
				),
				"it" => array(
					"fn" => "Nome",
					"ln" => "Cognome",
					"email" => "Indirizzo di e-mail",
					"subscribe" => "Iscriviti"
				)
			);
		}
		
		return $strings[$language][$key];
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		extract( $args );
		$capture = $instance['capture'];
		$title 	 = $instance['title'];
		$form_id = isset( $instance['form_id'] ) ? $instance['form_id'] : ( isset( $instance['group_id'] ) ? $instance['group_id'] : '' );
		$lang    = isset($instance['language']) ? $instance['language'] : 'en';
		
		echo $args['before_title'] . esc_html($title) . $args['after_title'];
		echo "<form class='directmail_sf' method='post' action='https://dm-mailinglist.com/subscribe' accept-charset='UTF-8' target='_blank'>\n";
	    printf( "<input type='hidden' name='form_id' value='%s'>\n", esc_attr( $form_id ) );
	    echo "<input type='email' name='email' style='display: none'>\n";
		echo '<table border=0 cellpadding=0 cellspacing=0 width=100%>';
	    if ( $capture == 'email_and_name' ) {
	        printf( "<tr><td><input type='text' name='first_name' data-label='%s:' id='dmsf_first_name_%s' placeholder='%s'></td></tr>\n", esc_attr($this->ls('fn', $lang)), esc_attr($form_id), esc_attr($this->ls('fn', $lang)) );
	        printf( "<tr><td><input type='text' name='last_name' data-label='%s:' id='dmsf_last_name_%s' placeholder='%s'></td></tr>\n", esc_attr($this->ls('ln', $lang)), esc_attr($form_id), esc_attr($this->ls('ln', $lang)) );
	    }
	    printf( "<tr><td><input type='email' name='subscriber_email' data-label='%s:' id='dmsf_email_%s' placeholder='%s'></td></tr>\n", esc_attr($this->ls('email', $lang)), esc_attr($form_id), esc_attr($this->ls('email', $lang)) ); 
	    printf( "<tr><td><input type='submit' value='%s'></td></tr>\n", esc_attr($this->ls('subscribe', $lang)) );
		echo '</table>';	
	    echo "</form>";
		echo $args['after_widget'];
	}

 	public function form( $instance ) {
		if ( !isset( $instance['title'] ) ) {
			$instance['title'] = 'Subscribe to our Newsletter';
		}
	
		if ( !isset( $instance['capture'] ) ) {
			$instance['capture'] = 'email_only';
		}
		
		if ( !isset( $instance['form_id'] ) && isset( $instance['group_id'] ) ) {
			$instance['form_id'] = $instance['group_id'];
		}
		
		if ( !isset( $instance['form_id'] ) ) {
			$instance['form_id'] = "";
		}
		
		if ( !isset( $instance['language'] ) ) {
			$instance['language'] = "en";
		}
	       
	    $title              = htmlspecialchars(strip_tags(stripslashes($instance['title'])), ENT_QUOTES);
	    $form_id            = htmlspecialchars(strip_tags(stripslashes($instance['form_id'])), ENT_QUOTES);
	    $email_name_checked = $instance['capture'] == "email_and_name" ? "checked='checked'" : "";
	    $email_checked      = $instance['capture'] == "email_only"     ? "checked='checked'" : "";
	    
		echo "<p>";
	    echo "<label class='directmail_sf_label'>Title:</label><input class='widefat' type='text' name='" . $this->get_field_name( 'title' ) . "' value='$title'/>";
		echo "</p>";
		echo "<p>";
	    echo "<label class='directmail_sf_label'>Subscribe Form ID:</label><input class='widefat' type='text' name='" . $this->get_field_name( 'form_id' ) . "' value='$form_id'/>";
		echo "</p>";
		echo "<p>";
	    echo "<a href='https://www.directmailmac.com/support/article/327' target='_blank'>What is my form id?</a>";
		echo "</p>";
		echo "<p>";
	    echo "<input type='radio' name='" . $this->get_field_name( 'capture' ) . "' value='email_only' ". $email_checked . "/>&nbsp;Ask for subscriber&#8217;s email<br/>";
	    echo "<input type='radio' name='" . $this->get_field_name( 'capture' ) . "' value='email_and_name' ". $email_name_checked . " />&nbsp;Ask for subscriber&#8217;s name and email<br/>";
		echo "</p>";
		echo "<p>";
	    echo "<label class='directmail_sf_label'>Language:</label>";
		echo "<select class='widefat' name='" . $this->get_field_name( 'language' ) . "'>";
		foreach ( array("en" => "English", "fr" => "French", "de" => "German", "it" => "Italian", "es" => "Spanish") as $lang_code => $lang_name ) {
			printf( "<option value='%s'%s>%s</option>", $lang_code, $lang_code == $instance['language'] ? " selected" : "", $lang_name );
		}
		echo "</select>";
		echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		
		$instance['title']    = $new_instance['title'];
		$instance['form_id']  = $new_instance['form_id'];
		$instance['capture']  = $new_instance['capture'];
		$instance['language'] = $new_instance['language'];
		
		return $instance;
	}
}

add_action( 'widgets_init', 'directmail_sf_register_widget' );

function directmail_sf_register_widget()
{
    register_widget( 'Direct_Mail_Subscribe_Form_Widget' );
}

function directmail_sf_client_css()
{
    echo "<style type=\"text/css\">.directmail_sf { display: block; } .directmail_sf table, .directmail_sf table td { border: none !important; } .directmail_sf td input[type=text], .directmail_sf td input[type=email] { width: 100%; box-sizing: border-box; }</style>";
}

function directmail_sf_admin_css()
{
    echo "<style type=\"text/css\">.directmail_sf_label { display: block; }</style>";
}

function directmail_sf_placeholder_fallback_script()
{
	echo <<<EOD
<script type="text/javascript">
(function(){
	var target = document;
	var attacher = target.addEventListener;
	var eventName = "load";
	
	if ( !attacher ) {
		target = window;
		attacher = target.attachEvent;
		eventName = "onload";
	}
	
	if ( attacher ) {
		attacher.call( target, eventName, function() {
			var input = document.createElement("input");

			if ( !( "placeholder" in input ) && document.querySelectorAll ) {
				var needLabels = document.querySelectorAll(".directmail_sf input[data-label]");

				for ( var i = 0; i < needLabels.length; i++ ) {
					var item = needLabels.item(i);
					var label = document.createElement("label");

					label.setAttribute( "for", item.getAttribute("id") );
					label.innerText = item.getAttribute("data-label");
					item.parentNode.insertBefore( label, item );
					item.parentNode.insertBefore( document.createElement("br"), item );
				}
			}
		}, false );
	}
})();
</script>	
EOD;
}
