<?php
define("vw_version","1.0.1");

// // Php Mailer
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\SMTP;

// //Load Composer's autoloader
// require '../../vendor/autoload.php';


class MySQL {

  public $_dbc;
  
  function __construct($dbc) {
    $this->_dbc = $dbc;
  }

  /**
   * Get One Item Shortcut
   * <p>
   * Used to get a single item from a MYSQL Database
   * using Prepared Statements. Fastest querier out
   * of the three used here
   * 
   * @param $q The Prepared Query
   * @param $t The Prepared Variable Types, a single String with i,s,d,b for each of the variables
   * @param $p The Variable(s) for the query. If more than one variable use an array,order following $t
   * @param $dbc Database Connection
   * 
   * @return String A single item, treated as a string unless implicitly / explicitly used
   */
  function GetOneItem($q,$t,$p) {  
    $_tmpr = "";
    $r = $this->_dbc->prepare($q);
    if(is_array($p)) {
      $r->bind_param($t,...$p);
    } else {
      $r->bind_param($t,$p);
    }
    $r->execute();
    $r->store_result();
    // If row exists ----------------------
    if($r->num_rows > 0 ) {
      $r->bind_result($_tmpr);
      $r->fetch();
    } 
    $r->free_result();
    $r->close();

    return $_tmpr;
  }

  /**
   * Get One Row Shortcut
   * <p>
   * Used to get a single row from a MYSQL Database
   * using Prepared Statements. Second fastest querier out
   * of the three used here
   * 
   * @param $q The Prepared Query
   * @param $t The Prepared Variable Types, a single String with i,s,d,b for each of the variables
   * @param $p The Variable(s) for the query. If more than one variable use an array,order following $t
   * @param $dbc Database Connection
   * 
   * @return Array single row array
   */
  function GetOneRow($q,$t,$p,$dbc,$out_type=MYSQLI_ASSOC) {
    $_tmp = "";
    if(!$r = $this->_dbc->prepare($q)){
      return $this->_dbc->error;
    }
    if(is_array($p)) {
      if(!$r->bind_param($t,...$p)) {
        return $r->error;
      }
    } else {
      if(!$r->bind_param($t,$p)){
        return $r->error;
      }
    }
    $r->execute();
    $_r = $r->get_result();
    // If row exists ----------------------
    if($_r->num_rows > 0 ) {
      $_tmp = $_r->fetch_array($out_type);
    } 
    $r->free_result();
    $r->close();
    return $_tmp;
  }

  function GetOneRowNoParam($q) {
    $res = $this->_dbc->query($q);

    if($res->num_rows > 0) {
      $row = $res->fetch_assoc();
      $res->close();
    } else {
      $row = null;
    }
    return $row;
  }

  /**
   * Get Row Shortcut
   * <p>
   * Used to get all rows from a MYSQL Database
   * using Prepared Statements. Slowest querier out
   * of the three used here
   * 
   * @param $q The Prepared Query
   * @param $t The Prepared Variable Types, a single String with i,s,d,b for each of the variables
   * @param $p The Variable(s) for the query. If more than one variable use an array,order following $t
   * @param $dbc Database Connection
   * 
   * @return Array multi-row array, reminder that even one row is 2nd tier array $res[0][""]
   */
  function GetRow($q,$t,$p,$dbc,$out_type=MYSQLI_ASSOC) {  
    $_tmp = "";
    $r = $this->_dbc->prepare($q);
    if(is_array($p)) {
      if(!$r->bind_param($t,...$p)) {
        return $r->error;
      }
    } else {
      $r->bind_param($t,$p);
    }
    $r->execute();
    $_r = $r->get_result();
    $_tmp = array();
    // If row exists ----------------------
    if($_r->num_rows > 0 ) {
      $_tmp = $_r->fetch_all($out_type);
    } 
    $r->free_result();
    $r->close();

    return $_tmp;
  }

  function GetRowNoParam($q,$dbc,$out_type=MYSQLI_ASSOC) {  
    $r = $this->_dbc->query($q);
    $_tmp = [];
    /* $_tmp = array(); */
    // If row exists ----------------------
    if($r->num_rows > 0 ) {
      $_tmp = $r->fetch_all($out_type);
    } 
    $r->free_result();
    // $r->close();

    return $_tmp;
  }

  /**
   * Execute Prepared Statement Shortcut
   * <p>
   * Used to execute prepared Statements that are not queries, INSERT,UPDATE,DELETE etc
   * 
   * @param $q The Prepared Query
   * @param $t The Prepared Variable Types, a single String with i,s,d,b for each of the variables
   * @param $p The Variable(s) for the query. If more than one variable use an array,order following $t
   * @param $dbc Database Connection
   * 
   * @return bool/mixed Depending on statement. Insert gives inserted ID, the rest true, all gives error n false if error
   */
  function Exec_Prepared($q,$t,$p) {    
    if(!$r = $this->_dbc->prepare($q)) {
      return $this->_dbc->error;
      exit();
    }
    if(is_array($p)) {
      if(!$r->bind_param($t,...$p)) {
        return $r->error;
        exit();
      }
    } else {
      if(!$r->bind_param($t,$p)) {
        return $r->error;
        exit();
      }
    }
    $out = $r->execute();
    if($out) {
      if(strpos($q,'INSERT') !== False) {
        $out = $r->insert_id;
        $r->close();
        return $out;
      } else {
        $r->close();
        return true;
      }
    } else {
      $error = $r->error;
      $r->close();
      return $error;
    }
  }

  function ExecNoParam($q) {
    if(!$this->_dbc->query($q)) {
      return $this->_dbc->error;
      exit();
    } else {
      if(strpos(strtoupper($q),'INSERT') !== False) {
        $out = $this->_dbc->insert_id;
        return $out;
      } else {
        return true;
      }
    }
  }
  
}

// class Data_Encryption_Processing {

//   public $userDB;
//   public $keyCol;
//   public $encCol;
//   public $idCol;
//   public $isBase64 = false;

//   function __construct($DB,$keyName,$encName,$idName) {
//     $this->userDB = $DB;
//     $this->keyCol = $keyName;
//     $this->encCol = $encName;
//     $this->idCol = $idName;
//   }

//   function setBase64($yesNo) {
//     $this->isBase64 = $yesNo;
//   }

//   function createPasswordHash($password,$nonce) {
//     return hash_pbkdf2("sha256",$password,$nonce,200000,32);
//   }

//   function encryptCustomKey($source,$key,$nonce,$dbc) {
//     // Create a key from the given key and nonce
//     $createdKey = $this->createPasswordHash($key,$nonce);

//     $encryptedData = sodium_crypto_aead_aes256gcm_decrypt(
//       $source,
//       "",
//       $nonce,
//       $createdKey
//     );
//   }

//   function encryptData($source,$user_id,$passwordHash,$dbc) {
//       $source = (is_array($source)? json_encode($source) : $source);
//       $mysql = new MySQL();
//       // Get the Key
//       $getKey = $mysql->GetOneItem("SELECT $this->keyCol FROM $this->userDB WHERE $this->idCol = ?","i",$user_id,$dbc);
//       // Key is in Base64, Decode
//       $getKey = ($this->isBase64? base64_decode($getKey) : $getKey);
  
//       // Split key into components
//       $key = substr($getKey,0,strlen($getKey)-12-12);
//       $ukN = substr($getKey,strlen($getKey) - 12 -12,12);
//       // Decrypt the key
//       $decrpytedKey = sodium_crypto_aead_aes256gcm_decrypt(
//       substr($getKey,0,strlen($getKey)-12-12),
//       "",
//       $ukN,
//       $passwordHash
//       );
  
//       // Encrypt the data using the decrypted key
//       $nonce = random_bytes(12);
//       $encryptedData = sodium_crypto_aead_aes256gcm_encrypt(
//       $source,
//       "",
//       $nonce,
//       $decrpytedKey
//       );
  
//       $encryptedData = ($this->isBase64? base64_encode($encryptedData.$nonce) : $encryptedData.$nonce );
//       return $encryptedData;
//   }

//   function decryptData($source,$user_id,$passwordHash,$dbc,$forgot=false) {
//       $mysql = new MySQL();
//       // Normal Use, decrypt a key using passhash
//       if(!$forgot){  
//         // Get the Key
//         $getKey = $mysql->GetOneItem("SELECT $this->keyCol FROM $this->userDB WHERE $this->idCol = ?","i",$user_id,$dbc);
    
//         // Key is in Base64, Decode
//         $getKey = ($this->isBase64? base64_decode($getKey) : $getKey);
    
//         // Split key into components
//         $key = substr($getKey,0,strlen($getKey)-12-12);
//         $ukN = substr($getKey,strlen($getKey) - 12 -12,12);
//         $pkN = substr($getKey,strlen($getKey) - 12,12);
//         // Decrypt the key
//         $decrpytedKey = sodium_crypto_aead_aes256gcm_decrypt(
//             $key,
//             "",
//             $ukN,
//             $passwordHash
//         );
//         if($decrpytedKey === FALSE) {
//           return FALSE;
//         }
//       } else {
//         // Get the Key
//         $getKey = $mysql->GetOneItem("SELECT $this->encCol FROM $this->userDB WHERE $this->idCol = ?","i",$user_id,$dbc);
    
//         $getKey = ($this->isBase64? base64_decode($getKey) : $getKey);  

//         $DUK = sodium_crypto_aead_aes256gcm_decrypt(
//             substr($getKey,32,strlen($getKey) - 32 - 12),       /* EUK */
//             "",
//             substr($getKey,strlen($getKey)-12,12),              /* KeyNonce */
//             substr($getKey,0,32)                                /* Key */
//         );
//         $decrpytedKey = $DUK;
//       } 
  
//       // Source is Base64 encoded, decode first
//       $_source = ($this->isBase64? base64_decode($source) : $source);
//       // Nonce 
//       $nonce = substr($_source,strlen($_source)-12);
//       // Decrypt the data using the decrypted key
//       $encryptedData = sodium_crypto_aead_aes256gcm_decrypt(
//           substr($_source,0,strlen($_source)-12),
//           "",
//           $nonce,
//           $decrpytedKey
//       );
//       return $encryptedData;
//   }

//   function createEntry($database,$password,$keyCol,$encKeyCol,$idCol,$dbc) {
//     $mysql = new MySQL();
//     // Add an entry into the user db
//     $id = $mysql->ExecNoParam(" INSERT INTO $database VALUES ()",$dbc);
    
//     if(!is_numeric($id)) {
//       return retStatus("NOK",$id);       
//     }

//     // Create user key
//     $userKey = sodium_crypto_aead_aes256gcm_keygen();
//     // Create password key 
//         // Create a Nonce
//         $nonce = random_bytes(12);
//         // Create Key from PAssword
//         $passwordKey = hash_pbkdf2("sha256",$password,$nonce,200000,32);
    
//     // Encrypt User Key to be saved
//     $userKeyNonce = random_bytes(12);
//     $u_key = sodium_crypto_aead_aes256gcm_encrypt(
//         $userKey,
//         "",
//         $userKeyNonce,
//         $passwordKey
//     );
    
//     $u_key = $u_key.$userKeyNonce.$nonce;

//     // Key in case forgotten password
//     $forgotNonce = random_bytes(12);
//     $forgetKey = sodium_crypto_aead_aes256gcm_keygen();
//     $encryptedUserKey = sodium_crypto_aead_aes256gcm_encrypt(
//         $userKey,
//         "",
//         $forgotNonce,
//         $forgetKey
//     );

//     $encryptedUserKey = $forgetKey.$encryptedUserKey.$forgotNonce;

//     // Save key
//     $vals = array($u_key,$encryptedUserKey,$id);
//     $updateKey = $mysql->Exec_Prepared("    UPDATE
//                                               $database
//                                             SET                                            
//                                               $keyCol = ?,
//                                               $encKeyCol = ?
//                                             WHERE
//                                               $idCol = ?","ssi",$vals,$dbc);
//     return retStatus("OK",array(
//       "userID" => $id,
//       "status" => true,
//       "passHash" => $passwordKey,
//       "nonce" => $nonce
//     ));
//   }

//   function forgetPassword($newPassword,$id,$dbc) {
//     $mysql = new MySQL();
//     // Decrypt Key
//     $getKey = $mysql->GetOneItem("SELECT $this->encCol FROM $this->userDB WHERE $this->idCol = ?","i",$id,$dbc);
//     $getKey = ($this->isBase64? base64_decode($getKey) : $getKey);
//     // Split key into components
//     $keyNonce = substr($getKey,strlen($getKey)-12,12);
//     $key = substr($getKey,0,32);
//     $EUK = substr($getKey,32,strlen($getKey) - 32 - 12);

//     $DUK = sodium_crypto_aead_aes256gcm_decrypt(
//         $EUK,
//         "",
//         $keyNonce,
//         $key
//     );
//     // Encrypt user key using new password
//     $newPasswordNonce = random_bytes(12);
//     $passwordKey = $this->createPasswordHash($newPassword,$newPasswordNonce);
//     $newUKNonce = random_bytes(12);
//     $newEUK = sodium_crypto_aead_aes256gcm_encrypt(
//         $DUK,
//         "",
//         $newUKNonce,
//         $passwordKey
//     );
//     $newEUK = $newEUK.$newUKNonce.$newPasswordNonce;
    
//     // Update Other Key
//     $newForgotNonce = random_bytes(12);
//     $newForgotKey = sodium_crypto_aead_aes256gcm_keygen();
//     $encryptedForgetKey = sodium_crypto_aead_aes256gcm_encrypt(
//         $DUK,
//         "",
//         $newForgotNonce,
//         $newForgotKey
//     );


//     $encryptedForgetKey = $newForgotKey.$encryptedForgetKey.$newForgotNonce;
//     // Save keys
//     $vals = array($newEUK,$encryptedForgetKey,$id);
//     $updateKey = $mysql->Exec_Prepared("    UPDATE
//                                         $this->userDB
//                                     SET                                            
//                                         $this->keyCol = ?,
//                                         $this->encCol = ?
//                                     WHERE
//                                         $this->idCol = ?","ssi",$vals,$dbc);
//     return $updateKey;
//   }

//   function decodeTag($tag) {
//     $tag = urldecode(base64_decode($tag));

//     $pass = substr($tag,0,64);
//     $tag = substr($tag,64);
//     $nonce = substr($tag,0,24);
//     $tag = substr($tag,24);

//     return sodium_crypto_aead_aes256gcm_decrypt(hex2bin($tag),'',hex2bin($nonce),hex2bin($pass));
//   }

//   function createTag($id) {
//     $randPass = random_bytes(32);
//     $randNonce = random_bytes(12);

//     $tag = bin2hex(sodium_crypto_aead_aes256gcm_encrypt($id.":".date('U'),'',$randNonce,$randPass));

//     return urlencode(base64_encode(bin2hex($randPass).bin2hex($randNonce).$tag));    
//   }
// }

// function emailTemplates($type,$data=[]) {
//   switch($type) {
//     case "RESET_PASS":
//       $body = 
//         '<table width="100%" border="0" cellspacing="0" cellpadding>
//           <tr>
//               <th style="height:10vh;"></th>
//               <th style="height:10vh;"></th>
//               <th style="height:10vh;"></th>
//           </tr>
//           <tr>
//               <td style="width:100vw">
//                   <table style="padding: 1em;" width="100%" border="0" cellspacing="0" cellpadding>
//                       <tr>
//                           <th><img src="https://ccec.my/assets/image/logo/chaching.png" alt="Some Logo"></th>
//                       </tr>
//                       <tr>
//                           <th style="font-size: 2em;font-weight: 800; padding-bottom:2em">Password Request Successful!</th>
//                       </tr>  
//                       <tr>
//                           <td style="font-size: 1em;font-weight: 800;padding-bottom: 1em;">
//                             <p>Your account password was successfully updated!</p>             
//                             <p style="text-align: center;">
//                               <a 
//                               style="
//                                 margin: auto;
//                                 width: 40%;
//                                 height: 1.3em;
//                                 font-size: 1.4em;
//                                 background: #75ccff;
//                                 padding: 0.5em 1em;
//                                 border-radius: 1em;
//                                 text-decoration: none;
//                                 border: 1px solid darkblue;" 
//                               href="https://ccec.my/">Return to ccec.my !</a>
//                             </p>                    
//                           <td>
//                       </tr>
//                       <tr>
//                           <td>
//                               <table style="border-top:1px solid;font-size: 0.8em;font-style: italic;" width="100%" border="0" cellspacing="0" cellpadding>
//                                   <tr>
//                                       <td>This email is sent from ccec.my to '.$data['email'].'</td>
//                                   </tr>
//                               </table>
//                           </td>    
//                       </tr>
//                   </table>
//               </td>
//           </tr>
//       </table>';
//       break;
//     case "FOR_PASS":
//       $id = base64_encode((int)$data['id']^0xFFFFFFFF);
//       $body = 
//         '<table width="100%" border="0" cellspacing="0" cellpadding>
//           <tr>
//               <th style="height:10vh;"></th>
//               <th style="height:10vh;"></th>
//               <th style="height:10vh;"></th>
//           </tr>
//           <tr>
//               <td style="width:100vw">
//                   <table style="padding: 1em;" width="100%" border="0" cellspacing="0" cellpadding>
//                       <tr>
//                           <th><img src="https://ccec.my/assets/image/logo/chaching.png" alt="Some Logo"></th>
//                       </tr>
//                       <tr>
//                           <th style="font-size: 2em;font-weight: 800; padding-bottom:2em">Forgot Password</th>
//                       </tr>  
//                       <tr>
//                           <td style="font-size: 1em;font-weight: 800;padding-bottom: 1em;">
//                             <p>We received a password reset request for this email</p>
//                             <p>To proceed with the password reset request, please follow the link below</p>                    
//                             <p style="text-align: center;">
//                               <a 
//                               style="
//                                 margin: auto;
//                                 width: 40%;
//                                 height: 1.3em;
//                                 font-size: 1.4em;
//                                 background: #75ccff;
//                                 padding: 0.5em 1em;
//                                 border-radius: 1em;
//                                 text-decoration: none;
//                                 border: 1px solid darkblue;" 
//                               href="https://ccec.my/password_req.php?req='.$data['tag'].'&tag='.$id.'&reason=PASS_RESET&source=PASS_RESET_PAGE">Reset Password</a>
//                             </p>                    
//                           <td>
//                       </tr>
//                       <tr>
//                           <td>
//                               <table style="border-top:1px solid;font-size: 0.8em;font-style: italic;" width="100%" border="0" cellspacing="0" cellpadding>
//                                   <tr>
//                                       <td>This email is sent from ccec.my to '.$data['email'].'</td>
//                                   </tr>
//                               </table>
//                           </td>    
//                       </tr>
//                   </table>
//               </td>
//           </tr>
//       </table>';
//       break;
//     case "REG_USER":
//       $body = 
//         '<table width="100%" border="0" cellspacing="0" cellpadding>
//           <tr>
//               <th style="height:10vh;"></th>
//               <th style="height:10vh;"></th>
//               <th style="height:10vh;"></th>
//           </tr>
//           <tr>
//               <td style="width:100vw">
//                   <table style="
//                       padding: 1em;" width="100%" border="0" cellspacing="0" cellpadding>
//                       <tr>
//                           <th><img src="https://ccec.my/assets/image/logo/chaching.png" alt="Some Logo"></th>
//                       </tr>
//                       <tr>
//                           <th style="font-size: 2em;font-weight: 800; padding-bottom:2em">User Registration</th>
//                       </tr>                      
//                       <tr>
//                         <td style="font-size: 1.3em;padding-bottom:0.5em">Hi '.$data['Name'].',</td>
//                       </tr>
//                       <tr>
//                           <td style="font-size: 1em;font-weight: 800;padding-bottom: 1em;">
//                             <p>An account has been registered at  <a href="https://ccec.my/">CCEC.my</a> using this email address.</p>
//                             <p>If this is not you, please contact <a href="mailto:secretariat@ccec.my">cectretariat@ccec.my</a></p>                    
//                             <p style="text-align: center;">
//                               <a 
//                               style="
//                                 margin: auto;
//                                 width: 40%;
//                                 height: 1.3em;
//                                 font-size: 1.4em;
//                                 background: #75ccff;
//                                 padding: 0.5em 1em;
//                                 border-radius: 1em;
//                                 text-decoration: none;
//                                 border: 1px solid darkblue;" 
//                               href="https://ccec.my/">Return to ccec.my !</a></a></p>                    
//                           <td>
//                       </tr>
//                       <tr>
//                           <td>
//                               <table style="border-top:1px solid;font-size: 0.8em;font-style: italic;" width="100%" border="0" cellspacing="0" cellpadding>
//                                   <tr>
//                                       <td>This email is sent from ccec.my to '.$data['email'].'</td>
//                                   </tr>
//                               </table>
//                           </td>    
//                       </tr>
//                   </table>
//               </td>
//           </tr>
//       </table>';
//       break;
//   }
//   return $body;
// }

// function mailer($body,$to,$from = "",$subject,$attachment=false) {  

//   try{
//     $mail = new PHPMailer(true);

//     // $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;                      //Enable verbose debug output
    
//     //Server settings
//     $mail->isSMTP();                                            // Send using SMTP
//     $mail->Host       = '';                      // Set the SMTP server to send through
//     $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
//     $mail->Username   = '';             // SMTP username
//     $mail->Password   = '';                         // SMTP password
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
//     $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

//     //Recipients
//     $mail->setFrom($from, '');
//     $mail->addAddress($to);     // Add a recipient  // Name is optional
//     // $mail->addReplyTo('info@example.com', 'Information');

//     // Attachments
//       if($attachment === false) {

//       } else {
//         $mail->addAttachment($attachment);    // Optional name
//       }

//     // Content
//     $mail->isHTML(true);                                  // Set email format to HTML
//     $mail->Subject = $subject;
//     $mail->Body    = $body;

//     $mail->send();
//     return true;
//   } catch (Exception $e) {
//     return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//   }
// }

function exitStatus($type="OK",$extraData="") {
  $debug = debug_backtrace();
  $from = substr($debug[0]['file'],strrpos($debug[0]['file'],"\\")+1);
  $from = substr($from,0,3).(strpos($from,"_") > 0? substr($from,strpos($from,"_")+1,3) : "");
  $data = ( isset($extraData->error) ?  $extraData->error : $extraData );
  $code = ($type = "OK" ? "OK" : $from."-".$debug[0]['line']);
  echo json_encode(array(
    "status" => $type,
    "data" => $data,
    "code" => $code
  ));
  return;
}

/**
 * Return Status
 * Same like exitStatus except it doesnt print the status out but sends an array for function to read
 */
function retStatus($type="OK",$extraData=[]) {
  $debug = debug_backtrace();
  $from = substr($debug[0]['file'],strrpos($debug[0]['file'],"\\")+1);
  $from = substr($from,0,3).(strpos($from,"_") > 0? substr($from,strpos($from,"_")+1,3) : "");
  $data = ( isset($extraData->error) ?  $extraData->error : $extraData );
  $code = ($type = "OK" ? "OK" : $from."-".$debug[0]['line']);
  return array(
    "status" => $type,
    "data" => $data,
    "code" => $code
  ); 
}


?>