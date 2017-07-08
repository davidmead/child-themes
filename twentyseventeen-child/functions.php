<?php
function my_theme_enqueue_styles() {

    $parent_style = 'twentyseventeen-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

/**
 * Add mf2 h-entry to article.
 */
function my_post_classes( $classes ) {
        $classes = array_diff( $classes, array( 'hentry' ) );

            if ( 'page' !== get_post_type() ) {
                // Adds a class for microformats v2
                $classes[] = 'h-entry';
                // add hentry to the same tag as h-entry
                $classes[] = 'hentry';
            }

        return $classes;
    }

add_filter( 'post_class', 'my_post_classes' );

/**
 * Add mf2 p-author and v-card to article.
 */
if ( ! function_exists( 'twentyseventeen_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function twentyseventeen_posted_on() {

    // Get the author name; wrap it in a link.
    $byline = sprintf(
        /* translators: %s: post author */
        __( 'by %s', 'twentyseventeen' ),
        '<span class="author p-author vcard h-card"><a class="u-url url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
    );

    // Finally, let's write all of this to the page.
    echo '<span class="posted-on">' . twentyseventeen_time_link() . '</span><span class="byline"> ' . $byline . '</span>';
}
endif;



/**
 * Add u-url to link to work with Bridgy webmentions.
 */
if ( ! function_exists( 'twentyseventeen_time_link' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 */
function twentyseventeen_time_link() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		get_the_date( DATE_W3C ),
		get_the_date(),
		get_the_modified_date( DATE_W3C ),
		get_the_modified_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
	return sprintf(
		/* translators: %s: post date */
		__( '<span class="screen-reader-text">Posted on</span> %s', 'twentyseventeen' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="u-url">' . $time_string . '</a>'
	);
}
endif;









?>