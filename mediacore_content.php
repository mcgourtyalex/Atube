<?php

// Populate the widget:
function mediacore_test_content() {

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

        // Echo the header
        echo "<h2>Latest ATube Videos:</h2>";
        // Echo the table to hold 4 videos
        echo '<table style="border: none;">';
        // Video TR
        echo '<tr>';
        for ($i = 0; $i < 4; $i++){
            echo '<td style="border: none; text-align: center;">';
            echo embedify($links[$i]);
            echo '</td>';
        }
        echo '</tr>';
        // Title TR
        echo '<tr>';
        for ($i = 0; $i < 4; $i++){
            echo '<td style="border: none; text-align: center;">';
            // Link with href
            echo '<a href="'.$hrefs[$i].'">';
            // Echo title
            echo '<h5>'.$titles[$i].'</h5>';
            echo '</a>';
            echo '</td>';
        }
        echo '</tr>';
        
        echo '</table>';

        // More
        echo '<a href="https://atube.autodesk.com/media?show=latest"><h4>> More on Atube </h4></a>';
        
    } else {
        // Echo if RSS can't be reached
        echo "Whoops! RSS unavailable.";
    }

}

// Returns formatted video embed
function embedify($link) {
    return '<video preload style="width: 200px; height: 200px; border: 1px solid #BBBBBB;" width="200" height="200" frameborder="0" controls>
    <source src="'.$link.'" type="video/mp4">
    </video> ';
}

// Returns formatted link
function linkify($link) {
    $link = str_replace('?download=1', "", $link);
    return 'https://atube.autodesk.com'.$link;
}



?>