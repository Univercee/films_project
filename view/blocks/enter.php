<?php 
    ob_start();
    View::viewEnterForm(true);
    $content = ob_get_clean();
    include 'view/layout/emptyLayout.php' 
?>