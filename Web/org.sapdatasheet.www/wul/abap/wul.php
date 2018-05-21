<?php
$__ROOT__ = dirname(dirname(dirname(__FILE__)));

require_once($__ROOT__ . '/include/common/global.php');
require_once($__ROOT__ . '/include/common/abap_db.php');
require_once($__ROOT__ . '/include/common/abap_ui.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

// Variables from Dispatcher
//   $dpSrcOType         - Source Object Type, like: TABL
//   $dpMiddle           - Source Object Name + '-' + Source Sub-Object name, like: BKPF-MANDT
//   $dpSrcOName         - Source Object Name, like: BKPF, BSEG
//   $dpSrcSubobj        - Source Sub-Object, like: Table Field Name
//   $dpOType            - Target Object Type
//   $dpPage             - Target Result Page Number, in case there are too many results

$counter_list = ABAPANA_DB_TABLE::WULCOUNTER_List($dpSrcOType, $dpSrcOName, $dpSrcSubobj);
$counter_value = ABAPANA_DB_TABLE::WULCOUNTER($dpSrcOType, $dpSrcOName, $dpOType, $dpSrcSubobj);
$wul_list = ABAP_DB_TABLE_CREF::YWUL($dpSrcOType, $dpSrcOName, $dpSrcSubobj, $dpOType, $dpPage);

$srcObjDesc = ABAP_UI_TOOL::GetObjectDescr($dpSrcOType, $dpSrcOName);
$title_name = ABAP_UI_TOOL::GetObjectTitle($dpSrcOType, $dpSrcOName, $dpMiddle);
$GLOBALS['TITLE_TEXT'] = "Where Used List for " . $title_name;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/abap.css" type="text/css" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE_SAPDS::TITLE ?> </title>
        <meta name="keywords" content="SAP,ABAP,<?php $dpSrcOType ?>,<?php $dpSrcOName ?>,<?php $srcObjDesc ?>" />
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
                <a href="/abap/">ABAP Object</a>
            </div>

            <!-- Content Object -->
            <div class="content_obj_title"><span><?php echo $GLOBALS['TITLE_TEXT'] ?></span></div>
            <div class="content_obj">
                <div>
                    <?php include $__WS_ROOT__ . '/common-php/google/adsense-content-top.html' ?>
                </div>

                <?php
                $wulSrcTitle = 'SAP ABAP ' . GLOBAL_ABAP_OTYPE::getOTypeDesc($dpSrcOType) . ' '
                        . GLOBAL_ABAP_ICON::getIcon4Otype($dpSrcOType)
                        . ABAP_UI_DS_Navigation::GetObjectHyperlink($dpSrcOType, $dpSrcOName, $dpSrcSubobj);
                if (!empty($srcObjDesc)){
                    $wulSrcTitle = $wulSrcTitle . ' (' . $srcObjDesc . ')';
                }
                ?>
                <h4><?php echo $wulSrcTitle ?> is used by</h4>
                <?php
                // print_r($counter_list);

                foreach ($counter_list as $counter) {
                    echo GLOBAL_ABAP_ICON::getIcon4Otype($counter['OBJ_TYPE']) . ' ';
                    echo ABAP_UI_DS_Navigation::GetWulHyperlink($counter, FALSE);
                    echo '&nbsp;';
                }
                ?>

                <h4><?php echo GLOBAL_ABAP_OTYPE::getOTypeDesc($dpOType) ?>
                    <?php echo ABAP_UI_DS_Navigation::GetWulHyperlinks($dpSrcOType, $dpSrcOName, $dpSrcSubobj, $dpOType, $counter_value, FALSE) ?>
                </h4>
                <table class="alv">
                    <tr>
                        <th class="alv"> # </th>
                        <th class="alv"> Object Type </th>
                        <th class="alv"> Object Name </th>
                        <th class="alv"> Object Description </th>
                        <th class="alv"> Package  </th>
                        <th class="alv"> Structure Package </th>
                        <th class="alv"> Software Component </th>
                    </tr>
                    <tr>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_CONST::INDEX_SEQNO_DTEL) ?></th>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument('TROBJTYPE') ?></th>
                        <th class="alv">&nbsp;</th>
                        <th class="alv">&nbsp;</th>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument('DEVCLASS') ?></th>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument('DEVCLASS') ?></th>
                        <th class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument('DLVUNIT') ?></th>
                    </tr>
                    <?php
                    $count = 0;
                    foreach ($wul_list as $wul) {
                        $count++;
                        ?>
                        <tr><td class="alv" style="text-align: right;"><?php echo number_format($count) ?> </td>
                            <td class="alv"><?php echo GLOBAL_ABAP_ICON::getIcon4Otype($wul['OBJ_TYPE']) ?>
                                <?php echo ABAP_UI_DS_Navigation::GetOTypeHyperlink($wul['OBJ_TYPE']) ?>&nbsp;</td>
                            <td class="alv">
                                <?php echo ABAP_UI_DS_Navigation::GetObjectHyperlink($wul['OBJ_TYPE'], $wul['OBJ_NAME'], $wul['SUB_NAME']) ?>
                                <?php if ($wul['OBJ_TYPE'] == GLOBAL_ABAP_OTYPE::FUNC_NAME && strlen($wul['SOURCE']) > 0) { ?>
                                    <br /><code><?php echo $wul['SOURCE'] ?></code>
                                    <?php
                                } else if ($wul['OBJ_TYPE'] == GLOBAL_ABAP_OTYPE::CLAS_NAME && $wul['SUB_TYPE'] == ABAP_DB_TABLE_CREF::YWUL_SUB_TYPE_METH && strlen($wul['SUB_NAME']) > 0) {
                                    $cpdname = ABAP_DB_TABLE_CREF::YSEOPROG_CPDNAME($wul['OBJ_NAME'], $wul['SUB_NAME']);
                                    if (strlen($cpdname) > 0) {
                                        ?>
                                        <br /><code>Method: <?php echo $cpdname ?></code>
                                        <?php
                                    }
                                } else if ($wul['OBJ_TYPE'] == GLOBAL_ABAP_OTYPE::VIEW_NAME && $wul['SUB_TYPE'] == ABAP_DB_TABLE_CREF::YWUL_SUB_TYPE_VIED && strlen($wul['SUB_NAME']) > 0) {
                                    ?>
                                    - <?php echo $wul['SUB_NAME'] ?>
                                <?php } ?>
                            </td>
                            <td class="alv"><?php echo ABAP_UI_TOOL::GetObjectDescr($wul['OBJ_TYPE'], $wul['OBJ_NAME']) ?></td>
                            <td class="alv"><?php echo (GLOBAL_UTIL::IsNotEmpty($wul['APPL_NAME'])) ? GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() : '' ?>
                                <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($wul['APPL_NAME']) ?>&nbsp;</td>
                            <td class="alv"><?php echo (GLOBAL_UTIL::IsNotEmpty($wul['APPL_PACKET'])) ? GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() : '' ?>
                                <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($wul['APPL_PACKET']) ?>&nbsp;</td>
                            <td class="alv"><?php echo (GLOBAL_UTIL::IsNotEmpty($wul['APPL_DLVUNIT'])) ? GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() : '' ?>
                                <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($wul['APPL_DLVUNIT']) ?>&nbsp;</td>
                        </tr>
                        <?php
                    }
                    while ($count <= ABAP_UI_CONST::WUL_ROW_MINIMAL) {
                        $count++;
                        ?>
                        <tr><td colspan="7">&nbsp;</td></tr>
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
