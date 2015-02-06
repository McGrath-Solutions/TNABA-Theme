<?php

class Theme_Content_Parser {
	
	/**
	* xml file with theme content
	* 
	* @var object of class SimpleXMLElement
	*/
	private $_xml;
	
	/**
	* available sidebar block types
	* 
	* @var array of strings
	*/
	private $_block_types = array( 'widget', 'menuWidget', 'block' );
	
	/**
	* initialization of <var>$_xml</var>
	*
	* @param string $path path to file with content
	*/
	function __construct( $path ) {
		$this->_xml = simplexml_load_file( $path );
		if ( ! $this->_xml ) {
			// error
		}
	}
	
	/**
	* returns the array of posts
	* every post is a key=>value array 
	*
	* if posts were not found returns "false"
	*
	* if successful returns the information about posts in the following way
	* 'name' - unique post name
	* 'title' - post title
	* 'caption' - post caption
	* 'status' - published or draft
	* 'content' - post content
	* 'styles' - aditional post styles
	* 
	* @return false|array
	*/
	public function get_posts() {
		if ( ! isset( $this->_xml->posts )
			|| ! isset( $this->_xml->posts->post ) ) {
			return false;
		}
		
		$posts_info = array();
		foreach ( $this->_xml->posts->post as $post_node ) {
			$post_attributes = $post_node->attributes();

			$current_post_info = array(
				'name' => (string) $post_attributes->name,
				'title' => (string) $post_attributes->title,
				'caption' => (string) $post_attributes->caption,
				'status' => (string) $post_attributes->status,
				'content' => (string) $post_node->content,
				'styles' => (string) $post_node->styles,
				'path' => (string) $post_attributes->name,
				'show_in_hmenu' => false,
				'show_in_vmenu' => false,
			);
			
			$posts_info[] = $current_post_info;
		}
		return $posts_info;
	}
	
	/**
	* returns the array of pages
	* every page is a key=>value array 
	*
	* if pages were not found returns "false"
	*
	* if successful returns the information about pages in the following way
	* 'name' - unique page name
	* 'title' - page title 
	* 'caption' - page caption 
	* 'path' - the path from the root page
	* 'posts_page' - set as "true" if the current page is used for outputting the posts 
	* 'content' - the content of the page
	* 'styles' - aditional page styles
	* 
	* @return false|array
	*/
	public function get_pages() {
		if ( ! isset( $this->_xml->pages )
			|| ! isset( $this->_xml->pages->page ) ) {
			return false;
		}
		
		$pages_info = array();
		foreach ( $this->_xml->pages->page as $page_node ) {
			$this->parse_page( $page_node, $pages_info );
		}
		return $pages_info;
	}

	/**
	* returns the array of sidebars
	* every sidebar is a key=>value array 
	*
	* if sidebars were not found returns "false"
	*
	* if successful returns the information about sidebars in the following way
	* 'caption' - sidebar title
	* 'name' - sidebar1|sidebar2|content-before|content-after|inactive
	* 'blocks' - the array of text blocks and widgets contained in sidebar
	*
	* each block is defined by the following parameters
	* 'type' - widget|menuWidget|block
	* 'name' - unique widget name
	* 'title' - widget title
	* 'content' - an optional field, widget content
	*
	* @return false|array
	*/
	public function get_sidebars() {
		if ( ! isset( $this->_xml->sidebars ) 
			|| ! isset( $this->_xml->sidebars->sidebar ) ) {
			return false;
		}
		
		$sidebars_info = array();
		foreach ( $this->_xml->sidebars->sidebar as $sidebar_node ) {
			$blocks = array();
			$this->parse_blocks( $sidebar_node, $blocks );

			$sidebars_info[] = array(
					'caption' => (string) $sidebar_node->attributes()->caption,
					'name' => (string) $sidebar_node->attributes()->name,
					'blocks' => $blocks,
			);
		}
		return $sidebars_info;
	}
	
	/**
	* parses the current $page_node, returns the result to в $pages_info
	* is called recursively for all subpages
	*
	* @param SimpleXMLElement $page_node
	* @param array &$pages_info the information about pages and sub pages of the current node
	*/
	private function parse_page( $page_node, &$pages_info, $parent =  NULL ) {
		$page_attributes = $page_node->attributes();
		$has_sub_pages = isset( $page_node->pages ) 
					&& isset ( $page_node->pages->page );
		$is_posts_page = strtolower($page_attributes->posts_page) == 'true'? true: false;
		$show_in_hmenu = strtolower($page_attributes->showInHmenu) == 'true'? true: false;
		$show_in_vmenu = strtolower($page_attributes->showInVmenu) == 'true'? true: false;
		$description = $page_attributes->description;
		$keywords = $page_attributes->keywords;
		$metatags = $page_attributes->metaTags;
		$content = $is_posts_page 
					? $page_node->layout->struct 
					: $page_node->content;
		$info = array(
			'name' => (string) $page_attributes->name,
			'title' => (string) $page_attributes->title,
			'caption' => (string) $page_attributes->caption,
			'posts_page' => $is_posts_page,
			'path' => (string) $page_attributes->path,
			'content' => (string) $content,
			'styles' => (string) $page_node->styles,
			'has_sub_pages' => (string) $has_sub_pages,
			'parent_path' => (isset($parent) && isset($parent->attributes()->path) ? (string)$parent->attributes()->path : NULL),
			'head' => (string)$page_node->pageHead,
			'show_in_hmenu' => $show_in_hmenu,
			'show_in_vmenu' => $show_in_vmenu,
		);
		if ( isset($description) && !empty($description) && $description != "" ) {
			$info['description'] = "<meta name=\"description\" content=\"$description\">\r\n";
		}
		if ( isset($keywords) && !empty($keywords) && $keywords != "") {
			$info['keywords'] = "<meta name=\"keywords\" content=\"$keywords\">\r\n";
		}
		if ( isset($metatags) && !empty($metatags) && $metatags != "") {
			$info['metatags'] = (string)$metatags . "\r\n";
		}
		
		$pages_info[] = $info;
		
		if ( ! $has_sub_pages ) {
			return;
		}
		
		$sub_pages = $page_node->pages->page;
		foreach ( $sub_pages as $sub_page_node ) {
			$this->parse_page( $sub_page_node, $pages_info, $page_node );
		}
	}
	
	/**
	* parses blocks contained in $blocks_node, returns the result to $blocks_info
	* 
	* @param SimpleXMLElement $blocks_node
	* @param array &$blocks_info information about blocks
	*/
	private function parse_blocks( $blocks_node, &$blocks_info ) {
		if ( ! isset( $blocks_node ) ) {
			return;
		}
		
		foreach ( $this->_block_types as $block_type ) {
			$result = $this->get_blocks_by_type( $block_type, $blocks_node );

			if ( $result ) {
				$blocks_info = array_merge( $blocks_info, $result );
			}
		}
	}
	
	/**
	* gets all $block_type elements from $block_node 
	* 
	* @param string $block_type
	* @param SimpleXMLElement $blocks_node 
	* @return array 
	*/
	private function get_blocks_by_type( $block_type, $blocks_node ) {
		if ( ! isset( $blocks_node->$block_type ) ) {
			return false;
		}

		$result = array();
		$widget_nodes = $blocks_node->$block_type;
		
		foreach ( $widget_nodes as $node ) {
			$info = array();
			$info['type'] = $block_type;
			$info['name'] = (string) $node->attributes()->name;
			$info['title'] = (string) $node->attributes()->title;				
			if ( isset( $node->content ) ) {
				$info['content'] = (string) $node->content;;
			}
			if ( isset($node->pageHead) ) {
				$info['head'] = (string)$node->pageHead;
			}
			$result[] = $info;
		}
		return $result;
	}
}
