<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('profile_model','pro');
        $this->load->model('user_model','usr');
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	 
	 public function index()
	 {
	     echo "hello";
	 }

public function myemail(){
    $this->email->from('info@himrishtey.com', 'Himrishtey Marriage Bureau');
                $this->email->to('choudharyrajesh488@gmail.com');
                 
                $this->email->subject('Thanks for register');
                $this->email->message('Thanks for register with Himrishtey Marriage Bureau. Please complete your profile to get a perfect match for you');
                $this->email->send();
}

   
  public function send(){
       $json_data = [
    "to" => 'dtSwoat8S26tSWu-ObyAGy:APA91bHSfk9mYF6OppEcEWpalAlHFjoFc7tQJ7o5IEluBMt4pmjltsrnOqHNifcxPANP0jE-XhfTDGg7QbS_R4Q2IReCvz4onxuFhAxfbrVwMqYfe8F_2z1G3wbPs6qtf8q95-uT2m3e',
    "notification" => [
        "body" => "SOMETHING",
        "title" => "SOMETHING",
        "icon" => "ic_launcher"
    ],
];
       $data = json_encode($json_data);
       print_r($data);
//FCM API end-point
$url = 'https://fcm.googleapis.com/fcm/send';
//api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
$server_key = 'AAAAo9Nf94w:APA91bHfBBdIIbHy5RoVFSQN1zoeZeARj8ZYYRcWmasAxn4N7NhLGwd1zAi6RNvycJDCtzf4d9Y2Eg-wnq15VV4dvbH1m2BDrgDAnS7SQP1WrxHVB-rAWC2zpY__rEM251oAMkU6o7RU';
//header with content_type api key
$headers = array(
    'Content-Type:application/json',
    'Authorization:key='.$server_key
);
//CURL request to route notification to FCM connection server (provided by Google)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Oops! FCM Send Error: ' . curl_error($ch));
}else{
    print_r($result);
}

curl_close($ch);
   }
   
   public function sendnot(){
        $url = "https://fcm.googleapis.com/fcm/send";
    $token = "himrishtey@gmail.com";
    $serverKey = 'AAAAo9Nf94w:APA91bHfBBdIIbHy5RoVFSQN1zoeZeARj8ZYYRcWmasAxn4N7NhLGwd1zAi6RNvycJDCtzf4d9Y2Eg-wnq15VV4dvbH1m2BDrgDAnS7SQP1WrxHVB-rAWC2zpY__rEM251oAMkU6o7RU';
    $title = "Notification title";
    $body = "Hello I am from Your php server";
    $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
   }
   
   function sendFCM() {
$message ="test";
$id = 'himrishtey@gmail.com';
$message_info='';
$type ='';
    $API_ACCESS_KEY = "AAAAo9Nf94w:APA91bHfBBdIIbHy5RoVFSQN1zoeZeARj8ZYYRcWmasAxn4N7NhLGwd1zAi6RNvycJDCtzf4d9Y2Eg-wnq15VV4dvbH1m2BDrgDAnS7SQP1WrxHVB-rAWC2zpY__rEM251oAMkU6o7RU";

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'registration_ids' => array (
                    $id
            ),
            'data' => array (
                    "message" => $message,
                    'message_info' => $message_info,
            ),                
            'priority' => 'high',
            'notification' => array(
                        'title' => $message['title'],
                        'body' => $message['body'],                            
            ),
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
    );
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    $result = curl_exec ( $ch );
    print_r($result);
    curl_close ( $ch );
}

    function abc(){
        $senderids = '703625951116';
        $title = "Test";
        $description= "mymessage";
        $imgurl = null;
    $serverkey = 'AAAAo9Nf94w:APA91bHfBBdIIbHy5RoVFSQN1zoeZeARj8ZYYRcWmasAxn4N7NhLGwd1zAi6RNvycJDCtzf4d9Y2Eg-wnq15VV4dvbH1m2BDrgDAnS7SQP1WrxHVB-rAWC2zpY__rEM251oAMkU6o7RU';// this is a Firebase server key 
    $data = array(
    			'registration_ids' => $senderids,
                 'notification' => 
                 		array(
                 		'body' => $description,
                        'title' => $title,
                        "image"=> $imgurl),
                        "data"=> array(
                        		"click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                                "sound"=> "default", 
                                 "status"=> "done"
                                 )
                        ); 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://fcm.googleapis.com/fcm/send");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: key='.$serverkey));
    $output = curl_exec ($ch);
    curl_close ($ch);
    print_r($output);
}

public function test(){
    $query = $this->db->select('id')->from('members')->get();
$result = $query->result();
foreach ($result as $row) {
    $this->db->set('password', 'HIM'.rand(00000,99999)); 
    $this->db->where('id',$row->id);
    $this->db->update('members');
}
}
    }

