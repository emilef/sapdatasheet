<!DOCTYPE html>
<!-- Application component object. -->
<?php
$__WS_ROOT__ = dirname(__FILE__, 4);
$__ROOT__ = dirname(__FILE__, 3);

require_once ($__WS_ROOT__ . '/common-php/library/global.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_db.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_ui.php');
require_once ($__WS_ROOT__ . '/common-php/library/schemaorg.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

if (empty($ObjID)) {
    ABAP_UI_TOOL::Redirect404();
}
$df14l = ABAP_DB_TABLE_HIER::DF14L(strtoupper($ObjID));
if (empty($df14l['FCTR_ID'])) {
    ABAP_UI_TOOL::Redirect404();
}

$df14l_desc = ABAP_DB_TABLE_HIER::DF14T($ObjID);
$hier = ABAP_DB_TABLE_HIER::Hier(ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::BMFR_NAME, $df14l['FCTR_ID']);
$child_bmfr = ABAP_DB_TABLE_HIER::DF14L_Child($df14l['PS_POSID'], $df14l['FCTR_ID']);
$child_tdevc = ABAP_DB_TABLE_HIER::TDEVC_DEVCLASS($df14l['FCTR_ID']);

$GLOBALS['TITLE_TEXT'] = ABAP_UI_TOOL::GetObjectTitle(GLOBAL_ABAP_OTYPE::BMFR_NAME, $df14l['FCTR_ID'], $df14l['PS_POSID']);

$json_ld = new \OrgSchema\Thing();
$json_ld->name = $df14l['PS_POSID'];
$json_ld->alternateName = $df14l_desc;
$json_ld->description = $GLOBALS['TITLE_TEXT'];
$json_ld->image = GLOBAL_ABAP_ICON::getIconURL(GLOBAL_ABAP_ICON::OTYPE_BMFR, TRUE);
$json_ld->url = ABAP_UI_DS_Navigation::GetObjectURL(GLOBAL_ABAP_OTYPE::BMFR_NAME, $df14l['FCTR_ID']);
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE_SAPDS::TITLE ?> </title>
        <meta name="author" content="SAP Datasheet" />
        <meta name="description" content="<?php echo GLOBAL_WEBSITE_SAPDS::META_DESC; ?>" />
        <meta name="keywords" content="SAP,<?php echo GLOBAL_ABAP_OTYPE::BMFR_DESC ?>,<?php echo $df14l['FCTR_ID'] ?>,<?php echo $df14l['PS_POSID'] ?>,<?php echo $df14l_desc ?>" />

        <link rel="stylesheet" type="text/css"  href="/3rdparty/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css"  href="/sapdatasheet.css"/>

        <script type="application/ld+json"><?php echo $json_ld->toJson() ?></script>
    </head>
    <body>

        <!-- Header -->
        <?php require $__ROOT__ . '/include/header.php' ?>

        <!-- Left -->
        <div class="left">
            <h5>&nbsp;</h5>
            <h5>Object Hierarchy</h5>
            <table class="content_obj">
                <tbody>
                    <tr><td class="left_attribute">Software Component</td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() ?>
                            <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($hier->DLVUNIT, $hier->DLVUNIT_T) ?>&nbsp;</td></tr>
                    <tr><td class="left_attribute"> Application Component ID</td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?>
                            <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($hier->FCTR_ID, $hier->POSID, $hier->POSID_T) ?>&nbsp;</td></tr>
                </tbody>
            </table>

            <?php require $__ROOT__ . '/include/abap_relatedlinks.php' ?>
            <h5>&nbsp;</h5>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Content Navigator -->
            <div class="content_navi">
                <a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home page</a> &gt;
                <a href="/abap/">ABAP Object</a> &gt;
                <a href="/abap/bmfr/">ABAP <?php echo GLOBAL_ABAP_OTYPE::BMFR_DESC ?></a> &gt;
                <a href="#"><?php echo $df14l['PS_POSID'] ?></a>
            </div>

            <!-- Content Object -->
            <div class="content_obj_title"><span><?php echo $GLOBALS['TITLE_TEXT'] ?></span></div>
            <div class="content_obj">
                <div>
                    <?php include $__WS_ROOT__ . '/common-php/google/adsense-content-top.html' ?>
                </div>
                <h4> Basic Data </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Application Component    </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?></td>
                            <td class="field"><?php echo $df14l['FCTR_ID'] ?> &nbsp;</td></tr>
                        <tr><td class="content_label"> Application Component ID </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?></td>
                            <td class="field"><?php echo $df14l['PS_POSID'] ?> &nbsp;</td></tr>
                        <tr><td class="content_label"> Short Description        </td>
                            <td>&nbsp;</td>
                            <td class="field"><?php echo $df14l_desc ?> &nbsp;</td></tr>
                        <tr><td class="content_label"> First Release Date       </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4Date() ?></td>
                            <td class="field"><?php echo $df14l['FSTDATE'] ?>&nbsp;</td></tr>
                        <tr><td class="content_label"> First Release            </td>
                            <td>&nbsp;</td>
                            <td class="field"><?php echo $df14l['RELE'] ?>&nbsp;</td></tr>
                    </tbody>
                </table><!-- Basic Data: End -->

                <!-- Application Component Content -->
                <?php if (count($child_bmfr) > 0 || count($child_tdevc) > 0) { ?>
                    <h4> Content </h4>
                    <!-- Contained Application Component -->
                    <?php if (count($child_bmfr) > 0) { ?>
                        <table class="alv">
                            <caption>Contained Application Component</caption>
                            <tr>
                                <th class="alv"> Application Component ID</th>
                                <th class="alv"> Short Description </th>
                                <th class="alv"> Application Component</th></tr>
                            <?php
                            foreach ($child_bmfr as $child_bmfr_item) {
                                $child_bmfr_item_desc = ABAP_DB_TABLE_HIER::DF14T($child_bmfr_item['FCTR_ID'])
                                ?>
                                <tr><td class="alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?>
                                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($child_bmfr_item['FCTR_ID'], $child_bmfr_item['PS_POSID'], $child_bmfr_item_desc) ?></td>
                                    <td class="alv"><?php echo htmlentities($child_bmfr_item_desc) ?>&nbsp;</td>
                                    <td class="alv"><?php echo $child_bmfr_item['FCTR_ID'] ?>&nbsp;</td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?><!-- Contained Application Component: End -->

                    <!-- Contained Packages -->
                    <?php if (count($child_tdevc) > 0) { ?>
                        <table class="alv">
                            <caption>Contained Package</caption>
                            <tr>
                                <th class="alv"> Package </th>
                                <th class="alv"> Short Description </th></tr>
                            <?php
                            foreach ($child_tdevc as $child_tdevc_item) {
                                $child_tdevc_item_desc = ABAP_DB_TABLE_HIER::TDEVCT($child_tdevc_item['DEVCLASS']);
                                ?>
                                <tr><td class="alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?>
                                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($child_tdevc_item['DEVCLASS'], $child_tdevc_item_desc) ?></td>
                                    <td class="alv"><?php echo htmlentities($child_tdevc_item_desc) ?>&nbsp;</td></tr>
                            <?php } ?>
                        </table>
                    <?php } ?><!-- Contained Packages: End -->

                <?php } ?><!-- Application Component Content: End -->

                <h4> Hierarchy </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Software Component      </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() ?></td>
                            <td class="field"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($hier->DLVUNIT, $hier->DLVUNIT_T) ?>&nbsp;</td><td> <?php echo $hier->DLVUNIT_T ?>&nbsp;</td></tr>
                        <tr><td class="content_label"> SAP Release Created in  </td>
                            <td>&nbsp;</td>
                            <td class="field"><?php echo $hier->CRELEASE ?>&nbsp;</td><td>&nbsp;</td></tr>
                    </tbody>
                </table><!-- Hierarchy: End -->

            </div>
        </div><!-- Content: End -->

        <!-- Footer -->
        <?php require $__ROOT__ . '/include/footer.php' ?>

    </body>
</html>
<?php
// Close PDO Database Connection
ABAP_DB_TABLE::close_conn();