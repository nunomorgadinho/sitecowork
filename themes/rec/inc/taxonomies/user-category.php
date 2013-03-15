<?php

class User_Category {
	
	function __construct() {
	    register_taxonomy(
	        'user_category',
	        'user',
	        array(
	            'public' => true, // Esta taxonomy poderá ser acessada a partir do frontend
	            'labels' => array(
	                'name' => __( 'Categories' ),
	                'singular_name' => __( 'Category' ),
	                'menu_name' => __( 'Categories' ),
	                'search_items' => __( 'Search Categories' ),
	                'popular_items' => __( 'Popular Categories' ),
	                'all_items' => __( 'All Categories' ),
	                'edit_item' => __( 'Edit Category' ),
	                'update_item' => __( 'Update Category' ),
	                'add_new_item' => __( 'Add Category' ),
	                'new_item_name' => __( 'Category Name' ),
	                'add_or_remove_items' => __( 'Add or Remove Categories' ),
	                'choose_from_most_used' => __( 'Search between popular Categories' ),
	                ),
	            'rewrite' => array(
	                'with_front' => true,
	                'slug' => 'author/category' // Usamos 'author' para manter a estrutura de links coerente
	            ),
	            'hierarchical' => true,
	            'capabilities' => array(
	                'manage_terms' => 'edit_users', // Vamos manter as mesmas permissões que as de edição de usuário
	                'edit_terms'   => 'edit_users', // de maneira a manter a estrutura simples
	                'delete_terms' => 'edit_users',
	                'assign_terms' => 'read',
	            ),
	            'update_count_callback' => array( &$this, 'rec_update_category_count' ) // Usamos uma função customizada para contar o número de usuários associados a determinada profissão
	        )
	    );
		
		add_action( 'admin_menu', array( &$this, 'admin_page' ) );
		add_filter( 'parent_file', array( &$this, 'fix_user_tax_page' ) );
		add_filter( 'manage_edit-user_category_columns', array( &$this, 'user_category_columns' ) );
		add_action( 'manage_user_category_custom_column', array( &$this, 'user_category_columns_content' ), 10, 3 );
		add_action( 'show_user_profile', array( &$this, 'category_section_profile' ), 1 );
		add_action( 'edit_user_profile', array( &$this, 'category_section_profile' ), 1 );
		add_action( 'personal_options_update', array( &$this, 'save_categories' ) );
		add_action( 'edit_user_profile_update', array( &$this, 'save_categories' ) );
		add_filter( 'manage_users_columns', array( &$this, 'manage_users_columns' ) );
		add_action( 'manage_users_custom_column', array( &$this, 'manage_users_custom_column' ), 10, 3 );
		add_action( 'manage_users_sortable_columns', array( &$this, 'manage_users_sortable_columns' ) );
 
		
	}
	
	function rec_update_category_count( $terms, $taxonomy ) {
	    global $wpdb;
	 
	    // Por cada termo da taxonomia vamos correr o código...
	    foreach ( (array) $terms as $term ) {
	 
	        // Puxamos do banco de dados o contador atual
	        $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term ) );
	 
	        // E guardamos o número de usuários associados a esse termo
	        do_action( 'edit_term_taxonomy', $term, $taxonomy );
	        $wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
	        do_action( 'edited_term_taxonomy', $term, $taxonomy );
	        // Devemos manter sempre estas do_action() pois são específicas do WordPress e são responsáveis
	        // por, além de outras coisas, aumentar o contador.
	 
	    }
	
	}

	function admin_page() {
 
	    $tax = get_taxonomy( 'user_category' );
	 
	    add_users_page(
	        esc_attr( $tax->labels->menu_name ),
	        esc_attr( $tax->labels->menu_name ),
	        $tax->cap->manage_terms,
	        'edit-tags.php?taxonomy=' . $tax->name
	    );
	 
	}
	
	function fix_user_tax_page( $parent_file = '' ) {
	    global $pagenow;
	 
	    if ( ! empty( $_GET[ 'taxonomy' ] ) && $_GET[ 'taxonomy' ] == 'user_category' && $pagenow == 'edit-tags.php' ) {
	        $parent_file = 'users.php';
	    }
	 
	    return $parent_file;
	}
	
	function user_category_columns( $columns ) {
	    unset( $columns['posts'] ); // Remove a coluna posts
	    $columns['users'] = __( 'Users' ); // Adicionar uma coluna users
	    return $columns;
	 
	}

	function user_category_columns_content( $display, $column, $term_id ) {
	    if ( 'users' === $column ) {
	        $term = get_term( $term_id, 'user_category' );
	        echo $term->count;
	 
	    }
	 
	}
	
	function category_section_profile( $user ) {
 
	    $tax = get_taxonomy( 'user_category' );
	 
	    // Temos que ter a certeza que o usuário que está a visualizar term permissão
	    // para alterar a profissão.
	    if ( ! current_user_can( $tax->cap->assign_terms ) )
	        return;
	 
	    // Vamos buscar um array com todas as profissões pre-configuradas
	    $terms = get_terms( 'user_category', array( 'hide_empty' => false ) ); ?>
	 
	    <h3><?php _e( 'Categories', 'rec' ); ?></h3>
	 
	    <table class="form-table">
	        <tr>
	 			
	            <td style="-moz-column-count: 10;-moz-column-gap: 20px;-webkit-column-count: 10;-webkit-column-gap: 20px;column-count: 10;column-gap: 20px;">
	            	<?php foreach ( (array) $terms as $term ) : ?>
	            	<p>
		            	<label for="<?php echo esc_attr( $term->slug ); ?>">
		            		<input id="<?php echo esc_attr( $term->slug ); ?>" type="checkbox" name="categories[]" value="<?php echo esc_attr( $term->slug ); ?>"<?php checked( true, is_object_in_term( $user->ID, 'user_category', $term ) ); ?>> 
		                	<?php _e( $term->name, 'rec' ); ?>
		                </label>
	                </p>
					<?php endforeach; ?>
	            </td>
	        </tr>
	 		 
	    </table>
	<?php 
	}
 

 
	/**
	 * Esta função guarda o valor da profissão do usuário a partir do valor escolhido no perfil.
	 * Usamos simplesmente a função wp_set_object_terms() para guardar a profissão.
	 *
	 * @param int $user_id O ID do usuário para guardar a profissão
	*/
	function save_categories( $user_id ) {
	    $tax = get_taxonomy( 'category' );
	 
	    // Certificamo-nos que o usuário tem permissão para alterar a profissão
	    if ( !current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) )
	        return false;
	 
	    // Buscamos o valor da profissão escolhido pelo usuário
	    $terms = $_POST['categories'];
	 
	    // Guardamos o valor da profissão no banco de dados através da função 
	    // nativa do WordPress wp_set_object_terms()
	    wp_set_object_terms( $user_id, $terms, 'user_category', false );
	 
	    // Limpamos a cache de termos (importante para manter a consistência)
	    clean_object_term_cache( $user_id, 'user_category' );
	 
	}
	
	
	function manage_users_columns( $columns ) {
	    $columns['user_category'] = __( 'User Category', 'rec' );
	    return $columns;
	 
	}
	
	function manage_users_custom_column( $value, $column_name, $user_id ) {
		
	    if( 'user_category' == $column_name ) {
	    	$categories = wp_get_object_terms( $user_id, 'user_category' );
			
			$categories_html = array();
			foreach ( (array) $categories as $category ) {
				$categories_html[] = sprintf( '<a href="users.php?user_category=%1$s">%2$s</a>', $category->slug, $category->name );
				
			}
			return implode( ', ', $categories_html );
			
	    }
	 
	}
	
	function manage_users_sortable_columns($columns ) {
		$columns['user_category'] = 'user_category';
		return $columns;
 
	}
	
	
	function query_users( $query = '' ) {
		$user_query = new WP_User_Query( 
			wp_parse_args( $query, array(
				'who' => 'videomaker',
				'orderby' => 'display_name'
				)
			)
		);
		
		
		
	}
	
	
	function get_users_in_category( $category = '', $number = -1, $offset = 0 ) {
		$user_query = new WP_User_Query( array(
			'who' => 'videomaker',
			'number' => (int) $number,
			'offset' => (int) $offset,
			'orderby' => 'display_name',
			'tax_query' => array(
				array(
					'taxonomy' => 'user_category',
					'field' => is_int( $category ) ? 'id' : 'slug',
					'terms' => $category,
					)
				)
			)
		);
		
		
		
	}
	
}

function rec_user_category_init() {
	global $_wp_rec_user_category;
	$_wp_rec_user_category = new User_Category();
	
}
add_action( 'init', 'rec_user_category_init' );
