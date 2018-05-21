<?php
$__WS_ROOT__ = dirname(__FILE__, 4);
$__ROOT__ = dirname(__FILE__, 3);

require_once ($__WS_ROOT__ . '/common-php/library/global.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_db.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_ui.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

$GLOBALS['TITLE_TEXT'] = "SAP ABAP " . GLOBAL_ABAP_OTYPE::CVERS_DESC;
?>
<!DOCTYPE html>
<!-- Software component index. -->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/abap.css" type="text/css" />
        <title>SAP ABAP <?php echo GLOBAL_ABAP_OTYPE::CVERS_DESC ?> <?php echo GLOBAL_WEBSITE_SAPDS::TITLE ?> </title>
        <meta name="keywords" content="SAP,ABAP,<?php echo GLOBAL_ABAP_OTYPE::CVERS_DESC ?>" />
        <meta name="description" content="<?php echo GLOBAL_WEBSITE_SAPDS::META_DESC ?>" />
        <meta name="author" content="SAP Datasheet" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>

        <!-- Header -->
        <?php require $__ROOT__ . '/include/header.php' ?>

        <!-- Left -->
        <?php require $__ROOT__ . '/include/abap_index_left.php' ?>

        <!-- Content -->
        <div class="content">
            <!-- Content Navigator -->
            <div class="content_navi">
                <a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home page</a> &gt; 
                <a href="/abap/">ABAP Object</a> &gt; 
                <a href="/abap/cvers/"><?php echo GLOBAL_ABAP_OTYPE::CVERS_DESC ?></a> 
            </div>

            <!-- Content Object -->
            <div class="content_obj_title"><span>SAP ABAP <?php echo GLOBAL_ABAP_OTYPE::CVERS_DESC ?></span></div>
            <div class="content_obj">
                <div>
                    <?php include $__WS_ROOT__ . '/common-php/google/adsense-content-top.html' ?>
                </div>

                <h4> <?php echo GLOBAL_ABAP_OTYPE::CVERS_DESC ?> </h4>
                <table class="alv">
                    <tr>
                        <th class="alv"> # </th>
                        <th class="alv"> Software Component </th>
                        <th class="alv"> Short Description </th>
                        <th class="alv"> Component Type </th>
                        <th class="alv"> Component Type Text </th>
                    </tr>
                    <tr>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_CONST::INDEX_SEQNO_DTEL, '?') ?></th>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_TABLE_HIER::CVERS_COMPONENT_DTEL, '?') ?></th>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_TABLE_HIER::CVERS_REF_DESC_TEXT_DTEL, '?') ?></th>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_TABLE_HIER::CVERS_COMP_TYPE_DTEL, '?') ?></th>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_TABLE_DOMA::DD07T_DDTEXT_DTEL, '?') ?></th>
                    </tr>
                    <?php
                    $table = ABAP_DB_TABLE_HIER::CVERS_List();
                    $count = 0;
                    foreach ($table as $row) {
                        $count++;
                        $cvers_component = $row['COMPONENT'];
                        $cvers_desc = ABAP_DB_TABLE_HIER::CVERS_REF($row['COMPONENT']);
                        $cvers_comp_type = $row['COMP_TYPE'];
                        $cvers_comp_type_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_TADIR_COMP_TYPE, $row['COMP_TYPE']);
                        ?>
                        <tr><td class="alv" style="text-align: right;"><?php echo number_format($count) ?> </td>
                            <td class="alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() ?>
                                <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($cvers_component, $cvers_desc) ?></td>
                            <td class="alv"><?php echo htmlentities($cvers_desc) ?></td>
                            <td class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_TADIR_COMP_TYPE, $cvers_comp_type, $cvers_comp_type_desc) ?></td>
                            <td class="alv"><?php echo htmlentities($cvers_comp_type_desc) ?></td></tr>
                        <?php } ?>
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
