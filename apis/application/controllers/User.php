<?php



defined('BASEPATH') OR exit('No direct script access allowed');



// This can be removed if you use __autoload() in config.php OR use Modular Extensions

// require APPPATH . '/libraries/REST_Controller.php';
require(APPPATH.'/libraries/REST_Controller.php');



/**

 * This is an example of a few basic user interaction methods you could use

 * all done with a hardcoded array

 *

 * @package         CodeIgniter

 * @subpackage      Rest Server

 * @category        Controller

 * @author          Phil Sturgeon, Chris Kacerguis

 * @license         MIT

 * @link            https://github.com/chriskacerguis/codeigniter-restserver

 */

class User extends REST_Controller {



    function __construct()

    {

        // Construct the parent class

        parent::__construct();



        $this->load->model('user_model','usr');

    }



    public function step_one_registration_post(){

        $data = array(

            'fullname'              => $this->input->post('full_name'),

            'profile_created_for'   => $this->input->post('profile_created_for'),

            // 'username'              => $this->input->post('username'),

            'phone'                 => $this->input->post('phone_number'),

            'email'                 => $this->input->post('email'),

            'password'              => $this->input->post('password'),

            'profile_completed'     => $this->input->post('profile_completed'),

            'google_token'          => $this->input->post('google_token'),

            'gender'                => $this->input->post('gender')

        );
        $email = $this->input->post('email');

        $user = $this->usr->get_user($data);

        $users = $this->usr->get_user_phone($data);

        if($user == 0 && $users == 0){

            $query = $this->usr->step_one_registration($data);       

            if(!$query){

                $this->response( [

                    'success'=>false,

                    'message'=>'User Not added'

                ], REST_Controller::HTTP_NOT_FOUND );

            }else{

                // $this->email->from('info@himrishtey.com', 'Himrishtey Marriage Bureau');
                // $this->email->to($email);
                 
                // $this->email->subject('himrishtey.com');
                // $this->email->message('Thanks for register with Himrishtey Marriage Bureau. Please complete your profile to get a perfect match');
                // $this->email->send();
                
                
                // $this->email->from('info@himrishtey.com', 'Himrishtey Marriage Bureau'); 
                // $this->email->to("himrishtey@gmail.com");
                // $this->email->subject('Himrishtey.com'); 
                // $this->email->message('A new Registration is from '.$this->input->post('full_name').'  Please check Admin for Details');
                // $this->email->send();
                
                $this->response( [

                 'success'=>true,

                 'user'=>$query,

                 'message' => 'Thank you for registering with HimRishtey'

             ], REST_Controller::HTTP_OK );

            }

        }else{

            if($user == 0){

                $this->response( [

                    'success'=>false,

                    'message'=>'Phone Number already exist. Please Login',

                ], REST_Controller::HTTP_BAD_REQUEST );

            }elseif($users == 0){

                $this->response( [

                    'success'=>false,

                    'message'=>'Email ID already exist.Please Login',

                ], REST_Controller::HTTP_BAD_REQUEST );

            }else{

                $this->response( [

                    'success'=>false,

                    'message'=>'Email & Phone Number already exist.Please Login',

                ], REST_Controller::HTTP_BAD_REQUEST );

            }

        }





    }

public function step_one_ios_registration_post(){

        $data = array(

            'fullname'              => $this->input->post('full_name'),

            'profile_created_for'   => $this->input->post('profile_created_for'),

            // 'username'              => $this->input->post('username'),

            'phone'                 => $this->input->post('phone_number'),

            'email'                 => $this->input->post('email'),

            'password'              => $this->input->post('password'),

            'profile_completed'     => $this->input->post('profile_completed'),

            'google_token'          => $this->input->post('google_token'),

            'gender'                => $this->input->post('gender')

        );
        $email = $this->input->post('email');

        $user = $this->usr->get_user($data);

        $users = $this->usr->get_user_phone($data);

        if($user == 0 && $users == 0){

            $query = $this->usr->step_one_ios_registration($data);       

            if(!$query){

                $this->response( [

                    'success'=>false,

                    'message'=>'User Not added'

                ], REST_Controller::HTTP_NOT_FOUND );

            }else{

                // $this->email->from('info@himrishtey.com', 'Himrishtey Marriage Bureau');
                // $this->email->to($email);
                 
                // $this->email->subject('himrishtey.com');
                // $this->email->message('Thanks for register with Himrishtey Marriage Bureau. Please complete your profile to get a perfect match');
                // $this->email->send();
                
                
                // $this->email->from('info@himrishtey.com', 'Himrishtey Marriage Bureau'); 
                // $this->email->to("himrishtey@gmail.com");
                // $this->email->subject('Himrishtey.com'); 
                // $this->email->message('A new Registration is from '.$this->input->post('full_name').'  Please check Admin for Details');
                // $this->email->send();
                
                $this->response( [

                 'success'=>true,

                 'user'=>$query,

                 'message' => 'Thank you for registering with HimRishtey'

             ], REST_Controller::HTTP_OK );

            }

        }else{

            if($user == 0){

                $this->response( [

                    'success'=>false,

                    'message'=>'Phone Number already exist. Please Login',

                ], REST_Controller::HTTP_BAD_REQUEST );

            }elseif($users == 0){

                $this->response( [

                    'success'=>false,

                    'message'=>'Email ID already exist.Please Login',

                ], REST_Controller::HTTP_BAD_REQUEST );

            }else{

                $this->response( [

                    'success'=>false,

                    'message'=>'Email & Phone Number already exist.Please Login',

                ], REST_Controller::HTTP_BAD_REQUEST );

            }

        }





    }


    public function registration_step_two_post(){

        $data = array(



            'date_of_birth'         => $this->input->post('date_of_birth'),

            'time_of_birth'         => $this->input->post('time_of_birth'),

            'birth_place'           => $this->input->post('birth_place'),

            'height'                => $this->input->post('height'),

            'country'               => $this->input->post('country'),

            'state'                 => $this->input->post('state'),

            'city'                  => $this->input->post('city'),

            'user_id'               => $this->input->post('user_id'),

            'profile_completed'     => $this->input->post('profile_completed'),

        );



        $user = $this->usr->registration_step_two($data);

        if(!$user){

            $this->response( [

                'success'=>false,

                'message'=>'User Not added'

            ], REST_Controller::HTTP_OK );

        }else{

            $this->response( [

             'success'=>true,

             'user'=>$user,

             'message' => 'Great ! 20% Profile completed'

         ], REST_Controller::HTTP_OK );

        }

    }



    public function registration_step_three_post(){

        $data = array(

            'education'             => $this->input->post('education'),

            'employed_in'           => $this->input->post('employed_in'),

            'occupation'            => $this->input->post('occupation'),

            'annual_income'         => $this->input->post('annual_income'),

            'user_id'               => $this->input->post('user_id'),

            'profile_completed'     => $this->input->post('profile_completed'),

        );



        $user = $this->usr->registration_step_three($data);

        if(!$user){

            $this->response( [

                'success'=>false,

                'message'=>'User Not added'

            ], REST_Controller::HTTP_OK );

        }else{

            $this->response( [

             'success'=>true,

             'user'=>$user,

             'message' => 'Awesome ! 25% Profile completed'

         ], REST_Controller::HTTP_OK );

        }

    }



    public function registration_step_four_post(){
        
        $marital_status = $this->input->post('marital_status');
        if($marital_status  == 'Never Married'){
            $no_of_child = 0;
        }else{
            $no_of_child =  $this->input->post('no_of_child');
        }

        $data = array(

            'marital_status'        => $this->input->post('marital_status'),

            'mother_tongue'         => $this->input->post('mother_tongue'),

            'religion'              => $this->input->post('religion'),

            'cast'                  => $this->input->post('cast'),

            'manglik'               => $this->input->post('is_manglik'),

            'horoscope_needed'      => $this->input->post('horoscope_needed'),

            'user_id'               => $this->input->post('user_id'),

            'profile_completed'     => $this->input->post('profile_completed'),

            'no_of_child'           => $no_of_child,

        );

        



        $user = $this->usr->registration_step_four($data);

        if(!$user){

            $this->response( [

                'success'=>false,

                'message'=>'User Not added'

            ], REST_Controller::HTTP_OK );

        }else{

            $this->response( [

             'success'=>true,

             'user'=>$user,

             'message' => 'Hurray ! 30% Profile completed'

         ], REST_Controller::HTTP_OK );

        }

    }



    public function add_profile_photo_post(){

      $base64_image = $this->input->post('image');

      $data['user_id'] = $this->input->post('user_id');

      $data['profile_completed'] = $this->input->post('profile_completed');

		//decode base64 string

      $image = base64_decode($base64_image);



      $f = finfo_open();

      $mime_type = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);

      

      $image_type = substr($mime_type, strrpos($mime_type, '/') + 1);



      $image_name = "member-photo-".$data['user_id'].".$image_type";

      $cur_folder = $_SERVER['DOCUMENT_ROOT']."/photos/photo/".$image_name;

      $folder = file_put_contents($cur_folder, $image);

      $rpth = "/photos/photo/";

      $result = $this->usr->add_profile_photo($image_name,$data);

      if ($result)

      {		

					// Set the response and exit

       $this->response([

          'data' => $result, 

          'status' => TRUE,

          'message' => "Hurray ! Profile is completed about 50%"

					], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code		 

   }

   else

   {

					// Set the response and exit

       $this->response([

          'status' => FALSE,

          'message' => 'Data Not inserted'

					], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

   }	 

}



public function update_profile_photo_post(){

    $base64_image = $this->input->post('image');

    $data['user_id'] = $this->input->post('user_id');

    $user_id = $this->input->post('user_id');



        //decode base64 string

    $image = base64_decode($base64_image);



    $f = finfo_open();

    $mime_type = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);



    $image_type = substr($mime_type, strrpos($mime_type, '/') + 1);

    

    $image_name = "member-photo-".$data['user_id'].'-'.rand(10000,99999).".$image_type";

    $cur_folder = $_SERVER['DOCUMENT_ROOT']."/photos/photo/".$image_name;

    $folder = file_put_contents($cur_folder, $image);

    $rpth = "/himrishtey/photos/photo/";

    $result = $this->usr->update_profile_photo($image_name,$data);

    if($result){

     $photo_linkss =  $this->db->select('photo')->from('members')->where('id',$user_id)->get()->row();

     $photo_links = (array)$photo_linkss;

     if($photo_links){

        $photo_link = "https://gallpakki.com/photos/photo/".$photo_links['photo'];

    }

}

if ($result)

{       

                    // Set the response and exit

    $this->response([

        'data' => $result, 

        'status' => TRUE,

        'image' => $photo_link,

        'message' => "Profile Photo Updated"

                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code       

}

else

{

                    // Set the response and exit

    $this->response([

        'status' => FALSE,

        'message' => 'Data Not inserted'

                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

}    

}



public function login_post(){
    $username =   $this->input->post('username');
    $pass =    $this->input->post('password');
    $device_token = $this->input->post('google_token');
    $query = $this->usr->login($username,$pass,$device_token);
    if($query){
        if($query->photo == ''){
            if($query->gender == "Male"){
                $query->photo = 'https://gallpakki.com/img/boy.jpg';
            }else{
                $query->photo = 'https://gallpakki.com/img/girl.jpg';
            }
        }
        if($query->photo_approved == "Yes"){
                $query->photo = "https://gallpakki.com/photos/photo/".$query->photo;
        }
    }

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Invalid login credentials'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'user'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function logout_post(){

    $uid = $this->input->post('user_id');

    $query = $this->usr->logout($uid);

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'User Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'user'=>$query

     ], REST_Controller::HTTP_OK );

    }    



}



public function profile_get($user_id){

    $query2 = $this->usr->profile($user_id);

    $query = (array) $query2;

    $created_for = $query['profile_created_for'];

    if($created_for == "Self"){

        $query['profile_created_for'] = 'Self';

    }elseif($created_for == 'Relative'){

        $query['profile_created_for'] = 'Relative';

    }elseif($created_for == 'Son' || $created_for == 'Daughter'){

        $query['profile_created_for'] = 'Parents';

    }elseif($created_for == 'Brother' || $created_for == 'Sister'){

        $query['profile_created_for'] = 'Brother or Sister';

    }elseif($created_for == 'Client (Marriage bureau)'){

        $query['profile_created_for'] = 'Marriage Bureau';

    }else{

        $query['profile_created_for'] = 'Friendsss';

    }
    if($query['no_of_child'] == null){
        $query['no_of_child'] = 0;
    }

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'User Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'user'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function profile_created_for_get()

{

    $query = $this->usr->get_profile_created_for();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Data Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'user'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function heights_get()

{

    $query = $this->usr->get_heights();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Heights Data Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Heights'=>$query

     ], REST_Controller::HTTP_OK );

    }

}

public function app_config_get()

{

    $query = $this->db->get('app_config')->result();
    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Data Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'version'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function countries_get()

{

    $query = $this->usr->get_countries();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Countries Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Countries'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function states_post(){

    $country_id =   $this->input->post('country_id');

    $query = $this->usr->get_states($country_id);

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'States Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

       $this->response( [

        'success'=>true,

        'States'=>$query

    ], REST_Controller::HTTP_OK );

   }

}



public function cities_post(){

    $state_id =   $this->input->post('state_id');

    $query = $this->usr->get_cities($state_id);

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'City Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

       $this->response( [

        'success'=>true,

        'States'=>$query

    ], REST_Controller::HTTP_OK );

   }

}



public function add_city_post(){

    $data['state_id'] =   $this->input->post('state_id');

    $data['name'] =   $this->input->post('city_name');

    $query = $this->usr->add_city($data);

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'City Not Added'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

       $this->response( [

        'success'=>true,

        'States'=>$query

    ], REST_Controller::HTTP_OK );

   }

}



public function educations_get()

{

    $query = $this->usr->get_educations();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Educations data Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Educations'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function employer_get()

{

    $query = $this->usr->get_employer();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Employer Type Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Employers'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function occupations_get()

{

    $query = $this->usr->get_occupations();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Occupations Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Occupations'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function add_occupation_post()

{

    $name = $this->input->post('occ_name');
    $occ = $this->db->get_where('occupations',array('occupation'=> $name))->row();
    if($occ){
        $this->response( [

            'success'=>false,

            'message'=>'Occupations already exist'

        ], REST_Controller::HTTP_NOT_FOUND );
    }else{

    $query = $this->usr->add_occupation($name);

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Occupations Not Added'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Occupations'=>$query,

         'message' => 'Occupation Added'

     ], REST_Controller::HTTP_OK );

    }
}
} 



public function annual_incomes_get()

{

    $query = $this->usr->get_annual_incomes();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Annual Income Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Annual_Incomes'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function marital_status_get()

{

    $query = $this->usr->get_marital_status();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Marital Status Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Marital_Status'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function religions_get()

{

    $query = $this->usr->get_religions();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Religions Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Religions'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function casts_get()

{

    $query = $this->usr->get_casts();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Casts Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Casts'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function mother_tongues_get()

{

    $query = $this->usr->get_mother_tongues();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Mother Tongues Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'Mother_Tongues'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



public function reset_password_post()

{

   $data['user_id'] = $this->input->post('user_id');

   $data['password'] = $this->input->post('password');

   $query = $this->usr->reset_password($data);

   if(!$query){

    $this->response( [

        'success'=>false,

        'message'=>'Error While Updatig Password'

    ], REST_Controller::HTTP_NOT_FOUND );

}else{

    $this->response( [

     'success'=>true,

     'Password'=>$query,

     'message' => 'Password Updated'

 ], REST_Controller::HTTP_OK );

}

}



public function family_status_get()

{

    $query = $this->usr->get_family_status();

    if(!$query){

        $this->response( [

            'success'=>false,

            'message'=>'Family Status Not Found'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $this->response( [

         'success'=>true,

         'family_status'=>$query

     ], REST_Controller::HTTP_OK );

    }

}



// public function otp_login_post()

// {

//     $this->load->helper('string');

//     $phone_number = $this->input->post('mobile_number');

//     $otp  = random_int(1000, 9999);

//     $member = $this->db->get_where('members',array('mobile_number'=>$phone_number))->row();
//     // echo '<pre>'; print_r($member); die;
//     if(!empty($member)){
//     $this->db->where('phone',$phone_number);

//     if($this->db->delete('otp_request')){

//         $data  = array(

//             'phone' => $phone_number,

//             'otp'   => $otp,

//         );

//         if($this->db->insert('otp_request',$data)){

//             $messageText="".$otp."  is the OTP to login your himrishtey account.";
//             $messageText=urlencode($messageText);
//             $send_otp = "http://nimbusit.biz/api/SmsApi/SendMultipleApi?UserID=himrishteybiz&Password=vqbj8362VQ&SenderID=HIMRMB&Phno=".$phone_number."&Msg=".$messageText."&EntityID=1701164189692214854&TemplateID=1707166088646916717";
//             // echo $send_otp; die;

//             if(!$send_otp){
//                 echo "test";die;

//                 $this->response( [

//                     'success'=>false,

//                     'message'=>'network issue'

//                 ], REST_Controller::HTTP_NOT_FOUND );

//             }else{
//                 $this->response( [

//                     'success'=>true,

//                     'OTP Sent'=>redirect($send_otp),

//                     'message' => 'OTP sent successfully on entered number' 

//                 ], REST_Controller::HTTP_OK );

//             }

//         }

//     }
//     }else{
//         $this->response( [

//                     'success'=>false,

//                     'message'=>'Phone Number not register with Himrishtey'

//                 ], REST_Controller::HTTP_NOT_FOUND );
//     }

// }

public function otp_login_post()
{
    $this->load->helper('string');

    $phone_number = $this->input->post('mobile_number');

    $otp = random_int(1000, 9999);

    $member = $this->db->get_where('members', [
        'mobile_number' => $phone_number
    ])->row();

    if (empty($member)) {

        $this->response([
            'success' => false,
            'message' => 'Phone Number not registered with Himrishtey'
        ], REST_Controller::HTTP_NOT_FOUND);

        return;
    }


    // Remove previous OTP
    $this->db->where('phone', $phone_number);
    $this->db->delete('otp_request');


    // Insert new OTP
    $data = [
        'phone' => $phone_number,
        'otp'   => $otp
    ];

    if (!$this->db->insert('otp_request', $data)) {

        $this->response([
            'success' => false,
            'message' => 'Unable to generate OTP'
        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

        return;
    }


    // SMS Message
    $messageText = $otp . " is the OTP to login your himrishtey account.";

    $messageText = urlencode($messageText);


    // SMS API URL
    $url = "http://nimbusit.biz/api/SmsApi/SendMultipleApi?"
        . "UserID=himrishteybiz"
        . "&Password=vqbj8362VQ"
        . "&SenderID=HIMRMB"
        . "&Phno=" . $phone_number
        . "&Msg=" . $messageText
        . "&EntityID=1701164189692214854"
        . "&TemplateID=1707166088646916717";


    // Call SMS API using CURL
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true
    ]);

    $sms_response = curl_exec($curl);


    if (curl_errno($curl)) {

        $error = curl_error($curl);

        curl_close($curl);

        $this->response([
            'success' => false,
            'message' => 'SMS gateway error',
            'error' => $error
        ], REST_Controller::HTTP_BAD_GATEWAY);

        return;
    }


    curl_close($curl);


    if (empty($sms_response)) {

        $this->response([
            'success' => false,
            'message' => 'SMS not sent'
        ], REST_Controller::HTTP_BAD_GATEWAY);

        return;

    }


    // Success response
    $this->response([
        'success' => true,
        'message' => 'OTP sent successfully',
        'sms_response' => $sms_response
    ], REST_Controller::HTTP_OK);
}

public function get_user_data_from_otp_post()

{

    $otp = $this->input->post('otp');

    $uotp = $this->db->get_where('otp_request', array('otp =' => $otp))->row();

    if(!$uotp){

        $this->response( [

            'success'=>false,

            'message'=>'network issue'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $user = $this->db->get_where('members', array('mobile_number =' => $uotp->phone))->row();

        if(!$user){

            $this->response( [

                'success'=>false,

                'message'=>'No Data Found'

            ], REST_Controller::HTTP_NOT_FOUND );

        }else{

            $this->response( [

                'success'=>true,

                'user'=>$user,

                'message' => 'No User Found' 

            ], REST_Controller::HTTP_OK );

        }

    }

}



public function forget_password_post()

{

$this->load->helper('string');
    $phone_number = $this->input->post('mobile_number');
    $otp_type = 1;
    $otp  = random_int(1000, 9999);
    if(filter_var($phone_number, FILTER_VALIDATE_EMAIL)){
        $user = $this->db->get_where('members', array('email =' => $phone_number))->row();
    }else{
        $user = $this->db->get_where('members', array('mobile_number =' => $phone_number))->row();
    }

    if(!$user){

        $this->response( [

            'success'=>false,

            'message'=>'Email or Phone Number is Not Registered with HimRishtey'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{
        $mobile_number = $user->mobile_number;
        $email= $user->email;
        $where = array('phone' => $phone_number , 'otp_type' => $otp_type);

        $this->db->where($where);

        if($this->db->delete('otp_request')){



            $data  = array(

                'phone' => $phone_number,

                'otp'   => $otp,

                'otp_type' => $otp_type

            );
            
            if($this->db->insert('otp_request',$data)){
                    // $config = Array(
                    //   'protocol' => 'smtp',
                    //   'smtp_host' => 'himrishtey.com',
                    //   'smtp_crypto' => 'ssl',
                    //   'smtp_port' => 465,
                    //       'smtp_user' => 'info@himrishtey.com',// change it to yours
                    //       'smtp_pass' => 'mydev@980555', // change it to yours
                    //       'mailtype' => 'html',
                    //       'newline'   => "\r\n",
                    //       'charset' => 'UTF-8',
                    //       'wordwrap' => TRUE
                    //   );
                    // $this->load->library('email');
                    // $this->email->initialize($config);
                    // $this->email->from('info@himrishtey.com', 'One Time password');
                    // $this->email->to($email);
                    // $this->email->subject('One Time password');
                    // $this->email->message($otp.' is the OTP to reset your Password for your himrishtey account');
                    // if($this->email->send()){

                    $messageText="".$otp." is the OTP to reset your Password for your himrishtey account";
                    $messageText=urlencode($messageText);
                    $otp= "http://nimbusit.biz/api/SmsApi/SendMultipleApi?UserID=himrishteybiz&Password=vqbj8362VQ&SenderID=HIMRMB&Phno=".$mobile_number."&Msg=".$messageText."&EntityID=1701164189692214854&TemplateID=1707166036739168867";
    

                if(!$otp){

                    $this->response( [

                        'success'=>false,

                        'message'=>'network issue'

                    ], REST_Controller::HTTP_NOT_FOUND );

                }else{  

                    $this->response( [

                        'success'=>true,

                        'phone'=>redirect($otp),

                        'message' => 'OTP sent successfully to the entered number' 

                    ], REST_Controller::HTTP_OK );

                }
            // }
        }
        
    }
    }
}



public function update_password_post()

{

    $otp = $this->input->post('otp');

    $password = $this->input->post('password');

    $otp_type='1';

    $motp = $this->db->get_where('otp_request', array('otp' => $otp , 'otp_type' => $otp_type))->row();

    if(!$motp){

        $this->response( [

            'success'=>false,

            'message'=>'OTP not matched'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{
         if(filter_var($motp->phone, FILTER_VALIDATE_EMAIL)){
            $usser = $this->db->get_where('members', array('email' => $motp->phone))->row();
         }else{
            $usser = $this->db->get_where('members', array('mobile_number' => $motp->phone))->row();
         }
        
        
        $id = $usser->id;

        $this->db->where('id',$id);

        $updated =  $this->db->update('members',array('password'=> $password));

        if(!$updated){

            $this->response( [

                'success'=>false,

                'message'=>'network issue'

            ], REST_Controller::HTTP_NOT_FOUND );

        }else{  

            $this->response( [

                'success'=>true,

                'user'=>$updated,

                'message' => 'Password Updated successfully' 

            ], REST_Controller::HTTP_OK );

        }

    }



}



public function verify_mobile_otp_post()

{

    $this->load->helper('string');

    $phone_number = $this->input->post('mobile_number');
    $req_user = $this->db->get_where('members',array('mobile_number' => $phone_number))->row();
    // if(empty($req_user)){
    //     $this->response( [

    //         'success'=>false,

    //         'message'=>'Entered Phone Number is not registered with us'

    //     ], REST_Controller::HTTP_NOT_FOUND );
    // }else{

    $otp  = random_int(1000, 9999);

    $otp_type = "2";

    $where = array('phone' => $phone_number , 'otp_type' => $otp_type);

    $this->db->where($where);

    if($this->db->delete('otp_request')){

        $data  = array(

            'phone' => $phone_number,

            'otp'   => $otp,

            'otp_type' => $otp_type

        );

        if($this->db->insert('otp_request',$data)){
            
            $messageText= $otp." is the OTP to verify your mobile number for your himrishtey account";
            $messageText=urlencode($messageText);
            // $send_otp = "http://nimbusit.biz/api/SmsApi/SendBulkApi?UserID=himrishteybiz&Password=vqbj8362VQ&SenderID=HIMRMB&Phno=".$phone_number."&Msg=".$messageText."&EntityID=1701164189692214854&TemplateID=1707166036743902118";
            // $send_otp = redirect("http://nimbusit.info/api/pushsms.php?user=t5himrishtey&key=010PJ5bV50xoQ7ifAbvI&sender=HIMRMB&mobile=".$phone_number."&text=".$otp." is the OTP to verify your mobile number for your himrishtey account&entityid=1701164189692214854&templateid=1707166036743902118");
            $send_otp = "http://biz.sms4power.com/api/SmsApi/SendSingleApi?UserID=himrishteybiz&Password=vqbj8362VQ&SenderID=HIMRMB&Phno=".$phone_number."&Msg=".$messageText."&EntityID=1701164189692214854&TemplateID=1707166036743902118";
            if(!$send_otp){

                $this->response( [

                    'success'=>false,

                    'message'=>'network issue'

                ], REST_Controller::HTTP_NOT_FOUND );

            }else{

                $this->response( [

                    'success'=>true,

                    'OTP Sent'=>redirect($send_otp),

                    'message' => 'OTP sent successfully to registered number' 

                ], REST_Controller::HTTP_OK );

            }

        }

    }

//}
}



public function verify_mobile_post()

{

    $otp = $this->input->post('otp');
    $user_id = $this->input->post('user_id');

    $member_type = 'Verified';

    $otp_type='2';

    $motp = $this->db->get_where('otp_request', array('otp' => $otp , 'otp_type' => $otp_type))->row();
// echo '<pre>'; print_r($motp);die;
    if(!$motp){

        $this->response( [

            'success'=>false,

            'message'=>'OTP not matched'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{

        $usser = $this->db->get_where('members', array('id'=>$user_id))->row();
        $id = $usser->id;

        $this->db->where('id',$id);

        $updated =  $this->db->update('members',array('member_type'=> $member_type,'mobile_number'=>$motp->phone));

        if(!$updated){

            $this->response( [

                'success'=>false,

                'message'=>'network issue',

                'verified' => 'no',

            ], REST_Controller::HTTP_NOT_FOUND );

        }else{  

            $this->response( [

                'success'=>true,

                'user'=>$updated,

                'verified' => 'yes',

                'message' => 'Member Verified' 

            ], REST_Controller::HTTP_OK );

        }

    }



}


public function testt_post(){
    $this->load->helper('string');
    $phone_number = $this->input->post('mobile_number');
    $otp_type = 1;
    $otp  = random_int(1000, 9999);
    if(filter_var($phone_number, FILTER_VALIDATE_EMAIL)){
        $user = $this->db->get_where('members', array('email =' => $phone_number))->row();
    }else{
        $user = $this->db->get_where('members', array('mobile_number =' => $phone_number))->row();
    }
    if(!$user){

        $this->response( [

            'success'=>false,

            'message'=>'Email or Phone Number is Not Registered with HimRishtey'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{
        $this->response( [

                        'success'=>true,

                        'user' => $user,
                        'message' => 'email register with us' 

                    ], REST_Controller::HTTP_OK );
    }

}



public function test_post(){
    $this->load->helper('string');
    $phone_number = $this->input->post('mobile_number');
    $otp_type = 1;
    $otp  = random_int(1000, 9999);
    if(filter_var($phone_number, FILTER_VALIDATE_EMAIL)){
        $user = $this->db->get_where('members', array('email =' => $phone_number))->row();
        
    }else{
        $user = $this->db->get_where('members', array('mobile_number =' => $phone_number))->row();
    }

    if(!$user){
       
        $this->response( [

            'success'=>false,

            'message'=>'Email or Phone Number is Not Registered with HimRishtey'

        ], REST_Controller::HTTP_NOT_FOUND );

    }else{
        $mobile_number = $user->mobile_number;
        $email= $user->email;

        $where = array('phone' => $phone_number , 'otp_type' => $otp_type);

        $this->db->where($where);

        if($this->db->delete('otp_request')){



            $data  = array(

                'phone' => $phone_number,

                'otp'   => $otp,

                'otp_type' => $otp_type

            );
            
            if($this->db->insert('otp_request',$data)){
                    $config = Array(
                      'protocol' => 'smtp',
                      'smtp_host' => 'mail.himrishtey.com',
                      'smtp_crypto' => 'ssl',
                      'smtp_port' => 465,
                          'smtp_user' => 'info@himrishtey.com',// change it to yours
                          'smtp_pass' => 'mydev@980555', // change it to yours
                          'mailtype' => 'html',
                          'newline'   => "\r\n",
                          'charset' => 'UTF-8',
                          'wordwrap' => TRUE
                      );
                    $this->load->library('email');
                    $this->email->initialize($config);
                    $this->email->from('info@himrishtey.com', 'One Time password');
                    $this->email->to($email);
                    $this->email->subject('One Time password');
                    $this->email->message($otp.' is the OTP to reset your Password for your himrishtey account');
                    if($this->email->send()){

                    $messageText="".$otp." is the OTP to reset your Password for your himrishtey account";
                    $messageText=urlencode($messageText);
                    $otp= "http://nimbusit.biz/api/SmsApi/SendMultipleApi?UserID=himrishteybiz&Password=vqbj8362VQ&SenderID=HIMRMB&Phno=".$mobile_number."&Msg=".$messageText."&EntityID=1701164189692214854&TemplateID=1707166036739168867";
    

                if(!$otp){

                    $this->response( [

                        'success'=>false,

                        'message'=>'network issue'

                    ], REST_Controller::HTTP_NOT_FOUND );

                }else{  

                    $this->response( [

                        'success'=>true,

                        'phone'=>redirect($otp),

                        'message' => 'OTP sent successfully to the entered number' 

                    ], REST_Controller::HTTP_OK );

                }
            }
        }
        
    }
    }
}
}

?>

