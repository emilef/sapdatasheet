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
$ObjID = strtoupper($ObjID);
$classdef = ABAP_DB_TABLE_SEO::SEOCLASSDF(strtoupper($ObjID));
if (is_null($classdef['CLSNAME']) || GLOBAL_UTIL::IsEmpty($classdef['CLSNAME'])) {
    ABAP_UI_TOOL::Redirect404();
}

$exposure_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCLASSDF_EXPOSURE_DOMAIN, $classdef['EXPOSURE']);
$rstat_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCLASSDF_RSTAT_DOMAIN, $classdef['RSTAT']);
$category_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCLASSDF_CATEGORY_DOMAIN, $classdef['CATEGORY']);
$package_tx = ABAP_DB_TABLE_HIER::TDEVCT($classdef['CATEGORY']);

$class_tx = htmlentities(ABAP_DB_TABLE_SEO::SEOCLASSTX($ObjID));

$typepls = ABAP_DB_TABLE_SEO::SEOTYPEPLS($ObjID);
$interfaces = ABAP_DB_TABLE_SEO::SEOMETAREL($ObjID, ABAP_DB_TABLE_SEO::SEOMETAREL_RELTYPE_ALL);
$metarel_refs = ABAP_DB_TABLE_SEO::SEOMETAREL_REFCLSNAME($ObjID);
$friends = ABAP_DB_TABLE_SEO::SEOFRIENDS($ObjID);
$attributes = ABAP_DB_TABLE_SEO::SEOCOMPO($ObjID, ABAP_DB_TABLE_SEO::SEOCOMPO_CMPTYPE_0);
$methods = ABAP_DB_TABLE_SEO::SEOCOMPO($ObjID, ABAP_DB_TABLE_SEO::SEOCOMPO_CMPTYPE_1);
$events = ABAP_DB_TABLE_SEO::SEOCOMPO($ObjID, ABAP_DB_TABLE_SEO::SEOCOMPO_CMPTYPE_2);
$types = ABAP_DB_TABLE_SEO::SEOCOMPO($ObjID, ABAP_DB_TABLE_SEO::SEOCOMPO_CMPTYPE_3);

$wul_counter_list = ABAPANA_DB_TABLE::WULCOUNTER_List(GLOBAL_ABAP_OTYPE::INTF_NAME, $classdef['CLSNAME']);
$wil_enabled = TRUE;
$wil_counter_list = ABAPANA_DB_TABLE::WILCOUNTER_List(GLOBAL_ABAP_OTYPE::INTF_NAME, $classdef['CLSNAME']);

$hier = ABAP_DB_TABLE_HIER::Hier(ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::INTF_NAME, $ObjID);
$GLOBALS['TITLE_TEXT'] = ABAP_UI_TOOL::GetObjectTitle(GLOBAL_ABAP_OTYPE::INTF_NAME, $ObjID);

$json_ld = new \OrgSchema\Thing();
$json_ld->name = $classdef['CLSNAME'];
$json_ld->alternateName = $class_tx;
$json_ld->description = $GLOBALS['TITLE_TEXT'];
$json_ld->image = GLOBAL_ABAP_ICON::getIconURL(GLOBAL_ABAP_ICON::OTYPE_INTF, TRUE);
$json_ld->url = ABAP_UI_DS_Navigation::GetObjectURL(GLOBAL_ABAP_OTYPE::INTF_NAME, $json_ld->name);
?>
<!DOCTYPE html>
<!-- IMG Activity object. -->
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE::SAPDS_ORG_TITLE ?> </title>
        <meta name="author" content="SAP Datasheet" />
        <meta name="description" content="<?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE::SAPDS_ORG_TITLE ?>" />
        <meta name="keywords" content="SAP,<?php echo GLOBAL_ABAP_OTYPE::CLAS_DESC ?>,<?php echo $ObjID ?>,<?php echo $class_tx ?>" />

        <link rel="stylesheet" type="text/css"  href="/3rdparty/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css"  href="/sapdatasheet.css"/>

        <script type="application/ld+json"><?php echo $json_ld->toJson() ?></script>
    </head>
    <body>
        <!-- Header -->
        <?php require $__ROOT__ . '/include/header.php' ?>

        <div class="container-fluid">
            <div class="row">
                <div  class="col-xl-2 col-lg-2 col-md-3  col-sm-3    bd-sidebar bg-light">
                    <!-- Left Side bar -->
                    <h6 class="pt-4">Object Hierarchy</h6>
                    <table>
                        <tbody>
                            <tr><td><small><strong>Software Component</strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($hier->DLVUNIT, $hier->DLVUNIT_T) ?>&nbsp;</td></tr>
                            <tr><td><small><strong> Application Component ID</strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($hier->FCTR_ID, $hier->POSID, $hier->POSID_T) ?>&nbsp;</td></tr>
                            <tr><td><small><strong> Package </strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($hier->DEVCLASS, $hier->DEVCLASS_T) ?></td></tr>
                            <tr><td><small><strong> Object type </strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeINTF() ?>
                                    <a href="/abap/intf/"><?php echo GLOBAL_ABAP_OTYPE::INTF_DESC ?></a></td></tr>
                            <tr><td><small><strong>Object name </strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeINTF() ?>
                                    <a href="#" title="<?php echo $class_tx ?>"><?php echo $ObjID ?></a> </td></tr>
                        </tbody>
                    </table>

                    <?php require $__ROOT__ . '/include/abap_oname_wul.php' ?>
                    <?php require $__ROOT__ . '/include/abap_ads_side.php' ?>
                </div>

                <main class="col-xl-8 col-lg-8 col-md-6  col-sm-9    col-12 bd-content" role="main">
                    <nav class="pt-3" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home</a></li>
                            <li class="breadcrumb-item"><a href="/abap/">ABAP Object Types</a></li>
                            <li class="breadcrumb-item"><a href="/abap/intf/"><?php echo GLOBAL_ABAP_OTYPE::INTF_DESC ?></a></li>
                            <li class="breadcrumb-item active"><a href="#"><?php echo $ObjID ?></a></li>
                        </ol>
                    </nav>

                    <div class="card shadow">
                        <div class="card-header sapds-card-header"><?php echo $GLOBALS['TITLE_TEXT'] ?></div>
                        <div class="card-body table-responsive sapds-card-body">
                            <div class="align-content-start"><?php include $__WS_ROOT__ . '/common-php/google/adsense-content-top.html' ?></div>
                            <?php require $__ROOT__ . '/include/abap_desc_language.php' ?>
                            <?php require $__ROOT__ . '/include/abap_oname_hier.php' ?>

                            <!-- Meta Relationship (top 50) -->
                            <?php if (empty($metarel_refs) === FALSE) { ?>
                                <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4WUL() ?> Meta Relationship - Used By </h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Relationship type </th>
                                        <th class="sapds-alv"> Used by </th>
                                        <th class="sapds-alv"> Short Description </th>
                                        <th class="sapds-alv"> Created on</th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    $metarel_ref_exceeded = FALSE;
                                    foreach ($metarel_refs as $metarel_ref) {
                                        $count++;
                                        if ($count > ABAP_DB_CONST::SEO_METAREL_LIMIT) {
                                            // Avoid too many records at the top section
                                            $metarel_ref_exceeded = TRUE;
                                            break;
                                        }

                                        $reltype_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOMETAREL_RELTYPE_DOMAIN, $metarel_ref['RELTYPE']);
                                        $metarel_cls_desc = ABAP_DB_TABLE_SEO::SEOCLASSTX($metarel_ref['CLSNAME']);
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOMETAREL_RELTYPE_DOMAIN, $reltype_desc, $metarel_ref['RELTYPE']) ?>&nbsp;</td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetObjectHyperlink(GLOBAL_ABAP_OTYPE::SEOC_NAME, $metarel_ref['CLSNAME']) ?></td>
                                            <td class="sapds-alv"><?php echo $metarel_cls_desc ?></td>
                                            <td class="sapds-alv"><?php echo $metarel_ref['CREATEDON'] ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if ($metarel_ref_exceeded === TRUE) { ?>
                                        <tr><td class="sapds-alv text-right">...</td>
                                            <td  class="sapds-alv" colspan="4">Click <a href="#<?php echo ABAP_UI_CONST::ANCHOR_SEOMETARELFL ?>">here</a> to see Used By full list (<?php echo count($metarel_refs) ?> items)</td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } ?>


                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4DisplayText() ?> Properties </h5>
                            <table>
                                <tbody>
                                    <tr><td class="sapds-gui-label"> Interface </td>
                                        <td class="sapds-gui-field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Intf($ObjID, $class_tx, FALSE); ?> </td>
                                        <td> &nbsp;</td>
                                    </tr>
                                    <tr><td class="sapds-gui-label"> Short Description</td>
                                        <td class="sapds-gui-field"> <?php echo $class_tx ?> &nbsp;</td>
                                        <td> &nbsp; </td>
                                    </tr>
                                </tbody>
                            </table>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4Header() ?> General Data </h5>
                            <table>
                                <tbody>
                                    <tr><td class="sapds-gui-label"> Package</td>
                                        <td class="sapds-gui-field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($hier->DEVCLASS, $hier->DEVCLASS_T) ?> &nbsp;</td>
                                        <td> <?php echo $hier->DEVCLASS_T ?>&nbsp; </td>
                                    </tr>
                                    <!--
                                    <tr><td class="sapds-gui-label"> Original Language </td>
                                        <td class="sapds-gui-field"> <?php echo '' ?> &nbsp;</td>
                                        <td> <?php echo '' ?>&nbsp; </td>
                                    </tr>
                                    -->
                                    <tr><td class="sapds-gui-label"> Created </td>
                                        <td class="sapds-gui-field"> <?php echo $classdef['CREATEDON'] ?> &nbsp;</td>
                                        <td> <?php echo $classdef['AUTHOR'] ?>&nbsp; </td>
                                    </tr>
                                    <tr><td class="sapds-gui-label"> Last changed </td>
                                        <td class="sapds-gui-field"> <?php echo $classdef['CHANGEDON'] ?> &nbsp;</td>
                                        <td> <?php echo $classdef['CHANGEDBY'] ?>&nbsp; </td>
                                    </tr>
                                    <tr><td class="sapds-gui-label"> Unicode checks active </td>
                                        <td><?php echo ABAP_UI_TOOL::GetCheckBox("UNICODE", $classdef['UNICODE']) ?> &nbsp;</td>
                                        <td>&nbsp; </td>
                                    </tr>
                                </tbody>
                            </table>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4Include() ?> Forward declarations </h5>
                            <?php if (empty($typepls) === FALSE) { ?>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Type group / Object type </th>
                                        <th class="sapds-alv"> Type </th>
                                        <th class="sapds-alv"> Type Description</th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    foreach ($typepls as $typepl) {
                                        $count++;
                                        $typepl_type_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOTYPEPLS_TPUTYPE_DOMAIN, $typepl['TPUTYPE']);
                                        if ($typepl['TPUTYPE'] == ABAP_DB_TABLE_SEO::SEOTYPEPLS_TPUTYPE_1 || $typepl['TPUTYPE'] == ABAP_DB_TABLE_SEO::SEOTYPEPLS_TPUTYPE_2) {
                                            $typepl_type = ABAP_UI_DS_Navigation::GetHyperlink4Clas($typepl['TYPEGROUP'], NULL);
                                        } else {
                                            $typepl_type = $typepl['TYPEGROUP'];
                                        }
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><?php echo $typepl_type ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOTYPEPLS_TPUTYPE_DOMAIN, $typepl_type_desc, $typepl['TPUTYPE']) ?>&nbsp;</td>
                                            <td class="sapds-alv"><?php echo $typepl_type_desc ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <code>Interface <?php echo $ObjID ?> has no forward declaration.</code>
                            <?php } ?>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeINTF() ?> Interfaces </h5>
                            <?php if (empty($interfaces) === FALSE) { ?>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Interface </th>
                                        <th class="sapds-alv"> Abstract </th>
                                        <th class="sapds-alv"> Final </th>
                                        <th class="sapds-alv"> Description</th>
                                        <th class="sapds-alv"> Created on</th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    foreach ($interfaces as $interface) {
                                        $count++;
                                        $interface_tx = ABAP_DB_TABLE_SEO::SEOCLASSTX($interface['REFCLSNAME']);
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Intf($interface['REFCLSNAME'], $interface_tx) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetCheckBox("IMPABSTRCT", $interface['IMPABSTRCT']) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetCheckBox("IMPFINAL", $interface['IMPFINAL']) ?></td>
                                            <td class="sapds-alv"><?php echo $interface_tx ?></td>
                                            <td class="sapds-alv"><?php echo $interface['CREATEDON'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <code>Interface <?php echo $ObjID ?> has no interface.</code>
                            <?php } ?>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4OOClassFriend() ?> Friends </h5>
                            <?php if (empty($friends) === FALSE) { ?>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Friend </th>
                                        <th class="sapds-alv"> Modeled only </th>
                                        <th class="sapds-alv"> Created on </th>
                                        <th class="sapds-alv"> Description</th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    foreach ($friends as $friend) {
                                        $count++;
                                        $friend_tx = ABAP_DB_TABLE_SEO::SEOCLASSTX($friend['REFCLSNAME']);
                                        $friend_state = ($friend['STATE'] == 1) ? '' : 'X';
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Clas($friend['REFCLSNAME'], $friend_tx) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetCheckBox("FRIEND_STATE", $friend_state) ?></td>
                                            <td class="sapds-alv"><?php echo $friend['CREATEDON'] ?></td>
                                            <td class="sapds-alv"><?php echo $friend_tx ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <code>Interface <?php echo $ObjID ?> has no friend.</code>
                            <?php } ?>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4OOClassAttribute() ?> Attributes </h5>
                            <?php if (empty($attributes) === FALSE) { ?>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Attribute </th>
                                        <th class="sapds-alv"> Level </th>
                                        <th class="sapds-alv"> Visibility </th>
                                        <th class="sapds-alv"> Read only</th>
                                        <th class="sapds-alv"> Typing </th>
                                        <th class="sapds-alv"> Associated Type </th>
                                        <th class="sapds-alv"> Initial Value </th>
                                        <th class="sapds-alv"> Description </th>
                                        <th class="sapds-alv"> Created on </th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    foreach ($attributes as $attribute) {
                                        $count++;
                                        $seocomp_tx = ABAP_DB_TABLE_SEO::SEOCOMPOTX($ObjID, $attribute['CMPNAME']);
                                        $seocomp_df = ABAP_DB_TABLE_SEO::SEOCOMPODF($ObjID, $attribute['CMPNAME']);
                                        $attribute_level_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_ATTDECLTYP_DOMAIN, $seocomp_df['ATTDECLTYP']);
                                        $seocomp_visibility_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_EXPOSURE_DOMAIN, $seocomp_df['EXPOSURE']);
                                        $seocomp_typing_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_TYPTYPE_DOMAIN, $seocomp_df['TYPTYPE']);
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><?php echo $seocomp_df['CMPNAME'] ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_ATTDECLTYP_DOMAIN, $attribute_level_tx, $seocomp_df['ATTDECLTYP']) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_EXPOSURE_DOMAIN, $seocomp_visibility_tx, $seocomp_df['EXPOSURE']) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetCheckBox('ATTRDONLY', $seocomp_df['ATTRDONLY']) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_TYPTYPE_DOMAIN, $seocomp_typing_tx, $seocomp_df['TYPTYPE']) ?></td>
                                            <td class="sapds-alv"><?php
                                                if ($seocomp_df['TYPTYPE'] == ABAP_DB_TABLE_SEO::SEOCOMPODF_TYPTYPE_3) {
                                                    $clstype = ABAP_DB_TABLE_SEO::SEOCLASS($seocomp_df['TYPE']);
                                                    if ($clstype['CLSTYPE'] == ABAP_DB_TABLE_SEO::SEOCLASS_CLSTYPE_CLAS) {
                                                        echo ABAP_UI_DS_Navigation::GetHyperlink4Clas($seocomp_df['TYPE'], NULL);
                                                    } else {
                                                        echo ABAP_UI_DS_Navigation::GetHyperlink4Intf($seocomp_df['TYPE'], NULL);
                                                    }
                                                } else {
                                                    echo $seocomp_df['TYPE'];
                                                }
                                                ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_df['ATTVALUE'] ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_tx ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_df['CREATEDON'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <code>Interface <?php echo $ObjID ?> has no attribute.</code>
                            <?php } ?>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4OOClassMethod() ?> Methods </h5>
                            <?php if (empty($methods) === FALSE) { ?>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Method </th>
                                        <th class="sapds-alv"> Level </th>
                                        <th class="sapds-alv"> Visibility </th>
                                        <th class="sapds-alv"> Method type </th>
                                        <th class="sapds-alv"> Description </th>
                                        <th class="sapds-alv"> Created on </th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    foreach ($methods as $method) {
                                        $count++;
                                        $seocomp_tx = ABAP_DB_TABLE_SEO::SEOCOMPOTX($ObjID, $method['CMPNAME']);
                                        $seocomp_df = ABAP_DB_TABLE_SEO::SEOCOMPODF($ObjID, $method['CMPNAME']);
                                        $method_level_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_MTDDECLTYP_DOMAIN, $seocomp_df['MTDDECLTYP']);
                                        $seocomp_visibility_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_EXPOSURE_DOMAIN, $seocomp_df['EXPOSURE']);
                                        $method_type_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPO_MTDTYPE_DOMAIN, $method['MTDTYPE']);
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OOClassMethod() ?>
                                                <a href="#<?php echo ABAP_UI_TOOL::GetClassMethodAnchorName($seocomp_df['CMPNAME']) ?>"><?php echo $seocomp_df['CMPNAME'] ?></a></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_MTDDECLTYP_DOMAIN, $method_level_tx, $seocomp_df['MTDDECLTYP']) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_EXPOSURE_DOMAIN, $seocomp_visibility_tx, $seocomp_df['EXPOSURE']) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPO_MTDTYPE_DOMAIN, $method_type_tx, $method['MTDTYPE']) ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_tx ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_df['CREATEDON'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <code>Interface <?php echo $ObjID ?> has no method.</code>
                            <?php } ?>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4OOClassEvent() ?> Events </h5>
                            <?php if (empty($events) === FALSE) { ?>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Event </th>
                                        <th class="sapds-alv"> Type </th>
                                        <th class="sapds-alv"> Visibility </th>
                                        <th class="sapds-alv"> Description </th>
                                        <th class="sapds-alv"> Created on </th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    foreach ($events as $event) {
                                        $count++;
                                        $seocomp_tx = ABAP_DB_TABLE_SEO::SEOCOMPOTX($ObjID, $event['CMPNAME']);
                                        $seocomp_df = ABAP_DB_TABLE_SEO::SEOCOMPODF($ObjID, $event['CMPNAME']);
                                        $event_type_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_EVTDECLTYP_DOMAIN, $seocomp_df['EVTDECLTYP']);
                                        $seocomp_visibility_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_EXPOSURE_DOMAIN, $seocomp_df['EXPOSURE']);
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><a href="#<?php echo ABAP_UI_TOOL::GetClassMethodAnchorName($seocomp_df['CMPNAME']) ?>"><?php echo $seocomp_df['CMPNAME'] ?></a></td>
                                            <td class="sapds-alv"><?php echo $event_type_tx ?>
                                                <br/>(<?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_EVTDECLTYP_DOMAIN, $seocomp_df['EVTDECLTYP'], $event_type_tx) ?>)
                                            </td>
                                            <td class="sapds-alv"><?php echo $seocomp_visibility_tx ?>
                                                <br/>(<?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_EXPOSURE_DOMAIN, $seocomp_df['EXPOSURE'], $seocomp_visibility_tx) ?>)
                                            </td>
                                            <td class="sapds-alv"><?php echo $seocomp_tx ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_df['CREATEDON'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <code>Interface <?php echo $ObjID ?> has no event.</code>
                            <?php } ?>


                            <h5 class="pt-4"> Types </h5>
                            <?php if (empty($types) === FALSE) { ?>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Type </th>
                                        <th class="sapds-alv"> Visibility </th>
                                        <th class="sapds-alv"> Typing </th>
                                        <th class="sapds-alv"> Associated Type </th>
                                        <th class="sapds-alv"> Description </th>
                                        <th class="sapds-alv"> Created on </th>
                                        <th class="sapds-alv"> Type Source </th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    foreach ($types as $type) {
                                        $count++;
                                        $seocomp_tx = ABAP_DB_TABLE_SEO::SEOCOMPOTX($ObjID, $type['CMPNAME']);
                                        $seocomp_df = ABAP_DB_TABLE_SEO::SEOCOMPODF($ObjID, $type['CMPNAME']);
                                        $seocomp_visibility_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_EXPOSURE_DOMAIN, $seocomp_df['EXPOSURE']);
                                        $seocomp_typing_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOCOMPODF_TYPTYPE_DOMAIN, $seocomp_df['TYPTYPE']);
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><?php echo $seocomp_df['CMPNAME'] ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_EXPOSURE_DOMAIN, $seocomp_visibility_tx, $seocomp_df['EXPOSURE']) ?></td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOCOMPODF_TYPTYPE_DOMAIN, $seocomp_typing_tx, $seocomp_df['TYPTYPE']) ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_df['TYPE']; ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_tx ?></td>
                                            <td class="sapds-alv"><?php echo $seocomp_df['CREATEDON'] ?></td>
                                            <td class="sapds-alv"><code><?php echo $seocomp_df['TYPESRC'] ?></code></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } else { ?>
                                <code>Interface <?php echo $ObjID ?> has no local type.</code>
                            <?php } ?>

                            <!-- <h4> Alias </h4> -->
                            <?php if (empty($methods) === FALSE) { ?>
                                <h5 class="pt-4"> Method Signatures </h5>
                                <?php
                                foreach ($methods as $method) {
                                    $method_paras = ABAP_DB_TABLE_SEO::SEOSUBCO($ObjID, $method['CMPNAME'], ABAP_DB_TABLE_SEO::SEOSUBCO_SCOTYPE_0);
                                    $method_excps = ABAP_DB_TABLE_SEO::SEOSUBCO($ObjID, $method['CMPNAME'], ABAP_DB_TABLE_SEO::SEOSUBCO_SCOTYPE_1);
                                    ?>
                                    <p><strong id="<?php echo ABAP_UI_TOOL::GetClassMethodAnchorName($method['CMPNAME']) ?>"><code>Method <?php echo $method['CMPNAME'] ?> Signature</code></strong></p>
                                    <?php if (empty($method_paras) === FALSE) { ?>
                                        <table class="table table-sm">
                                            <tr>
                                                <th class="sapds-alv"> # </th>
                                                <th class="sapds-alv"> Type </th>
                                                <th class="sapds-alv"> Parameter </th>
                                                <th class="sapds-alv"> Pass Value </th>
                                                <th class="sapds-alv"> Optional </th>
                                                <th class="sapds-alv"> Typing Method </th>
                                                <th class="sapds-alv"> Associated Type </th>
                                                <th class="sapds-alv"> Default value </th>
                                                <th class="sapds-alv"> Description </th>
                                                <th class="sapds-alv"> Created on </th>
                                            </tr>
                                            <?php
                                            $count = 0;
                                            foreach ($method_paras as $method_para) {
                                                $count++;
                                                $subco_tx = ABAP_DB_TABLE_SEO::SEOSUBCOTX($ObjID, $method_para['CMPNAME'], $method_para['SCONAME']);
                                                $subco_df = ABAP_DB_TABLE_SEO::SEOSUBCODF($ObjID, $method_para['CMPNAME'], $method_para['SCONAME']);
                                                $subco_decltype_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARDECLTYP_DOMAIN, $subco_df['PARDECLTYP']);
                                                $subco_passvalue_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARPASSTYP_DOMAIN, $subco_df['PARPASSTYP']);
                                                $subco_typing_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_TYPTYPE_DOMAIN, $subco_df['TYPTYPE']);
                                                ?>
                                                <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetOOParameterIcon($subco_df['PARDECLTYP']) ?>
                                                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARDECLTYP_DOMAIN, $subco_decltype_tx, $subco_df['PARDECLTYP']) ?></td>
                                                    <td class="sapds-alv"><?php echo $method_para['SCONAME'] ?></td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARPASSTYP_DOMAIN, $subco_passvalue_tx, $subco_df['PARPASSTYP']) ?></td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetCheckBox('PAROPTIONL', $subco_df['PAROPTIONL']) ?></td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOSUBCODF_TYPTYPE_DOMAIN, $subco_typing_tx, $subco_df['TYPTYPE']) ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_df['TYPE'] ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_df['PARVALUE'] ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_tx ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_df['CREATEDON'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    <?php } else { ?>
                                        <code>Method <?php echo $method['CMPNAME'] ?> on Interface <?php echo $ObjID ?> has no parameter.</code>
                                    <?php } ?>


                                    <?php if (empty($method_excps) === FALSE) { ?>
                                        <table class="table table-sm">
                                            <tr>
                                                <th class="sapds-alv"> # </th>
                                                <th class="sapds-alv"> Exception </th>
                                                <th class="sapds-alv"> Resumable </th>
                                                <th class="sapds-alv"> Description </th>
                                                <th class="sapds-alv"> Created on </th>
                                            </tr>
                                            <?php
                                            $count = 0;
                                            foreach ($method_excps as $method_para) {
                                                $count++;
                                                $subco_tx = ABAP_DB_TABLE_SEO::SEOSUBCOTX($ObjID, $method_para['CMPNAME'], $method_para['SCONAME']);
                                                $subco_df = ABAP_DB_TABLE_SEO::SEOSUBCODF($ObjID, $method_para['CMPNAME'], $method_para['SCONAME']);
                                                $subco_decltype_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARDECLTYP_DOMAIN, $subco_df['PARDECLTYP']);
                                                $subco_passvalue_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARPASSTYP_DOMAIN, $subco_df['PARPASSTYP']);
                                                $subco_typing_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_TYPTYPE_DOMAIN, $subco_df['TYPTYPE']);
                                                ?>
                                                <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                                    <td class="sapds-alv"><?php echo GLOBAL_ABAP_ICON::getIcon4Alert() ?>
                                                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Clas($method_para['SCONAME'], $subco_tx) ?></td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetCheckBox('IS_RESUMABLE', $subco_df['IS_RESUMABLE']) ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_tx ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_df['CREATEDON'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    <?php } else { ?>
                                        <br /><code>Method <?php echo $method['CMPNAME'] ?> on Interface <?php echo $ObjID ?> has no exception.</code>
                                    <?php } ?>

                                <?php } ?>
                            <?php } ?>

                            <?php if (empty($events) === FALSE) { ?>
                                <h5 class="pt-4"> Event Signatures </h5>
                                <?php
                                foreach ($events as $event) {
                                    $event_paras = ABAP_DB_TABLE_SEO::SEOSUBCO($ObjID, $event['CMPNAME'], ABAP_DB_TABLE_SEO::SEOSUBCO_SCOTYPE_0);
                                    ?>
                                    <br /><strong id="<?php echo ABAP_UI_TOOL::GetClassMethodAnchorName($event['CMPNAME']) ?>"><code>Event <?php echo $event['CMPNAME'] ?> Signature</code></strong>
                                    <?php if (empty($event_paras) === FALSE) { ?>
                                        <table class="table table-sm">
                                            <tr>
                                                <th class="sapds-alv"> # </th>
                                                <th class="sapds-alv"> Parameter </th>
                                                <th class="sapds-alv"> Type </th>
                                                <th class="sapds-alv"> Pass Value </th>
                                                <th class="sapds-alv"> Optional </th>
                                                <th class="sapds-alv"> Typing Method </th>
                                                <th class="sapds-alv"> Associated Type </th>
                                                <th class="sapds-alv"> Default value </th>
                                                <th class="sapds-alv"> Description </th>
                                                <th class="sapds-alv"> Created on </th>
                                            </tr>
                                            <?php
                                            $count = 0;
                                            foreach ($event_paras as $event_para) {
                                                $count++;
                                                $subco_tx = ABAP_DB_TABLE_SEO::SEOSUBCOTX($ObjID, $event_para['CMPNAME'], $event_para['SCONAME']);
                                                $subco_df = ABAP_DB_TABLE_SEO::SEOSUBCODF($ObjID, $event_para['CMPNAME'], $event_para['SCONAME']);
                                                $subco_decltype_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARDECLTYP_DOMAIN, $subco_df['PARDECLTYP']);
                                                $subco_passvalue_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARPASSTYP_DOMAIN, $subco_df['PARPASSTYP']);
                                                $subco_typing_tx = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOSUBCODF_TYPTYPE_DOMAIN, $subco_df['TYPTYPE']);
                                                ?>
                                                <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                                    <td class="sapds-alv"><?php echo $event_para['SCONAME'] ?></td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARDECLTYP_DOMAIN, $subco_decltype_tx, $subco_df['PARDECLTYP']) ?></td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOSUBCODF_PARPASSTYP_DOMAIN, $subco_passvalue_tx, $subco_df['PARPASSTYP']) ?></td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_TOOL::GetCheckBox('PAROPTIONL', $subco_df['PAROPTIONL']) ?></td>
                                                    <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOSUBCODF_TYPTYPE_DOMAIN, $subco_typing_tx, $subco_df['TYPTYPE']) ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_df['TYPE'] ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_df['PARVALUE'] ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_tx ?></td>
                                                    <td class="sapds-alv"><?php echo $subco_df['CREATEDON'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    <?php } else { ?>
                                        <br /><code>Event <?php echo $event['CMPNAME'] ?> on Interface <?php echo $ObjID ?> has no parameter.</code>
                                    <?php } ?>

                                <?php } ?>
                            <?php } ?>

                            <?php if (isset($metarel_ref_exceeded) && $metarel_ref_exceeded === TRUE) { ?>
                                <h5 class="pt-4"> Meta Relationship - Used By (full list) </h5>
                                <a id="<?php echo ABAP_UI_CONST::ANCHOR_SEOMETARELFL ?>"></a>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="sapds-alv"> # </th>
                                        <th class="sapds-alv"> Relationship type </th>
                                        <th class="sapds-alv"> Used by </th>
                                        <th class="sapds-alv"> Short Description </th>
                                        <th class="sapds-alv"> Created on</th>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    foreach ($metarel_refs as $metarel_ref) {
                                        $count++;
                                        $reltype_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_SEO::SEOMETAREL_RELTYPE_DOMAIN, $metarel_ref['RELTYPE']);
                                        $metarel_cls_desc = ABAP_DB_TABLE_SEO::SEOCLASSTX($metarel_ref['CLSNAME']);
                                        ?>
                                        <tr><td class="sapds-alv text-right"><?php echo number_format($count) ?> </td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_SEO::SEOMETAREL_RELTYPE_DOMAIN, $reltype_desc, $metarel_ref['RELTYPE']) ?>&nbsp;</td>
                                            <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetObjectHyperlink(GLOBAL_ABAP_OTYPE::SEOC_NAME, $metarel_ref['CLSNAME']) ?></td>
                                            <td class="sapds-alv"><?php echo $metarel_cls_desc ?></td>
                                            <td class="sapds-alv"><?php echo $metarel_ref['CREATEDON'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>

                            <?php } ?>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4History() ?> History </h5>
                            <table>
                                <tbody>
                                    <tr><td class="sapds-gui-label"> Last changed by/on      </td><td class="sapds-gui-field"><?php echo $classdef['AUTHOR'] ?>&nbsp;</td><td> <?php echo $classdef['CHANGEDON'] ?>&nbsp;</td></tr>
                                    <tr><td class="sapds-gui-label"> SAP Release Created in  </td><td class="sapds-gui-field"><?php echo $hier->CRELEASE ?>&nbsp;</td><td>&nbsp;</td></tr>
                                </tbody>
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
