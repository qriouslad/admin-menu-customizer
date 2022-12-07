<?php

namespace AMCUST\Classes;

/**
 * Class that provides common methods used throughout the plugin
 *
 * @since 2.5.0
 */
class Common_Methods {

    /**
     * Remove html tags and content inside the tags from a string
     *
     * @since 1.1.0
     */
    public function strip_html_tags_and_content( $string ) {

        // Strip HTML tags and content inside them. Ref: https://stackoverflow.com/a/39320168
        $string = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $string);

        // Strip any remaining HTML or PHP tags
        $string = strip_tags( $string );

        return $string;

    }

}