<!DOCTYPE html>
<!-- IMG Activity object. -->
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
$imgach = ABAP_DB_TABLE_CUS0::CUS_IMGACH(strtoupper($ObjID));
if (empty($imgach['ACTIVITY'])) {
    ABAP_UI_TOOL::Redirect404();
}

$imgach_t = ABAP_DB_TABLE_CUS0::CUS_IMGACT($imgach['ACTIVITY']);
$atrh = ABAP_DB_TABLE_CUS0::CUS_ATRH($imgach['ATTRIBUTES']);
$dok_clas = substr($imgach['DOCU_ID'], 0, 4);
$dok_name = substr($imgach['DOCU_ID'], 4);
$dok_html = ABAP_DB_TABLE_CUS0::YDOK_HY($imgach['DOCU_ID']);
$tfm18_list = ABAP_DB_TABLE_CUS0::TFM18($dok_clas, $dok_name);
$atrcou_list = ABAP_DB_TABLE_CUS0::CUS_ATRCOU($imgach['ACTIVITY']);
$acth = ABAP_DB_TABLE_CUS0::CUS_ACTH($imgach['C_ACTIVITY']);
$actobj_list = ABAP_DB_TABLE_CUS0::CUS_ACTOBJ($imgach['C_ACTIVITY']);

$hier = ABAP_DB_TABLE_HIER::Hier(ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::CUS0_NAME, $imgach['ACTIVITY']);
$GLOBALS['TITLE_TEXT'] = ABAP_UI_TOOL::GetObjectTitle(GLOBAL_ABAP_OTYPE::CUS0_NAME, $imgach['ACTIVITY']);

$json_ld = new \OrgSchema\Thing();
$json_ld->name = $imgach['ACTIVITY'];
$json_ld->alternateName = $imgach_t;
$json_ld->description = $GLOBALS['TITLE_TEXT'];
$json_ld->image = GLOBAL_ABAP_ICON::getIconURL(GLOBAL_ABAP_ICON::OTYPE_CUS0, TRUE);
$json_ld->url = ABAP_UI_DS_Navigation::GetObjectURL(GLOBAL_ABAP_OTYPE::CUS0_NAME, $imgach['ACTIVITY']);
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/abap.css" type="text/css" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE_SAPDS::TITLE ?> </title>
        <meta name="keywords" content="SAP,<?php echo GLOBAL_ABAP_OTYPE::CUS0_DESC ?>,<?php echo $imgach['ACTIVITY'] ?>,<?php echo $imgach_t ?>" />
        <meta name="description" content="<?php echo GLOBAL_WEBSITE_SAPDS::META_DESC; ?>" />
        <meta name="author" content="SAP Datasheet" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
                    <tr><td class="left_attribute"> Application Component</td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?>
                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($hier->FCTR_ID, $hier->POSID, $hier->POSID_T) ?>&nbsp;</td></tr>
                    <tr><td class="left_attribute"> Package </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?>
                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($hier->DEVCLASS, $hier->DEVCLASS_T) ?></td></tr>
                    <tr><td class="left_attribute"> Object type </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCUS0() ?>
                            <a href="/abap/cus0/"><?php echo GLOBAL_ABAP_OTYPE::CUS0_DESC ?></a></td></tr>
                    <tr><td class="left_attribute"> Object name </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCUS0() ?>
                            <a href="#" title="<?php echo $imgach_t ?>"><?php echo $imgach['ACTIVITY'] ?></a> </td></tr>
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
                <a href="/abap/cus0/"><?php echo GLOBAL_ABAP_OTYPE::CUS0_DESC ?></a> &gt;
                <a href="#"><?php echo $imgach['ACTIVITY'] ?></a>
            </div>

            <!-- Content Object -->
            <div class="content_obj_title"><span><?php echo $GLOBALS['TITLE_TEXT'] ?></span></div>
            <div class="content_obj">
                <div>
                    <?php include $__WS_ROOT__ . '/common-php/google/adsense-content-top.html' ?>
                </div>

                <?php require $__ROOT__ . '/include/abap_oname_hier.php' ?>

                <h4> IMG Tree </h4>
                
                <h4> IMG Activity </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> ID </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cus0IMGActivity($imgach['ACTIVITY'], $imgach_t, FALSE); ?> </td>
                            <td> <?php echo htmlentities($imgach_t) ?> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Transaction Code </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tran($imgach['TCODE'], null) ?> &nbsp;</td>
                            <td> <?php echo ABAP_DB_TABLE_TRAN::TSTCT($imgach['TCODE']) ?>&nbsp; </td>
                        </tr>
                        <tr><td class="content_label"> Created on </td>
                            <td class="field"> <?php echo $imgach['FDATE'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Customizing Attributes </td>
                            <td class="field"> <?php echo $imgach['ATTRIBUTES'] ?> &nbsp;</td>
                            <td> <?php echo ABAP_DB_TABLE_CUS0::CUS_ATRT($imgach['ATTRIBUTES']) ?>&nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Customizing Activity </td>
                            <td class="field"> <?php echo $imgach['C_ACTIVITY'] ?> &nbsp;</td>
                            <td> <?php echo ABAP_DB_TABLE_CUS0::CUS_ACTT($imgach['C_ACTIVITY']) ?>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>

                <h4> Document </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Document Class </td>
                            <td class="field"> <?php echo $dok_clas ?> &nbsp;</td>
                            <td> Hypertext: Object Class - Class to which a document belongs.</td>
                        </tr>
                        <tr><td class="content_label"> Document Name </td>
                            <td class="field"> <?php echo $dok_name ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <?php if (empty($dok_html) === FALSE) { ?>
                    <div class="f1doc"><?php echo $dok_html ?></div>
                <?php } ?>

                <h4> Business Attributes </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> ASAP Roadmap ID </td>
                            <td class="field"> <?php echo $atrh['ROADMAP_ID'] ?> &nbsp;</td>
                            <td> <?php echo ABAP_DB_TABLE_CUS0::TROADMAPT($atrh['ROADMAP_ID']) ?>&nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Mandatory / Optional </td>
                            <td class="field"> <?php echo $atrh['ACTIVITY'] ?> &nbsp;</td>
                            <td> <?php echo ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_CUS_ATRH_ACTIVITY, $atrh['ACTIVITY']) ?>&nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Critical / Non-Critical </td>
                            <td class="field"> <?php echo $atrh['CRITICAL'] ?> &nbsp;</td>
                            <td> <?php echo ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_CUS_ATRH_CRITICAL, $atrh['CRITICAL']) ?>&nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Country-Dependency </td>
                            <td class="field"> <?php echo $atrh['COUNTRY'] ?> &nbsp;</td>
                            <td> <?php echo ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_CUS_ATRH_COUNTRY, $atrh['COUNTRY']) ?>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <?php if (count($atrcou_list) > 0) { ?>
                    <table class="alv">
                        <tr>
                            <th class="alv"> Customizing Attributes </th>
                            <th class="alv"> Country Key </th>
                            <th class="alv"> Country Name </th>
                        </tr>
                        <?php foreach ($atrcou_list as $atrcou) { ?>
                            <tr><td class="alv"><?php echo $atrcou['ATTR_ID'] ?> </td>
                                <td class="alv"><?php echo $atrcou['COUNTRY'] ?> </td>
                                <td class="alv"><?php echo ABAP_DB_TABLE_CUS0::T005T($atrcou['COUNTRY']) ?> </td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>


                <?php if (count($tfm18_list) > 0) { ?>
                    <h4> Assigned Application Components</h4>
                    <table class="alv">
                        <tr>
                            <th class="alv"> Documentation Object Class </th>
                            <th class="alv"> Documentation Object Name </th>
                            <th class="alv"> Current line number </th>
                            <th class="alv"> Application Component </th>
                            <th class="alv"> Application Component Name </th>
                        </tr>
                        <?php
                        foreach ($tfm18_list as $tfm18) {
                            $tfm18_desc = ABAP_DB_TABLE_HIER::DF14T($tfm18['FUNCT']);
                            ?>
                            <tr><td class="alv"><?php echo $tfm18['DOKCLASS'] ?> </td>
                                <td class="alv"><?php echo $tfm18['DOKNAME'] ?> </td>
                                <td class="alv"><?php echo $tfm18['LINNO'] ?> </td>
                                <td class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($tfm18['FUNCT'], $tfm18['FUNCT'], $tfm18_desc); ?> </td>
                                <td class="alv"><?php echo htmlentities($tfm18_desc) ?>&nbsp;</td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>

                <h4> Maintenance Objects </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Maintenance object type </td>
                            <td class="field"> <?php echo $acth['ACT_TYPE'] ?> &nbsp;</td>
                            <td> <?php echo ABAP_UI_CUS0::GetImgActivityTypeDesc($acth['ACT_TYPE']) ?>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <?php if (count($actobj_list) > 0) { ?>
                    <table class="alv">
                        <th>Assigned objects</th>
                        <tr>
                            <th class="alv"> Customizing Object </th>
                       <!-- <th class="alv"> Object Description </th> -->
                            <th class="alv"> Object Type </th>
                            <th class="alv"> Transaction Code </th>
                            <th class="alv"> Sub-object </th>
                            <th class="alv"> Do not Summarize </th>
                            <th class="alv"> Skip Subset Dialog Box</th>
                       <!-- <th class="alv"> Current Settings </th> -->
                            <th class="alv"> Description for multiple selections </th>
                        </tr>
                        <?php foreach ($actobj_list as $actobj) { ?>
                            <tr><td class="alv"><?php echo $actobj['OBJECTNAME'] ?> </td>
                                <td class="alv"><?php echo $actobj['OBJECTTYPE'] ?> - <?php echo ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_CUS_ACTOBJ_OBJECTTYPE, $actobj['OBJECTTYPE']) ?></td>
                                <td class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tran($actobj['TCODE'], null) ?> </td>
                                <td class="alv"><?php echo $actobj['SUBOBJNAME'] ?> </td>
                                <td class="alv"><?php echo ABAP_UI_TOOL::GetCheckBox("TXN_NO_CON", $actobj['TXN_NO_CON']) ?> </td>
                                <td class="alv"><?php echo ABAP_UI_TOOL::GetCheckBox("SUPRESS_FL", $actobj['SUPRESS_FL']) ?> </td>
                                <td class="alv"><?php echo htmlentities(ABAP_DB_TABLE_CUS0::CUS_ACTOBT($actobj)) ?>&nbsp;</td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>

                <h4> History </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Last changed by/on      </td><td class="field"><?php echo $imgach['LUSER'] ?>&nbsp;</td><td> <?php echo $imgach['LDATE'] ?>&nbsp;</td></tr>
                        <tr><td class="content_label"> SAP Release Created in  </td><td class="field"><?php echo $hier->CRELEASE ?>&nbsp;</td><td>&nbsp;</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <?php require $__ROOT__ . '/include/footer.php' ?>

    </body>
</html>
<?php
// Close PDO Database Connection
ABAP_DB_TABLE::close_conn();
