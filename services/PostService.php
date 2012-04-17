<?php

/**
 * Provides access to WordPress posts.
 */
class PostService {
	
	
	/**
	 * Find all the posts belonging to a category specified by its slug.
	 * @param string $slug The category slug.
	 * @param integer $offset The offset from where to start (default = 0).
	 * @param integer $limit The maximum number of results (default = unlimited).
	 * @return array An array of posts.
	 */
	public function findByCategorySlug($slug, $types = array('any'), $includeSubcategories = false, $offset = 0, $limit = -1) {
		$category = get_category_by_slug($slug);

		$args = array(
					'numberposts' => $limit,
					'offset' => $offset,
					'post_type' => $types,
					'post_status' => 'any',
					'orderby' => 'rand'
				);
		if (true == $includeSubcategories) {
			$args = array_merge($args, array('cat' => $category->cat_ID ));
		} else {
			$args = array_merge($args, array('category__in' => array( $category->cat_ID )));
		}

		return get_posts($args);
	}
	
	/**
	 * The posts array (resulting from a get_posts call) don't have the custom_fields. This method 
	 * will add the custom fields in the 'custom' key to every post in the array and return the array itself.
	 * @param array $posts
	 * @return array The array of posts including the custom fields. 
	 */
	public function loadCustomFields(&$posts) {
		
		foreach ($posts as &$post) {
			
			if (false === is_array($post))
				$post = get_object_vars($post);
			
			$post['custom_fields'] = get_post_custom($post['ID']);
		}
		
		return $posts;
		
	}
	
	public function loadCategories(&$posts) {
		foreach ($posts as &$post) {
			if (false === is_array($post))
				$post = get_object_vars($post);

			$post_categories = wp_get_post_categories( $post['ID'] );
			$categories = array();
			
			foreach($post_categories as $category_id){
				$category = get_category( $category_id );
				$categories[] = array( 'name' => $category->name, 'slug' => $category->slug );
			}

			$post['categories'] = $categories;
		}
		
		return $posts;
	}
	
}

?>