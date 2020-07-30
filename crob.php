<?php
// if ( !defined('_PS_VERSION_') ) exit;

// You can now access this controller from /your_admin_directory/index.php?controller=AdminIcecatAdmin
// echo 'ok';
$site_base_path="../../";
require_once($site_base_path . 'config/config.inc.php'); 
require_once($site_base_path . 'init.php');
if(Configuration::get('ICECAT_FULLINDEX_LOADED') && Configuration::get('ICECAT_CATEGORIESLIST_LOADED') && Configuration::get('ICECAT_SUPPLIERSLIST_LOADED'))
{
    $crons = Db::getInstance()->executeS('SELECT *
        FROM `'._DB_PREFIX_.'supplier_feeds`'
    );
    foreach($crons as $cron){

        if($cron['table_created']=="1"){
            $now = time();
            $last_execution = (int)$cron['last_execution'];
            $interval = (int)$cron['interval'];

            if($now - $last_execution > $interval * 3600){
                
                $path = $cron['url'];
                $table_name = _DB_PREFIX_ . 'supplier_feed_' . $cron['meta_title'];
                $delimiter = $cron['delimiter'];
                $table_fields = $cron['fields'];
                $handle = fopen($path, "r");
                $count = 0;
                if($cron['header_exists']=="1")$line = trim(fgets($handle));

                while(!feof($handle)) {

                    $line = trim(fgets($handle));
                    $table_field_list = explode(",", $table_fields);
                    $value_list = explode($delimiter, $line);
                    $updated_values = [];

                    foreach($value_list as $key => $value){
                        $field_name = $table_field_list[$key];
                        $updated_values["$field_name"] = $value;
                    }

                    $manufacturer = $updated_values['manufacturer'];
                    $model = $updated_values['model'];

                    $exits = Db::getInstance()->executeS("SELECT * FROM `$table_name` WHERE `manufacturer` = '$manufacturer' AND `model` = '$model' LIMIT 1"
                    );

                    if($exits){
                        Db::getInstance()->update(
                            'supplier_feed_' . $cron['meta_title'],
                            $updated_values,
                            '`manufacturer` = \''.$manufacturer.'\' AND `model` = \''.$model.'\''
                        );
                    } else {
                        $values=implode("','",explode($delimiter, $line));
                        $sql = "INSERT INTO $table_name ($table_fields) VALUES ('$values')";  
                        Db::getInstance()->execute($sql);
                    }

                    $count ++;
                    if($count > 0) break;
                }

                fclose($handle);

                $values = array(
                    'last_execution' => time()
                );
                Db::getInstance()->update(
                    'supplier_feeds',
                    $values,
                    'id_supplier = '.$cron['id_supplier']
                );
            }
        }
    }
}
else
{
    // echo date('H');
    $icecat_paths = [
        'full' => [
            'index' => "data.icecat.biz/export/level4/EN/files.index.csv.gz",
            'daily' => "data.icecat.biz/export/level4/EN/daily.index.xml.gz",
            'category' => "data.Icecat.biz/export/level4/refs/CategoriesList.xml.gz",
            'supplier' => "data.Icecat.biz/export/level4/refs/SuppliersList.xml.gz",
            'taxonomy' => "data.Icecat.biz/export/level4/refs/CategoryFeaturesList.xml.gz"
        ],
        'open' => [
            'index' => "data.icecat.biz/export/freexml/EN/files.index.csv.gz",
            'daily' => "data.icecat.biz/export/freexml/EN/daily.index.xml.gz",
            'category' => "data.Icecat.biz/export/freexml.int/refs/CategoriesList.xml.gz",
            'supplier' => "data.Icecat.biz/export/freexml.int/refs/SuppliersList.xml.gz",
            'taxonomy' => "data.Icecat.biz/export/freexml.int/refs/CategoryFeaturesList.xml.gz"
        ]
    ];

    $icecat_login = Configuration::get('ICECAT_LOGIN');
    $icecat_password = Configuration::get('ICECAT_PASSWORD');
    $icecat_type = 'open';
    
    $start_time = time();

    // // Icecat index

    // echo "<br> index update->";

    // if(getCSV("http://".$icecat_login.":".$icecat_password."@".$icecat_paths[$icecat_type]['index']))
    //     echo "<br> index update complete!";
    // else
    //     echo "<br> index update failed!";
    
    // Icecat daily index

    // echo "<br> index update->";

    // if(icecatIndexTable(getXML("http://".$icecat_login.":".$icecat_password."@".$icecat_paths[$icecat_type]['daily'])))
    //     echo "<br> index update complete!";
    // else
    //     echo "<br> index update failed!";

    // Icecat categories list

    echo "<br> category update->";

    if(icecatCategoryTable(getXML("http://".$icecat_login.":".$icecat_password."@".$icecat_paths[$icecat_type]['category'])))
        echo "<br> category update complete!";
    else
        echo "<br> category update failed!";

    // Icecat suppliers list

    // echo "<br> supplier update->";

    // if(icecatBrandTable(getXML("http://".$icecat_login.":".$icecat_password."@".$icecat_paths[$icecat_type]['supplier'])))
    //     echo "<br> supplier update complete!";
    // else
    //     echo "<br> supplier update failed!";
    
    echo time()-$start_time;
    // echo "<br>";
    // require_once "memory.php";
}

function icecatIndexTable($xml)
{
    if (ob_get_level() == 0) ob_start();
    echo "<br> xml to json parsing...";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();
    
    $fileIndexes = $xml->children()[0];
    $p = [];
    foreach($fileIndexes as $file)
        $p[(int)$file['Supplier_id']][(string)$file['Prod_ID']] = (int)$file['Product_ID'];
    
    $xml = null;
    
    echo "<br> processing... ";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $tempfile = "temp_index";
    $fh = fopen($tempfile, "w");
    fwrite($fh, str_replace("'", "\'", json_encode($p)));
    fclose($fh);
    
    ob_end_flush();

    return true;
}

function icecatCategoryTable($xml) 
{
    if (ob_get_level() == 0) ob_start();
    echo "<br> xml to json parsing...";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $cateories_list = $xml->children()[0]->children()[0];
    $c = [];
    foreach($cateories_list as $category) {
        $ci=(int)$category['ID'];
        $cp=(int)$category->ParentCategory['ID'];
        $cn=(string)$category->Name[0]['Value'];
        if(strlen($cn)==0)continue;
        $c[$ci]['title'] = $cn;
        $c[$ci]['id'] = $ci;
        $c[$cp]['subs'][] = &$c[$ci];
    }

    $xml = null;
    
    echo "<br> storing... ";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $values = array(
        'data' => str_replace("'", "\'", json_encode($c[1]))
    );

    $c = null;
    
    ob_end_flush();
    
    return Db::getInstance()->update(
        'icecat',
        $values,
        '`list_name` = \'category\''
    );

}

function icecatBrandTable($xml) 
{
    if (ob_get_level() == 0) ob_start();
    echo "<br> xml to json parsing...";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $suppliers_list = $xml->children()[0]->children()[0];
    $s=[];
    foreach($suppliers_list as $supplier)
        $s[(int)$supplier['ID']] = (string)$supplier['Name'];
    
    $xml = null;
    
    echo "<br> storing... ";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $values = array(
        'data' => str_replace("'", "\'", json_encode($s))
    );

    $s = null;
    
    ob_end_flush();
    
    return Db::getInstance()->update(
        'icecat',
        $values,
        '`list_name` = \'supplier\''
    );
}

function getXML($path)
{
    if (ob_get_level() == 0) ob_start();
    echo "<br> downloading...";
    echo str_pad('',4096)."\n";   
    ob_flush();
    flush();

    $ch = curl_init($path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    
    $data = curl_exec($ch);
    // $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $tempfile = "temp";
    $fh = fopen($tempfile, "w");
    fwrite($fh, $data);
    fclose($fh);
    curl_close($ch);
    $data = null;

    echo "<br> unzipping...";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $sfp = gzopen($tempfile, "rb");
    $string = gzread($sfp,100*1024*1024);
    gzclose($sfp);

    echo "<br> xml loading...";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $xml = simplexml_load_string($string) or die("Error: Cannot create object");
    $string = null;

    ob_end_flush();

    return $xml;
}

function getCSV($path)
{
    if (ob_get_level() == 0) ob_start();
    echo "<br> downloading...";
    echo str_pad('',4096)."\n";   
    ob_flush();
    flush();

    $ch = curl_init($path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    
    $data = curl_exec($ch);
    // $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $tempfile = "temp";
    $fh = fopen($tempfile, "w");
    fwrite($fh, $data);
    fclose($fh);
    curl_close($ch);
    $data = null;

    echo "<br> unzipping...";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $tempfile = "temp"; 
    $sfp = gzopen($tempfile, "rb");
    $tempfile1 = "temp1";
    $fh = fopen($tempfile1, "w");
    
    while (!gzeof($sfp))
    {
        $string = gzread($sfp,100*1024*1024);
        fwrite($fh, $string);
    }
    fclose($fh);
    gzclose($sfp);
    
    $string = null;

    echo "<br> csv loading...";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $fh = fopen($tempfile1, 'r');
    $p = [];
    $data = fgetcsv($fh, 0, "\t");
    $count = 0; $percent = 0;
    while ($data = fgetcsv($fh, 0, "\t"))
    {
        $p[(int)$data[4]][(string)$data[5]] = (int)$data[1];
        $count++;
        if($count>100000){
            $count = 0;
            $percent++;

            if($percent<100) echo "<br> $percent% ";
            echo str_pad('',4096)."\n"; 
            ob_flush();
            flush();
        }
    }

    echo "<br> 100% ";
    fclose($fh);

    echo "<br> processing... ";
    echo str_pad('',4096)."\n"; 
    ob_flush();
    flush();

    $tempfile = "temp_index";
    $fh = fopen($tempfile, "w");
    fwrite($fh, str_replace("'", "\'", json_encode($p)));
    fclose($fh);
    ob_end_flush();

    return true;
}