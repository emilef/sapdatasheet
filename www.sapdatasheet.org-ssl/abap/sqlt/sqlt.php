<!DOCTYPE html>
<!-- DDIC Cluster/Pool table object. -->
<?php
$__ROOT__ = dirname(dirname(dirname(__FILE__)));
require_once ($__ROOT__ . '/include/common/global.php');
require_once ($__ROOT__ . '/include/common/abap_db.php');
require_once ($__ROOT__ . '/include/common/abap_ui.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

if (!isset($ObjID)) {
    $ObjID = filter_input(INPUT_GET, 'id');
}

if (empty($ObjID)) {
    ABAP_UI_TOOL::Redirect404();
}
$sqlt = ABAP_DB_TABLE_TABL::DD06L(strtoupper($ObjID));
if (empty($sqlt['SQLTAB'])) {
    ABAP_UI_TOOL::Redirect404();
}
$sqlt_desc = ABAP_DB_TABLE_TABL::DD06T($sqlt['SQLTAB']);
$sqlt_sqlcalss_desc = ABAP_UI_TOOL::GetSqltDesc($sqlt['SQLCLASS']);
$dd16s = ABAP_DB_TABLE_TABL::DD16S($sqlt['SQLTAB']);

$wul_counter_list = ABAPANA_DB_TABLE::WULCOUNTER_List(GLOBAL_ABAP_OTYPE::SQLT_NAME, $sqlt['SQLTAB']);
$hier = ABAP_DB_TABLE_HIER::Hier(ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::SQLT_NAME, $sqlt['SQLTAB']);
$GLOBALS['TITLE_TEXT'] = ABAP_UI_TOOL::GetObjectTitle(GLOBAL_ABAP_OTYPE::SQLT_NAME, $sqlt['SQLTAB']);
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/abap.css" type="text/css" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE_SAPDS::TITLE ?> </title>
        <meta name="keywords" content="SAP,<?php echo GLOBAL_ABAP_OTYPE::SQLT_DESC ?>,<?php echo $sqlt_sqlcalss_desc ?>,<?php echo $sqlt['SQLTAB'] ?>,<?php echo $sqlt_desc ?>" />
        <meta name="description" content="<?php echo GLOBAL_WEBSITE_SAPDS::META_DESC; ?>" />
        <meta name="author" content="SAP Datasheet" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
                    <tr><td class="left_attribute"> Package </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?>
                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($hier->DEVCLASS, $hier->DEVCLASS_T) ?></td></tr>
                    <tr><td class="left_attribute"> Object type </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeSQLT() ?>
                            <a href="/abap/sqlt/"><?php echo $sqlt_sqlcalss_desc; ?></a></td></tr>
                    <tr><td class="left_attribute"> Object name </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeSQLT() ?>
                            <a href="#" title="<?php echo $sqlt_desc ?>"><?php echo $sqlt['SQLTAB'] ?></a> </td></tr>
                </tbody>
            </table>

            <?php require $__ROOT__ . '/include/abap_oname_wul.php' ?>
            <?php require $__ROOT__ . '/include/abap_relatedlinks.php' ?>
            <h5>&nbsp;</h5>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Content Navigator -->
            <div class="content_navi">
                <a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home page</a> &gt; 
                <a href="/abap/">ABAP Object</a> &gt; 
                <a href="/abap/sqlt/">ABAP <?php echo $sqlt_sqlcalss_desc ?></a> &gt; 
                <a href="#"><?php echo $sqlt['SQLTAB'] ?></a>
            </div>

            <!-- Content Object -->
            <div class="content_obj_title"><span><?php echo $GLOBALS['TITLE_TEXT'] ?></span></div>
            <div class="content_obj">
                <div>
                    <?php include $__ROOT__ . '/include/google/adsense-content-top.html' ?>
                </div>

                <?php require $__ROOT__ . '/include/abap_oname_hier.php' ?>

                <h4> Basic Data </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Category              </td><td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_TABL::DD06L_SQLCLASS_DOMAIN, $sqlt['SQLCLASS'], $sqlt_sqlcalss_desc) ?> &nbsp;</td><td><?php echo $sqlt_sqlcalss_desc ?>&nbsp;</td></tr>
                        <tr><td class="content_label"> <?php echo $sqlt_sqlcalss_desc; ?> </td><td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Sqlt($sqlt['SQLTAB'], $sqlt_desc) ?> &nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td class="content_label"> Short Description     </td><td class="field"> <?php echo htmlentities($sqlt_desc) ?> &nbsp;</td><td>&nbsp;</td></tr>
                    </tbody>
                </table>

                <!-- Components -->
                <h4> Components </h4>
                <table class="alv">
                    <thead>
                        <tr><th class="alv">#</th>
                            <th class="alv">Field name</th>
                            <th class="alv">Key</th>
                            <th class="alv">Data type</th>
                            <th class="alv">Length</th>
                            <th class="alv">Internal type</th>
                            <th class="alv">Internal length</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach ($dd16s as $dd16s_item) { 
                            $count++;
                            ?>
                            <tr><td class="alv" style="text-align: right;"><?php echo number_format($count) ?> </td>
                                <td class="alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDTF() ?>
                                    <?php echo $dd16s_item['FIELDNAME'] ?></td>
                                <td class="alv"><?php echo ABAP_UI_TOOL::GetCheckBox($dd16s_item['FIELDNAME'], $dd16s_item['KEYFLAG']) ?></td>
                                <td class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_DATATYPE, $dd16s_item['DATATYPE'], '') ?></td>
                                <td class="alv"><?php echo intval($dd16s_item['LENG']) ?></td>
                                <td class="alv"><?php echo $dd16s_item['INTTYPE'] ?></td>
                                <td class="alv"><?php echo intval($dd16s_item['INTLEN']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <!-- Contained table -->
                <h4> Contained table </h4>
                <table class="alv">
                    <thead>
                        <tr><th class="alv">Table name</th>
                            <th class="alv">Short description</th>
                            <th class="alv">Date of Last Change</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $dd02l_sqlt = ABAP_DB_TABLE_TABL::DD02L_SQLTAB($sqlt['SQLTAB']);
                        foreach ($dd02l_sqlt as $dd02l_sqlt_item) {
                            $dd02l_sqlt_item_desc = ABAP_DB_TABLE_TABL::DD02T($dd02l_sqlt_item['TABNAME']);
                            ?>
                            <tr>
                                <td class="alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeTABL() ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tabl($dd02l_sqlt_item['TABNAME'], $dd02l_sqlt_item_desc) ?></td>
                                <td class="alv"><?php echo htmlentities($dd02l_sqlt_item_desc) ?></td>
                                <td class="alv"><?php echo $dd02l_sqlt_item['AS4DATE'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h4> History </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Last changed by/on      </td><td class="field"><?php echo $sqlt['AS4USER'] ?>&nbsp;</td><td> <?php echo $sqlt['AS4DATE'] ?>&nbsp;</td></tr>
                        <tr><td class="content_label"> SAP Release Created in  </td><td class="field"><?php echo $hier->CRELEASE ?>&nbsp;</td><td>&nbsp;</td></tr>
                    </tbody>
                </table>

            </div>
        </div><!-- Content: End -->

        <!-- Footer -->
        <?php require $__ROOT__ . '/include/footer.php' ?>

    </body>
</html>
<?php
// Close PDO Database Connection
ABAP_DB_TABLE::close_conn();