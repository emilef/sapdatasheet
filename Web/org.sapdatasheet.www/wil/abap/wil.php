<?php
$__WS_ROOT__ = dirname(__FILE__, 4);
$__ROOT__ = dirname(__FILE__, 3);

require_once($__WS_ROOT__ . '/common-php/library/global.php');
require_once($__WS_ROOT__ . '/common-php/library/abap_db.php');
require_once($__WS_ROOT__ . '/common-php/library/abap_ui.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

// Variables from Dispatcher
//   $dpOType            - Source Object Type, like: TABL
//   $dpOName            - Source Object Name, like: BKPF, BSEG
//   $dpSrcOType         - Target Object Type
//   $dpPage             - Target Result Page Number, in case there are too many results

$counter_list = ABAPANA_DB_TABLE::WILCOUNTER_List($dpOType, $dpOName);
$counter_value = ABAPANA_DB_TABLE::WILCOUNTER($dpOType, $dpOName, $dpSrcOType);
$wil_list = ABAP_DB_TABLE_CREF::YWIL($dpOType, $dpOName, $dpSrcOType, $dpPage);

$objDesc = ABAP_UI_TOOL::GetObjectDescr($dpOType, $dpOName);
$GLOBALS['TITLE_TEXT'] =  GLOBAL_ABAP_OTYPE::getOTypeDesc($dpSrcOType) . " list used by " . ABAP_UI_TOOL::GetObjectTitle($dpOType, $dpOName);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE::SAPDS_ORG_TITLE ?> </title>
        <meta name="author" content="SAP Datasheet" />
        <meta name="description" content="<?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE::SAPDS_ORG_TITLE ?>" />
        <meta name="keywords" content="SAP,ABAP,<?php echo GLOBAL_ABAP_OTYPE::getOTypeDesc($dpOType) ?>,<?php echo $dpOName ?>,<?php echo $objDesc ?>" />

        <link rel="stylesheet" type="text/css"  href="/3rdparty/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css"  href="/sapdatasheet.css"/>
    </head>
    <body>
        <!-- Header -->
        <?php require $__ROOT__ . '/include/header.php' ?>

        <div class="container-fluid">
            <div class="row">
                <div  class="col-xl-2 col-lg-2 col-md-3  col-sm-3    bd-sidebar bg-light">
                    <!-- Left Side bar -->
                    <?php require $__ROOT__ . '/include/abap_index_left.php' ?>
                </div>

                <main class="col-xl-8 col-lg-8 col-md-6  col-sm-9    col-12 bd-content" role="main">
                    <nav class="pt-3" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home</a></li>
                            <li class="breadcrumb-item active"><a href="/abap/">ABAP Object Types</a></li>
                        </ol>
                    </nav>

                    <div class="card shadow">
                        <div class="card-header sapds-card-header"><?php echo $GLOBALS['TITLE_TEXT'] ?></div>
                        <div class="card-body table-responsive sapds-card-body">
                            <div class="align-content-start"><?php include $__WS_ROOT__ . '/common-php/google/adsense-content-top.html' ?></div>
                            <?php require $__ROOT__ . '/include/abap_desc_language.php' ?>

                            <?php
                            $wilTitle = 'SAP ABAP ' . GLOBAL_ABAP_OTYPE::getOTypeDesc($dpOType) . ' '
                                    . GLOBAL_ABAP_ICON::getIcon4Otype($dpOType) . ' '
                                    . ABAP_UI_DS_Navigation::GetObjectHyperlink($dpOType, $dpOName);
                            if (!empty($objDesc)) {
                                $wilTitle = $wilTitle . ' (' . $objDesc . ')';
                            }
                            ?>
                            <h5><?php echo $wilTitle ?> is using</h5>
                            <ul class="nav nav-pills">
                                <?php foreach ($counter_list as $counter) { ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo ($dpSrcOType == $counter['SRC_OBJ_TYPE']) ? 'active' : '' ?>"
                                       href="<?php echo ABAP_UI_DS_Navigation::GetWilPath($counter) ?>">
                                        <?php echo GLOBAL_ABAP_ICON::getIcon4Otype($counter['SRC_OBJ_TYPE']) ?>
                                        <?php echo ABAP_UI_DS_Navigation::GetWilLabel($counter) ?></a>
                                </li>
                                <?php } ?>
                            </ul>

                            <h5><?php echo ABAP_UI_DS_Navigation::GetWilHyperlinks($dpOType, $dpOName, $dpSrcOType, $counter_value, FALSE) ?> </h5>
                            <table class="table table-sm">
                                <tr>
                                    <th class="sapds-alv"> # </th>
                                    <th class="sapds-alv"> Object Type </th>
                                    <th class="sapds-alv"> Object Name </th>
                                    <th class="sapds-alv"> Object Description </th>
                                    <th class="sapds-alv"> Note </th>
                                </tr>
                                <tr>
                                    <th class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_CONST::INDEX_SEQNO_DTEL) ?></th>
                                    <th class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument('TROBJTYPE') ?></th>
                                    <th class="sapds-alv">&nbsp;</th>
                                    <th class="sapds-alv">&nbsp;</th>
                                    <th class="sapds-alv">&nbsp;</th>
                                </tr>
                                <?php
                                $count = 0;
                                foreach ($wil_list as $wil) {
                                    $count++;
                                    ?>
                                    <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                        <td class="sapds-alv"><?php echo GLOBAL_ABAP_ICON::getIcon4Otype($wil['SRC_OBJ_TYPE']) ?>
                                            <?php echo ABAP_UI_DS_Navigation::GetOTypeHyperlink($wil['SRC_OBJ_TYPE']) ?>&nbsp;</td>
                                        <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetObjectHyperlink($wil['SRC_OBJ_TYPE'], $wil['SRC_OBJ_NAME'], $wil['SRC_SUBOBJ']) ?></td>
                                        <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetObjectDescr($wil['SRC_OBJ_TYPE'], $wil['SRC_OBJ_NAME'], $wil['SRC_SUBOBJ']) ?></td>
                                        <td class="sapds-alv"><?php
                                        if (empty(trim($wil['SOURCE'])) === FALSE) {
                                            echo '<code>SOURCE</code> ' . $wil['SOURCE'];
                                        }
                                            ?></td>
                                    </tr>
                                    <?php
                                }
                                while ($count <= ABAP_UI_CONST::WUL_ROW_MINIMAL) {
                                    $count++;
                                    ?>
                                    <tr><td colspan="5">&nbsp;</td></tr>
                                <?php } ?>

                            </table>

                        </div> 
                    </div><!-- End Card -->
                </main>

                <div  class="col-xl-2 col-lg-2 d-md-3    col-sm-none" >
                    <!-- Right Side bar -->
                    <?php require $__ROOT__ . '/include/abap_relatedlinks.php' ?>
                </div>
            </div><!-- End of row -->
            <hr>
        </div><!-- End container-fluid, main content -->

        <!-- Footer -->
        <?php require $__ROOT__ . '/include/footer.php' ?>
    </body>
</html>
<?php
// Close PDO Database Connection
ABAP_DB_TABLE::close_conn();
