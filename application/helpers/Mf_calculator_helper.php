<?php defined('BASEPATH') OR exit('No direct script access allowed');

  function get_mf_details($from_date,$to_date)
  {
    $url = "http://portal.amfiindia.com/DownloadNAVHistoryReport_Po.aspx?mf=53&tp=1&frmdt=".$from_date."&todt=".$to_date;
    $data = get_web_page( $url );
    $comma_seperated = str_replace(";",",",$data);
    create_csv_file($comma_seperated);
  }

  function get_web_page( $url )
  {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $content;
  }

  function create_csv_file($file_contents)
  {
    if(!file_exists(get_mf_file_name())):
        $file = fopen(get_mf_file_name(),"w");
        fwrite($file,$file_contents);
        fclose($file);
    endif;
  }

  function get_mf_file_name()
  {
    return "downloads/mf-".date("d-M-Y").".csv";
  }
?>