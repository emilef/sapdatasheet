<?php

$GLOBALS['TITLE_TEXT'] = 'SAP';
error_reporting(-1);

/** Web Site Constants. */
class WEBSITE {

    const NAME = 'SAP Datasheet';
    const DESC = 'The Best Run SAP Run SAPDatasheet';
    const TITLE = ' - SAP Datasheet - The Best Run SAP Run SAPDatasheet';
    const META_DESC = 'Datasheet for all SAP objects: domain, data element, table, transaction code, etc';

}

/** Sitemap constants. */
class SITEMAP {

    const MAX_URL_COUNT = 50000;

}

/** ABAP Object types and description. */
class ABAP_OTYPE {

    const BMFR_NAME = 'BMFR';
    const BMFR_DESC = 'Application Component';
    const CLAS_NAME = 'CLAS';
    const CLAS_DESC = 'Class';
    const CUS0_NAME = 'CUS0';
    const CUS0_DESC = 'Customizing IMG Activity';
    const CVERS_NAME = 'CVERS';
    const CVERS_DESC = 'Software Component';
    const DEVC_NAME = 'DEVC';
    const DEVC_DESC = 'Package';
    const DOMA_NAME = 'DOMA';
    const DOMA_DESC = 'Domain';
    const DTEL_NAME = 'DTEL';
    const DTEL_DESC = 'Data Element';
    const FUNC_NAME = 'FUNC';
    const FUNC_DESC = 'Function Module';
    const FUGR_NAME = 'FUGR';
    const FUGR_DESC = 'Function Group';
    const INTF_NAME = 'INTF';
    const INTF_DESC = 'Interface';
    const PROG_NAME = 'PROG';
    const PROG_DESC = 'Program';
    const SQLT_NAME = 'SQLT';
    const SQLT_DESC = 'Table Cluster/Pool';
    const TABL_NAME = 'TABL';
    const TABL_DESC = 'Table';
    const TRAN_NAME = 'TRAN';
    const TRAN_DESC = 'Transaction Code';
    const VIEW_NAME = 'VIEW';
    const VIEW_DESC = 'View';
    const MENU_NAME = 'MENU';
    const MENU_DESC = 'SAP Menu';
    const SU21_NAME = 'SU21';
    const SU21_DESC = 'Authorization Object';
    const PFCG_NAME = 'PFCG';
    const PFCG_DESC = 'Role';
    const RZ10_NAME = 'RZ10';
    const RZ10_DESC = 'NetWeaver Profile';

}

/**
 * HTTP Status Codes.
 * 
 * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html Status Code Definitions
 */
class HTTP_STATUS {

    const STATUS_100 = "HTTP/1.1 100 Continue";
    const STATUS_101 = "HTTP/1.1 101 Switching Protocols";
    const STATUS_200 = "HTTP/1.1 200 OK";
    const STATUS_201 = "HTTP/1.1 201 Created";
    const STATUS_202 = "HTTP/1.1 202 Accepted";
    const STATUS_203 = "HTTP/1.1 203 Non-Authoritative Information";
    const STATUS_204 = "HTTP/1.1 204 No Content";
    const STATUS_205 = "HTTP/1.1 205 Reset Content";
    const STATUS_206 = "HTTP/1.1 206 Partial Content";
    const STATUS_300 = "HTTP/1.1 300 Multiple Choices";
    const STATUS_301 = "HTTP/1.1 301 Moved Permanently";
    const STATUS_302 = "HTTP/1.1 302 Found";
    const STATUS_303 = "HTTP/1.1 303 See Other";
    const STATUS_304 = "HTTP/1.1 304 Not Modified";
    const STATUS_305 = "HTTP/1.1 305 Use Proxy";
    const STATUS_307 = "HTTP/1.1 307 Temporary Redirect";
    const STATUS_400 = "HTTP/1.1 400 Bad Request";
    const STATUS_401 = "HTTP/1.1 401 Unauthorized";
    const STATUS_402 = "HTTP/1.1 402 Payment Required";
    const STATUS_403 = "HTTP/1.1 403 Forbidden";
    const STATUS_404 = "HTTP/1.1 404 Not Found";
    const STATUS_405 = "HTTP/1.1 405 Method Not Allowed";
    const STATUS_406 = "HTTP/1.1 406 Not Acceptable";
    const STATUS_407 = "HTTP/1.1 407 Proxy Authentication Required";
    const STATUS_408 = "HTTP/1.1 408 Request Time-out";
    const STATUS_409 = "HTTP/1.1 409 Conflict";
    const STATUS_410 = "HTTP/1.1 410 Gone";
    const STATUS_411 = "HTTP/1.1 411 Length Required";
    const STATUS_412 = "HTTP/1.1 412 Precondition Failed";
    const STATUS_413 = "HTTP/1.1 413 Request Entity Too Large";
    const STATUS_414 = "HTTP/1.1 414 Request-URI Too Large";
    const STATUS_415 = "HTTP/1.1 415 Unsupported Media Type";
    const STATUS_416 = "HTTP/1.1 416 Requested range not satisfiable";
    const STATUS_417 = "HTTP/1.1 417 Expectation Failed";
    const STATUS_500 = "HTTP/1.1 500 Internal Server Error";
    const STATUS_501 = "HTTP/1.1 501 Not Implemented";
    const STATUS_502 = "HTTP/1.1 502 Bad Gateway";
    const STATUS_503 = "HTTP/1.1 503 Service Unavailable";
    const STATUS_504 = "HTTP/1.1 504 Gateway Time-out";

}

/**
 * Download file Format
 */
class DOWNLOAD {

    const FORMAT_CSV = "CSV";
    const FORMAT_XLS = "XLS";
    const FORMAT_XLSX = "XLSX";

}

class GLOBAL_UTIL {

    const SAP_DESC_LANGU = 'SAP_DESC_LANGU';

    /**
     * Source: http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php/834355#834355
     */
    public static function StartsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * Source: http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php/834355#834355
     */
    public static function EndsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    /**
     * Update the $GLOBALS['SAP_DESC_LANGU'] variable based on HTTP cookie value.
     */
    public static function UpdateSAPDescLangu() {
        if (empty($_COOKIE['sap-desc-langu']) === FALSE and strlen($_COOKIE['sap-desc-langu']) == 1) {
            switch ($_COOKIE['sap-desc-langu']) {
                case "N": case "E": case "F": case "D": case "I":
                case "J": case "3": case "L": case "P": case "R":
                case "1": case "S": case "M": case "T":
                    $sap_desc_langu = $_COOKIE['sap-desc-langu'];
                    break;

                default:
                    $sap_desc_langu = "E";
                    break;
            }
        } else {
            $sap_desc_langu = "E";
        }
        $GLOBALS[GLOBAL_UTIL::SAP_DESC_LANGU] = $sap_desc_langu;
    }

    /**
     * Get OB Folder based on the langeuage.
     */
    public static function GetObFolder($dirname) {
        if ($GLOBALS[GLOBAL_UTIL::SAP_DESC_LANGU] == ABAP_DB_CONST::LANGU_EN) {
            $ob_folder = $dirname;
        } else {
            $ob_folder = $dirname . "/" . $GLOBALS[GLOBAL_UTIL::SAP_DESC_LANGU];
        }

        return $ob_folder;
    }

}
