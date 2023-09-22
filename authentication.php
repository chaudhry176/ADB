<?php
    
    include('dbconfig.php');
    $muse_client = 1;
    $current_time = time();

    $sql = "SELECT * FROM `credentials` WHERE `muse_client_id` = '$muse_client' AND `adp_type`  = 'adp_now' order by id desc";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($result)) {

        $client_id = $row['client_id'];
        $client_secret = $row['client_secret'];
        $certificate_key = $row['certificate_key_file'];
        $certificate_private = $row['certificate_private_file'];
        $credentials_id = $row['id'];
        $access_token_expiry = $row['access_token_expiry'];
        $access_token = $row['access_token'];
        $adp_organization_id = $row['adp_organization_id'];


    if($access_token_expiry>$current_time){
      
      $access_token_adps['adp_now'] = $access_token;
      // echo "Token is valid";
    }
    else {

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_SSLCERT, "certificates/$certificate_key");
    curl_setopt($curl, CURLOPT_SSLKEY, "certificates/$certificate_private");
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.adp.com/auth/oauth/v2/token",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=$client_id&client_secret=$client_secret",
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
   // echo $response;




    $response = json_decode($response,TRUE);
    $access_token = $response['access_token'];
    $token_expire = $current_time + 3500;

      // echo $access_token;
    
?>

<?php

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_SSLCERT, "certificates/$certificate_key");
    curl_setopt($curl, CURLOPT_SSLKEY, "certificates/$certificate_private");
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.adp.com/events/core/v1/consumer-application-subscription-credentials.read',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
      "events": [
        {
          "serviceCategoryCode": {
            "codeValue": "core"
            },
          "eventNameCode": {
            "codeValue": "consumer-application-subscription-credential.read"
            },
          "data": {
          "transform": {
              "queryParameter": "$filter=subscriberOrganizationOID eq \''.$adp_organization_id.'\'"
            }
          }
        }
      ]
    }',
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $access_token",
        'Content-Type: application/json',
        'Cookie: BIGipServerp_mkplproxy-dc1=3194028299.20480.0000; BIGipServerp_mkplproxy-dc2=670892811.20480.0000'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    //echo $response;
    $response = json_decode($response,TRUE);
    
    foreach($response['events'] as $events){
        $consumerApplicationSubscriptionCredentials = $events['data']['output']['consumerApplicationSubscriptionCredentials'];
        foreach($consumerApplicationSubscriptionCredentials as $credentials){
            echo $client_id = $credentials['clientID'];
            echo " / ".$client_secret = $credentials['clientSecret'];
            break;
        }
        break;
    }
    
    //echo $client_id . $client_secret;
    
    
    
        
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_SSLCERT, "certificates/$certificate_key");
    curl_setopt($curl, CURLOPT_SSLKEY, "certificates/$certificate_private");
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.adp.com/auth/oauth/v2/token',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=$client_id&client_secret=$client_secret",
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    // echo $response;
    $response = json_decode($response,TRUE); 
    
    $access_token = $response['access_token'];
    $access_token_adps['adp_now'] = $access_token;

    mysqli_query($conn,"UPDATE `credentials` SET `access_token`='$access_token',`access_token_created_time`='$current_time',`access_token_expiry`='$token_expire' WHERE `id` = '$credentials_id'");
    }

}

?>

<?php
    
    include('dbconfig.php');
    $muse_client = 1;
    $current_time = time();

    $sql = "SELECT * FROM `credentials` WHERE `muse_client_id` = '$muse_client' AND `adp_type`  = 'adp_powered' order by id desc";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($result)) {

        $client_id = $row['client_id'];
        $client_secret = $row['client_secret'];
        $certificate_key = $row['certificate_key_file'];
        $certificate_private = $row['certificate_private_file'];
        $credentials_id = $row['id'];
        $access_token_expiry = $row['access_token_expiry'];
        $access_token = $row['access_token'];
        $adp_organization_id = $row['adp_organization_id'];

    if($access_token_expiry>$current_time){
        $access_token_adps['adp_powered_token'] = $access_token;
      // echo "Token is valid";
    }
    else {

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_SSLCERT, "certificates/$certificate_key");
    curl_setopt($curl, CURLOPT_SSLKEY, "certificates/$certificate_private");
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.adp.com/auth/oauth/v2/token",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=$client_id&client_secret=$client_secret",
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
   // echo $response;




    $response = json_decode($response,TRUE);
    $access_token = $response['access_token'];
    $token_expire = $current_time + 3500;

      // echo $access_token;
    
?>

<?php

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_SSLCERT, "certificates/$certificate_key");
    curl_setopt($curl, CURLOPT_SSLKEY, "certificates/$certificate_private");
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.adp.com/events/core/v1/consumer-application-subscription-credentials.read',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
      "events": [
        {
          "serviceCategoryCode": {
            "codeValue": "core"
            },
          "eventNameCode": {
            "codeValue": "consumer-application-subscription-credential.read"
            },
          "data": {
          "transform": {
              "queryParameter": "$filter=subscriberOrganizationOID eq \''.$adp_organization_id.'\'"
            }
          }
        }
      ]
    }',
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $access_token",
        'Content-Type: application/json',
        'Cookie: BIGipServerp_mkplproxy-dc1=3194028299.20480.0000; BIGipServerp_mkplproxy-dc2=670892811.20480.0000'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    //echo $response;
    $response = json_decode($response,TRUE);
    
    foreach($response['events'] as $events){
        $consumerApplicationSubscriptionCredentials = $events['data']['output']['consumerApplicationSubscriptionCredentials'];
        foreach($consumerApplicationSubscriptionCredentials as $credentials){
            $client_id = $credentials['clientID'];
            $client_secret = $credentials['clientSecret'];
            break;
        }
        break;
    }
    
    //echo $client_id . $client_secret;
    
    
    
        
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_SSLCERT, "certificates/$certificate_key");
    curl_setopt($curl, CURLOPT_SSLKEY, "certificates/$certificate_private");
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.adp.com/auth/oauth/v2/token',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=$client_id&client_secret=$client_secret",
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    // echo $response;
    $response = json_decode($response,TRUE); 
    
     $access_token = $response['access_token'];
     $access_token_adps['adp_powered_token'] = $access_token;
    mysqli_query($conn,"UPDATE `credentials` SET `access_token`='$access_token',`access_token_created_time`='$current_time',`access_token_expiry`='$token_expire' WHERE `id` = '$credentials_id'");
    }

    $access_tokens_main['tokens'] = ($access_token_adps);
    $authentication  = json_encode($access_tokens_main);
    $authentication  = json_decode($authentication,TRUE);
}

?>