<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';
/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded arrayr
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Profile extends REST_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('profile_model','pro');
        $this->load->model('user_model','usr');
        $this->load->helper('notificationhelper_helper');
    }
    public function user_get($user_id){
         $query2 = $this->usr->profile($user_id);
        $query = (array) $query2;
        
       /* $datee = $query->birth_date_time;
                    if(strpos($datee,' AM')){
                        $usr->birth_date_time = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date = new DateTime($datee);
                        $usr->birth_date_time = $date->format('Y-m-d H:i:s');
                        
                    }else{
                        $usr->birth_date_time = $datee;
                    }*/
        if(!empty($query)){
        $full_name = $query['full_name'];
        $fname = explode(' ',$full_name);
        $count = count($fname);
        if($count > 1){
            if($lname = $fname[1]){
                $first = mb_substr($lname, 0, 1);
                $query['full_name'] = $fname[0]. ' '.$first;
            }
        }else{
            $query['full_name'] = $full_name;
        }
            // $dd = $query['birth_date_time'];
            // $query['birth_date_time'] = date('Y-m-d h:i:s A', strtotime($dd));
        $created_for = $query['profile_created_for'];
        if($created_for == "Self"){
            $query['profile_created_for'] = 'Self';
        }elseif($created_for == 'Relative'){
            $query['profile_created_for'] = 'Relative';
        }elseif($created_for == 'Son' || $created_for == 'Daughter'){
            $query['profile_created_for'] = 'Parents';
        }elseif($created_for == 'Brother' || $created_for == 'Sister'){
            $query['profile_created_for'] = 'Sibilings';
        }elseif($created_for == 'Client (Marriage bureau)'){
            $query['profile_created_for'] = 'Marriage Bureau';
        }else{
            $query['profile_created_for'] = 'Friend';
        }
        $active = $query['active'];
        if($active == 'Yes'){
            $query['active'] = 'Active';
        }elseif($active == 'No'){
            $query['active'] = 'Inactive';
        }elseif($active =='Banned'){
            $query['active'] = 'Banned';
        }elseif($active =='deleted'){
            $query['active'] = 'Deleted';
        }else{
            $query['active'] = 'Free';
        }
            
        $query['partner_annual_income_from'] = number_format((int) filter_var($query['partner_annual_income_from'], FILTER_SANITIZE_NUMBER_INT), 1, '.', '');
        $query['partner_annual_income_to'] = number_format((int) filter_var($query['partner_annual_income_to'], FILTER_SANITIZE_NUMBER_INT), 1, '.', '');
         $gallery = $this->db->get_where('member_photos',array('member_id' => $user_id))->result();
            $images=array();
            if(!empty($gallery)){
                foreach($gallery as $key=> $gal){
                   // $images[] = 'https://gallpakki.com/photos/photo_gallery/'.$gal->photo;
                   $images[$key]['images'] = 'https://gallpakki.com/photos/photo_gallery/'.$gal->photo;
                   $images[$key]['privacy'] = $gal->photo_privacy;
                   $images[$key]['id'] = $gal->id;
                }
                 
            }
        }
                if(!$query){
                    $this->response( [
                        'success'=>false,
                        'message'=>'User Not Found'
                    ], REST_Controller::HTTP_NOT_FOUND );
                }else{
                    $this->response( [
                        
                        'success'=>true,
                        'data'=>['user'=>$query,JSON_NUMERIC_CHECK],
                        'images' => $images,
                    ], REST_Controller::HTTP_OK );
                }
        }
      public function view_post(){
    	$data['user_id'] = $this->input->post('user_id');
    	$data['profile_id'] = $this->input->post('profile_id');
        $view = $this->usr->add_profile_view($data);
        $query = $this->usr->view_profile($data);
        $interests_received = $this->db->get_where('sent_interests',array('member_id' => $data['profile_id'],'profile_id' => $data['user_id']))->result();
        $interests_sent = $this->db->get_where('sent_interests',array('profile_id' => $data['profile_id'],'member_id' => $data['user_id']))->result();
        $gallery = $this->db->get_where('member_photos',array('member_id' => $data['profile_id'],'photo_approved' => 'Yes'))->result();
        $user = $this->db->get_where('members',array('id'=>$data['user_id']))->row();
        $profile = $this->db->get_where('members',array('id'=>$data['profile_id'], 'active=>"Yes"'))->row();
        $notification = array(
            'token' => $profile->google_token,
            'title' => 'Profile Viewed',
            'message' => $user->profile_id .' viewed your profile please check. Himrishtey',
            'image' => 'https://gallpakki.com/photos/photo/'.$user->photo
        );
            $images=array();
            if(!empty($gallery)){
                foreach($gallery as $key=> $gal){
                   $images[] = 'https://gallpakki.com/photos/photo_gallery/'.$gal->photo;
                   // $images[$key]['images'] = 'https://gallpakki.com/photos/photo_gallery/'.$gal->photo;
                   // $images[$key]['privacy'] = $gal->photo_privacy;
                }    
            }
                if(!$query){
                    $this->response( [
                        'success'=>false,
                        'message'=>'User Not Found'
                    ], REST_Controller::HTTP_NOT_FOUND );
                }else{
                    //$this->view_profile_notification($user->profile_id,$profile->email);
                    $this->response( [
                        'success'=>true,
                        'data'=>['user'=>$query,JSON_NUMERIC_CHECK],
                        'images' => $images,
                        'interests_received' => $interests_received,
                        'interests_sent' => $interests_sent,
                        "notification" => $this->push_note($notification)
                    ], REST_Controller::HTTP_OK );
                }
        }
    public function profile_update_post()
    {
        $pdata = array();
        $user_id = $this->input->post('user_id');
        $user = $this->db->get_where('members', array('id =' => $user_id))->row();
        // return $user;
        $pdata['user_id'] = $user_id;
// $height = $this->input->post('height');
        if(isset($_POST['profile_created_for']) && !empty($_POST['profile_created_for'])){
            $pdata['profile_created_for'] = $this->input->post('profile_created_for');
        }else{
            $pdata['profile_created_for'] = $user->profile_created_for;
        }
        // if(isset($_POST['gender']) && !empty($_POST['gender'])){
        //     $pdata['gender'] = $this->input->post('gender');
        // }else{
        //     $pdata['gender'] = $user->gender;
        // }
        if(isset($_POST['marital_status']) && !empty($_POST['marital_status'])){
            $pdata['marital_status'] = $this->input->post('marital_status');
        }else{
            $pdata['marital_status'] = $user->marital_status;
        }
        if(isset($_POST['height']) && !empty($_POST['height'])){
            $pdata['height'] = $this->input->post('height');
        }else{
            $pdata['height'] = $user->height;
        }
        if(isset($_POST['health_info']) && !empty($_POST['health_info'])){
            $pdata['health_info'] = $this->input->post('health_info');
        }else{
            $pdata['health_info'] = $user->health_info;
        }
        if(isset($_POST['any_disability']) && !empty($_POST['any_disability'])){
            $pdata['any_disability'] = $this->input->post('any_disability');
        }else{
            $pdata['any_disability'] = $user->any_disability;
        }
        if(isset($_POST['blood_group']) && !empty($_POST['blood_group'])){
            $pdata['blood_group'] = $this->input->post('blood_group');
        }else{
            $pdata['blood_group'] = $user->blood_group;
        }
        if(isset($_POST['date_of_birth']) && isset($_POST['time_of_birth']) && !empty($_POST['date_of_birth']) && !empty($_POST['time_of_birth'])){
            $pdata['birth_date_time'] = $this->input->post('date_of_birth') . ' ' .$this->input->post('time_of_birth');
        }else{
            $pdata['birth_date_time'] = $user->birth_date_time;
        }
        if(isset($_POST['birth_place']) && !empty($_POST['birth_place'])){
            $pdata['birth_place'] = $this->input->post('birth_place');
        }else{
            $pdata['birth_place'] = $user->birth_place;
        }
        if(isset($_POST['horoscope_needed']) && !empty($_POST['horoscope_needed'])){
            $pdata['horoscope_needed'] = $this->input->post('horoscope_needed');
        }else{
            $pdata['horoscope_needed'] = $user->horoscope_needed;
        }
        if(isset($_POST['manglik']) && !empty($_POST['manglik'])){
            $pdata['manglik'] = $this->input->post('manglik');
        }else{
            $pdata['manglik'] = $user->manglik;
        }
        if(isset($_POST['mobile_number']) && !empty($_POST['mobile_number'])){
            $pdata['mobile_number'] = $this->input->post('mobile_number');
        }else{
            $pdata['mobile_number'] = $user->mobile_number;
        }
        if(isset($_POST['alternate_number']) && !empty($_POST['alternate_number'])){
            $pdata['alternate_number'] = $this->input->post('alternate_number');
        }else{
            $pdata['alternate_number'] = $user->alternate_number;
        }
        if(isset($_POST['email']) && !empty($_POST['email'])){
            $pdata['email'] = $this->input->post('email');
        }else{
            $pdata['email'] = $user->email;
        }
        if(isset($_POST['whatsapp_number']) && !empty($_POST['whatsapp_number'])){
            $pdata['whatsapp_number'] = $this->input->post('whatsapp_number');
        }else{
            $pdata['whatsapp_number'] = $user->whatsapp_number;
        }
        if(isset($_POST['religion']) && !empty($_POST['religion'])){
            $pdata['religion'] = $this->input->post('religion');
        }else{
            $pdata['religion'] = $user->religion;
        }
        if(isset($_POST['mother_tongue']) && !empty($_POST['mother_tongue'])){
            $pdata['mother_tongue'] = $this->input->post('mother_tongue');
        }else{
            $pdata['mother_tongue'] = $user->mother_tongue;
        }
        if(isset($_POST['gotra']) && !empty($_POST['gotra'])){
            $pdata['gotra'] = $this->input->post('gotra');
        }else{
            $pdata['gotra'] = $user->gotra;
        }
        if(isset($_POST['sub_community']) && !empty($_POST['sub_community'])){
            $pdata['sub_cast'] = $this->input->post('sub_community');
        }else{
            $pdata['sub_cast'] = $user->sub_cast;
        }
        if(isset($_POST['community']) && !empty($_POST['community'])){
            $pdata['cast'] = $this->input->post('community');
        }else{
            $pdata['cast'] = $user->cast;
        }
        if(isset($_POST['about_my_education']) && !empty($_POST['about_my_education'])){
            $pdata['about_my_education'] = $this->input->post('about_my_education');
        }else{
            $pdata['about_my_education'] = $user->about_my_education;
        }
        if(isset($_POST['education']) && !empty($_POST['education'])){
            $pdata['education'] = $this->input->post('education');
        }else{
            $pdata['education'] = $user->education;
        }
        if(isset($_POST['any_other_qualifications']) && !empty($_POST['any_other_qualifications'])){
            $pdata['any_other_qualifications'] = $this->input->post('any_other_qualifications');
        }else{
            $pdata['any_other_qualifications'] = $user->any_other_qualifications;
        }
        if(isset($_POST['about_my_career']) && !empty($_POST['about_my_career'])){
            $pdata['about_my_career'] = $this->input->post('about_my_career');
        }else{
            $pdata['about_my_career'] = $user->about_my_career;
        }
        if(isset($_POST['employed_in']) && !empty($_POST['employed_in'])){
            $pdata['employed_in'] = $this->input->post('employed_in');
        }else{
            $pdata['employed_in'] = $user->employed_in;
        }
        if(isset($_POST['occupation']) && !empty($_POST['occupation'])){
            $pdata['occupation'] = $this->input->post('occupation');
        }else{
            $pdata['occupation'] = $user->occupation;
        }
        if(isset($_POST['organization_name']) && !empty($_POST['organization_name'])){
            $pdata['organization_name'] = $this->input->post('organization_name');
        }else{
            $pdata['organization_name'] = $user->organization_name;
        }
        if(isset($_POST['job_location']) && !empty($_POST['job_location'])){
            $pdata['job_location'] = $this->input->post('job_location');
        }else{
            $pdata['job_location'] = $user->job_location;
        }
        if(isset($_POST['annual_income']) && !empty($_POST['annual_income'])){
            $pdata['annual_income'] = $this->input->post('annual_income');
        }else{
            $pdata['annual_income'] = $user->annual_income;
        }
        if(isset($_POST['about_family']) && !empty($_POST['about_family'])){
            $pdata['about_family'] = $this->input->post('about_family');
        }else{
            $pdata['about_family'] = $user->about_family;
        }
        if(isset($_POST['father_name']) && !empty($_POST['father_name'])){
            $pdata['father_name'] = $this->input->post('father_name');
        }else{
            $pdata['father_name'] = $user->father_name;
        }
        if(isset($_POST['father_occupation']) && !empty($_POST['father_occupation'])){
            $pdata['father_occupation'] = $this->input->post('father_occupation');
        }else{
            $pdata['father_occupation'] = $user->father_occupation;
        }
        if(isset($_POST['mother_name']) && !empty($_POST['mother_name'])){
            $pdata['mother_name'] = $this->input->post('mother_name');
        }else{
            $pdata['mother_name'] = $user->mother_name;
        }
        if(isset($_POST['mother_occupation']) && !empty($_POST['mother_occupation'])){
            $pdata['mother_occupation'] = $this->input->post('mother_occupation');
        }else{
            $pdata['mother_occupation'] = $user->mother_occupation;
        }
        if(isset($_POST['no_of_brothers']) && !empty($_POST['no_of_brothers'])){
            $pdata['no_of_brothers'] = $this->input->post('no_of_brothers');
        }else{
            $pdata['no_of_brothers'] = $user->no_of_brothers;
        }
        if(isset($_POST['no_of_sisters']) && !empty($_POST['no_of_sisters'])){
            $pdata['no_of_sisters'] = $this->input->post('no_of_sisters');
        }else{
            $pdata['no_of_sisters'] = $user->no_of_sisters;
        }
        if(isset($_POST['married_brothers']) && !empty($_POST['married_brothers'])){
            $pdata['married_brothers'] = $this->input->post('married_brothers');
        }else{
            $pdata['married_brothers'] = $user->married_brothers;
        }
        if(isset($_POST['married_sisters']) && !empty($_POST['married_sisters'])){
            $pdata['married_sisters'] = $this->input->post('married_sisters');
        }else{
            $pdata['married_sisters'] = $user->married_sisters;
        }
        if(isset($_POST['family_income']) && !empty($_POST['family_income'])){
            $pdata['family_income'] = $this->input->post('family_income');
        }else{
            $pdata['family_income'] = $user->family_income;
        }
        if(isset($_POST['family_status']) && !empty($_POST['family_status'])){
            $pdata['family_status'] = $this->input->post('family_status');
        }else{
            $pdata['family_status'] = $user->family_status;
        }
        if(isset($_POST['diet']) && !empty($_POST['diet'])){
            $pdata['diet'] = $this->input->post('diet');
        }else{
            $pdata['diet'] = $user->diet;
        }
        if(isset($_POST['is_drinking']) && !empty($_POST['is_drinking'])){
            $pdata['is_drinking'] = $this->input->post('is_drinking');
        }else{
            $pdata['is_drinking'] = $user->is_drinking;
        }
        if(isset($_POST['is_smoking']) && !empty($_POST['is_smoking'])){
            $pdata['is_smoking'] = $this->input->post('is_smoking');
        }else{
            $pdata['is_smoking'] = $user->is_smoking;
        }
        if(isset($_POST['about_me']) && !empty($_POST['about_me'])){
            $pdata['about_me'] = $this->input->post('about_me');
        }else{
            $pdata['about_me'] = $user->about_me;
        }
        if(isset($_POST['no_of_child']) && !empty($_POST['no_of_child'])){
            $pdata['no_of_child'] = $this->input->post('no_of_child');
        }else{
            $pdata['no_of_child'] = $user->no_of_child;
        }
        if(isset($_POST['native_place']) && !empty($_POST['native_place'])){
            $pdata['native_place'] = $this->input->post('native_place');
        }else{
            $pdata['native_place'] = $user->native_place;
        }
        $user = $this->pro->update_step_one($pdata);
        if(!$user){
            $this->response( [
                'success'=>false,
                'message'=>'User Not updated'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$user,
                'message' => 'Great '
            ], REST_Controller::HTTP_OK );         
        }
    }
    public function recent_profiles_post()
    {
        $data['member_gender'] = $this->input->post('gender');
        $data['user_id'] = $this->input->post('user_id');
        
        $data['page'] = $this->input->post('page_no');
        $profiles = $this->pro->get_recent_profiles($data);
        if(!$profiles){
            $this->response( [
                'success'=>false,
                'message'=>'User Not found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$profiles,
                'message' => 'Great '
            ], REST_Controller::HTTP_OK );
        }
    }
    public function verified_profiles_post()
    {
        $data['gender'] = $this->input->post('gender');
        $data['user_id'] = $this->input->post('user_id');
        $data['page'] = $this->input->post('page_no');
        $profiles = $this->pro->verified_profiles($data);
        if(!$profiles){
            $this->response( [
                'success'=>false,
                'message'=>'User Not updated'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$profiles,
                'message' => 'Great '
            ], REST_Controller::HTTP_OK );
        }
    }
    public function matching_profiles_post(){
      
        $user_id = $this->input->post('user_id');
        // $gender = $this->input->post('gender');
        //  $page = $this->input->post('page_no');
        $profiles = $this->pro->matching_profiles($user_id, $gender, $page);
        if(!$profiles){
            $this->response( [
                'success'=>false,
                'message'=>'Matching Profiles Not found'
            ], REST_Controller::HTTP_OK );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$profiles,
                'message' => 'Profiles searched '
            ], REST_Controller::HTTP_OK );
        }
    }
//     public function send_interest_post(){
// // ini_set('display_errors', 1);
//         $data['user_id'] = $this->input->post('user_id');
//         $pid = $this->input->post('profile_id'); 
//     //     if(is_numeric($pid)){
//     //         $data['profile_id'] = $this->input->post('profile_id');$ussr = $this->db->get_where('members',array('id'=>$pid))->row();
//     //   }else{
//     //         // $pp = (int) filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
//     //       $ussr = $this->db->get_where('members',array('profile_id'=>$pid))->row();
//     //     $data['profile_id']=$ussr->id; // $data['profile_id'] = abs(10000-$pp);print_r($data)
//     //     }//;
//     //     $sentInterest = $this->pro->send_interest($data);
//         //$usr = 10000+$data['user_id'];
//         //$user = 'HIM'.$usr;
        
//         if(is_numeric($pid)){
//             $data['profile_id'] = $this->input->post('profile_id');
//         }else{
//             $pp = (int) filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
//             $data['profile_id'] = abs(10000-$pp);
//         }
//         $sentInterest = $this->pro->send_interest($data);
//         // if($sentInterest){
//         //     $this->interest_received_notification($profile_id,$email,$user);
//         // }
//         $usr = 10000+$data['user_id'];
//         $user = 'HIM'.$usr;
//         //$sentInterest = $this->pro->send_interest($data);// $to = $ussr->google_token;
//         //$user=$ussr->profile_id;// $profile_id = $ussr->profile_id;
//         // $number = $ussr->mobile_number;
//         // $email = $ussr->email;
//         // $messageText=" A new interest has received from in your id ".$profile_id. ", Please check your account : HIMRMB";
//         // $messageText=urlencode($messageText);
//         // $request = "http://nimbusit.info/api/pushsms.php?user=t5himrishtey&key=010GK17405B84txa8io9&sender=HIMRMB&mobile=".$number."&text=".$messageText."&entityid=1701164189692214854&templateid=1707166254915499453";
//         if(!$sentInterest){
//             $this->response( [
//                 'success'=>false,
// 'user'=>$ussr->id,
//                 'message'=>'Interest not Sent'
//             ], REST_Controller::HTTP_NOT_FOUND );
//         }else{
//             $this->response( [
//               'success'=>true,
//                 'user'=>$ussr,
                
               
//                 'message' => 'Interest Sent Successfully ',
//                 //'msg'=>$request
//             ], REST_Controller::HTTP_OK );
//         }
//     }
  public function send_interest_post(){
        $data['user_id'] = $this->input->post('user_id');
        $pid = $this->input->post('profile_id'); 
        if(is_numeric($pid)){
            $data['profile_id'] = $this->input->post('profile_id');
        }else{
            $pp = (int) filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
            $data['profile_id'] = abs(10000-$pp);
        }
        $sentInterest = $this->pro->send_interest($data);
        $usr = 10000+$data['user_id'];
        $user = 'HIM'.$usr;
        //$sentInterest = $this->pro->send_interest($data);// $to = $ussr->google_token;
        //$user=$ussr->profile_id;// $profile_id = $ussr->profile_id;
        $sender = $this->db->get_where('members',array('id'=>$data['user_id']))->row();
        $ussr = $this->db->get_where('members',array('id'=>$pid))->row();
        $number = $ussr->mobile_number;
        $email = $ussr->email;
        $token = $ussr->google_token;
        $messageText=" A new interest has received from in your id ".$profile_id. ", Please check your account : HIMRMB";
        $messageText=urlencode($messageText);
        $request = "http://nimbusit.info/api/pushsms.php?user=t5himrishtey&key=010GK17405B84txa8io9&sender=HIMRMB&mobile=".$number."&text=".$messageText."&entityid=1701164189692214854&templateid=1707166254915499453";
        
        if(!$sentInterest){
            $this->response( [
                'success'=>false,
            'user'=>$ussr->id,
                'message'=>'Interest not Sent'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
               
            $this->response( [
               'success'=>true,
                'user'=>$ussr,
                'message' => 'Interest Sent Successfully ',
                'notification' => $this->notification($token,$sender->profile_id)
                //'msg'=>$request
            ], REST_Controller::HTTP_OK );
        }
    }
    public function interest_action_post(){
        $data['user_id'] = $this->input->post('user_id');
        $data['profile_id'] = $this->input->post('profile_id');
        $data['status'] = $this->input->post('status');
        $actionInterest = $this->pro->action_interest($data);
        
        
        $ussr = $this->db->get_where('members',array('id'=>$data['user_id']))->row();
        $action = $this->db->get_where('members',array('id'=>$data['profile_id']))->row();
        $to = $ussr->google_token;
        $profile = $action->profile_id;
        if(!$actionInterest){
            $this->response( [
                'success'=>false,
                'message'=>'Interest status not saved'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            if($data['status'] == 1){
                $dt['message'] = "Your Interested is accepted by ".$profile."";
                $dt['token'] = $to;
                $dt['title'] = "Himrishtey";
            }elseif($data['status'] == 2){
                $dt['message'] = "Your Interested is rejected by ".$profile."";
                $dt['token'] = $to;
                $dt['title'] = "Himrishtey";
                  
            }
            $this->response( [
               'success'=>true,
                'user'=>$actionInterest,
                'message' => 'Interest status saved  Successfully ',
                'notification' => $this->push_note($dt)
                
            ], REST_Controller::HTTP_OK );
        }
    }
    public function sent_interest_list_post(){
        $user_id = $this->input->post('user_id');
   
        $sentInterest = $this->pro->sent_interest_list($user_id);  
        if(!$sentInterest){
            $this->response( [
                'success'=>false,
                'message'=>'No Interest Found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$sentInterest,
                'message' => 'Interest list Found'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function accepted_sent_interest_list_post(){
        $user_id = $this->input->post('user_id');
   
        $sentInterest = $this->pro->accepted_sent_interest_list($user_id);  
        if(!$sentInterest){
            $this->response( [
                'success'=>false,
                'message'=>'No Interest Found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$sentInterest,
                'message' => 'Interest list Found'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function rejected_sent_interest_list_post(){
        $user_id = $this->input->post('user_id');
        $sentInterest = $this->pro->rejected_sent_interest_list($user_id); 
        if(!$sentInterest){
            $this->response( [
                'success'=>false,
                'message'=>'No Interest Found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$sentInterest,
                'message' => 'Interest list Found'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function received_interest_post(){
        $user_id = $this->input->post('user_id');
   
        $sentInterest = $this->pro->received_interest($user_id);  
        if(!$sentInterest){
            $this->response( [
                'success'=>false,
                'message'=>'No Interest Found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$sentInterest,
                'message' => 'Recieved Interest Found'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function accepted_interests_post(){
        $user_id = $this->input->post('user_id');
   
        $acceptedInterest = $this->pro->accepted_interests($user_id);  
        
        if(!$acceptedInterest){
            $this->response( [
                'success'=>false,
                'message'=>'No Accepted Interest Found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$acceptedInterest,
                'message' => 'Accepted Interests Found'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function rejected_interests_post(){
        $user_id = $this->input->post('user_id');
   
        $rejectedInterest = $this->pro->rejected_interests($user_id);  
        
        if(!$rejectedInterest){
            $this->response( [
                'success'=>false,
                'message'=>'No Accepted Interest Found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$rejectedInterest,
                'message' => 'Accepted Interests Found'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function shortlisted_profiles_post(){
        $user_id = $this->input->post('user_id');
        $page = $this->input->post('page_no');
   
        $shortlistProfiles = $this->pro->shortlisted_profiles($user_id,$page);  
        if(!$shortlistProfiles){
            $this->response( [
                'success'=>false,
                'message'=>'No Shortlisted Profile Found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$shortlistProfiles,
                'message' => 'Shortlisted Profile Found'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function shortlist_profile_post(){
        $data['user_id'] = $this->input->post('user_id');
        $data['profile_id'] = $this->input->post('profile_id');
        $shortlist = $this->pro->shortlist_profile($data);
        $user = $this->db->get_where('members',array('id'=>$data['user_id']))->row();
        $profile = $this->db->get_where('members',array('id'=>$data['profile_id'], 'active=>"Yes"'))->row();
        if(!$shortlist){
            $this->response( [
                'success'=>false,
                'message'=>'Profile not Shortlisted'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->profile_shortlist_notification($user->profile_id,$profile->email);
            $this->response( [
               'success'=>true,
                'user'=>$shortlist,
                'message' => 'Profile Shortlisted Successfully '
            ], REST_Controller::HTTP_OK );
        }
    }
    public function delete_profile_post()
    {
        $data['user_id'] = $this->input->post('user_id');
        $data['reason'] = $this->input->post('reason');
        $delete = $this->pro->delete_profile($data);
        if(!$delete){
            $this->response( [
                'success'=>false,
                 'message'=>'Request to delete profile not submitted'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$delete,
                'message' => 'Request to delete profile submitted Successfully'
            ], REST_Controller::HTTP_OK );
        }   
    }
    public function report_profile_post()
    {
        $data['user_id'] = $this->input->post('user_id');
        $data['profile_id'] = $this->input->post('profile_id');
        $data['reason'] = $this->input->post('reason');
        $report = $this->pro->report_profile($data);
        if(!$report){
            $this->response( [
                'success'=>false,
                 'message'=>'Report profile not submitted'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$report,
                'message' => 'Profile Reported'
            ], REST_Controller::HTTP_OK );
        }   
    }
    public function search_profile_by_id_post()
    {
        $profile_id = $this->input->post('profile_id');
        $user_id = $this->input->post('user_id');
        $user = $this->pro->search_profile_by_id($profile_id,$user_id);
        if(!$user){
            $this->response( [
                'success'=>false,
                 'message'=>'Profile not searched'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$user,
                'message' => 'Profile Searched'
            ], REST_Controller::HTTP_OK );
        } 
    }
    public function quick_search_post()
    {
        $age_from = "";
        $age_from_search ="";
        $age_to = "";
        $age_to_search = "";
        $marital_status = "";
        $marital_status_search = "";
        $religion = "";
        $religion_search = array();
        $cast = "";
        $cast_search =array(); 
        $today = date('Y-m-d');
        $member_id = $_POST['user_id'];
        $member_gender = $_POST['gender'];
        $page = $_POST['page_no'];
        $limit = 10;
        $offset = ($page - 1) * $limit;
        if(isset($_POST['age_from']) && $_POST['age_from'] != '')
        {
            $age_from = $_POST['age_from'];
            $age_from_search = "and (FLOOR(DATEDIFF('$today', members.birth_date_time)/365)) >= $age_from";
        }
        if(isset($_POST['age_to']) && $_POST['age_to'] != '')
        {
            $age_to = $_POST['age_to'];
            $age_to_search = "and (FLOOR(DATEDIFF('$today', members.birth_date_time)/365)) <= $age_to";
        }
        if(isset($_POST['religion']) && $_POST['religion'] != '')
        {
            $religion_search = explode(',',$_POST['religion']);
        }
        if(isset($_POST['cast']) && $_POST['cast'] != '')
        {
            $cast_search = explode(',',$_POST['cast']);
        }
        if(isset($_POST['marital_status']) && $_POST['marital_status'] != '')
        {
            $marital_status = $_POST['marital_status'];
            $marital_status_search = "and marital_status = '$marital_status'";
        }
           $qry = "SELECT members.*, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime 
            FROM members 
            WHERE gender != '$member_gender' 
              AND id != $member_id 
              AND active = 'Yes' 
              $age_from_search 
              $age_to_search 
              $marital_status_search 
            ORDER BY activation_number DESC 
            LIMIT $limit OFFSET $offset";
            // echo $qry; die;
            $query1 = $this->db->query($qry);
            $results = $query1->result();
            // echo '<pre>'; print_r($results); die;
            $user = array();
            foreach($results as $key => $row){
                // $user[$key]['id'] = $row->id;
                    $id = $row->id;
                    // $date1 = new DateTime($row->birth_date_time);
                    // $date2 = $date1->diff(new DateTime($today));
                    $datee = $row->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date11 = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date11 = str_replace(" PM","",$datee);
                    }else{
                        $date11 = $datee;
                    }
                    $date1 = new DateTime($date11);
                    $date2 = $date1->diff(new DateTime($today));
                    $age_years = $date2->y;
                    $age_months = $date2->m;
                    $birth_date_time = $row->birthdatetime;
                    $gender = $row->gender;
                    $height = $row->height;
                    $religion = $row->religion;
                    //$religion = explode(',', $religion);
                    $mother_tongue = $row->mother_tongue;
                    $cast = $row->cast;
                    $education = $row->education;
                    $employed_in = $row->employed_in;
                    $occupation = $row->occupation;
                    $country_living_in = $row->country_living_in;
                    $state_living_in = $row->state_living_in;
                    $city_living_in = $row->city_living_in;
                    $address_living_in = $row->address_living_in;
                    $location = $city_living_in."<br>".$state_living_in." ".$country_living_in;
                    $photo = $row->photo;
                    $photo_approved = $row->photo_approved;
                    $profile_completed = $row->profile_completed;
                    // if($profile_completed == 100){
                        if($religion_search == array())
                        {
                            $religion_search = array(0);
                        }
                        if($cast_search == array())
                        {
                            $cast_search = array(0);
                        }
                        if(in_array($religion, $religion_search) && in_array($cast, $cast_search)   )
                        {
                            $user[$key]['id'] = $row->id;
                            $user[$key]['activation_number'] = $row->activation_number;
                            $user[$key]['full_name'] = $row->full_name;
                            $date1 = new DateTime($row->birth_date_time);
                            $date2 = $date1->diff(new DateTime($today));
                            $user[$key]['age_years'] = $date2->y;
                            $user[$key]['age_months'] = $date2->m;
                            $user[$key]['birth_date_time'] = $row->birthdatetime;
                            $user[$key]['profile_id'] = $row->profile_id;
                            $user[$key]['gender'] = $row->gender;
                            $user[$key]['height'] = $row->height;
                            $user[$key]['religion'] = $row->religion;
                            //$religion = explode(',', $religion);
                            $user[$key]['mother_tongue'] = $row->mother_tongue;
                            $user[$key]['cast'] = $row->cast;
                            $user[$key]['education'] = $row->education;
                            $user[$key]['employed_in'] = $row->employed_in;
                            $user[$key]['occupation'] = $row->occupation;
                            $user[$key]['country_living_in'] = $row->country_living_in;
                            $user[$key]['state_living_in'] = $row->state_living_in;
                            $user[$key]['city_living_in'] = $row->city_living_in;
                            $user[$key]['address_living_in'] = $row->address_living_in;
                            $user[$key]['annual_income'] = $row->annual_income;
                            $user[$key]['location'] = $city_living_in."<br>".$state_living_in." ".$country_living_in;
                            $user[$key]['photo'] = $row->photo;
                            $user[$key]['photo_approved'] = $row->photo_approved;
                            $profile=$this->db->get_where('sent_interests', array('member_id =' => $member_id ,'profile_id' => $row->id))->row();
                            if(!empty($profile)){
                            $user[$key]['interest'] = '1';
                            }else{
                                $user[$key]['interest'] = '0';
                            }
                            $user[$key] = array_merge((array) $row, $user[$key]);
                        }
                    }
            // }
            $result=array();
        foreach($user as $key => $value)
        {
            if(!empty($value["id"]))
            {
                $result[]=$value;
            }
        }
            if(!$result){
            $this->response( [
                'success'=>false,
                 'message'=>'Profile not searched'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'user'=>$result, 
                'message' => 'Profile Searched'
            ], REST_Controller::HTTP_OK );
        } 
    }
//     public function advance_search_post()
//     {
//         $profile_id_search = "";
//         $state = "";
//         $state_search = "";
//         $city = "";
//         $city_search = "";
//         $employed_in = "";
//         $employed_in_search = array();
//         $age_from = "";
//         $age_from_search ="";
//         $age_to = "";
//         $age_to_search = "";
//         $height_from = "";
//         $height_from_search = "";
//         $height_to = "";
//         $height_to_search = "";
//         $manglik = "";
//         $manglik_search = "";
//         $marital_status = "";
//         $marital_status_search = array();
//         $annual_income = "";
//         $annual_income_search = "";
//         $family_status = "";
//         $family_status_search = "";
//         $family_type = "";
//         $family_type_search = "";
//         $religion = "";
//         $religion_search = array();
//         $mother_tongue = "";
//         $mother_tongue_search = array();
//         $cast = "";
//         $cast_search =array(); 
//         $education = "";
//         $education_search = array();
//         $today = date('Y-m-d');
//         $member_id = $_POST['user_id'];
//         $member_gender = $_POST['gender'];
//         if(isset($_POST['profile_id']) && $_POST['profile_id'] != '')
//         {
//             $profile_id = $_POST['profile_id'];
//             $age_from_search = "and profile_id = '$profile_id'";
//         }
//         if(isset($_POST['state_name']) && $_POST['state_name'] != '')
//         {
//             $state = $_POST['state_name'];
//             $state_search = "and state_living_in = '$state'";
//         }
//         if(isset($_POST['city']) && $_POST['city'] != '')
//         {
//             $city = $_POST['city'];
//             $city_search = "and city_living_in = '$city'";
//         }
//         if(isset($_POST['employed_in']) && $_POST['employed_in'] != '')
//         {
//             $employed_in_search = explode(',',$_POST['employed_in']);
//         }
//         if(isset($_POST['age_from']) && $_POST['age_from'] != '')
//         {
//             $age_from = $_POST['age_from'];
//             $age_from_search = "and (FLOOR(DATEDIFF('$today', members.birth_date_time)/365)) >= $age_from";
//         }
//         if(isset($_POST['age_to']) && $_POST['age_to'] != '')
//         {
//             $age_to = $_POST['age_to'];
//             $age_to_search = "and (FLOOR(DATEDIFF('$today', members.birth_date_time)/365)) <= $age_to";
//         }
//         if(isset($_POST['height_from']) && $_POST['height_from'] != '')
//         {
//             $height_from = $_POST['height_from'];
//             $height_from_search = " and height >= $height_from";
//         }
//         if(isset($_POST['height_to']) && $_POST['height_to'] != '')
//         {
//             $height_to = $_POST['height_to'];
//             $height_to_search = "and height <= $height_to";
//         }
//         if(isset($_POST['religion']) && $_POST['religion'] != '')
//         {
//             $religion_search = explode(',',$_POST['religion']);
//         }
//         if(isset($_POST['mother_tongue']) && $_POST['mother_tongue'] != '')
//         {
//             $mother_tongue_search = explode(',',$_POST['mother_tongue']);
//         }
//         if(isset($_POST['cast']) && $_POST['cast'] != '')
//         {
//             $cast_search = explode(',',$_POST['cast']);
//         }
//         if(isset($_POST['manglik']) && $_POST['manglik'] != '')
//         {
//             $manglik = $_POST['manglik'];
//             $manglik_search = "and manglik = '$manglik'";
//         }
//         if(isset($_POST['marital_status']) && $_POST['marital_status'] != '')
//         {
//             // $marital_status = $_POST['marital_status'];
//           $marital_status_search = explode(',',$_POST['marital_status']);
//           foreach($marital_status_search as &$v) {
//               $v = strtolower($v);
//             }
//           // $marital_status_search =  array_map('strtolower', $marital_status_search);
//         }
//         if(isset($_POST['education']) && $_POST['education'] != '')
//         {
//             $education_search = explode(',',$_POST['education']);
//         }
//         if(isset($_POST['annual_income']) && $_POST['annual_income'] != '')
//         {
//             $annual_income = $_POST['annual_income'];
//             $annual_income_search = "and annual_income >= '$annual_income' && annual_income <> 'Not Disclosed' ";
//             //How
//         }
//         if(isset($_POST['family_status']) && $_POST['family_status'] != '')
//         {
//             $family_status = $_POST['family_status'];
//             $family_status_search = "and family_status = '$family_status'";
//         }
//         if(isset($_POST['family_type']) && $_POST['family_type'] != '')
//         {
//             $family_type = $_POST['family_type'];
//             $family_type_search = "and family_type = '$family_type'";
//         }
//         $qry = "Select members.* from members where gender != '$member_gender' and id != $member_id and active = 'Yes' $profile_id_search $city_search $state_search $age_from_search $age_to_search $height_from_search $height_to_search $manglik_search  $annual_income_search $family_status_search $family_type_search and profile_hide !='yes'  order by id DESC";
//             $query1 = $this->db->query($qry);
//             // $this->db->order_by('activation_number','desc');
//             $results = $query1->result();
//             // $user = array();
//             $key = 0;
//             foreach($results as $row){
//                 //$user[$key]['id'] = '';
//                     $id = $row->id;
//                     // $date1 = new DateTime($row->birth_date_time);
//                     // $date2 = $date1->diff(new DateTime($today));
//                     // $age_years = $date2->y;
//                     // $age_months = $date2->m;
//                     $datee = $row->birth_date_time;
//                     if(strpos($datee,' AM')){
//                         $date11 = str_replace(" AM","",$datee);
//                     }elseif(strpos($datee,' PM')){
//                         $date11 = str_replace(" PM","",$datee);
//                     }else{
//                         $date11 = $datee;
//                     }
//                     $date1 = new DateTime($date11);
//             $date2 = $date1->diff(new DateTime($today));
//                     $age_years = $date2->y;
//                     $age_months = $date2->m;
//                     $birth_date_time = $row->birthdatetime;
//                     $gender = $row->gender;
//                     $height = $row->height;
//                     $religion = $row->religion;
//                     //$religion = explode(',', $religion);
//                     $mother_tongue = $row->mother_tongue;
//                     $cast = $row->cast;
//                     $marital_status = strtolower($row->marital_status);
//                     $education = $row->education;
//                     $employed_in = $row->employed_in;
//                     $occupation = $row->occupation;
//                     $country_living_in = $row->country_living_in;
//                     $state_living_in = $row->state_living_in;
//                     $city_living_in = $row->city_living_in;
//                     $address_living_in = $row->address_living_in;
//                     $location = $city_living_in."<br>".$state_living_in." ".$country_living_in;
//                     $photo = $row->photo;
//                     $photo_approved = $row->photo_approved;
//                     $profile_completed = $row->profile_completed;
//                     // if($profile_completed == 100){
//                         //$age_years >= $age_from_search && $age_years <= $age_to_search
//                         //   
//                         if($religion_search == array())
//                         {
//                             $religion_search = array(0);
//                         }
//                         if($cast_search == array())
//                         {
//                             $cast_search = array(0);
//                         }
//                         if($education_search == array())
//                         {
//                             $education_search = array(0);
//                         }
//                         if($mother_tongue_search == array())
//                         {
//                             $mother_tongue_search = array(0);
//                         }
//                         if($employed_in_search == array())
//                         {
//                             $employed_in_search = array(0);
//                         }
//                         if($marital_status_search == array())
//                         {
//                             $marital_status_search = array(0);
//                         }
                        
//                         if(in_array($religion, $religion_search) && in_array($cast, $cast_search) && in_array($education, $education_search) && in_array($mother_tongue, $mother_tongue_search) && in_array($employed_in, $employed_in_search) && in_array($marital_status, $marital_status_search))
//                         {
                         
//                             $user[$key]['id'] = $row->id;
                            
//                             $user[$key]['activation_number'] = $row->activation_number;
//                             $user[$key]['profile_id'] = $row->profile_id;
                            
//                             $user[$key]['full_name'] = $row->full_name;
//                             $date1 = new DateTime($row->birth_date_time);
//                             $date2 = $date1->diff(new DateTime($today));
//                             $user[$key]['age_years'] = $date2->y;
//                             $user[$key]['age_months'] = $date2->m;
//                             $user[$key]['birth_date_time'] = $row->birthdatetime;
                            
//                             $user[$key]['birth_date_time'] = $row->birth_date_time;
//                             $user[$key]['gender'] = $row->gender;
//                             $user[$key]['height'] = $row->height;
//                             $user[$key]['religion'] = $row->religion;
//                             //$religion = explode(',', $religion);
//                             $user[$key]['mother_tongue'] = $row->mother_tongue;
//                             $user[$key]['cast'] = $row->cast;
//                             $user[$key]['education'] = $row->education;
//                             $user[$key]['employed_in'] = $row->employed_in;
//                             $user[$key]['occupation'] = $row->occupation;
//                             $user[$key]['country_living_in'] = $row->country_living_in;
//                             $user[$key]['state_living_in'] = $row->state_living_in;
//                             $user[$key]['city_living_in'] = $row->city_living_in;
//                             $user[$key]['address_living_in'] = $row->address_living_in;
//                             $user[$key]['annual_income'] = $row->annual_income;
//                             $user[$key]['location'] = $city_living_in."<br>".$state_living_in." ".$country_living_in;
//                             // $user[$key]['photo'] = $row->photo;
//                             $user[$key]['photo'] = $row->photo;
//                             $user[$key]['photo_approved'] = $row->photo_approved;
//                             $profile=$this->db->get_where('sent_interests', array('member_id =' => $member_id ,'profile_id' => $row->id))->row();
//                             if(!empty($profile)){
//                             $user[$key]['interest'] = '1';
//                             }else{
//                                 $user[$key]['interest'] = '0';
//                             }
// $key++;
//                         }
//                     }
                    
// if (count($user) >= 30) {
//     // Get 2 random keys
//     $randomKeys = array_rand($user, 30);
//     // Create the subset using the random keys
//     $subset = array_intersect_key($user, array_flip($randomKeys));
// }
//             // }
//         if(!$user){
//             $this->response( [
//                 'success'=>true,
//                  'message'=>'Profile not searched',
                 
//                  'user' => []
//             ], REST_Controller::HTTP_OK );
//         }else{
//             $this->response( [
//               'success'=>true,
//                 'user'=>$user, 
//                 'message' => 'Profile Searched'
//             ], REST_Controller::HTTP_OK );
//         } 
//     }
public function advance_search_post() {
    // echo '<pre>'; print_r($_POST); die;
    $today = date('Y-m-d');
    $member_id = $_POST['user_id'];
    $member_gender = $_POST['gender'];
    $page = $_POST['page_no'];
    $limit = 10; 
    $offset = ($page - 1) * $limit;
    
    // Initialize search conditions
    $search_conditions = "WHERE gender != ? AND id != ? AND active = 'Yes' AND profile_hide != 'yes'";
    $params = [$member_gender, $member_id];
    // Build dynamic conditions
    if (!empty($_POST['profile_id'])) {
        $search_conditions .= " AND profile_id = ?";
        $params[] = $_POST['profile_id'];
    }
    if (!empty($_POST['state_name'])) {
        $search_conditions .= " AND state_living_in = ?";
        $params[] = $_POST['state_name'];
    }
    if (!empty($_POST['city'])) {
        $search_conditions .= " AND city_living_in = ?";
        $params[] = $_POST['city'];
    }
    if (!empty($_POST['employed_in'])) {
        $employed_in = explode(',', $_POST['employed_in']);
        $placeholders = implode(',', array_fill(0, count($employed_in), '?'));
        $search_conditions .= " AND employed_in IN ($placeholders)";
        $params = array_merge($params, $employed_in);
    }
    if (!empty($_POST['age_from'])) {
        $search_conditions .= " AND (FLOOR(DATEDIFF('$today', members.birth_date_time)/365)) >= ?";
        $params[] = $_POST['age_from'];
    }
    if (!empty($_POST['age_to'])) {
        $search_conditions .= " AND (FLOOR(DATEDIFF('$today', members.birth_date_time)/365)) <= ?";
        $params[] = $_POST['age_to'];
    }
    if (!empty($_POST['height_from'])) {
        $search_conditions .= " AND height >= ?";
        $params[] = $_POST['height_from'];
    }
    if (!empty($_POST['height_to'])) {
        $search_conditions .= " AND height <= ?";
        $params[] = $_POST['height_to'];
    }
    if (!empty($_POST['religion'])) {
        $religion = explode(',', $_POST['religion']);
        $placeholders = implode(',', array_fill(0, count($religion), '?'));
        $search_conditions .= " AND religion IN ($placeholders)";
        $params = array_merge($params, $religion);
    }
    if (!empty($_POST['mother_tongue'])) {
        $mother_tongue = explode(',', $_POST['mother_tongue']);
        $placeholders = implode(',', array_fill(0, count($mother_tongue), '?'));
        $search_conditions .= " AND mother_tongue IN ($placeholders)";
        $params = array_merge($params, $mother_tongue);
    }
    if (!empty($_POST['cast'])) {
        $cast = explode(',', $_POST['cast']);
        $placeholders = implode(',', array_fill(0, count($cast), '?'));
        $search_conditions .= " AND cast IN ($placeholders)";
        $params = array_merge($params, $cast);
    }
    if (!empty($_POST['marital_status'])) {
        $marital_status = array_map('strtolower', explode(',', $_POST['marital_status']));
        $placeholders = implode(',', array_fill(0, count($marital_status), '?'));
        $search_conditions .= " AND LOWER(marital_status) IN ($placeholders)";
        $params = array_merge($params, $marital_status);
    }
    if (!empty($_POST['education'])) {
        $education = array_map('strtolower', explode(',', $_POST['education']));
        $placeholders = implode(',', array_fill(0, count($education), '?'));
        $search_conditions .= " AND LOWER(education) IN ($placeholders)";
        $params = array_merge($params, $education);
    }
    if (!empty($_POST['manglik'])) {
        $search_conditions .= " AND manglik = ?";
        $params[] = $_POST['manglik'];
    }
    // Add limit and randomize if needed
    $search_conditions .= "ORDER BY activation_number DESC LIMIT $limit OFFSET $offset";
    // echo '<pre>'; print_r($params);
    // echo $search_conditions;die;
    // Build and execute the query
    $qry = "SELECT members.* FROM members $search_conditions";
    $query1 = $this->db->query($qry, $params);
    $results = $query1->result();
    // Process the results
    // $user = [];
    foreach ($results as $key => $row) {
        $date1 = new DateTime($row->birth_date_time);
        $date2 = $date1->diff(new DateTime($today));
        $row->age_years = $date2->y;
        $row->age_months = $date2->m;
    //     $user[] = [
    //         'id' => $row->id,
    //         'activation_number' => $row->activation_number,
    //         'profile_id' => $row->profile_id,
    //         'full_name' => $row->full_name,
            
    //         'birth_date_time' => $row->birth_date_time,
    //         'gender' => $row->gender,
    //         'height' => $row->height,
    //         'religion' => $row->religion,
    //         'mother_tongue' => $row->mother_tongue,
    //         'cast' => $row->cast,
    //         'education' => $row->education,
    //         'employed_in' => $row->employed_in,
    //         'occupation' => $row->occupation,
    //         'country_living_in' => $row->country_living_in,
    //         'state_living_in' => $row->state_living_in,
    //         'city_living_in' => $row->city_living_in,
    //         'address_living_in' => $row->address_living_in,
    //         'annual_income' => $row->annual_income,
    //         'location' => "{$row->city_living_in}<br>{$row->state_living_in} {$row->country_living_in}",
    //         'photo' => $row->photo,
    //         'photo_approved' => $row->photo_approved,
    //         'interest' => $this->hasSentInterest($member_id, $row->id) ? '1' : '0',
    //     ];
    }
    // Return the response
    if (empty($results)) {
        $this->response([
            'success' => true,
            'message' => 'Profile not searched',
            'user' => []
        ], REST_Controller::HTTP_OK);
    } else {
        $this->response([
            'success' => true,
            'user' => $results,
            'message' => 'Profile Searched'
        ], REST_Controller::HTTP_OK);
    }
}
public function advance_search1_post() {
    $today = date('Y-m-d');
    $member_id = $_POST['user_id'];
    $member_gender = $_POST['gender'];
    
    // Initialize search conditions
    $search_conditions = [];
    
    // Build search conditions based on input
    $this->buildSearchConditions($search_conditions, $_POST, $today);
    
    // Build the SQL query
    $qry = "SELECT members.* FROM members 
            WHERE gender != ? AND id != ? AND active = 'Yes' 
            AND profile_hide != 'yes' " . implode(" ", $search_conditions) . 
            " ORDER BY id DESC";
    
    $query1 = $this->db->query($qry, [$member_gender, $member_id]);
    $results = $query1->result();
    // Process results
    $user = [];
    foreach ($results as $row) {
        $user[] = $this->buildUserData($row, $today, $member_id);
    }
    if (empty($user)) {
        $this->response([
            'success' => true,
            'message' => 'Profile not searched',
            'user' => []
        ], REST_Controller::HTTP_OK);
    } else {
        $this->response([
            'success' => true,
            'user' => $user,
            'message' => 'Profile Searched'
        ], REST_Controller::HTTP_OK);
    }
}
private function buildSearchConditions(array &$conditions, array $data, $today) {
    if (!empty($data['profile_id'])) {
        $conditions[] = "AND profile_id = ?";
    }
    if (!empty($data['state_name'])) {
        $conditions[] = "AND state_living_in = ?";
    }
    if (!empty($data['city'])) {
        $conditions[] = "AND city_living_in = ?";
    }
    if (!empty($data['employed_in'])) {
        $conditions[] = "AND employed_in IN (" . implode(',', array_fill(0, count(explode(',', $data['employed_in'])), '?')) . ")";
    }
    if (!empty($data['age_from'])) {
        $conditions[] = "AND (FLOOR(DATEDIFF('$today', birth_date_time)/365)) >= ?";
    }
    if (!empty($data['age_to'])) {
        $conditions[] = "AND (FLOOR(DATEDIFF('$today', birth_date_time)/365)) <= ?";
    }
    if (!empty($data['height_from'])) {
        $conditions[] = "AND height >= ?";
    }
    if (!empty($data['height_to'])) {
        $conditions[] = "AND height <= ?";
    }
    // Add similar checks for other fields as needed
}
private function buildUserData($row, $today, $member_id) {
    $date1 = new DateTime($row->birth_date_time);
    $date2 = $date1->diff(new DateTime($today));
    return [
        'id' => $row->id,
        'activation_number' => $row->activation_number,
        'profile_id' => $row->profile_id,
        'full_name' => $row->full_name,
        'age_years' => $date2->y,
        'age_months' => $date2->m,
        'birth_date_time' => $row->birth_date_time,
        'gender' => $row->gender,
        'height' => $row->height,
        'religion' => $row->religion,
        'mother_tongue' => $row->mother_tongue,
        'cast' => $row->cast,
        'education' => $row->education,
        'employed_in' => $row->employed_in,
        'occupation' => $row->occupation,
        'country_living_in' => $row->country_living_in,
        'state_living_in' => $row->state_living_in,
        'city_living_in' => $row->city_living_in,
        'address_living_in' => $row->address_living_in,
        'annual_income' => $row->annual_income,
        'location' => "{$row->city_living_in}<br>{$row->state_living_in} {$row->country_living_in}",
        'photo' => $row->photo,
        'photo_approved' => $row->photo_approved,
        'interest' => $this->hasSentInterest($member_id, $row->id) ? '1' : '0'
    ];
}
private function hasSentInterest($member_id, $profile_id) {
    $profile = $this->db->get_where('sent_interests', ['member_id' => $member_id, 'profile_id' => $profile_id])->row();
    return !empty($profile);
}
    public function update_payment_status_post()
    {
    	$data['user_id'] = $this->input->post('user_id');
        $data['plan_id'] = $this->input->post('plan_id');
        $data['payment_date'] = $this->input->post('payment_date');
        $data['amount'] = $this->input->post('amount');
        $amount = $this->input->post('amount');
        $data['payment_id'] = $this->input->post('txn_id');
        $data['remark'] = $this->input->post('remark');
    	$data['payment_status'] = $this->input->post('payment_status');
    $ch = $this->pro->update_payment_status($data);
      
        
        if(!$ch){
            
            $this->response( [
                'success'=>false,
                 'message'=>'Payment Status Not Updated'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'Status'=>$user,
                'message' => 'Payment Status Updated'
            ], REST_Controller::HTTP_OK );
        } 
        }
    
    public function set_partner_preferences_post()
    {
        $data = array();
        $data['member_id'] = $this->input->post('user_id');
        $user_id = $this->input->post('user_id');
        $user = $this->db->get_where('members', array('id =' => $user_id))->row();
        if(isset($_POST['looking_for']) && !empty($_POST['looking_for'])){
            $data['looking_for'] = $this->input->post('looking_for');
        }else{
            $data['looking_for'] = $user->looking_for;
        }
        if(isset($_POST['partner_age_from']) && !empty($_POST['partner_age_from'])){
            $data['partner_age_from'] = $this->input->post('partner_age_from');
        }else{
            $data['partner_age_from'] = $user->partner_age_from;
        }
        if(isset($_POST['partner_age_to']) && !empty($_POST['partner_age_to'])){
            $data['partner_age_to'] = $this->input->post('partner_age_to');
        }else{
            $data['partner_age_to']=$user->partner_age_to;
        }
        if(isset($_POST['partner_country']) && !empty($_POST['partner_country'])){
            $data['partner_country'] =  $this->input->post('partner_country');
        }else{ 
            $data['partner_country'] = $user->partner_country;
        }
        if(isset($_POST['partner_religion']) && !empty($_POST['partner_religion'])){
            $data['partner_religion'] = $this->input->post('partner_religion');
        }else{
            $data['partner_religion'] = $user->partner_religion;
        }
        if(isset($_POST['partner_cast'])  && !empty($_POST['partner_cast'])){
            $data['partner_cast'] = $this->input->post('partner_cast');
        }else{
            $data['partner_cast']= $user->partner_cast;
        }
        if(isset($_POST['partner_height_from']) && !empty($_POST['partner_height_from'])){
            $data['partner_height_from'] = $this->input->post('partner_height_from');
        }else{
            $data['partner_height_from']=$user->partner_height_from;
        }
        if(isset($_POST['partner_height_to']) && !empty($_POST['partner_height_to'])){
            $data['partner_height_to'] = $this->input->post('partner_height_to');
        }else{
            $data['partner_height_to']=$user->partner_height_to;
        }
        if(isset($_POST['partner_education']) && !empty($_POST['partner_education'])){
            $data['partner_education'] = $this->input->post('partner_education');
        }else{
            $data['partner_education']=$user->partner_education;
        }
        if(isset($_POST['partner_mothertongue']) && !empty($_POST['partner_mothertongue'])){
            $data['partner_mothertongue'] = $this->input->post('partner_mothertongue');
        }else{
            $data['partner_mothertongue']=$user->partner_mothertongue;
        }
        if(isset($_POST['partner_annual_income_from']) && !empty($_POST['partner_annual_income_from'])){
            $data['partner_annual_income_from'] = $this->input->post('partner_annual_income_from');
        }else{
            $data['partner_annual_income_from'] = $user->partner_annual_income_from;
        }
        if(isset($_POST['partner_annual_income_to']) && !empty($_POST['partner_annual_income_to'])){
            $data['partner_annual_income_to'] = $this->input->post('partner_annual_income_to');
        }else{
            $data['partner_annual_income_to'] = $user->partner_annual_income_to;
        }
        if(isset($_POST['is_partner_manglik']) && !empty($_POST['is_partner_manglik'])){
        	$data['is_partner_manglik'] = $this->input->post('is_partner_manglik');
        }else{
            $data['is_partner_manglik'] = $user->is_partner_manglik;
        }
        if(isset($_POST['partner_occupation']) && !empty($_POST['partner_occupation'])){
        	$data['partner_occupation'] = $this->input->post('partner_occupation');
        }else{
            $data['partner_occupation'] = $user->partner_occupation;
        }	
        if(isset($_POST['partner_state']) && !empty($_POST['partner_state'])){
        	$data['partner_state'] = $this->input->post('partner_state');
        }else{
            $data['partner_state'] = $user->partner_state;
        }
        if(isset($_POST['partner_city']) && !empty($_POST['partner_city'])){
        	$data['partner_city'] = $this->input->post('partner_city');
        }else{
            $data['partner_city'] = $user->partner_city;
        }
        if(isset($_POST['partner_diet']) && !empty($_POST['partner_diet'])){
        	$data['partner_diet'] = $this->input->post('partner_diet');
        }else{
            $data['partner_diet'] = $user->partner_diet;
        }
        if(isset($_POST['is_partner_smoking']) && !empty($_POST['is_partner_smoking'])){
        	$data['is_partner_smoking'] = $this->input->post('is_partner_smoking');
        }else{
            $data['is_partner_smoking'] = $user->is_partner_smoking;
        }
        if(isset($_POST['is_partner_drinking']) && !empty($_POST['is_partner_drinking'])){
        	$data['is_partner_drinking'] = $this->input->post('is_partner_drinking');
        }else{
            $data['is_partner_drinking'] = $user->is_partner_drinking;
        }
        if(isset($_POST['about_my_partner']) && !empty($_POST['about_my_partner'])){
            $data['about_my_partner'] = $this->input->post('about_my_partner');
        }else{
            $data['about_my_partner'] = $user->about_my_partner;
        }
        $query = $this->pro->set_partner_preferences($data);
       
        if(!$query){
            $this->response( [
                'success'=>false,
                 'message'=>'Error'
            ], REST_Controller::HTTP_NOT_FOUND );  
        }else{  
            $this->response( [
               'success'=>true,
                'Status'=>$query,
                'message' => 'Data Updated'
            ], REST_Controller::HTTP_OK );
        }
        
    } 
    
    
    public function add_gallery_post(){
		                $supported_image = array('gif','jpg','jpeg','png');
						$user_id = $_POST['user_id'];
						$b_image = $_POST['image'];
						 $base64_images = explode(",",$b_image);
						//decode base64 string
						
						 foreach($base64_images as $base64_image){
						$image = base64_decode($base64_image);
					
						$f = finfo_open();
						$mime_type = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
						$image_type = substr($mime_type, strrpos($mime_type, '/') + 1);
						 $image_name = md5(uniqid(rand(), true)).".$image_type";
						//create png from decoded base 64 string and save the image in the parent folder
				//		$bpth = $_SERVER['DOCUMENT_ROOT']."/topStore/assets/uploads/stories/";
						$cur_folder = $_SERVER['DOCUMENT_ROOT']."/photos/photo_gallery/".$image_name;
						$folder = file_put_contents($cur_folder, $image);
						//$bpth = $_SERVER['DOCUMENT_ROOT']."/custom_uploads/images/";
						$rpth = "/photos/photo_gallery/";
				//		$img_name = implode(" ",$image_name);
						$ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
						if (in_array($ext, $supported_image)) {
							$result = $this->pro->gallery_images($user_id,$image_name);
						}else{
						}
					}
		 
            if ($result)
            {		
                // Set the response and exit
				$this->response([
					'data' => $result, 
					'status' => TRUE,
					'message' => "Gallery Added Successfully"
				], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code		 
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'Gallery Not added'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
		}
	public function offers_get(){
  
        $offers = $this->pro->get_offers();  
        if(!$offers){
            $this->response( [
                'success'=>false,
                'message'=>'No Interest Found'
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'offers'=>$offers,
                'message' => 'Offer Found'
            ], REST_Controller::HTTP_OK );
        }
    }
   public function rating_us_post()
   {
    $data['name'] = $this->input->post('name');
    $data['profile_id'] = $this->input->post('profile_id');
    $data['email']  = $this->input->post('email');
    $data['rating'] = $this->input->post('rating');
    $data['description'] = $this->input->post('description');
    $data['submited_at'] = date('d-m-Y');
    $rating = $this->pro->rating_us($data);
    if(!$rating){
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
               'success'=>true,
                'rating'=>$rating,
                'message' => 'Rating Submitted'
            ], REST_Controller::HTTP_OK );
        }
   }
   public function photo_privacy_post()
    {
        $data['user_id'] = $this->input->post('user_id');
        $data['status'] = $this->input->post('privacy_status');
        $privacy = $this->pro->photo_privacy($data);
        if(!$privacy){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                   'success'=>true,
                    'rating'=>$privacy,
                    'message' => 'Photo Privacy Updated'
                ], REST_Controller::HTTP_OK );
            }
       }
       public function get_memberships_get()
       {
            $membership = $this->pro->get_memberships();
            if(!$membership){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'memberships'=>$membership,
                    'message' => 'memberships Data Found'
                ], REST_Controller::HTTP_OK );
            }
       }
       public function get_plans_post()
       {
            $id = $this->input->post('membership_id');
            $plans = $this->pro->get_plans($id);
            if(!$plans){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'memberships'=>$plans,
                    'message' => 'Plans Found'
                ], REST_Controller::HTTP_OK );
            }
       }
       public function add_to_shortlist_post()
       {
            $data['user_id'] = $this->input->post('user_id');
            $data['profile_id'] = $this->input->post('profile_id');
            // $ussr = $this->db->get_where('members',array('id'=>$data['profile_id']))->row();
            // $userr = $this->db->get_where('members',array('id'=>$data['user_id']))->row();
            // $to = $ussr->google_token;
            // $profile = $userr->profile_id;
            $short = $this->pro->add_to_shortlist($data);
            if(!$short){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                // $message =  $profile." Shortlist Your Profile.";
                // $to = $to;
                // $title = "Himrishtey";
                // $this->send_not($message,$to,$title);
                $this->response( [
                    'success'=>true,
                    'shortlist'=>$short,
                    'message' => 'Profile Shortlisted'
                ], REST_Controller::HTTP_OK );
            }
       }
       public function remove_from_shortlist_post()
       {
            $data['user_id'] = $this->input->post('user_id');
            $data['profile_id'] = $this->input->post('profile_id');
            $rshort = $this->pro->remove_from_shortlist($data);
            if(!$rshort){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'shortlist'=>$rshort,
                    'message' => 'Profile removed from Shortlisted'
                ], REST_Controller::HTTP_OK );
            }
       }
       public function view_contact_post()
       {
        $data = array();
            $user_id = $this->input->post('user_id');
            $profile_id = $this->input->post('profile_id');
            $profile_amount = $this->input->post('profile_amount');
            $this->db->select_max('id');
            $this->db->where('member_id', $user_id);
            $user = $this->db->get('member_wallet')->row();
        if(!empty($user)){
            $wid = $user->id;
        }else{
            $wid = 0;
        }
        
        $wallet_amount = $this->db->get_where('member_wallet', array('id'=>$wid))->row();
             if(!empty($wallet_amount)){
                $balance = $wallet_amount->wallet_balance;
            }else{
                $balance = 0;
            }
            if($balance < $profile_amount){
                $this->response( [
                    'success'=>false,
                    'blnc' => $balance,
                    'message'=>'Insuffucient Amount, Please Add Money to the Wallet',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $new_balance = $balance - $profile_amount;
                $data = array('member_id' =>$user_id, 'wallet_balance'=> $new_balance, 'amount_deducted' => $profile_amount, 'amount_added' => 0);
                // $where = array('member_id'=> $user_id);
                // $this->db->where($where);
                $viewP = $this->db->insert('member_wallet',$data);
                $viewed_contact = $this->pro->viewed_contact($user_id,$profile_id);
                if(!$viewP){
                    $this->response( [
                        'success'=>false,
                        'message'=>'Something went wrong',
                    ], REST_Controller::HTTP_NOT_FOUND );
                }else{
                    $this->response( [
                        'success'=>true,
                        'shortlist'=>$viewP,
                        'message' => 'Contact Detail viewed'
                    ], REST_Controller::HTTP_OK );
                }
            }
       }
       public function hide_profile_post()
       {
            $data['user_id']  = $this->input->post('user_id');
            $data['days'] = $this->input->post('days');
            $hide = $this->pro->hide_profile($data);
                if(!$hide){
                    $this->response( [
                        'success'=>false,
                        'message'=>'Something went wrong',
                    ], REST_Controller::HTTP_NOT_FOUND );
                }else{
                    $this->response( [
                        'success'=>true,
                        'Hidden'=>$hide,
                        'message' => 'Profile Hidden '
                    ], REST_Controller::HTTP_OK );
                }
       }
       public function update_wallet_balance_post()
       {
            $data['member_id'] = $this->input->post('user_id');
            $data['amount'] = $this->input->post('amount');
            $data['payment_id'] = $this->input->post('txn_id') ;
            $data['remarks'] = $this->input->post('remark');  
            $wallet = $this->pro->update_wallet($data);     
            if(!$wallet){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'Hidden'=>$wallet,
                    'message' => 'Wallet Updated'
                ], REST_Controller::HTTP_OK );
            }
       }
       public function viewed_contacts_post()
       {
            $user_id = $this->input->post('user_id'); 
            $myContacts = $this->pro->viewed_contacts($user_id);     
            if(!$myContacts){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'user'=>$myContacts,
                    'message' => 'Viewed Cantact List Found'
                ], REST_Controller::HTTP_OK );
            }
       }
       public function callback_number_get()
       {
            $callback = $this->pro->get_callbacknumber();     
            if(!$callback){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'number'=>['number'=>$callback],
                    // 'number' => $callback,
                    'message' => 'callback number Found'
                ], REST_Controller::HTTP_OK );
            }
       }
       public function call_back_post()
{
    $user_id = $this->input->post('user_id');
    $number  = trim($this->input->post('number'));

    $member = $this->db->get_where('members', array('id' => $user_id))->row();

    if (!$member) {
        $this->response([
            'success' => false,
            'message' => 'Member not found'
        ], REST_Controller::HTTP_NOT_FOUND);
        return;
    }

    $messageText = "A call request from id " . $member->profile_id .
                   " regarding membership. Call back immediately " .
                   $member->full_name . ".HIMRMB";

    $messageText = urlencode($messageText);

    $request = "http://nimbusit.biz/api/SmsApi/SendMultipleApi?" .
               "UserID=himrishteybiz" .
               "&Password=vqbj8362VQ" .
               "&SenderID=HIMRMB" .
               "&Phno=" . $number .
               "&Msg=" . $messageText .
               "&EntityID=1701164189692214854" .
               "&TemplateID=1707166254945835455";
$request = preg_replace('/\s+/', '', $request);
echo $request; die;
    // Call SMS API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        $this->response([
            'success' => false,
            'message' => 'SMS API request failed'
        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    } else {
        $this->response([
            'success' => true,
            'number' => $number,
            'sms_response' => $response,
            'message' => 'Callback SMS sent successfully'
        ], REST_Controller::HTTP_OK);
    }
}
        public function wallet_offer_get()
       {
            $wallet_offers = $this->pro->get_wallet_offer();    
            $wallet_offer = (array) $wallet_offers; 
            if(!$wallet_offer){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'number'=>$wallet_offer,
                    'message' => 'Wallet Offer Found'
                ], REST_Controller::HTTP_OK );
            }
       }
       
        public function user_wallet_txns_post()
       {
            $user_id = $this->input->post('user_id');
            $wallet_offers = $this->pro->get_wallet_transactions($user_id);    
            $wallet_offer =  $wallet_offers; 
            if(!$wallet_offer){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'number'=>$wallet_offer,
                    'message' => 'Wallet transactions Found'
                ], REST_Controller::HTTP_OK );
            }
       }
       
       public function delete_gallery_image_post()
       {
            $data['user_id'] = $this->input->post('user_id');
            $data['id'] = $this->input->post('photo_id');
            $image = $this->pro->delete_gallery_image($data);    
            if(!$image){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'number'=>$image,
                    'message' => 'Photo deleted Succesfully'
                ], REST_Controller::HTTP_OK );
            }
       }
       
    public function all_interest_list_post(){
        $user_id = $this->input->post('user_id');
        $all_interest['sentInterest'] = $this->pro->sent_interest_list($user_id);  
        $all_interest['rejectedInterest'] = $this->pro->rejected_interests($user_id);  
        $all_interest['acceptedInterest'] = $this->pro->accepted_interests($user_id);  
        $all_interest['received_interest'] = $this->pro->received_interest($user_id);  
        $all_interest['rejected_sent_interest'] = $this->pro->rejected_sent_interest_list($user_id); 
        $all_interest['accepted_sent_interest'] = $this->pro->accepted_sent_interest_list($user_id);
        if(!$all_interest){
                $this->response( [
                    'success'=>false,
                    'message'=>'Something went wrong',
                ], REST_Controller::HTTP_NOT_FOUND );
            }else{
                $this->response( [
                    'success'=>true,
                    'intrest'=>$all_interest,
                    'message' => 'Photo deleted Succesfully'
                ], REST_Controller::HTTP_OK );
            }
    }
       
    public function send_not($message,$to,$title){
        // echo "Hello"; die;
       $json_data = [
                "to" => $to,
                "notification" => [
                    "body" => $message,
                    "title" => $title,
                    "icon" => "ic_launcher"
                ],
            ];
                   $data = json_encode($json_data);
                   print_r($data);
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
               
               
               public function app_status_get()
               {
                $query = $this->db->get('app_status')->result(); 
                // $app_status = $query->app_status;
                // print_r($query);die;
                   if(!$query){
                    $this->response( [
    
                        'success'=>false,
    
                        'message'=>'Something went wrong',
    
                    ], REST_Controller::HTTP_NOT_FOUND );
    
                }else{
    
                    $this->response( [
    
                        'success'=>true,
    
                        'number'=>$query,
    
                        'message' => 'App Status Found'
    
                    ], REST_Controller::HTTP_OK );
    
                }
               }
               
        
    public function wallet_details_post()
    {
        $user_id = $this->input->post('user_id');
        $data['wallet_balance'] = $this->pro->get_wallet_balance($user_id);
        $data['wallet_offers'] = $this->pro->get_wallet_offer();
        $data['wallet_transactions'] = $this->pro->get_wallet_transactions($user_id);
        if(!$data){
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
                'success'=>true,
                'data'=>$data,
                'message' => 'Data Found'
            ], REST_Controller::HTTP_OK );
        }
    }
    
    public function profile_like_post()
    {
        $data['user_id'] = $this->input->post('user_id');
        $data['profile_id'] = $this->input->post('profile_id');
        $like = $this->pro->like_profile($data);
        if(!$like)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
                'success'=>true,
                'data'=>$like,
                'message' => 'Profile Liked'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function profile_unlike_post()
    {
        $data['user_id'] = $this->input->post('user_id');
        $data['profile_id'] = $this->input->post('profile_id');
        $like = $this->pro->unlike_profile($data);
        if(!$like)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
                'success'=>true,
                'data'=>$like,
                'message' => 'Profile unLiked'
            ], REST_Controller::HTTP_OK );
        }
    }
    public function add_success_story_post()
    {
        $supported_image = array('gif','jpg','jpeg','png');
        $data['user_id'] = $_POST['user_id'];
        $b_image = $_POST['image'];
        $data['description'] = $_POST['description'];
        $data['groom_name'] = $_POST['groom_name'];
        $data['bride_name'] = $_POST['bride_name'];
    	$base64_images = explode(",",$b_image);
        foreach($base64_images as $base64_image)
        {
			$image = base64_decode($base64_image);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
			$image_type = substr($mime_type, strrpos($mime_type, '/') + 1);
    		$image_name = md5(uniqid(rand(), true)).".$image_type";
            $cur_folder = $_SERVER['DOCUMENT_ROOT']."/photos/ss/".$image_name;
			$folder = file_put_contents($cur_folder, $image);
            $rpth = "/photos/photo_gallery/";
			$ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
			if (in_array($ext, $supported_image)) {
				$result = $this->pro->success_story($data,$image_name);
			}else{
			}
		}
        if ($result)
        {		
    		$this->response([
				'data' => $result, 
				'status' => TRUE,
				'message' => "Success Story Added Successfully"
			], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code		 
        }else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Story Not added'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
	}
    public function success_stories_get()
    {
        $like = $this->pro->get_success_stories();
        if(!$like)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
                'success'=>true,
                'data'=>$like,
                'message' => 'stories found'
            ], REST_Controller::HTTP_OK );
        }
    }
    
    public function who_viewed_post()
    {
        $user_id = $this->input->post('user_id');
        $page = $this->input->post('page_no');
        $like = $this->pro->who_viewed($user_id,$page);
        if(!$like)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
                'success'=>true,
                'user'=>$like,
                'message' => 'stories found'
            ], REST_Controller::HTTP_OK );
        }
    }
    
    public function who_likes_post()
    {
        $user_id = $this->input->post('user_id');
        $like = $this->pro->who_likes($user_id);
        if(!$like)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_OK );
        }else{
            $this->response( [
                'success'=>true,
                'user'=>$like,
                'message' => 'stories found'
            ], REST_Controller::HTTP_OK );
        }
    }
    
    public function viewed_by_me_post()
    {
        $user_id = $this->input->post('user_id');
        $page = $this->input->post('page_no');
        $like = $this->pro->viewed_by_me($user_id,$page);
        if(!$like)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_OK );
        }else{
            $this->response( [
                'success'=>true,
                'user'=>$like,
                'message' => 'stories found'
            ], REST_Controller::HTTP_OK );
        }
    }
   
   
   public function stats_get($user_id)
   {
    //   $data['profile_viewed'] = $this->db->where('viewed_profile_id',$user_id)->from('profile_viewed')->count_all_results();
    $data['profile_viewed'] = $this->db->select('count(*) as total')->from('profile_viewed pv')->join('members m', 'm.id = pv.member_id')
                            ->where('pv.viewed_profile_id', $user_id)
                            ->where('m.active', 'Yes')
                            ->where('m.profile_hide !=','yes')
                            ->get()
                            ->row()
                            ->total;
    //    $data['profile_likes'] = $this->db->where('like_profile_id',$user_id)->from('profile_like')->count_all_results();
        $data['profile_likes'] = $this->db->select('COUNT(*) as total')
        ->from('profile_like pl')
        ->join('members m', 'm.id = pl.user_id')
        ->where('pl.like_profile_id', $user_id)
        ->where('m.active', 'Yes')
        ->where('m.profile_hide !=','yes')
        ->get()
        ->row()
        ->total;
       $data['recieved_interest'] = $this->db->where(array('profile_id'=>$user_id ,'status'=> '0'))->from('sent_interests')->count_all_results();
       $data['contact_viewed'] = $this->db->where(array('member_id'=>$user_id ))->from('profile_viewed')->count_all_results();
       if(!$data)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_NOT_FOUND );
        }else{
            $this->response( [
                'success'=>true,
                'stats'=>$data,
                'message' => 'data found'
            ], REST_Controller::HTTP_OK );
        }
   }
   
    function interest_notification($profile_id, $email, $user)
    {
        $message = "New Interest Received From " . $user;
        $title = "New Interest Received";
        $this->email->from('info@gallpakki.com', 'Himrishtey Marriage Bureau'); 
        $this->email->to($email);
        $this->email->subject($title); 
        $this->email->message('A new interest has been received from profile ID "' . $profile_id . '", Please check your account : HIMRMB');
        return $this->email->send();    
    }
    
    function view_profile_notification($profile_id,$email)
    {
        $title = "Profile view";
        $this->email->from('info@gallpakki.com', 'Himrishtey Marriage Bureau'); 
        $this->email->to($email);
        $this->email->subject($title); 
        $this->email->message( $profile_id . ' Viewed Your Profile please Check, Please check your account : HIMRMB');
        return $this->email->send();
    }
    
    function profile_shortlist_notification($profile_id,$email)
    {
        $title = "Profile Shortlisted";
        $this->email->from('info@gallpakki.com', 'Himrishtey Marriage Bureau'); 
        $this->email->to($email);
        $this->email->subject($title); 
        $this->email->message( $profile_id . ' Shortlist Your Profile, Please check your account : HIMRMB');
        return $this->email->send();
    }
    
    public function save_plan_id_post()
    {
        $user_id = $this->input->post('user_id');
        $plan_id = $this->input->post('plan_id');
        $plan = $this->pro->save_plan_id($user_id,$plan_id);
        if(!$plan)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_OK );
        }else{
            $this->response( [
                'success'=>true,
                'user'=>$plan,
                'message' => 'Plan activated'
            ], REST_Controller::HTTP_OK );
        }
    }
    
    public function delete_interest_post()
    {
        $data['user_id'] = $this->input->post('user_id');
        $data['profile_id'] = $this->input->post('profile_id');
        $data = $this->pro->delete_interest($data);
        if(!$data)
        {
            $this->response( [
                'success'=>false,
                'message'=>'Something went wrong',
            ], REST_Controller::HTTP_OK );
        }else{
            $this->response( [
                'success'=>true,
                'user'=>$data,
                'message' => 'Interest deleted'
            ], REST_Controller::HTTP_OK );
        }
    }
    
     public function view_my_profile_get($user_id){
    	$data['user_id'] = $user_id;
    	$data['profile_id'] = $user_id;
        $query = $this->usr->view_my_profile($data);
        $interests_received = $this->db->get_where('sent_interests',array('member_id' => $data['profile_id'],'profile_id' => $data['user_id']))->result();
        $interests_sent = $this->db->get_where('sent_interests',array('profile_id' => $data['profile_id'],'member_id' => $data['user_id']))->result();
        // $ussr = $this->db->get_where('members',array('id'=>$data['profile_id']))->row();
        // $userr = $this->db->get_where('members',array('id'=>$data['user_id']))->row();
        // $to = $ussr->google_token;
        // $profile = $userr->profile_id;
        $gallery = $this->db->get_where('member_photos',array('member_id' => $data['profile_id'],'photo_approved' => 'Yes'))->result();
        $user = $this->db->get_where('members',array('id'=>$data['user_id']))->row();
        $profile = $this->db->get_where('members',array('id'=>$data['profile_id'], 'active=>"Yes"'))->row();
        
            $images=array();
            if(!empty($gallery)){
                foreach($gallery as $key=> $gal){
                   $images[] = 'https://gallpakki.com/photos/photo_gallery/'.$gal->photo;
                   // $images[$key]['images'] = 'https://gallpakki.com/photos/photo_gallery/'.$gal->photo;
                   // $images[$key]['privacy'] = $gal->photo_privacy;
                }    
            }
                if(!$query){
                    $this->response( [
                        'success'=>false,
                        'message'=>'User Not Found'
                    ], REST_Controller::HTTP_NOT_FOUND );
                }else{
                    $this->view_profile_notification($user->profile_id,$profile->email);
                    $this->response( [
                        'success'=>true,
                        'data'=>['user'=>$query,JSON_NUMERIC_CHECK],
                        'images' => $images,
                        'interests_received' => $interests_received,
                        'interests_sent' => $interests_sent,
                    ], REST_Controller::HTTP_OK );
                }
        }
        
        
       public function notification($token,$profile_id)
        {
            $this->load->library('firebase_notification');
    
            $response = $this->firebase_notification->sendNotification(
                $token,
                'New Interest Received',
                'New Interest Received from '.$profile_id.'! Himrishtey',
                [
                    'screen' => 'home',
                    'type'   => 'test'
                ]
            );
            return true;
        }
        
        public function push_note($data){
            $this->load->library('firebase_notification');
    
            $response = $this->firebase_notification->sendNotification(
                $data['token'],
                $data['title'],
                $data['message'],
                [
                    'screen' => 'home',
                    'type'   => 'test',
                    'image'  => $data['image']
                ]
            );
            return true;
        }
}
?>