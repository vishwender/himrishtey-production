<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function interest_received($profile_id, $email, $user)
{
    echo "here"; die;
    $message = "New Interest Received From " . $user;
    $title = "New Interest Received";
    $this->email->from('info@himrishtey.com', 'Himrishtey Marriage Bureau'); 
    $this->email->to($email);
    $this->email->subject($title); 
    $this->email->message('A new interest has been received from profile ID "' . $profile_id . '", Please check your account : HIMRMB');
    
    return $this->email->send();    
}


?>