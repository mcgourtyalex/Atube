<?php

// Populate the widget:
function mediacore_test_content() {

    $dom = new DOMDocument();
    $xml = $dom->load("http://atube/latest.xml");
    $links = [];
    $titles = [];
    $hrefs = [];

    $spot = 0;
    if ($xml) {

        $items = $dom->getElementsByTagName('item');
        foreach($items as $item) {
            
            // Pull the titles out
            $title = $item->getElementsByTagName('title')->item(0)->nodeValue;
            $titles[$spot] = $title;

            // Pull the mp4 links out
            $link = $item->getElementsByTagNameNS('*','content')->item(0)->getAttribute('url');
            $link = str_replace('?download=1', "", $link);
            $link = 'http://atube'.$link;
            $links[$spot] = $link;

            // Pull the hrefs out
            $href = $item->getElementsByTagName('link')->item(0)->nodeValue;
            $hrefs[$spot] = $href;

            // Move the spot
            $spot++;
        }


        echo "<h2>Latest ATube Videos:</h2>";
        echo '<table style="border: none;">';
        echo '<tr>';
        for ($i = 0; $i < 4; $i++){
            echo '<td style="border: none; text-align: center;">';
            embedify($links[$i]);
            echo '</td>';
        }
        echo '</tr>';
        echo '<tr>';
        for ($i = 0; $i < 4; $i++){
            echo '<td style="border: none; text-align: center;">';
            echo '<a href="'.$hrefs[$i].'">';
            echo '<h5>'.$titles[$i].'</h5>';
            echo '</a>';
            echo '</td>';
        }
        echo '</tr>';
        
        echo '</table>';

        echo '<a href="http://atube/media?show=latest"><h4>> More on Atube </h4></a>';
        
    } else {
        echo "Whoops! RSS unavailable.";
    }

}

function embedify($link) {
    echo '<video style="width: 200px; height: 200px; border: 1px solid #BBBBBB;" width="200" height="200" frameborder="0" controls>
    <source src="'.$link.'" type="video/mp4">
    </video> ';
}


?>