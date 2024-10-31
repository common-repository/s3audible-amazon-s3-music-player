<?php
/*
Plugin Name: S3bubble Amazon S3 Media Streaming
Plugin URI: http://www.s3bubble.com/
Description: S3Bubble is a wordpress plugin that will allow you to stream audio and video directly from Amazon s3, sign up for a account at s3bubble.com. 
Version: 3.5.4
Author: SoBytes
Author URI: http://sobytes.com/
License: GPL2
*/ 
 
/*  Copyright YEAR  Samuel East  (email : mail@samueleast.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 


if (!class_exists("s3audible")) {
	class s3audible {
		
		
		// property declaration
        public $s3audible_username = '';
		public $s3audible_email = '';
		public $bucket          = '';
		public $folder          = '';
		public $colour          = '#d84a38';
		public $fontcolour      = '#999';
		public $addborder       = 'true';
		public $width           = '100%';
		public $bgimage         = '';
		public $radius          = '0px';
		public $autoplay        = 'yes';
		public $jtoggle		    = 'true';
		public $download		= 'false';
		public $loggedin        = 'false';
		public $search          = 'false';
		public $vlogo           = 'http://s3audible.s3.amazonaws.com/icons/s3audible_play.png';
		public $icons           = 'white';
		
	    function s3audible() { //constructor
		
			$this->__construct();

		}
		
		function __construct(){
			
			// Set Plugin Path  
			$this->pluginPath = dirname(__FILE__);  
	  
			// Set Plugin URL  
			$this->pluginUrl = WP_PLUGIN_URL . '/s3audible-amazon-s3-music-player';
			
			// Put our defaults in the "wp-options" table
			add_option("s3-s3audible_username", $this->s3audible_username);
			add_option("s3-s3audible_email", $this->s3audible_email);
			add_option("s3-bucket", $this->bucket);
			add_option("s3-folder", $this->folder); 
			add_option("s3-colour", $this->colour);
			add_option("s3-fontcolour", $this->fontcolour);
			add_option("s3-width", $this->width);
			add_option("s3-bgimage", $this->bgimage);
			add_option("s3-radius", $this->radius);
			add_option("s3-autoplay", $this->autoplay);
			add_option("s3-jtoggle", $this->jtoggle);
			add_option("s3-download", $this->download);
			add_option("s3-loggedin", $this->loggedin);
			add_option("s3-search", $this->search);
			add_option("s3-vlogo", $this->vlogo);
			add_option("s3-icons", $this->icons);
			add_option("s3-addborder", $this->addborder);
			
			add_action('admin_menu', array( $this, 's3audible_admin_menu' ));
			add_action( 'wp_head', array( $this, 's3audible_css' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'include_jquery' ) );
			add_action( 'wp_footer', array( $this, 's3audible_javascript' ) );
			add_action( 'admin_head', array( $this, 's3audible_css_admin' ) );
			add_action( 'admin_footer', array( $this, 's3audible_javascript_admin' ) );
			add_shortcode( 's3audible', array( $this, 's3audible_player' ) );
			add_shortcode( 's3video', array( $this, 's3video_player' ) );
			add_shortcode( 's3audibleSingle', array( $this, 's3audibleSingle_player' ) );			
			add_shortcode( 's3videoSingle', array( $this, 's3videoSingle_player' ) );
			
		} // function
		
		
		function include_jquery(){	
			   //wp_deregister_script('jquery');
			   //wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"), false, '1.9.1');
			   //wp_enqueue_script('jquery');
		}
		
		// include css
		function s3audible_css(){
			
			// Styles
			$bucket	         = get_option("s3-bucket");
		   	$colour	         = get_option("s3-colour");
			$fontcolour	     = get_option("s3-fontcolour");
			$width	         = get_option("s3-width");
			$bgimage	     = get_option("s3-bgimage");
			$radius	         = get_option("s3-radius");
			$vlogo           = get_option("s3-vlogo");
			$icons           = get_option("s3-icons");
			$addborder       = get_option("s3-addborder");
            
			echo '<link rel="stylesheet" href="'.$this->pluginUrl.'/css/fa/font-awesome.min.css" />';
			echo '<link rel="stylesheet" href="'.$this->pluginUrl.'/css/style.css" />';
			// updated css
		    echo '<style type="text/css">.s3bubblePlayer {font-family: Arial, "MS Trebuchet", sans-serif;border-radius: '.stripcslashes($radius).' !important;-moz-border-radius: '.stripcslashes($radius).' !important;-webkit-border-radius: '.stripcslashes($radius).' !important;}
					.s3bubblePlayer a > * {color: '.stripcslashes($colour).'!important;font-style: normal!important;}
					.s3bubblePlayer a:visited {color: '.stripcslashes($colour).'!important;font-style: normal!important;}
					.s3bubblePlayer a:hover {color: '.stripcslashes($colour).'!important;font-style: normal!important;}
					.s3bubblePlayer a:active {color: '.stripcslashes($colour).'!important;font-style: normal!important;}
		            .s3-type-playlist {border-radius: '.stripcslashes($radius).' !important;-moz-border-radius: '.stripcslashes($radius).' !important;-webkit-border-radius: '.stripcslashes($radius).' !important;}
					.s3-play-bar {background: '.stripcslashes($colour).'!important;}
					.s3-current-time, .s3-duration, .s3-playlist ul li a.s3-playlist-current {color: '.stripcslashes($colour).'!important;}
					.s3-type-playlist-single {border-radius: '.stripcslashes($radius).' !important;-moz-border-radius: '.stripcslashes($radius).' !important;-webkit-border-radius: '.stripcslashes($radius).' !important;}
					.s3-video-play a {color: '.stripcslashes($colour).'!important;} 
					.s3-video-full div.s3-jplayer {background-color:'.stripcslashes($colour).';}   
					</style>';
		}
		// include css
		function s3audible_css_admin(){
			echo '<link rel="stylesheet" href="'.$this->pluginUrl.'/css/colorpicker.css" />';
		}
		
		// include javascript
		function s3audible_javascript(){
			
			$jtoggle = get_option("s3-jtoggle");	
			
			if($jtoggle == 'true'){
				echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>';
			}
			
			echo '<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>';
			echo '<script type="text/javascript" src="'.$this->pluginUrl.'/js/s3audible.min.js"></script>';
		
			
		}
		
		// include javascript
		function s3audible_javascript_admin(){
			
			echo '<script type="text/javascript" src="'.$this->pluginUrl.'/js/colorpicker.js"></script>';
			echo '<script type="text/javascript" src="'.$this->pluginUrl.'/js/eye.js"></script>';
 
			?><script type="text/javascript">
			    (function($){
					$("#colour").ColorPicker({
						onSubmit: function (hsb, hex, rgb, el) {
							$(el).val(hex);
							$(el).ColorPickerHide();
						},
						onBeforeShow: function () {
							$("#colour").val(this.value);
							$(this).ColorPickerSetColor(this.value);
				
						},
						onChange: function (hsb, hex, rgb) {
							$("#colour").val("#" + hex);
						}
					}).bind("keyup", function () {
						$(this).ColorPickerSetColor(this.value);
					});
					$("#fontcolour").ColorPicker({
						onSubmit: function (hsb, hex, rgb, el) {
							$(el).val(hex);
							$(el).ColorPickerHide();
						},
						onBeforeShow: function () {
							$("#fontcolour").val(this.value);
							$(this).ColorPickerSetColor(this.value);
				
						},
						onChange: function (hsb, hex, rgb) {
							$("#fontcolour").val("#" + hex);
						}
					}).bind("keyup", function () {
						$(this).ColorPickerSetColor(this.value);
					});
				
				
				$('.s3abgimage').click(function(){
	
					$('#bgimage').val($(this).attr('href'));
					
					return false;
					
				});
				
				
				
				})(jQuery);</script>

			<?php
			
		}
		
		function s3audible_admin_menu()  
    	{	
			$icon_url = "http://s3audible.s3.amazonaws.com/icons/wps3icon.png";
			add_menu_page( 'S3Bubble', 'S3Bubble', 10, 'S3Bubble', array($this, 's3audible_admin'), $icon_url );
    	}
		
		function s3audible_admin()  
    	{	
			
			if ( isset($_POST['submit']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if (! wp_verify_nonce($nonce, 'isd-updatesettings') ) die('Security check failed'); 
				if (!current_user_can('manage_options')) die(__('You cannot edit the search-by-category options.'));
				check_admin_referer('isd-updatesettings');	
			// Get our new option values
			$s3audible_username	= $_POST['s3audible_username'];
			$s3audible_email	= $_POST['s3audible_email'];
			$bucket				= $_POST['bucket'];
			$folder				= $_POST['folder'];
			$colour				= addslashes($_POST['colour']);
			$fontcolour			= addslashes($_POST['fontcolour']);
			$width				= addslashes($_POST['width']);
			$bgimage			= addslashes($_POST['bgimage']);
			$radius				= addslashes($_POST['radius']);
			$autoplay			= addslashes($_POST['autoplay']);
			$jtoggle			= addslashes($_POST['jtoggle']);
			$download			= addslashes($_POST['download']);
			$loggedin			= addslashes($_POST['loggedin']);
			$search			    = addslashes($_POST['search']);
			$vlogo		        = addslashes($_POST['vlogo']);
			$icons		        = addslashes($_POST['icons']);
			$addborder		    = addslashes($_POST['addborder']);
			
		    // Update the DB with the new option values
			update_option("s3-s3audible_username", mysql_real_escape_string($s3audible_username));
			update_option("s3-s3audible_email", mysql_real_escape_string($s3audible_email));
			update_option("s3-bucket", mysql_real_escape_string($bucket));
			update_option("s3-folder", mysql_real_escape_string($folder));
			update_option("s3-colour", mysql_real_escape_string($colour));
			update_option("s3-fontcolour", mysql_real_escape_string($fontcolour));
			update_option("s3-width", mysql_real_escape_string($width));
			update_option("s3-bgimage", mysql_real_escape_string($bgimage));
			update_option("s3-radius", mysql_real_escape_string($radius));
			update_option("s3-autoplay", mysql_real_escape_string($autoplay));
			update_option("s3-jtoggle", mysql_real_escape_string($jtoggle));
			update_option("s3-download", mysql_real_escape_string($download));
			update_option("s3-loggedin", mysql_real_escape_string($loggedin));
			update_option("s3-search", mysql_real_escape_string($search));
			update_option("s3-vlogo", mysql_real_escape_string($vlogo));
			update_option("s3-icons", mysql_real_escape_string($icons));
			update_option("s3-addborder", mysql_real_escape_string($addborder));
			}
			
			$s3audible_username	= get_option("s3-s3audible_username");
			$s3audible_email	= get_option("s3-s3audible_email");
			$bucket	            = get_option("s3-bucket");
			$folder				= get_option("s3-folder");
			$colour				= get_option("s3-colour");
			$fontcolour			= get_option("s3-fontcolour");
			$width				= get_option("s3-width");
			$bgimage			= get_option("s3-bgimage");
			$radius				= get_option("s3-radius");
			$autoplay			= get_option("s3-autoplay");
			$jtoggle	        = get_option("s3-jtoggle");	
			$download	        = get_option("s3-download");	
			$loggedin			= get_option("s3-loggedin");			
			$search			    = get_option("s3-search");
			$vlogo		        = get_option("s3-vlogo");
			$icons		        = get_option("s3-icons");
			$addborder		    = get_option("s3-addborder");
?>

<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>S3Bubble Amazon S3 Media Streaming Made Easy</h2>
	
	<div class="metabox-holder has-right-sidebar">
		
		<div class="inner-sidebar" style="width:581px">
 
			<div class="postbox">
				<h3><span>Add Video From Your <a href="http://S3Bubble.com/" target="_blank">S3Bubble.com</a> Account.</span></h3>
				<div class="inside">
<iframe style="width:100%" height="340" src="http://s3bubble.com/watch?v=DksNnhwTz&amp;share=true&amp;hex=e02222" frameborder="0" allowfullscreen=""></iframe>
				</div>
			</div>
 
			<div class="postbox">
				<h3><span>If you dont already have a amazon s3 account create a free one <a href="https://portal.aws.amazon.com/gp/aws/developer/registration/index.html" target="_blank">Amazon S3 Storage</a></span></h3>
				<div class="inside">
					<iframe style="width:100%" height="340" src="http://s3bubble.com/watch?v=GOxTyRzSB&amp;share=true&amp;hex=e02222" frameborder="0" allowfullscreen=""></iframe>
				</div>
			</div>
            

			<!-- ... more boxes ... -->
 
		</div> <!-- .inner-sidebar -->
 
		<div id="post-body">
			<div id="post-body-content" style="margin-right: 600px;">
 
				<div class="postbox">
					<h3><span>Please sign up for an account at <a href="http://S3Bubble.com/" target="_blank">http://S3Bubble.com</a> you will need to use the username and email you signed up with.</span></h3>
					<div class="inside">
						<form action="" method="post" id="isd-config" style="overflow: hidden;">
    <table class="form-table">
      <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('isd-updatesettings'); } ?>
       <tr>
        <th scope="row" valign="top"><label for="S3Bubble_username">S3Bubble Username:</label></th>
        <td><input type="text" name="s3audible_username" id="s3audible_username" class="regular-text" value="<?php echo $s3audible_username; ?>"/>
        	<br />
       <span class="description">Username you signed up to S3Bubble.com found <a href="http://S3Bubble.com/admin/#/profile" target="_blank">here</a></span>	
        </td>
        
      </tr> 
       <tr>
        <th scope="row" valign="top"><label for="s3audible_email">S3Bubble Email:</label></th>
        <td><input type="email" name="s3audible_email" id="s3audible_email" class="regular-text" value="<?php echo $s3audible_email; ?>"/>
        	<br />
        	<span class="description">Email you signed up to S3Bubble.com found <a href="http://S3Bubble.com/admin/#/profile" target="_blank">here</a></span>
        </td>
      </tr> 
      <tr>
        <th scope="row" valign="top"><label for="colour">Brand Color:</label></th>
        <td><input type="text" name="colour" id="colour" class="regular-text" value="<?php echo $colour; ?>"/>
        	<br />
        	<span class="description">Sets the brand colour for the player.</p>
        </td>
      </tr>
      <tr>
        <th scope="row" valign="top"><label for="radius">Border Radius:</label></th>
        <td><input type="text"  name="radius" id="radius" value="<?php echo $radius; ?>"/>
        	<br />
        	<span class="description">Here you can set a border radious for the player in pixels.</p>
        </td>
      </tr>
      <tr>
        <th scope="row" valign="top"><label for="search">Show Search:</label></th>
        <td><select name="search" id="search">
            <option value="<?php echo $search; ?>"><?php echo $search; ?></option>
            <option value="true">true</option>
            <option value="false">false</option>
          </select>
          <br />
          <span class="description">Allow users to search tracks.</p></td>
      </tr>
      
       <tr>
        <th scope="row" valign="top"><label for="jtoggle">Toggle Jquery Include:</label></th>
        <td><select name="jtoggle" id="jtoggle">
            <option value="<?php echo $jtoggle; ?>"><?php echo $jtoggle; ?></option>
            <option value="true">true</option>
            <option value="false">false</option>
          </select>
          <br />
          <span class="description">If plugin is not showing try this option.</p></td>
      </tr>
       <tr>
        <th scope="row" valign="top"><label for="download">Show Download Links:</label></th>
        <td><select name="download" id="download">
            <option value="<?php echo $download; ?>"><?php echo $download; ?></option>
            <option value="true">true</option>
            <option value="false">false</option>
          </select>
          <br />
          <span class="description">If set to true download links will show.</p>
          </td>
      </tr>
       <tr>
        <th scope="row" valign="top"><label for="loggedin">Download option logged in:</label></th>
        <td><select name="loggedin" id="loggedin">
            <option value="<?php echo $loggedin; ?>"><?php echo $loggedin; ?></option>
            <option value="true">true</option>
            <option value="false">false</option>
          </select>
          <br />
          <span class="description">Only allow download link for logged in users.</p></td>
      </tr>
      
       <tr>
          <td><span class="description">The powered by s3bubble link will only show during the trial period or if you upgrade it will be removed.</p></td>
      </tr>

    </table>
    <br/>
    <span class="submit" style="border: 0;">
    <input type="submit" name="submit" class="button button-primary button-hero" value="Save Settings" />
    </span>
  </form>
  
  <p>This plugin was developed by sobytes if you need any help please <a href="mailto:support@S3Bubble.com" target="_blank">contact us</a></p>

					</div> <!-- .inside -->
				</div>
                 
                 
                 <div class="postbox">
				<h3><span>Audio Shortcode Example</span></h3>
				<div class="inside">
					<p><code>[s3audible bucket="enter-your-bucket" folder="enter-your-bucket-folder" autoplay="true" height="400"]</code></p>
				</div>
			</div>
            
            <div class="postbox">
				<h3><span>Single Audio Shortcode Example</span></h3>
				<div class="inside">
					<p><code>[s3audibleSingle bucket="enter-your-bucket" track="enter-your-track-name" autoplay="true"]</code></p>
				</div>
			</div>
			<!-- ... more boxes ... -->
				
 
			</div> <!-- #post-body-content -->
		</div> <!-- #post-body -->
 
	</div> <!-- .metabox-holder -->
	
</div> <!-- .wrap -->

  

<?php	
       } 
	   
	   
	   function s3audible_player($atts){ 
	   
            // get option from database
			$s3audible_username = get_option("s3-s3audible_username");
			$s3audible_email = get_option("s3-s3audible_email");		
            $bucket	         = get_option("s3-bucket");
			$loggedin        = get_option("s3-loggedin");
			$search          = get_option("s3-search");
			if($loggedin == 'true'){
				if ( is_user_logged_in() ) {
					$download = get_option("s3-download");
				}
			}else{
				$download = get_option("s3-download");
			}
		   $array = array($s3audible_username, $s3audible_email);
           $bind = implode("|", $array);
		   $userdata = base64_encode($bind);
		   return '<div class="s3audible s3bubblePlayer" data-playlist="'.$atts['playlist'].'" data-height="'.$atts['height'].'" data-download="'.$download.'" data-search="'.$search.'" data-userdata="'.$userdata.'" data-bucket="'.$atts['bucket'].'" data-folder="'.$atts['folder'].'" data-autoplay="'.$atts['autoplay'].'"></div>';
		
        }

        function s3video_player($atts){ 
	   
            // get option from database
			$s3audible_username = get_option("s3-s3audible_username");
			$s3audible_email = get_option("s3-s3audible_email");		
            $bucket	         = get_option("s3-bucket");
			$loggedin        = get_option("s3-loggedin");
			$search          = get_option("s3-search");
			if($loggedin == 'true'){
				if ( is_user_logged_in() ) {
					$download = get_option("s3-download");
				}
			}else{
				$download = get_option("s3-download");
			}
		   $array = array($s3audible_username, $s3audible_email);
           $bind = implode("|", $array);
		   $userdata = base64_encode($bind);
		   return '<div class="s3video s3bubblePlayer" data-download="'.$download.'" data-search="'.$search.'" data-userdata="'.$userdata.'" data-bucket="'.$atts['bucket'].'" data-folder="'.$atts['folder'].'" data-autoplay="'.$atts['autoplay'].'"></div>';
		
        }

		function s3audibleSingle_player($atts){ 
	   
            // get option from database
			$s3audible_username = get_option("s3-s3audible_username");
			$s3audible_email = get_option("s3-s3audible_email");		
            $bucket	         = get_option("s3-bucket");
			$loggedin        = get_option("s3-loggedin");
			$search          = get_option("s3-search");
			if($loggedin == 'true'){
				if ( is_user_logged_in() ) {
					$download = get_option("s3-download");
				}
			}else{
				$download = get_option("s3-download");
			}
		   $array = array($s3audible_username, $s3audible_email);
           $bind = implode("|", $array);
		   $userdata = base64_encode($bind);
		   return '<div class="s3audibleSingle s3bubblePlayer" data-download="'.$download.'" data-userdata="'.$userdata.'" data-bucket="'.$atts['bucket'].'" data-track="'.$atts['track'].'" data-autoplay="'.$atts['autoplay'].'"></div>';
		
        }
        
		function s3videoSingle_player($atts){ 
	   
            // get option from database
			$s3audible_username = get_option("s3-s3audible_username");
			$s3audible_email = get_option("s3-s3audible_email");		
            $bucket	         = get_option("s3-bucket");
			$loggedin        = get_option("s3-loggedin");
			$search          = get_option("s3-search");
			if($loggedin == 'true'){
				if ( is_user_logged_in() ) {
					$download = get_option("s3-download");
				}
			}else{
				$download = get_option("s3-download");
			}
		   $array = array($s3audible_username, $s3audible_email);
           $bind = implode("|", $array);
		   $userdata = base64_encode($bind);
		   return '<div class="s3videoSingle s3bubblePlayer" data-download="'.$download.'" data-userdata="'.$userdata.'" data-bucket="'.$atts['bucket'].'" data-name="'.$atts['name'].'" data-autoplay="'.$atts['autoplay'].'"></div>';
		
        }
		
	}
// Initiate the class
$s3audible = new s3audible();
add_action( 'widgets_init', create_function( '', 'register_widget( "s3audible_widget" );' ) );
} //End Class s3audible

/**
 * Adds Foo_Widget widget.
 */
class s3audible_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		's3audible_widget', // Base ID
			'S3Bubble', // Name
			array( 'description' => __( 'S3Bubble Cloud Player and media manager', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$autoplay = apply_filters( 'autoplay', $instance['autoplay'] );
		$s3bucket = $instance['s3bucket'];
		$s3folder = $instance['s3folder'];
	
		echo $before_widget;
           
		    // get option from database
			$s3audible_username = get_option("s3-s3audible_username");
			$s3audible_email = get_option("s3-s3audible_email");		
			$loggedin        = get_option("s3-loggedin");
			$search          = get_option("s3-search");
			if($loggedin == 'true'){
				if ( is_user_logged_in() ) {
					$download = get_option("s3-download");
				}
			}else{
				$download = get_option("s3-download");
			}
			$array = array($s3audible_username, $s3audible_email);
            $bind = implode("|", $array);
		    $userdata = base64_encode($bind);
		    echo '<div class="s3audible s3bubblePlayer" data-download="'.$download.'" data-search="'.$search.'" data-userdata="'.$userdata.'" data-bucket="'.$s3bucket.'" data-folder="'.$s3folder.'" data-autoplay="'.$autoplay.'"></div>';
		
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update(  $new_instance, $old_instance  ) {
		$instance = $old_instance;
		$instance = array();
		$instance['autoplay'] = strip_tags( $new_instance['autoplay'] );
		$instance['s3bucket'] = strip_tags( $new_instance['s3bucket'] );
		$instance['s3folder'] = strip_tags( $new_instance['s3folder'] );
		return $instance;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		if ( isset( $instance[ 'autoplay' ] ) ) {
			$autoplay = $instance[ 'autoplay' ];
		}
		else {
			$autoplay = __( 'false', 'text_domain' );
		}
		if ( isset( $instance[ 's3bucket' ] ) ) {
			$s3bucket = $instance[ 's3bucket' ];
		}
		else {
			$s3bucket = __( 'enter bucket', 'text_domain' );
		}
		if ( isset( $instance[ 's3folder' ] ) ) {
			$s3folder = $instance[ 's3folder' ];
		}
		else {
			$s3folder = __( '', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'autoplay' ); ?>"><?php _e( 'Autoplay:true/false' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" type="text" value="<?php echo esc_attr( $autoplay ); ?>" />
		</p>
        <p>
		<label for="<?php echo $this->get_field_id( 's3bucket' ); ?>"><?php _e( 'Bucket' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 's3bucket' ); ?>" name="<?php echo $this->get_field_name( 's3bucket' ); ?>" type="text" value="<?php echo esc_attr( $s3bucket ); ?>" />
		</p>
         <p>
		<label for="<?php echo $this->get_field_id( 's3folder' ); ?>"><?php _e( 'Bucket Folder:Optional' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 's3folder' ); ?>" name="<?php echo $this->get_field_name( 's3folder' ); ?>" type="text" value="<?php echo esc_attr( $s3folder ); ?>" />
		</p>
		<?php 
	}

} // class s3audible widget