<?php

class WP_Custom_Post_Type {
	
	var $args = '';
	var $post_type = '';
	
	function __construct( $post_type, $args = '' ) {
		$this->post_type = $post_type;
		$this->args = $args;
		
		if ( is_admin() && isset( $args->messages ) )
			add_filter( 'post_updated_messages', array( &$this, 'post_type_messages' ) );
		
		if ( isset( $args->meta_fields ) ) {
			add_action( "register_{$post_type}_meta_fields", array( &$this, 'register_meta_fields' ) );
			add_action( 'edit_form_after_title', array( &$this, 'render_nonce_field' ) );
			
		}
		
		if ( is_admin() && isset( $args->after_title ) )
			add_action( 'edit_form_after_title', array( &$this, 'after_title_meta' ) );
		
		if ( is_admin() && isset( $args->after_editor ) )
			add_action( 'edit_form_after_title', array( &$this, 'after_editor_meta' ) );
		
		if ( is_admin() && isset( $args->manage_edit_columns ) ) {
			add_action( "manage_edit-{$post_type}_columns", array( &$this, 'manage_post_columns' ) );
			
			if ( isset( $args->custom_columns ) )
				add_action( "manage_{$post_type}_posts_custom_column", $args->custom_columns );
			
		}
		
		if ( is_admin() && isset( $args->manage_sortable_columns ) ) {
			add_action( "manage_edit-{$post_type}_sortable_columns", array( &$this, 'manage_sortable_columns' ) );
			add_filter( "request", array( &$this, 'intersect_request' ) );
		
		}
		
		if ( is_admin() && isset( $args->meta_fields ) ) {
			add_action( 'edit_post', array( &$this, 'save_edit_post_metas' ) );
			
		}
		
		do_action( "register_{$post_type}_meta_fields" );
		
	}
	
	
	function register_meta_fields() {
		global $post, $post_type;
		
		foreach ( (array) $this->args->meta_fields as $meta_field ) {
			if ( ! isset( $meta_field['name'] ) )
				continue;
			
			$this->meta_fields[ $meta_field['name'] ] = (object) wp_parse_args( 
				$meta_field, array(
					'type' => 'text',
					'label' => '',
					'attributes' => '',
					'description' => '',
					'validate' => '',
					'value' => '',
				)
			);
			
		}
		
	}
	
	function render_nonce_field() {
		wp_nonce_field( "_{$this->post_type}_nonce_field", '_meta_wpnonce' );
		
	}
	
	function after_title_meta() {
		global $post, $post_type;
		
		if ( ! in_array( $this->post_type, array( $post_type, $post->post_type ) ) )
			return;
		
		extract( wp_parse_args( (array) $this->args->after_title,
			array(
				'fields' => array(),
				'callback' => 'wp_form_table',
				'before' => '',
				'after' => '',
			)
		) );
		
		if ( empty( $fields ) )
			return;
		
		foreach ( (array) $fields as $i => $field )
			if ( ! array_key_exists( $field, $this->meta_fields ) )
				unset( $fields[ $i ] );
		
		$field_keys = array_flip( $fields );
		$fields = array_merge( $field_keys, array_intersect_key( $this->meta_fields, $field_keys ) );
		
		$this->render_metas( $fields, $callback, $before, $after );
		
	}
	
	function after_editor_meta() {
		global $post, $post_type;
		
		if ( ! in_array( $this->post_type, array( $post_type, $post->post_type ) ) )
			return;
		
		extract( wp_parse_args( (array) $this->args->after_editor,
			array(
				'fields' => array(),
				'callback' => 'wp_form_table',
				'before' => '',
				'after' => '',
			)
		) );
		
		if ( empty( $fields ) )
			return;
		
		foreach ( (array) $fields as $i => $field )
			if ( ! array_key_exists( $field, $this->meta_fields ) )
				unset( $fields[ $i ] );
		
		$field_keys = array_flip( $fields );
		$fields = array_merge( $field_keys, array_intersect_key( $this->meta_fields, $field_keys ) );
		
		$this->render_metas( $fields, $callback, $before, $after );
		
	}

	function render_metas( $fields, $callback, $before, $after ) {
		global $post, $post_type;
		
		foreach ( $fields as $meta_field => $field_args ) {
			if ( 'checkbox' == $field_args->type )
				$value = get_post_meta( $post->ID, "_{$post_type}_{$meta_field}" );
			else
				$value = get_post_meta( $post->ID, "_{$post_type}_{$meta_field}", true );
			
			$fields[ $meta_field ]->value = $value;
		
		}
		
		echo $before;
		call_user_func( $callback, $fields );
		echo $after;
		
	}
	
	
	function post_type_messages( $posts_messages ) {
		global $post, $post_ID;
		
		$messages = $this->args->messages;
		
		$posts_messages[ $this->post_type ] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( $messages['updated_view'], esc_url( get_permalink( $post_ID ) ) ),
			2 => $messages['updated'],
			3 => $messages['deleted'],
			4 => $messages['updated'],
			5 => isset($_GET['revision']) ? sprintf( $messages['revision_restored'], wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( $messages['published'], esc_url( get_permalink($post_ID) ) ),
			7 => $messages['saved'],
			8 => sprintf( $messages['submitted'], esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( $messages['scheduled'],
			  // translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( $messages['draft_updated'], esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);
		
		return $posts_messages;
		
	}
	
	
	/**
	 * Save edit screen metabox
	 * @since 1.0
	 */
	function save_edit_post_metas( $object_id ) {
		
		if ( ! isset( $_POST['_meta_wpnonce'] ) && ! check_admin_referer( "_{$this->post_type}_nonce_field", '_meta_wpnonce' ) )
			return;
		
		$post_type_object = get_post_type_object( get_post_type( $object_id ) );
		if ( ! current_user_can( $post_type_object->cap->edit_post, $object_id ) )
			return;
		
		// Save all metas now
		foreach ( $this->meta_fields as $meta => $field ) {
			if ( isset( $field->handle_save ) && false == $field->handle_save )
				continue;
			
			if ( isset( $_REQUEST[ $meta ] ) ) {
				if ( '' != $field->validate )
					$value = call_user_func( $field->validate, $_REQUEST[ $meta ], $meta, $field );
				else
					$value = $_REQUEST[ $meta ];
					
			} else {
				$value = '';
				
			}
				
			$metas[ "_{$this->post_type}_{$meta}" ] = $value;
			
		}
		
		foreach ( $metas as $field => $value ) {
			if ( is_array( $value ) ) {
				$this->save_multi_meta( $object_id, $field, $value );
				
			} else {
				$this->save_meta( $object_id, $field, $value );
				
			}
			
		}
		
		
	}
	
	/**
	 * Save course object
	 * 
	 * @since 1.0
	 */
	function save_multi_meta( $object_id, $field, $values ) {
				
		delete_post_meta( $object_id, $field );
			
		foreach ( $values as $value )
			add_post_meta( $object_id, $field, $value );
		
	}
	
	/**
	 * Save course object
	 * 
	 * @since 1.0
	 */
	function save_meta( $object_id, $field, $value ) {
		
		$meta = get_post_meta( $object_id, $field, true );
			
		if ( $meta && '' == $value )
			delete_post_meta( $object_id, $field );
			
		elseif ( $meta && $value )
			update_post_meta( $object_id, $field, $value, $meta );
			
		elseif ( $value )
			add_post_meta( $object_id, $field, $value );
		
	}
	
	
	function manage_post_columns( $columns ) {
		$new_columns = $this->args->manage_edit_columns;
		
		foreach ( (array) $new_columns as $column_id => $column_label ) {
			if ( null == $column_label || false == $column_label && isset( $columns[ $column_id ] ) )
				$new_columns[ $column_id ] = $columns[ $column_id ];
				
		}
		
		return $new_columns; 
		
	}
	
	function manage_sortable_columns() {
		$sortable = $this->args->manage_sortable_columns;
		
		foreach ( (array) $sortable as $column => $vars ) {
			if ( is_int( $column ) )
				$columns[ $vars ] = (string) $vars;
			else
				$columns[ $column ] = $column;
			
		}
		
		return $columns;
		
	}
	
	function intersect_request( $vars ) {
		if ( ! isset( $vars['orderby'] ) )
			return $vars;
		
		$sortable = $this->args->manage_sortable_columns;
		$order_by = $vars['orderby'];
		
		if ( isset( $sortable[ $order_by ] ) && is_array( $sortable[ $order_by ] ) ) {
			$vars = array_merge( $vars, $sortable[ $order_by ] );
			
		}
	 
	    return $vars;
		
	}
	
}



add_action( 'registered_post_type', '_cpt_registered_post_type', 10, 2 );

function _cpt_registered_post_type( $post_type, $args ) {
	global $wp_post_types;
	
	$post_type_object = new WP_Custom_Post_Type( $post_type, $args );
	$wp_post_types[$post_type]->registered_object = $post_type_object;
	
}


function wp_form_table( $fields, $echo = true ) {
	global $_form_table, $post;
	
	$_form_table = new WP_Admin_Table_Form();
	$_form_table->get_header();
	$_form_table->add_fields( $fields );
	$_form_table->organize_fields();
	$_form_table->get_footer();
	
	if ( $echo )
		$_form_table->render();
	
	return $_form_table;
	
}




/**
 * Helps with constructing admin table forms with elements
 *
 * @since 1.1
 */

class WP_Admin_Table_Form extends WP_Admin_Form {

	public function get_header() {
		$this->html .= "\n<table class=\"form-table\">\n\t<tbody>";
		
	}
	
	public function get_footer( $show_hidden_fields = true ) {
		$this->html .= "\n\t</tbody>\n</table>\n";
		
		if ( $show_hidden_fields )
			$this->get_hidden_fields();
		
	}
	
	public function get_hidden_fields() {
		if ( ! isset( $this->fields ) )
			return;
		
		foreach ( (array) $this->fields as $field ) {
			if ( ! is_object( $field ) )
				$field = (object) $field;
			
			if ( 'hidden' != $field->type )
				continue;
			
			$this->html .= "\n{$field->field}";
			
		}
		
	}
	
	public function organize_fields() {
		if ( ! isset( $this->fields ) )
			return;
		
		foreach ( (array) $this->fields as $field ) {
			if ( ! is_object( $field ) )
				$field = (object) $field;
			
			if ( 'hidden' == $field->type )
				continue;
			
			$this->html .= sprintf( "\n\t\t<tr valign=\"top\">\n\t\t\t<th scope=\"row\">{$field->label}</th>\n\t\t\t<td>{$field->field}</td>\n\t\t</tr>" );
			
		}
		
	}
	
	public function render() {
		echo "\n".$this->html."\n";
		
	}
	
}


/**
 * Helps with constructing admin forms
 *
 * @since 1.1
 */

class WP_Admin_Form {
	
	/**
	 * Rendering string
	 * 
	 * @since 1.1
	 */
	public $html = '';
	
	/**
	 * Constructed all the fields array.
	 * Calls proper functions.
	 * 
	 * @since 1.1
	 * 
	 * @param array $args Array of args
	 * 
	 * @return void
	 * 
	 */
	public function __construct() {
		foreach ( apply_filters( 'wp_admin_form_types', array(
			'hidden', 'input', 'text', 'number', 'email', 'url', 'date', 'checkbox', 'radio', 'select', 'textarea', 'editor', 'custom' )
			) as $type )
			add_action( "wp_admin_form_{$type}", array( &$this, "add_field_{$type}" ), 10, 2 );
		
	}
	
	public function add_fields( $fields ) {
		$index = 0;
		foreach ( (array) $fields as $name => $field ) {
			if ( ! is_object( $field ) )
				$field = (object) $field;
			
			$field->name = $name;
			
			if ( ! isset( $field->label ) )
				$field->label = '';
			
			if ( ! isset( $field->attributes ) )
				$field->attributes = array();
			
			if ( ! isset( $field->description ) )
				$field->description = '';
			
			do_action( "wp_admin_form_{$field->type}", $field );
			
		}
		
	}
	
	public function get_fields( $format = '' ) {
		if ( 'array' == strtolower( $format ) )
			return $this->fields;
		
		if ( 'p' == strtolower( $format ) ) {
			foreach ( (array) $this->fields as $field )
				$html .= "<p class=\"form-type-{$field->type}\">\n\t{$field->label}\n\t{$field->field}\n</p>";
			
			return $html;
			
		}
		
		if ( 'li' == strtolower( $format ) ) {
			foreach ( (array) $this->fields as $field )
				$html .= "<li class=\"form-type-{$field->type}\">\n\t{$field->label}\n\t{$field->field}\n</li>";
			
			return $html;
			
		}
		
		if ( in_array( $format, array( '', 'div' ) ) ) {
			foreach ( (array) $this->fields as $field )
				$html .= "<div class=\"form-type-{$field->type}\">\n\t{$field->label}\n\t{$field->field}\n</div>";
			
			return $html;
			
		}
		
	}
	
	public function _get_label( $id, $label ) {
		return sprintf( '<label for="%1$s">%2$s</label>', $id, $label );
		
	}
	
	public function add_field_hidden( $field = '' ) {
		$field = (object) wp_parse_args( 
			(array) $field, array(
				'id' => $field->name,
				'name' => '',
				'value' => '',
				'attributes' => '',
				'callback_value' => '',
			)
		);
		
		$this->fields[] = array(
			'type' => 'hidden',
			'label' => false,
			'field'=> sprintf( 
				'<input id="%1$s" type="hidden" name="%2$s" value="%3$s" %4$s>', 
				$field->id, 
				$field->name, 
				( $field->callback_value ? call_user_func( $field->callback_value, $field->value ) : $field->value ),
				_wp_convert_array_to_attrs( $field->attributes )
			),
		);
		
	}
	
	public function add_field_text( $field = '' ) {
		if ( ! isset( $field->type ) )
			$field->type = 'text';
		
		if ( ! isset( $field->attributes['class'] ) )
			$field->attributes['class'] = '';
		
		$field->attributes['class'] = sprintf( 'regular-text %s', $field->attributes['class'] );
		
		$field->attributes = wp_parse_args( $field->attributes, array(
			'maxlenght' => '80',
			'size' => '80',
			)
		);
		
		$this->add_field_input( $field );
	}
	
	public function add_field_number( $field = '' ) {
		if ( ! isset( $field->type ) )
			$field->type = 'number';
		
		if ( ! isset( $field->attributes['class'] ) )
			$field->attributes['class'] = '';
		
		$field->attributes['class'] = sprintf( 'code %s', $field->attributes['class'] );
		
		$this->add_field_input( $field );
	}
	
	public function add_field_email( $field = '' ) {
		if ( ! isset( $field->type ) )
			$field->type = 'email';
		
		if ( ! isset( $field->attributes['class'] ) )
			$field->attributes['class'] = '';
		
		$field->attributes['class'] = sprintf( 'regular-text code %s', $field->attributes['class'] );
		
		$this->add_field_input( $field );
	}
	
	public function add_field_url( $field = '' ) {
		if ( ! isset( $field->type ) )
			$field->type = 'url';
		
		if ( ! isset( $field->attributes['class'] ) )
			$field->attributes['class'] = '';
		
		$field->attributes['class'] = sprintf( 'regular-text code %s', $field->attributes['class'] );
		
		$this->add_field_input( $field );
	}

	public function add_field_date( $field = '' ) {
		if ( ! isset( $field->type ) )
			$field->type = 'date';
		
		if ( ! isset( $field->attributes['class'] ) )
			$field->attributes['class'] = '';
		
		$field->attributes['class'] = sprintf( 'code %s', $field->attributes['class'] );
		
		$this->add_field_input( $field );
		
	}
	
	public function add_field_time( $field = '' ) {
		if ( ! isset( $field->type ) )
			$field->type = 'time';
		
		if ( ! isset( $field->attributes['class'] ) )
			$field->attributes['class'] = '';
		
		$field->attributes['class'] = sprintf( 'code %s', $field->attributes['class'] );
		
		$this->add_field_input( $field );
		
	}
	
	
	public function add_field_input( $field = '' ) {
		$field = (object) wp_parse_args( 
			(array) $field, array(
				'label' => '',
				'id' => $field->name,
				'type' => 'text',
				'name' => '',
				'value' => '',
				'attributes' => array( 'class' => "regular-text" ),
				'description' => '',
				'callback_value' => '',
			)
		);
		
		$this->fields[] = array(
			'type' => $field->type,
			'label' => $this->_get_label( $field->id, $field->label ),
			'field'=> sprintf( 
				'<input id="%1$s" type="%2$s" name="%3$s" value="%4$s" %5$s> %6$s', 
				$field->id, 
				$field->type,
				$field->name, 
				( $field->callback_value ? call_user_func( $field->callback_value, $field->value ) : $field->value ),
				_wp_convert_array_to_attrs( $field->attributes ),
				$field->description
			),
		);
		
	}
	
	public function add_field_radio( $field = '' ) {
		$field = (object) wp_parse_args( 
			(array) $field, array(
				'label' => '',
				'id' => $field->name,
				'type' => 'radio',
				'name' => '',
				'value' => '',
				'attributes' => '',
				'description' => '',
				'options' => array(),
			)
		);
		
		foreach ( $field->options as $option_index => $option ) {
			if ( is_array( $option ) )
				$option = (object) $option;
			
			$html[] = $this->_get_label( $field->id.'-'.$option_index, 
				"\n\t" . sprintf( 
					'<input id="%1$s" type="%2$s" name="%3$s" value="%4$s" %5$s> %6$s', 
					$field->id.'-'.$option_index, 
					$field->type, 
					$field->name, 
					$option->value,
					_wp_convert_array_to_attrs( $field->attributes ).checked( $field->value, $option->value, false ), 
					$option->label
				) . "\n" 
			);
			
		}
		
		$this->fields[] = array(
			'type' => $field->type,
			'label' => $this->_get_label( $field->id, $field->label ),
			'field'=> implode( "\n", $html ) . $field->description
		);
		
	}
	
	/**
	 * $field [
	 * 	label, id, type, name, value, attributes, description, options
	 * ]
	 * $option [
	 * 	value, label
	 * ]
	 */
	public function add_field_checkbox( $field = '' ) {
		$field = (object) wp_parse_args( 
			(array) $field, array(
				'label' => '',
				'id' => $field->name,
				'type' => 'checkbox',
				'name' => '',
				'value' => '',
				'attributes' => '',
				'description' => '',
				'options' => '',
			)
		);
		
		$html = '';
		foreach ( (array) $field->options as $option_index => $option ) {
			if ( is_array( $option ) )
				$option = (object) $option;
			
			$checked = 0;
			if ( in_array( $option->value, $field->value ) ) {
				$checked = 1;
				
			}
			
			$html .= $this->_get_label( $field->id.'-'.$option_index, 
				"\n\t" . sprintf( 
					'<input id="%1$s" type="%2$s" name="%3$s[]" value="%4$s" %5$s> %6$s', 
					$field->id.'-'.$option_index, 
					$field->type, 
					$field->name, 
					$option->value,
					_wp_convert_array_to_attrs( $field->attributes ).checked( $checked, 1, false ),
					$option->label 
				) . "\n" 
			);
			
		}
		
		$this->fields[] = array(
			'type'  => $field->type,
			'label' => $this->_get_label( '', $field->label ),
			'field' => $html . $field->description,
		);
		
	}
	
	public function add_field_select( $field = '' ) {
		$field = (object) wp_parse_args( 
			(array) $field, array(
				'label' => '',
				'id' => $field->name,
				'type' => 'select',
				'name' => '',
				'value' => '',
				'attributes' => '',
				'description' => '',
				'options' => '',
			)
		);
		
		$this->fields[] = array(
			'type'  => $field->type,
			'label' => $this->_get_label( $field->id, $field->label ),
			'field' => sprintf( 
				'<select id="%1$s" name="%2$s" %3$s>%4$s</select> %5$s', 
				$field->id, 
				$field->name, 
				_wp_convert_array_to_attrs( $field->attributes ),
				$this->_select_options( $field->options, $field ), 
				$field->description 
			),
		);
		
	}

	public function _select_options( $options, $field ) {
		$html = '';
		foreach ( (array) $options as $option ) {
			if ( ! is_object( $option ) )
				$option = (object) wp_parse_args( 
					$option, array( 'value' => '', 'label' =>'' ) 
				);
			
			$html .= "\n\t<option value=\"{$option->value}\"".selected( $field->value, $option->value, false ).">{$option->label}</option>";
			
		}
		return $html;
		
	}
	
	public function add_field_textarea( $field = '' ) {
		$field = (object) wp_parse_args( 
			(array) $field, array(
				'label' => '',
				'id' => $field->name,
				'type' => 'textarea',
				'name' => '',
				'content' => '',
				'attributes' => array( 'rows' => 5, 'class' => 'regular-text' ),
				'description' => '',
				'callback_content' => '',
			)
		);
		
		$this->fields[] = array(
			'type'  => $field->type,
			'label' => $this->_get_label( $field->id, $field->label ),
			'field' => sprintf( 
				'<textarea id="%1$s" name="%2$s" %3$s>%4$s</textarea> %5$s', 
				$field->id, 
				$field->name,
				_wp_convert_array_to_attrs( $field->attributes ),
				( $field->callback_content ? call_user_func( $field->callback_content, $field->content ) : $field->content ),
				$field->description
			)
		);
		
	}
	
	public function add_field_editor( $field = '' ) {
		$field = (object) wp_parse_args( 
			(array) $field, array(
				'label' => '',
				'id' => $field->name,
				'type' => 'editor',
				'name' => '',
				'content' => '',
				'params' => null,
				'description' => '',
			)
		);
		
		ob_start();
		wp_editor( $field->content, $field->id, $field->params );
				
		$this->fields[] = array(
			'type'  => $field->type,
			'label' => $this->_get_label( $field->id, $field->label ),
			'field' => sprintf( 
				'<div class="wp-editor-%1$s">%2$s</div> %3$s', 
				$field->id, 
				ob_get_contents(), 
				$field->description 
			),
		);
		ob_end_clean();
		
	}
	
	public function add_field_custom( $field = '' ) {
		$field = (object) wp_parse_args( 
			(array) $field, array(
				'label' => '',
				'id' => $field->name,
				'callback' => '',
			)
		);
		
		$this->fields[] = array(
			'type'  => $field->type,
			'label' => $this->_get_label( $field->id, $field->label ),
			'field' => call_user_func( $field->callback ),
		);
		
	}
	
}

if ( ! function_exists( '_wp_convert_array_to_attrs' ) ) :
/**
 * @since 1.2
 */
function _wp_convert_array_to_attrs( $array ) {
	$html = '';
	foreach ( (array) $array as $attr => $value ) {
		$html[] = esc_attr( $attr ).'="'.esc_attr( $value ).'"';
		
	}
	
	return implode( ' ', (array) $html );
}
endif;
