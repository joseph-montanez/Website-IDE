<?php
$ctx = stream_context_create(array( 
    'http' => array( 
        'timeout' => 1 
        ) 
    ) 
); 
file_get_contents("http://example.com/", 0, $ctx); 

?>
