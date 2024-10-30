<?php
/*
Plugin Name: MJ News plugin
Plugin URI: http://mecontact.wordpress.com/mj-news/
Description: wordrpess news plugin to create/update/delete news. It also provide different category section to create category for news also provide you to enter tag for news and also the custom fields like in the post. it has very simple coding strucutre which help to change the design as per client requirement
Author: anil kumar
Version: 1.0
Author URI: http://about.me/anilDhiman
*/



add_shortcode('mjnews', 'mj_news');
add_action('init', 'mj_news_type_creation');


add_action("wp_enqueue_scripts", "mjnewsStyle"); 
function mjnewsStyle() {
   wp_register_style( 'mjnewform', plugins_url( '/css/mj.css', __FILE__ ),'all' );  
   wp_enqueue_style( 'mjnewform' );  
}
 
 

function mj_news_type_creation() {
   register_post_type('news', array(	'label' => 'News','description' => 'News section','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => true,'rewrite' => array('slug' => 'News'),'query_var' => true,'has_archive' => true,'exclude_from_search' => false,'menu_position' => '','supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes',),'labels' => array (
  'name' => 'News',
  'singular_name' => 'New',
  'menu_name' => 'News',
  'add_new' => 'Add News',
  'add_new_item' => 'Add New News',
  'edit' => 'Edit',
  'edit_item' => 'Edit News',
  'new_item' => 'New News',
  'view' => 'View News',
  'view_item' => 'View News',
  'search_items' => 'Search News',
  'not_found' => 'No News Found',
  'not_found_in_trash' => 'No News Found in Trash',
  'parent' => 'Parent News',
),) );
}
// add taxonomies to categorize different custom post types
add_action( 'init', 'mj_taxonomies', 0);


function mj_taxonomies() {
	register_taxonomy('news',array (
  0 => 'news',
),array( 'hierarchical' => true, 'label' => 'News category','show_ui' => true,'query_var' => true,'rewrite' => array('slug' => 'NewsCat'),'singular_label' => 'News') );
}

function mj_news(){ 
?>

<div id="mjnew">
	<?php	
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args=array(
    'post_type'         => 'news',
    'post_status'        => 'publish',
    //'orderby'                    => 'menu_order',
    'posts_per_page'         =>4,
    'caller_get_posts'            =>1,
    'paged'            =>$paged,
    );
	query_posts($args);
	global $post;
	if ( have_posts() ) : while ( have_posts() ) : the_post(); 
	
	?>
		<div class="mj-post">
			<div class="mj-post-inner">
				<h3>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h3>
				<div><?php echo substr(get_the_content(),0,200); ?></div>
				<div><a href="<?php echo get_permalink();?>">Read More</a></div>
			</div>
			<div class="mjfooter"><span class="fl">Posted by : <?php echo the_author();?></span><span class="fr">Posted on : <?php echo date('d F, Y',strtotime($post->post_date));?></span>
			<div class="clr"></div>
			</div>
		</div>
	<?php 
	
	endwhile; 
	endif; ?>

<?php 



global $wp_query;  
$total_pages = $wp_query->max_num_pages;  
if ($total_pages > 1){  
  $current_page = max(1, get_query_var('paged'));  
  echo '<div class="page_nav">';  
  echo paginate_links(array(  
      'base' => get_pagenum_link(1) . '%_%',  
      'format' => '/page/%#%',  
      'current' => $current_page,  
      'total' => $total_pages,  
      'prev_text' => 'Prev',  
      'next_text' => 'Next'  
    ));  
  echo '</div>';  
}
echo "</div>";
	wp_reset_query();
}

?>
