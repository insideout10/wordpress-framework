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
	public function findByCategorySlug($slug, $types = array('any'), $offset = 0, $limit = -1) {
		$category = get_category_by_slug($slug);

		$args = array(
					'numberposts' => $limit,
					'offset' => $offset,
					'category' => $category->cat_ID,
					'post_type' => $types,
					'post_status' => 'any',
					'orderby' => 'rand'
				);

		return get_posts($args);
	}
	
}

?>