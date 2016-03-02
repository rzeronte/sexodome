<?php
$iframe = $scene->iframe;
$pattern = "/width=\"[0-9]*\"/";
$iframe = preg_replace($pattern, "width='100%'", $iframe);
$pattern2 = "/width=\"[0-9]*+px\"/";
$pattern = "/width='[0-9]*'/";
$iframe = preg_replace($pattern, "width='100%'", $iframe);
$pattern2 = "/width='[0-9]*+px'/";

$iframe = preg_replace($pattern2, "width='100%'", $iframe);
?>
<?php echo $iframe;?>
