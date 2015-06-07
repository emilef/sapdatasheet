function langOnFocus(sapDescLangu) {
    sapDescLangu._initValue = sapDescLangu.value;
}
function langOnChnage(sapDescLangu) {
    if (sapDescLangu._initValue !== sapDescLangu.value) {
        // change happened
        setCookie('sap-desc-langu', sapDescLangu.value, 30);

        // Reload the Content page or Jump to Language Specific Index Page
        var pathArray = window.location.pathname.split('/');
        if (window.location.pathname === "/abap/bmfr/"
                || window.location.pathname === "/abap/cvers/"
                || window.location.pathname === "/abap/devc/"
                || window.location.pathname === "/abap/doma/"
                || window.location.pathname === "/abap/dtel/"
                || window.location.pathname === "/abap/fugr/"
                || window.location.pathname === "/abap/func/"
                || window.location.pathname === "/abap/prog/"
                || window.location.pathname === "/abap/sqlt/"
                || window.location.pathname === "/abap/tabl/"
                || window.location.pathname === "/abap/tran/") {
            if (sapDescLangu.value !== 'E') {
                newURL = window.location.href + sapDescLangu.value + "/";
                window.open(newURL, "_self");
            }
        } else if ((pathArray.length === 5 && window.location.pathname.length === 13)
                || (pathArray.length === 5 && window.location.pathname.length === 14 && window.location.pathname.startsWith("/abap/cvers/"))
                ) {
            //  -- /abap/devc/1/    -- 13
            //  -- /abap/cvers/1/   -- 14
            if (sapDescLangu.value === 'E') {
                newURL = window.location.protocol
                        + "//" + window.location.host
                        + "/" + pathArray[1] + "/" + pathArray[2] + "/";
            } else {
                newURL = window.location.protocol
                        + "//" + window.location.host
                        + "/" + pathArray[1] + "/" + pathArray[2] + "/" + sapDescLangu.value + "/";
            }
            window.open(newURL, "_self");
        } else if (pathArray.length === 4 && pathArray[3].substring(0, 6) === 'index-' && sapDescLangu.value !== 'E') {
            newURL = window.location.protocol
                    + "//" + window.location.host
                    + "/" + pathArray[1] + "/" + pathArray[2] + "/" + sapDescLangu.value + "/" + pathArray[3];
            window.open(newURL, "_self");
        } else if (pathArray.length === 5 && pathArray[4].substring(0, 6) === 'index-') {
            if (sapDescLangu.value === 'E') {
                newURL = window.location.protocol
                        + "//" + window.location.host
                        + "/" + pathArray[1] + "/" + pathArray[2] + "/" + pathArray[4];
            } else {
                newURL = window.location.protocol
                        + "//" + window.location.host
                        + "/" + pathArray[1] + "/" + pathArray[2] + "/" + sapDescLangu.value + "/" + pathArray[4];
            }
            window.open(newURL, "_self");
        } else {
            window.location.reload();
        }
    }
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}



