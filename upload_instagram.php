<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("vendor/autoload.php");
$instagram = new \InstagramAPI\Instagram();

/////// CONFIG ///////
$username = 'bsadkhin2';
$password = 'password123!';
$log = "log.txt";
$debug = true;
$base_dir = 'images';
$image_dir = "$base_dir/todo";
$completed_dir="$base_dir/done";
$number_of_files_to_upload = rand(1,10);

//////////////////////
$command= "find $image_dir -type f";
$files = array();
exec($command,$files);


for($i = 0 ; $i< $number_of_files_to_upload; $i++){
    shuffle($files);
    $image = array_pop($files); 
    print "About to upload $image"; 

    $i = new \InstagramAPI\Instagram($debug);
    $i->setUser($username, $password);
    try {
        $i->login();
    } catch (Exception $e) {
        $e->getMessage();
        print "Couldn't login";
        print "\t " . date(DATE_RFC2822) . "\n";
        exit();
    }
    try {
        $caption = "";
        $i->uploadPhoto($photo, $caption);
        $success=1;
    } catch (Exception $e) {
        $success=0;
        echo $e->getMessage();
        print "UPLOAD FAIL " . $photo;
    }
    if($success==1){
        $fn = basename($photo);
        $rename($photo,"$completed_dir/$fn");
    }
}

print "\t " . date(DATE_RFC2822) . "\n";
?>
