<?php
/**
 * Shortcodes Pack
 * 
 * @package: WBootStrap
 * @file: shortcodes.php
 * @author Ohad Raz 
 * @since 0.1
 */
 ?>
 <?php
 if (!class_exists('WBootStrap_Shortcodes')){
 	class WBootStrap_Shortcodes{
 		
 		/**
 		 * array for shortcode tags
 		 * @access protected
 		 */
 		protected $_shortcode_list;
 		
 		/**
 		 * array for footer js code
 		 * @access protected
 		 */
 		protected $_f_JS;

 		/**
 		 * flag for footer js code
 		 * @access protected
 		 */
 		protected $_f_JS_flag;

 		/**
 		 * array for temp container
 		 * @access protected
 		 */
 		protected $_temp;

 		/**
 		 * [shortcodes __constructor description]
 		 * @author ohad raz
 		 * @since 0.1
 		 * 
 		 */
 		public function __construct(){
 			$this->_f_JS_flag = false;
 			$this->_shortcode_list = array(
	 			'label',
	 			'alert',
	 			'button',
	 			'accordion',
	 			'acc_section',
	 			'tabs',
	 			'tab',
	 			'tooltip',
	 			'popover',
	 			'carousel',
	 			'carousel_item');
 			add_action('wp_footer',array(&$this ,'footer_js'));
 			$this->add_shortcodes();
 		}

 		public function footer_js(){
 			if ($this->_f_JS_flag){
 				?>
 				<script type="text/javascript">
 				jQuery(document).ready(function(){
 				<?php
 				foreach ($this->_f_JS as $key => $value) {
 					echo "//code for ".$key.'
 					'.$value ."\n";
 				}
 				echo '});'."\n";
 				echo '</script>'."\n";
 			}
 		}

 		/**
 		 * register shortcodes
 		 * @author ohad raz
 		 * @since 0.1
 		 */
 		public function add_shortcodes(){
 			foreach ($this->_shortcode_list as $shortcode){
 				add_shortcode($shortcode,array(&$this,'sh_handler_'.$shortcode));
 			}
 		}

 		/**
 		 * popover_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * @usage [popover title="a title" hover="hover this"]this will show in popover[/popover]
 		 */
 		public function sh_handler_popover($atts,$content=NULL){
 			extract( shortcode_atts( array(
			    'title' => '',
			    'hover' => ''
			), $atts ) );
			wp_enqueue_script('tooltip', get_template_directory_uri().'/assets/js/bootstrap-tooltip.js', array('jquery'),theme_version, true );
			wp_enqueue_script('popover', get_template_directory_uri().'/assets/js/bootstrap-popover.js', array('tooltip.js'),theme_version, true );
			$this->_f_JS['popover'] = 'jQuery("a[rel=\'popover\']").popover();';
			$this->_f_JS_flag = true;
 			return '<a data-content="'.$content.'" rel="popover" href="#" data-original-title="'.$title.'">'.$hover.'</a>';
 		}

 		/**
 		 * tooltip_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * @usage [tooltip title="this is a tooltip"]labeled text[/tooltip]
 		 */
 		public function sh_handler_tooltip($atts,$content=NULL){
 			extract( shortcode_atts( array(
			    'title' => ''
			), $atts ) );
			wp_enqueue_script('tooltip', get_template_directory_uri().'/assets/js/bootstrap-tooltip.js', array('jquery'),theme_version, true );
			$this->_f_JS['tooltip'] = 'jQuery("a[rel=\'tooltip\']").tooltip();';
			$this->_f_JS_flag = true;
 			return '<a rel="tooltip" href="#" data-original-title="'.$title.'">'.$content.'</a>';
 		}


 		/**
 		 * label_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * @usage [label color="gray"]labeled text[/label]
 		 */
 		public function sh_handler_label($atts,$content=NULL){
 			extract( shortcode_atts( array(
			    'color' => 'gray'
			), $atts ) );
			switch (strtolower($color)){
					case 'green':
						$class = ' label-success';
						break;
					case 'blue':
						$class = ' label-info';
						break;
					case 'red':
						$class = ' label-important';
						break;
					case 'yellow':
						$class = ' label-warning';
						break;
					default:
						$class = '';
						break;
			}

			return do_shortcode('<span class="label'.$class.'">'.$content.'</span>');
 		}

 		/**
 		 * alert_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * @usage [alert color="gray" js=false heading="heading text" block="false" close="false"]text in alert box[/alert]
 		 */
 		public function sh_handler_alert($atts,$content=NULL){
 			extract( shortcode_atts( array(
			    'color' => 'yellow',
			    'block' => false,
			    'close' => false,
			    'heading' => false,
			    'js' => false
			), $atts ) );
			switch (strtolower($color)){
					case 'green':
						$class = ' alert-success';
						break;
					case 'blue':
						$class = ' alert-info';
						break;
					case 'red':
						$class = ' alert-error';
						break;
					case 'yellow':
						$class = '';
						break;
					default:
						$class = '';
						break;
			}
			if ($block)
				$class .= ' alert-block fade in';
			$retVal = '<div class="alert'.$class.'">';
			if ($close){
				$retVal .= '<a href="#" class="close"';
				if ($js){
					$retVal .= ' data-dismiss="alert"';
					wp_enqueue_script('alert', get_template_directory_uri().'/assets/js/bootstrap-alert.js', array('jquery'),'1.0', true );
				}
				$retVal .= '>&times;</a>';
			}
			if($heading !== false)
				$retVal .= '<h4 class="alert-heading">'.$heading.'</h4>';
			return do_shortcode($retVal.$content.'</div>');
 		}

 		/**
 		 * button_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * @usage [button color="gray" size="small" url="http://www.google.com"]labeled text[/button]
 		 */
 		public function sh_handler_button($atts,$content=NULL){
 			extract( shortcode_atts( array(
			    'color' => 'gray',
			    'size' => '',
			    'url' => 'http://'
			), $atts ) );

			$class = '';
			//color
			switch (strtolower($color)){
					case 'green':
						$class = ' btn-success';
						break;
					case 'blue':
						$class = ' btn-primary';
						break;
					case 'red':
						$class = ' btn-danger';
						break;
					case 'yellow':
						$class = ' btn-warning';
						break;
					case 'light blue':
						$class = ' btn-info';
						break;
					default:
						$class = '';
						break;
			}
			//size 
			switch (strtolower($size)) {
				case 'small':
					$class .= ' btn-small';
					break;
				case 'large':
					$class .= ' btn-large';
					break;
				default:
					break;
			}

			return do_shortcode('<a class="btn'.$class.'" href="'.$url.'">'.$content.'</a>');
 		}

 		/**
 		 * accordion_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * see [acc_section] below
 		 * @usage 
 		 * [accordion only_one_open="false"]
 		 * 	[acc_section][/acc_section]
 		 * 	[acc_section][/acc_section]
 		 * [/accordion]
 		 */
 		public function sh_handler_accordion( $atts, $content = null ) {
			extract( shortcode_atts( array(
		    	'only_one_open' => false,
			), $atts ) );

		    $this->_temp['accordion']['count'] = 0;
		    $data_parent = $con_id = '';
		    do_shortcode($content);
		    if( isset($this->_temp['accordion']['tabs']) && is_array( $this->_temp['accordion']['tabs'])){
		    	$i = isset($this->temp['accordion']['last_count'])? $this->temp['accordion']['last_count'] : 1;
		    	if ($only_one_open == 'true'){
		    		$data_parent = ' data-parent="#accordion_'.$i.'"';
		    		$con_id = ' id="accordion_'.$i.'"';
		    	}
		        foreach( $this->_temp['accordion']['tabs'] as $tab ){
		            $tabs[] = '
		            <div class="accordion-group">
		        		<div class="accordion-heading">
		            		<a class="accordion-toggle" data-toggle="collapse"'.$data_parent.' href="#collapse_'.$i.'">
		            			'.$tab['title'].'
			                </a>
		        		</div>
		    			<div id="collapse_'.$i.'" class="accordion-body collapse '.$tab['open'].'" style="height: 0px; ">
		        			<div class="accordion-inner">
		    	    			'.$tab['content'].'
		        			</div>
		    			</div>
					</div>';
		            $i++;
		        }
		        $this->temp['accordion']['last_count'] = $i;
		        $temp = $this->_temp;
		        unset($temp['accordion']['tabs']);
		        $this->_temp = $temp;
		        wp_enqueue_script('collapse', get_template_directory_uri().'/assets/js/bootstrap-collapse.js', array('jquery'),'1.0', true );
		        $return = '<div class="accordion"'.$con_id.'>'.implode( "\n", $tabs ).'</div>';
		    }
		    return $return;
		}


		/**
 		 * accordion_section_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * @usage [acc_section title="collapse title" open="false"]section content[/acc_section]
 		 */
		public function sh_handler_acc_section( $atts, $content = null ) {
		    extract( shortcode_atts( array(
		    'title' => '',
		    'open' => false
		    ), $atts ) );

		    $i = $this->_temp['accordion']['count'];
		    $this->_temp['accordion']['tabs'][$i] = array( 
			    'title' => sprintf( $title, $this->_temp['accordion']['count'] ),
			    'content' =>  $content,
			    'open' => ($open)? 'in' : '',
			);
		    $this->_temp['accordion']['count'] = $this->_temp['accordion']['count'] + 1;
		}

		/**
 		 * tabs_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * see [tab] usage below
 		 * @usage 
 		 * [tabs location="top"]
 		 * 	[tab][/tab]
 		 *	[tab][/tab]
 		 * [/tabs]
 		 */
		public function sh_handler_tabs( $atts, $content = null ) {
			extract( shortcode_atts( array(
		    	'location' => 'top',
			), $atts ) );

			switch ($location) {
				case 'top':
					$location = '';
					break;
				case 'bottom':
					$location = ' tabs-below';
					break;
				case 'left':
					$location = ' tabs-left';
					break;
				case 'right':
					$location = ' tabs-right';
					break;
				default:
					$location = '';
					break;
			}
		    $this->_temp['tabs']['tab_count'] = 0;
		    do_shortcode($content);
		    if( is_array( $this->_temp['tabs']['tabs'] ) ){
		        $i = (isset($this->_temp['tabs']['last_count']))? $this->_temp['tabs']['last_count'] : 1;
		        foreach( $this->_temp['tabs']['tabs'] as $tab ){
		            $tabs[] = '<li'.$tab['active'].'><a data-toggle="tab" href="#tab'.$i.'">'.$tab['title'].'</a></li>';
		            $panes[] = '<div id="tab'.$i.'" class="tab-pane'.(($tab['active'] != '')? ' active' :'').'">' .$tab['content'].'</div>';
		            $i++;
		        }
		        $this->_temp['tabs']['last_count'] = $i;
		        wp_enqueue_script('tab', get_template_directory_uri().'/assets/js/bootstrap-tab.js', array('jquery'),theme_version, true );
		        $temp = $this->_temp;
		        unset($temp['tabs']['tabs']);
		        $this->_temp = $temp;
		        $return = '<div class="tabbable'.$location.'">';
		        if ($location == ' tabs-below'){
  					$return .= '<div class="tab-content">'.implode( "\n", $panes ).'</div>
  								<ul class="nav nav-tabs">'.implode( "\n", $tabs ).'</ul>';
		        }else{
		        	$return .= '<ul class="nav nav-tabs">'.implode( "\n", $tabs ).'</ul>
		            			<div class="tab-content">'.implode( "\n", $panes ).'</div>';
		        }
		        $return .= '</div>';
		    }
		    return $return;
		}

		/**
 		 * tab_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * see [tab]
 		 * @usage [tab title="tab title" active="true"]Tab content[/tab]
 		 */
		public function sh_handler_tab( $atts, $content = null ) {
		    extract( shortcode_atts( array(
		    'title' => '',
		    'active' => 'false',
		    ), $atts ) );

		    $i = $this->_temp['tabs']['tab_count'];
		    $this->_temp['tabs']['tabs'][$i] = array( 'title' => sprintf( $title, $i ), 'content' =>  $content, 'active' => ($active == 'true')? ' class="active"':'' );
		    $this->_temp['tabs']['tab_count'] = $this->_temp['tabs']['tab_count'] + 1;
		}

		/**
 		 * carousel_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * see [carousel_item] usage below
 		 * @usage 
 		 * [carousel nav_links="true"]
 		 * 		[carousel_item] [/carousel_item]
 		 * 		[carousel_item] [/carousel_item]
 		 * [/carousel]
 		 */
		public function sh_handler_carousel( $atts, $content = null ) {
			extract( shortcode_atts( array(
				'nav_links' => true
			), $atts ) );

		    $this->_temp['carousel']['item_count'] = 0;
		    do_shortcode($content);
		    if( is_array( $this->_temp['carousel']['items'] ) ){
		        $i = (isset($this->_temp['carousel']['last_count']))? $this->_temp['carousel']['last_count'] : 1;
		        foreach( $this->_temp['carousel']['items'] as $item ){
		            $items[] = '
		            <div class="item">
		            	<img src="'.$item['img'].'" alt="'.$item['alt'].'">
		            	<div class="carousel-caption">
		            		<h4>'.$item['caption_title'].'</h4>
		            		<p>'.$item['content'].'</p>
		            	</div>
		            </div>';
		            $i++;
		        }
		        $this->_temp['carousel']['last_count'] = $i;
		        $links = '';
		        if ($nav_links){
		        	$links = '<a data-slide="prev" href="#Carousel_'.$i.'" class="left carousel-control">‹</a>
							  <a data-slide="next" href="#Carousel_'.$i.'" class="right carousel-control">›</a>';
		        }
		        $temp = $this->_temp;
		        unset($temp['carousel']['items']);
		        $this->_temp = $temp;
		        wp_enqueue_script('carousel', get_template_directory_uri().'/assets/js/bootstrap-carousel.js', array('jquery'),theme_version, true );    
		        $this->_f_JS['carousel'] = 'jQuery(".carousel").carousel();';
				$this->_f_JS_flag = true;
		        $return = '<div class="carousel slide" id="Carousel_'.$i.'">
    						<div class="carousel-inner">'.implode( "\n", $items ).'</div>
    						'.$links.'
		            	  </div>';
		    }
		    return $return;
		}

		/**
 		 * carousel_item_shortcode_handler
 		 * @author	Ohad Raz
 		 * @since	0.1
 		 * @param  [array] $atts   array of shortcode attributes
 		 * @param  [string] $content 
 		 * @return [string]
 		 * 
 		 * 
 		 * @usage [carousel_item img="http://www.example.com/img1.jpg" alt="alternative text" title="caption title"]Caption content[/carousel_item]
 		 */
		public function sh_handler_carousel_item( $atts, $content = null ) {
		    extract( shortcode_atts( array(
		    'img' => '',
		    'alt' => '',
		    'caption_title' => ''
		    ), $atts ) );

		    $i = $this->_temp['carousel']['item_count'];
		    $this->_temp['carousel']['items'][$i] = array('content' =>  $content, 'img' => $img, 'alt' => $alt, 'caption_title' => $caption_title);
		    $this->_temp['carousel']['item_count'] = $this->_temp['carousel']['item_count'] + 1;
		}

 	}//end class
}//end if