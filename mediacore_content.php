<?php

// Populate the widget:
function mediacore_test_content($atts, $is_widg) {

    $atts = shortcode_atts( array(
        'number' => '5',
    ), $atts );

    $number = $atts['number'];

    // Create new document and load in the ATube RSS
    $dom = new DOMDocument();
    $xml = $dom->load("http://atube/latest.xml");

    // Arrays for info on each RSS elem
    // Links to mp4
    $links = [];
    // Title of videos
    $titles = [];
    // Links to video page
    $hrefs = [];

    // incrementer
    $spot = 0;

    // If the xml dom object is accessible
    if ($xml) {

        // get each item in the XML file and iterate
        $items = $dom->getElementsByTagName('item');
        $num_items = $items->length;
        if ($num_items < $number) {
            $number = $num_items;
        }

        foreach($items as $item) {
            
            // Pull the titles out
            $title = $item->getElementsByTagName('title')->item(0)->nodeValue;
            $titles[$spot] = $title;

            // Pull the mp4 links out (namespace complication)
            $link = $item->getElementsByTagNameNS('*','content')->item(0)->getAttribute('url');
            $links[$spot] = linkify($link);

            // Pull the hrefs out
            $href = $item->getElementsByTagName('link')->item(0)->nodeValue;
            $hrefs[$spot] = $href;

            // Move the spot
            $spot++;
        }

        ob_start();

        echo '<div style="overflow-x: scroll; overflow-y: hidden">';
        // Echo the table to hold 4 videos
        echo '<table style="border: none; width: inherit; padding: 0px; margin: 0px;">';
        // Video TR
        echo '<tr>';
        for ($i = 0; $i < $number; $i++){
            echo '<td style="border: none; text-align: center; padding: 0px; padding-right: 10px;">';
            echo embedify($hrefs[$i], 200, 200);
            echo '</td>';
        }
        echo '</tr>';
        // Title TR
        echo '<tr>';
        for ($i = 0; $i < $number; $i++){
            echo '<td style="border: none; text-align: center; width: 200px; min-width: 200px; vertical-align: center; padding: 0px;">';
            // Link with href
            echo '<a href="'.$hrefs[$i].'">';
            // Echo title
            echo $titles[$i];
            echo '</a>';
            echo '</td>';
        }
        echo '</tr>';
        
        echo '</table>';
        echo '</div>';

        // More
        echo '<a href="https://atube.autodesk.com/media?show=latest"><h4>> More on Atube </h4></a>';
        
    } else {
        // Echo if RSS can't be reached
        echo '<a href="https://atube.autodesk.com/media?show=latest"><h4>> Latest ATube Videos </h4></a>';
    }

    $output_string = ob_get_contents();;
    ob_end_clean();

    if ($is_widg) {
        echo $output_string;
    } else {
        return $output_string;
    }
}

// Echoes a single video to the page
function mediacore_single_content($atts) {

    $atts = shortcode_atts( array(
        'video' => '1',
        'width' => 200,
        'height' => 200,
    ), $atts );

    $width = $atts['width'];
    $height = $atts['height'];

    $vid_num = $atts['video'];
    if ($vid_num > 0) {
        $dom = new DOMDocument();
        $xml = $dom->load("http://atube/latest.xml"); 

        if ($xml) {
            ob_start();
            $items = $dom->getElementsByTagName('item');
            $item = $items->item($vid_num-1);
            $href = $item->getElementsByTagName('link')->item(0)->nodeValue;
            $link = $item->getElementsByTagNameNS('*','content')->item(0)->getAttribute('url');
            echo embedify($href, $width, $height);
 
            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }
    }
}

// Echoes a linked title to the page
function mediacore_single_content_name($atts) {

    $atts = shortcode_atts( array(
        'video' => '1',
    ), $atts );

    $vid_num = $atts['video'];
    if ($vid_num > 0) {
        $dom = new DOMDocument();
        $xml = $dom->load("http://atube/latest.xml"); 

        if ($xml) {
            ob_start();
            $items = $dom->getElementsByTagName('item');
            $item = $items->item($vid_num-1);
            $title = $item->getElementsByTagName('title')->item(0)->nodeValue;
            $href = $item->getElementsByTagName('link')->item(0)->nodeValue;
            echo '<a href="'.$href.'" class="atube_vid_link">';
            echo $title;
            echo '</a>';
 
            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }
    }
}


// ----------------- HELPER FUNCTIONS ------------------------

// Returns formatted video embed
function embedify($href, $width, $height) {
        $str = '<iframe src="'.$href.'/embed_player" style="width: ';
        $str = $str.$width.'px; height:'; 
        $str = $str.$height.'px; border: 1px solid #BBBBBB;"></iframe>';
        return $str;
}

// Returns formatted link
function linkify($link) {
    $link = str_replace('?download=1', "", $link);
    return 'https://atube.autodesk.com'.$link;
}

function mp4ify($link) {
    $link = str_replace('.swf', '.mp4', $link);
    return $link;
}

?>