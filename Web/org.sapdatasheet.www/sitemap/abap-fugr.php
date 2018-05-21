<?php
$__WS_ROOT__ = dirname(__FILE__, 3);

require_once ($__WS_ROOT__ . '/common-php/library/global.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_db.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_ui.php');
require_once ($__WS_ROOT__ . '/common-php/library/sitemap.php');

$list = ABAP_DB_TABLE_HIER::TADIR_FUGR_Sitemap();
$column_name = 'OBJ_NAME';

$fname_prefix = 'abap-fugr';
$i = 1;
$j = 1;
foreach ($list as $row) {
    if ($j == 1) {
        SitemapStartOB();
    }

    if (strlen(trim($row[$column_name])) > 0) {
        $prog = ABAP_DB_TABLE_PROG::GET_PROG_FUGR($row[$column_name]);
        $prog_meta = ABAP_DB_TABLE_PROG::YREPOSRCMETA(strtoupper($prog));
        if (!empty($prog_meta['PROGNAME'])) {
            $abapurl = GLOBAL_WEBSITE::URLPREFIX_SAPDS_ORG . "/abap/prog/" . strtolower($prog) . ".html";
            SitemapEchoUrl($abapurl);
        }
    }

    // Check if the Sitemap is full
    $j++;
    if ($j >= SITEMAP::MAX_URL_COUNT) {
        SitemapEndOB($fname_prefix, $i);
        $i++;
        $j = 1;
    }
} // End foreach

if ($j > 1) {
    SitemapEndOB($fname_prefix, $i);
}
