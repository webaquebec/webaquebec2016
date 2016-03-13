<?php
require "lib/twitteroauth/autoload.php";
require "lib/instagram/src/InstagramException.php";
require "lib/instagram/src/Instagram.php";
require "twitter_conf.php";
use Abraham\TwitterOAuth\TwitterOAuth;
use MetzWeb\Instagram\Instagram;

/**
 * Add the feed item post type
 */
add_action( 'init', 'waq2016_add_feed_item_post_type' );
function waq2016_add_feed_item_post_type() {
 register_post_type( 'feed_item',
     array(
         'labels' => array(
             'name' => __( 'Feed Item', 'waq2016' ),
             'singular_name' => __( 'Feed Item', 'waq2016' )
         ),
         'rewrite' => array (
             'slug'                => 'feed_item',
             'with_front'          => false,
             'pages'               => false,
             'feeds'               => false,
         ),
         'menu_icon'=>'dashicons-format-status',
         'public' => true,
         'has_archive' => true,
         'supports' => array('title')
     )
 );
}

function waq2016_create_feed_items_twitter(){
    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);
    $content = $connection->get("account/verify_credentials");
    $query = $connection->get("search/tweets", ["q" => '%23waq16%20OR%20to%3Awebaquebec%20OR%20%40webaquebec%20-filter:retweets']);

    foreach ($query->statuses as $key => $status) {
        if (!isset($status->retweeted_status)) {
            $title_id = 'twitter_'.$status->id_str;

            $custom_tweet = array(
                'type' => 'twitter',
                'text' => twitter_format(json_decode(json_encode($status), true)),
                'from' => array(
                    'name' => $status->user->name,
                    'nick' => $status->user->screen_name,
                    'pic' => str_replace('_normal','',$status->user->profile_image_url)
                )
            );

            if(isset($status->entities->media) && count($status->entities->media > 0)){
                $custom_tweet['images'] = array();
                foreach ($status->entities->media as $media) {
                    $media = array(
                        'thumbnail' => $media->media_url,
                        'src' => $media->media_url.':orig'
                    );
                    $custom_tweet['images'][] = $media;
                }
            }

            $postArgs = array(
                'post_status' => 'publish',
                'post_title' => $title_id,
                'post_type' => 'feed_item',
                'post_date' => date("Y-m-d H:i:s",strtotime($status->created_at))
            );

            if ($id = waq2016_feed_item_exists($title_id)) {
                //Post arguments generaux
                $postArgs['ID'] = $id;
            }

            if (isset($postArgs['ID'])) {
            } else {
                $id = wp_insert_post($postArgs);
                add_post_meta($id, 'feed_details', $custom_tweet, true);
            }
        }
    }
}

function waq2016_feed_item_exists($identifier) {
    global $wpdb;
    $post = $wpdb->get_row("SELECT ID FROM " . $wpdb->posts . " WHERE post_type IN ('feed_item') AND post_title='" . $identifier . "'");
    return  ($post) ? $post->ID : false;
}

function twitter_format($tweet) {
    $text = $tweet['text'];
    $entities = isset($tweet['entities']) ? $tweet['entities'] : array();

    $replacements = array();
    if (isset($entities['hashtags'])) {
        foreach ($entities['hashtags'] as $hashtag) {
            list ($start, $end) = $hashtag['indices'];
            $replacements[$start] = array($start, $end,
                "<a href=\"https://twitter.com/search?q={$hashtag['text']}\">#{$hashtag['text']}</a>");
        }
    }
    if (isset($entities['urls'])) {
        foreach ($entities['urls'] as $url) {
            list ($start, $end) = $url['indices'];
            // you can also use $url['expanded_url'] in place of $url['url']
            $replacements[$start] = array($start, $end,
                "<a href=\"{$url['url']}\">{$url['display_url']}</a>");
        }
    }
    if (isset($entities['user_mentions'])) {
        foreach ($entities['user_mentions'] as $mention) {
            list ($start, $end) = $mention['indices'];
            $replacements[$start] = array($start, $end,
                "<a href=\"https://twitter.com/{$mention['screen_name']}\">@{$mention['screen_name']}</a>");
        }
    }
    if (isset($entities['media'])) {
        foreach ($entities['media'] as $media) {
            list ($start, $end) = $media['indices'];
            $replacements[$start] = array($start, $end,
                "<a href=\"{$media['url']}\">{$media['display_url']}</a>");
        }
    }

    // sort in reverse order by start location
    krsort($replacements);

    foreach ($replacements as $replace_data) {
        list ($start, $end, $replace_text) = $replace_data;
        $text = mb_substr($text, 0, $start, 'UTF-8').$replace_text.mb_substr($text, $end, NULL, 'UTF-8');
    }
    return "<p>$text</p>";
}
