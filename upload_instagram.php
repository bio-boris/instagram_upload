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
$debug = false;
$base_dir = 'images';
$image_dir = "$base_dir/todo";
$completed_dir="$base_dir/done";
$number_of_files_to_upload = rand(1,10);
$number_of_files_to_upload = 2;

//////////////////////
$it = new RecursiveDirectoryIterator($image_dir);
$allowed = Array ( 'jpeg', 'jpg','png' );
$files = Array();
foreach(new RecursiveIteratorIterator($it) as $file)
{
    foreach(new RecursiveIteratorIterator($it) as $file) {
        if(in_array(substr($file, strrpos($file, '.') + 1),$allowed)) {
            $files[] = $file;
        }
    }
}
$count = count($files);
$number_of_files_to_upload = $number_of_files_to_upload <= $count ? $number_of_files_to_upload : $count;

///////LOGIN

$msg = "About to LOGIN to instagram"; 
fwrite(STDERR, "$msg\n");

$instagram = new \InstagramAPI\Instagram($debug);
$instagram->setUser($username, $password);
try {
    $instagram->login(true);
} catch (Exception $e) {
    $e->getMessage();
    print "Couldn't login";
    exit();
    }

///UPLOAD    
for($i = 1 ; $i<= $number_of_files_to_upload; $i++){

    shuffle($files);
    $photo = array_pop($files);

    //Not Logged
    $msg = "About to upload $photo"; 
    fwrite(STDERR, "$msg\n");

#Logging and renaming
  try {
        $caption = "";
        $instagram->uploadPhoto($photo, $caption);
        $success=1;
    } catch (Exception $e) {
        $success=0;
        print $e->getMessage();
        print "Failed to upload $photo\t" . date(DATE_RFC2822) . "\n";
    }
    if($success==1){
        $fn = basename($photo);
        rename($photo,"$completed_dir/$fn");
        print "Successfully uploaded $photo\t" . date(DATE_RFC2822) . "\n"; 
        
        //Not logged 
        $msg = "About to upload $photo"; 
        fwrite(STDERR, "$msg\n"); 
        sleep(10);
    }
}

fwrite(STDERR, "\n\n");
?>
