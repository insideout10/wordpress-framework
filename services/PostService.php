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

		$args = $this->getDefaultArgs($types, $offset, $limit);
		
		if (true == $includeSubcategories) {
			$args = array_merge($args, array('cat' => $category->cat_ID ));
		} else {
			$args = array_merge($args, array('category__in' => array( $category->cat_ID )));
		}
		
		return get_posts($args);
	}
	
	/**
	 * Find all the posts that have the specified slug names.
	 * @param array $slugs An array of slugs (or post-names).
	 * @return array An array of posts objects.
	 */
	public function findBySlugNames(&$slugs) {
		if (false == is_array($slugs) || 0 == sizeof($slugs))
			return array();
		
		global $wpdb;
		
		$query = "SELECT * FROM $wpdb->posts WHERE post_name = %s";
		
		if (1 < sizeof($slugs)) {
			for ($i = 1; $i < sizeof($slugs); $i++) {
				$query .= ' OR post_name = %s'; 
			}
		}

		$posts = $wpdb->get_results( $wpdb->prepare(
					$query,
					$slugs
				), OBJECT);
		
		return $posts;
	}

	public function findRelated($postId, $types = array('any'), $offset = 0, $limit = -1) {
		
		$tagIDs = array();
		foreach (get_the_tags($postId) as $tag)
			$tagIDs[] = $tag->term_id;
		
		return $this->findByTags($tagIDs, $types, $offset, $limit);
	}
	
	public function findByTags(&$tags, $types = array('any'), $offset = 0, $limit = -1) {
		$args = $this->getDefaultArgs($types, $offset, $limit);
		
		if (is_array($tags))
			$tagsArray = $tags;
		
		if (is_object($tags))
			$tagsArray = get_object_vars($tags);

		$args = array_merge(
					$args,
					array(
						'tag__in' => $tagsArray
					)
				);
		
		return get_posts($args);
	}
	
	/**
	 * Returns the default set of arguments for the get_posts call.
	 * @param array $ypes An array of post types. Default 'any'. 
	 * @param integer $offset The offset from where to start (default = 0).
	 * @param integer $limit The maximum number of results (default = unlimited).
	 */
	private function getDefaultArgs($types = array('any'), $offset = 0, $limit = -1) {
		return array(
				'numberposts' => $limit,
				'offset' => $offset,
				'post_type' => $types,
				'post_status' => 'any',
				'orderby' => 'rand'
		);
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
	
	public function loadAuthors(&$posts) {
		foreach ($posts as &$post) {
			if (false === is_array($post))
				$post = get_object_vars($post);
	
			$author = get_userdata( $post['post_author'] );
			$post['author'] = $author->display_name;
		}
	
		return $posts;
	}
	
	public function loadTags(&$posts) {
		foreach ($posts as &$post) {
			if (false === is_array($post))
				$post = get_object_vars($post);
		
			$post['tags'] = get_the_tags( $post['ID'] );
		}
		
		return $posts;
	}
	
}

?>