<?php
/*
Plugin Name: Shifter GitHub hosting plugin sample
Plugin URI: https://github.com/getshifter/shifter-github-hosting-plugin-sample/
Description: Shifter GitHub hosting plugin sample
Author: Shifter Team
Version: {release version}
Author URI: https://getshifter.io/
*/

add_action( 'admin_notices', function() {
    // get Shifter News
    $transient_key = 'shifter-news-posts';
    if ( false === ( $posts = get_transient( $transient_key ) ) ) {
        $url  = 'https://www.getshifter.io/feed/';
        $feed = fetch_feed( $url );

        if ( is_wp_error( $feed ) ) {
            return;
        }

        $items = $feed->get_items( 0, 10 );
        $posts = [];
        foreach ( $items as $item ) {
            $posts[] = sprintf(
                '<a href="%s" title="%s">%s</a>',
                esc_url_raw( $item->get_permalink() ),
                esc_attr( $item->get_title() ),
                $item->get_title()
            );
        }

        if ( empty( $posts ) ) {
            return;
        }

        set_transient( $transient_key, $posts, HOUR_IN_SECONDS );
    }

    if ( empty( $posts ) ) {
        return;
    }

    $shifter_news = $posts[ mt_rand( 0, count( $posts ) - 1 ) ];

    printf(
        '<p id="shifter"><span dir="ltr" lang="en">Shifter News: %s</span></p>',
        $shifter_news
    );
});

add_action( 'admin_head', function() {
    echo "
	<style type='text/css'>
	#shifter {
		float: right;
		padding: 5px 10px;
		margin: 0;
		font-size: 12px;
		line-height: 1.6666;
	}
	.rtl #shifter {
		float: left;
	}
	.block-editor-page #shifter {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#shifter,
		.rtl #shifter {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
});
