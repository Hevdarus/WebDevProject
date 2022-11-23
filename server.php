<?php
session_start();

$username = "";
$errors = array();
$success = array();

$db = mysqli_connect('localhost', 'root', '', 'webbeadando');

function readlines_from_file(string $filename)
{
    $lines = array();

    $myfile = fopen($filename, "r") or die("Unable to open file!");

    while (!feof($myfile)) {
        $temp = fgets($myfile);
        $lines[] = $temp;
    }

    fclose($myfile);

    return $lines;
}

if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = $_POST['password'];

    if (empty($username)) {
        array_push($errors, "Adja meg a felhasználónevét");
    }
    if (empty($password)) {
        array_push($errors, "Adja meg a jelszavát");
    }

    if (count($errors) == 0) {
        $query = "SELECT * FROM tabla WHERE Username='$username'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $lines = readlines_from_file("password.txt");

            $decoder = [5, -14, 31, -9, 3];
            foreach ($lines as $line) {
                $d = 0;
                $email = "";
                $email_found = false;
                $psw = "";

                for ($i = 0; $i < strlen($line) - 1 ; $i++) {
                    $ascii = ord($line[$i]);

                    if (chr($ascii - $decoder[$d % 5]) == '*') {
                        if($email == $username){
                            $email_found = true;
                            $d++;
                            
                        }else{
                            continue 2;
                        }

                    } else if(!$email_found) {
                        $char = chr($ascii - $decoder[$d % 5]);
                        $email = $email . $char;
                        $d++;
                    } else{
                        $char = chr($ascii - $decoder[$d % 5]);
                        $psw = $psw . $char;
                        $d++;
                    }
                }

                if($psw != ""){
                    if($psw == $password){
                        $color = mysqli_fetch_row($results)[2];
                        array_push($success, "Üdv, ". $username."! <br>
                        A kedvenc színed: ". $color);
                        break;
                    }else{
                        array_push($errors, "Hibás jelszó!");
                        break;
                    }
                }
            }

        } else {
            array_push($errors, "Nincs ilyen felhasználó!");
        }
    }
}

if (isset($_POST['decode'])) {
    $chars = "";
    $decoder = [5, -14, 31, -9, 3];
    $d = 0;

    $myfile = fopen("password.txt", "r") or die("Unable to open file!");

    while (!feof($myfile)) {
        $temp = fgetc($myfile);
        $chars = $chars . $temp;
    }

    fclose($myfile);

    $myfile = fopen("decoded.txt", "w") or die("Unable to open file!");

    for ($i = 0; $i < strlen($chars); $i++) {
        $ascii = ord($chars[$i]);
        if ($ascii == 10) {
            fwrite($myfile, PHP_EOL);
            $d = 0;
        } else {
            fwrite($myfile, chr($ascii - $decoder[$d % 5]));
            $d++;
        }


    }

    fclose($myfile);

}

?>
