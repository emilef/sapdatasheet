<!-- ABAP FUNC - 436,823 lines -->
<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once (__ROOT__ . '/include/global.php');
require_once (__ROOT__ . '/include/abap_db.php');

$func = ABAP_DB_TABLE_FUNC::TFDIR_Sitemap();
$num_rows = mysqli_num_rows($func);
$file_count = intval(ceil($num_rows / SITEMAP::MAX_URL_COUNT));

for ($i = 1; $i <= $file_count; $i++) {

    ob_start();
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
    echo "\r\n";

    $j = 1;
    while ($row = mysqli_fetch_array($func)) {
        $abapurl_obj = htmlentities(strtolower($row['FUNCNAME']), ENT_QUOTES, "UTF-8");
        if (strlen(trim($abapurl_obj)) > 0) {
            $abapurl = "http://www.sapdatasheet.org/abap/func/" . $abapurl_obj . ".html";

            echo '<url>';
            echo '<loc>' . $abapurl . '</loc>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.6</priority>';
            echo '</url>';
            echo "\r\n";
        }

        $j++;
        if ($j >= SITEMAP::MAX_URL_COUNT) {
            break;
        }
    } // End while

    echo '</urlset>';

    $ob_content = ob_get_contents();
    ob_end_flush();
    $filename = "./abap-func" . $i . ".xml";
    $ob_fp = fopen($filename, "w");
    fwrite($ob_fp, $ob_content);
    fclose($ob_fp);
} // End for