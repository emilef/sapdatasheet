<?php

/**
 * Site map constants.
 * 
 * @see https://www.sitemaps.org/protocol.html
 */
class SITEMAP {

    const MAX_URL_COUNT = 50000;

    /**
     * Current site-map file size.
     */
    protected $counter = 0;

    /**
     * Site map file name prefix.
     */
    protected $file_nameprefix = 'sitemap';

    /**
     * Site map file name sequence.
     */
    protected $file_nameseq = 0;

    // How frequently the page is likely to change. 
    const changefreq_always = 'always';
    const changefreq_hourly = 'hourly';
    const changefreq_daily = 'daily';
    const changefreq_weekly = 'weekly';
    const changefreq_monthly = 'monthly';
    const changefreq_yearly = 'yearly';
    const changefreq_never = 'never';
    // The priority of this URL relative to other URLs on your site.
    const priority_00 = '0.0';
    const priority_01 = '0.1';
    const priority_02 = '0.2';
    const priority_03 = '0.3';
    const priority_04 = '0.4';
    const priority_05 = '0.5';  // Defalt value 
    const priority_06 = '0.6';
    const priority_07 = '0.7';
    const priority_08 = '0.8';
    const priority_09 = '0.9';
    const priority_10 = '1.0';

    function __construct(string $prefix) {
        $this->file_nameprefix = $prefix;
    }

    /**
     * Echo one URL.
     */
    public final function EchoUrl(string $url, string $changefreq = self::changefreq_yearly, string $priority = self::priority_05) {
        // Echo one URL
        echo '<url>';
        echo '<loc>' . $url . '</loc>';
        echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
        echo '<changefreq>' . $changefreq . '</changefreq>';
        echo '<priority>' . $priority . '</priority>';
        echo '</url>';
        echo "\r\n";

        // Add counter
        $this->counter++;
        if ($this->counter >= self::MAX_URL_COUNT) {
            $this->EndOB();
            $this->counter = 0;
            $this->StartOB();
        }
    }

    /**
     * End the sitemap xml file.
     */
    public function EndOB() {
        echo '</urlset>';
        $ob_content = ob_get_contents();
        ob_end_flush();
        // Get the new file name, Add the file name sequence
        $this->file_nameseq++;
        $filename = "./" . $this->file_nameprefix . '-' . $this->file_nameseq . ".xml";
        // Write the file. Any existing file will be overwritten.
        file_put_contents($filename, $ob_content);
    }
    
    public function GetFilenameSeq() : int {
        return $this->file_nameseq;
    }


    /**
     * Start a new sitemap xml file.
     */
    public function StartOB() {
        ob_start();
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        echo "\r\n";
    }
    
}



/**
 * Tools to generate site map index.
 * 
 * @see https://www.sitemaps.org/protocol.html
 */
class SITEMAP_Index extends SITEMAP {

    function __construct(string $prefix) {
        parent::__construct($prefix);
    }
    /**
     * Echo one URL for the sitemapindex file.
     */
    function EchoUrl4Index(string $url) {
        // Echo one URL
        echo '<sitemap>';
        echo '<loc>' . $url . '</loc>';
        echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
        echo '</sitemap>';
        echo "\r\n";
        // Add counter
        $this->counter++;
        if ($this->counter >= SITEMAP::MAX_URL_COUNT) {
            $this->StopOB();
            $this->counter = 0;
            $this->StartOB();
        }
    }
    /**
     * Start a new sitemapindex xml file.
     */
    public function StartOB() {
        ob_start();
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        echo "\r\n";
        echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        echo "\r\n";
    }
    /**
     * End the sitemap xml file.
     */
    public function EndOB() {
        echo '</sitemapindex>';
        $ob_content = ob_get_contents();
        ob_end_flush();
        
        // Get the new file name, Add the file name sequence
        $this->file_nameseq++;
        $filename = "./" . $this->file_nameprefix . '-' . $this->file_nameseq . ".xml";
        // Write the file. Any existing file will be overwritten.
        file_put_contents($filename, $ob_content);
    }
}



/**
 * Start a new sitemap xml file.
 */
function SitemapStartOB() {
    ob_start();
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
    echo "\r\n";
}

/**
 * Echo one URL for the sitemap file.
 */
function SitemapEchoUrl($url, $priority = '0.7') {
    echo '<url>';
    echo '<loc>' . $url . '</loc>';
    echo '<changefreq>yearly</changefreq>';
    echo '<priority>' . $priority . '</priority>';
    echo '</url>';
    echo "\r\n";
}

/**
 * End the sitemap xml file.
 *
 * @param string $prefix File name prefix, example: 'wul-abap'
 * @param number $i File sequence number
 */
function SitemapEndOB($prefix, $i = NULL) {
    echo '</urlset>';

    $ob_content = ob_get_contents();
    ob_end_flush();
    if ($i === NULL) {
        $filename = "./" . $prefix . ".xml";
    } else {
        $filename = "./" . $prefix . $i . ".xml";
    }
    $ob_fp = fopen($filename, "w");
    fwrite($ob_fp, $ob_content);
    fclose($ob_fp);
}

function Sitemap4ABAPOType($obj_type, $list, $column_name, $fname_pre = NULL) {
    $fname_prefix = ($fname_pre === NULL) ? 'abap-' . $obj_type : $fname_pre;
    $i = 1;
    $j = 1;

    foreach ($list as $row) {
        if ($j == 1) {
            SitemapStartOB();
        }

        $obj_name = trim($row[$column_name]);
        if (strlen($obj_name) > 0) {
            $abapurl = GLOBAL_WEBSITE::URLPREFIX_SAPDS_ORG . ABAP_UI_DS_Navigation::GetObjectPath($obj_type, $obj_name);
            SitemapEchoUrl($abapurl);
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
}
