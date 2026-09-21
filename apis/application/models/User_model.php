<?php
class User_model extends CI_Model{
    public function __construct()
    {
        parent::__construct();
// Your own constructor code
    }
// code for registration api
    public function get_user($data)
    {
        $email = $data['email'];
        $phone = $data['phone'];
        $this->db->select('*');
        $this->db->from('members');
        $this->db->where('email',$email);
        $query=$this->db->get();
        $checkuser=$query->num_rows();
        return $checkuser;
    }
    public function get_user_phone($data)
    {
        $email = $data['email'];
        $phone = $data['phone'];
        $this->db->select('*');
        $this->db->from('members');
        $this->db->where('mobile_number',$phone);
        $query=$this->db->get();
        $checkuser=$query->num_rows();
        return $checkuser;
    }
    public function step_one_registration($data){
        date_default_timezone_set('Asia/Kolkata');
        $userdata = array(
            'full_name'             => $data['fullname'],
            'password'              => $data['password'],
                    // 'username'              => $data['username'],
            'email'                 => $data['email'],
            'mobile_number'         => $data['phone'],
            'profile_created_for'   => $data['profile_created_for'],
            'profile_completed'     => $data['profile_completed'],
            'google_token'          => $data['google_token'],
            'gender'                => $data['gender'],
            'registration_date'     => date('Y-m-d H:i:s'),
            
            'register_through'      => 'Android Application',
        );
        if($user = $this->db->insert('members',$userdata)){
            $insert_id = $this->db->insert_id();
            $pid = 10000 + intval($insert_id);
            $upuser = array(
                'profile_id' => 'PB'.$pid,
            );
            $this->db->where('id',$insert_id);
            $this->db->update('members', $upuser);
            $auser = $this->db->get_where('user_activity', array('user_id =' => '$insert_id'))->row();
            if(!empty($auser)){
                $uuser = array(
                    'last_active_date' => date("d-m-Y"),
                    'status' =>'online',
                );
                $this->db->where('user_id',$insert_id);
                $this->db->update('user_activity', $uuser);
            }else{
                $activitydata = array(
                    'user_id' => $insert_id,
                    'last_active_date' => date("m-d-Y"),
                    'status' => 'online',
                );
                $this->db->insert('user_activity',$activitydata);
            }
        }
        return $insert_id;
        return $user;       
    }
 public function step_one_ios_registration($data){
        date_default_timezone_set('Asia/Kolkata');
        $userdata = array(
            'full_name'             => $data['fullname'],
            'password'              => $data['password'],
                    // 'username'              => $data['username'],
            'email'                 => $data['email'],
            'mobile_number'         => $data['phone'],
            'profile_created_for'   => $data['profile_created_for'],
            'profile_completed'     => $data['profile_completed'],
            'google_token'          => $data['google_token'],
            'gender'                => $data['gender'],
            'registration_date'     => date('Y-m-d H:i:s'),
            
            'register_through'      => 'ios Application',
        );
        if($user = $this->db->insert('members',$userdata)){
            $insert_id = $this->db->insert_id();
            $pid = 10000 + intval($insert_id);
            $upuser = array(
                'profile_id' => 'PB'.$pid,
            );
            $this->db->where('id',$insert_id);
            $this->db->update('members', $upuser);
            $auser = $this->db->get_where('user_activity', array('user_id =' => '$insert_id'))->row();
            if(!empty($auser)){
                $uuser = array(
                    'last_active_date' => date("d-m-Y"),
                    'status' =>'online',
                );
                $this->db->where('user_id',$insert_id);
                $this->db->update('user_activity', $uuser);
            }else{
                $activitydata = array(
                    'user_id' => $insert_id,
                    'last_active_date' => date("m-d-Y"),
                    'status' => 'online',
                );
                $this->db->insert('user_activity',$activitydata);
            }
        }
        return $insert_id;
        return $user;       
    }
    public function registration_step_two($data){
        $userdata = array(
            'birth_date_time'       => $data['date_of_birth'].' '.$data['time_of_birth'],
            'birth_place'           => $data['birth_place'],
            'height'                => $data['height'],
            'country_living_in'     => $data['country'],
            'state_living_in'       => $data['state'],
            'city_living_in'        => $data['city'],
            'profile_completed'     => $data['profile_completed'],
        );
        $this->db->where('id',$data['user_id']);
        return $this->db->update('members', $userdata);
    }
    public function registration_step_three($data){
        $userdata = array(
            'education'             => $data['education'],
            'employed_in'           => $data['employed_in'],
            'occupation'            => $data['occupation'],
            'annual_income'         => $data['annual_income'],
            'profile_completed'     => $data['profile_completed'],
        );
        $this->db->where('id',$data['user_id']);
        return $this->db->update('members', $userdata);
    }
    public function registration_step_four($data){
        $userdata = array(
            'cast'                  => $data['cast'],
            'mother_tongue'         => $data['mother_tongue'],
            'marital_status'        => $data['marital_status'],
            'no_of_child'           => $data['no_of_child'],
            'horoscope_needed'      => $data['horoscope_needed'],
            'manglik'               => $data['manglik'],
            
            'religion'              => $data['religion'],
            'profile_completed'     => $data['profile_completed'],
        );
        $this->db->where('id',$data['user_id']);
        return $this->db->update('members', $userdata);
    }
    public function add_profile_photo($image_name,$data){
        $userdata = array(
            'photo'                  => $image_name,
            'profile_completed'     => $data['profile_completed'],
            'photo_approved'        => ' ',
        );
        $this->db->where('id',$data['user_id']);
        return $this->db->update('members', $userdata);
    }
    public function update_profile_photo($image_name,$data){
        $userdata = array(
            'photo'                  => $image_name,
            'photo_approved'        => ' ',
        );
        $this->db->where('id',$data['user_id']);
        return $this->db->update('members', $userdata);
    }
    public function login($username,$pass,$device_token){
       $this->db->where("password='$pass' AND profile_id='$username'");
       $this->db->or_where("password='$pass' AND email='$username'");
       $this->db->or_where("password='$pass' AND mobile_number='$username'");
       if($res = $this->db->get('members')->row()){
          $array = array('google_token' => $device_token);
          $this->db->where('id',$res->id);
          $this->db->update('members',$array);
           $insert_id = $res->id;
           
                // $auser = $this->db->get_where('user_activity', array('user_id =' => '$insert_id'))->row();
           $this->db->select('*');
           $this->db->from('user_activity');
           $this->db->where('user_id',$insert_id);
           $query=$this->db->get();
           $auser=$query->num_rows();
           if($auser > 0){
             $uuser = array(
                'last_active_date' => date("m-d-Y"),
                'last_active_time' => date('H:i'),
                'status' =>'online',
            );
             $this->db->where('user_id',$insert_id);
             $this->db->update('user_activity', $uuser);
         }else{
            $activitydata = array(
                'user_id' => $insert_id,
                'last_active_date' => date("m-d-Y"),
                'last_active_time' => date('H:i'),
                'status' => 'online',
            );
            $this->db->insert('user_activity',$activitydata);
        }
        $datee = $res->birth_date_time;
                    if(strpos($datee,' AM')){
                        $res->birth_date_time = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date = new DateTime($datee);
                        $res->birth_date_time = $date->format('Y-m-d H:i:s');
                    }else{
                        $res->birth_date_time = $datee;
                    }
    }
    return $res;
}
public function profile($user_id)
{
    $usr = array();
    $usr = $this->db->get_where('members', array('id =' => $user_id))->row();
    $date=date_create($usr->birth_date_time);
    $usr->birth_date_time = date_format($date,"Y-m-d H:i:s");
   /* $datee = $usr->birth_date_time;
                    if(strpos($datee,' AM')){
                        $usr->birth_date_time = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date = new DateTime($datee);
                        $usr->birth_date_time = $date->format('Y-m-d H:i:s');
                    }else{
                        $usr->birth_date_time = $datee;
                    }*/
    if($usr){
        if($usr->plan_id == ""){
            $usr->user_type = "No";
        }else{
            $usr->user_type = "Yes";
        } 
        if($usr->plan_id != ''){
                // $pmem = $this->db->get_where('payments', array('plan_id =' => $usr->plan_id,'member_id = '=>$user_id))->row();
                // $pid = $pmem->plan_id;
            $mem = $this->db->get_where('membership_plans', array('id =' => $usr->plan_id))->row();
            $mType = $mem->membership_type;
            $usr->plan_name = $mem->plan_name;
            if($mType == 1){
                $usr->membership_type = 'Normal Membership';
                $usr->membership_type_image = 'https://gallpakki.com/img/normal-membership.png';
            }elseif($mType == 2){
                $usr->membership_type = 'Premium Membership';
                $usr->membership_type_image = 'https://gallpakki.com/img/premium-membership.png';
            }
        }else{
            $usr->membership_type = 'No Membership';
            $usr->membership_type_image = '0';
        }
        // if($usr->plan_id != ''){
        
        if($usr->active == 'Yes'){
            $usr->plan_activated = "yes";
        }else{
            $usr->plan_activated = "No";
        }
    
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
            $usr->wallet_amount = $wallet_amount->wallet_balance;
        }else{
            $usr->wallet_amount = '0';
        }
        if($usr->photo == '')
        {
            if($usr->gender == 'Male')
            {
                $usr->photo = "https://gallpakki.com/img/boy.jpg";
            }else{
                $usr->photo = "https://gallpakki.com/img/girl.jpg";
            }
        }else{
            $usr->photo = "https://gallpakki.com/photos/photo/".$usr->photo;
        }
        if($usr->member_type == 'Verified'){
            $usr->member_type = "https://gallpakki.com/img/verified.png"; 
            $usr->mem_type = "Verified";
        }else{
            $usr->member_type = '0';
            $usr->mem_type = "Normal";
        } 
        if($usr->is_trusted == 'Trusted'){
            $usr->is_trusted = "https://gallpakki.com/img/trusted.png";
            $usr->trusted = "Trusted";
        }else{
            $usr->is_trusted = '0';
            $usr->trusted = "Normal";
        }
        $def = $this->db->get_where('users',array('user_type' => 1))->row();
        if($usr->relationship_manager != ""){
            $rm = $this->db->get_where('users',array('display_name' => $usr->relationship_manager))->row();
            if(!empty($rm)){
                $usr->relationship_manager_number = $rm->phone;
            }else{
                 $usr->relationship_manager_number = $def->phone;
            }
        }else{
            if($usr->assigned_to != ''){
                $as = $this->db->get_where('users',array('display_name' => $usr->assigned_to))->row();
                $usr->relationship_manager_number = $as->phone;
            }else{
                $usr->relationship_manager_number = $def->phone;
            }
           
        }
        return $usr;
    }else{
        return $usr;
    }
}
public function view_profile($data)
{
    $usr = array();
    $usr = $this->db->get_where('members', array('id =' => $data['profile_id'],'profile_hide !=' => 'yes','active'=>'Yes' ))->row();
    if(!empty($usr)){
        $profile=$this->db->get_where('sent_interests', array('member_id =' => $data['user_id'],'profile_id' => $data['profile_id']))->row();
        $short =  $this->db->where(['member_id'=> $data['user_id'],'profile_id' => $data['profile_id']])->from("short_listed")->count_all_results();
        $viewed_profile = $this->db->where(['member_id'=> $data['user_id']])->from("profile_viewed")->count_all_results();
        $viewed_contacts = $this->db->where(['member_id'=> $data['user_id']])->from("viewed_contacts")->count_all_results();
        $pc_user = $this->db->get_where('members', array('id =' => $data['user_id']))->row();
        $viewed_contact = $this->db->get_where('viewed_contacts', array('member_id =' => $data['user_id'],'profile_id' => $data['profile_id']))->row();
        if(!empty($viewed_contact)){
            $usr->profile_viewed = 'Yes';
        }else{
            $usr->profile_viewed = 'No';
        }
        
        $datee = $usr->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date = new DateTime($datee);
                        $usr->birth_date_time = $date->format('Y-m-d H:i:s');
                    }elseif(strpos($datee,' PM')){
                        $date = new DateTime($datee);
                        $usr->birth_date_time = $date->format('Y-m-d H:i:s');
                    }else{
                        $usr->birth_date_time = $datee;
                    }
        
        $like_profile = $this->db->get_where('profile_like', array('user_id =' => $data['user_id'],'like_profile_id' => $data['profile_id'],'status'=>'1'))->row();
        if(!empty($like_profile)){
            $usr->like = 'Yes';
        }else{
            $usr->like = 'No';
        }
        
        $created_for = $usr->profile_created_for;
        if($created_for == "Self"){
            $usr->profile_created_for = 'Self';
        }elseif($created_for == 'Relative'){
            $usr->profile_created_for = 'Relative';
        }elseif($created_for == 'Son' || $created_for == 'Daughter'){
            $usr->profile_created_for = 'Parents';
        }elseif($created_for == 'Brother' || $created_for == 'Sister'){
            $usr->profile_created_for = 'Sibilings';
        }elseif($created_for == 'Client (Marriage bureau)'){
            $usr->profile_created_for = 'Marriage Bureau';
        }else{
            $usr->profile_created_for = 'Friend';
        }
        if($usr->no_of_child == null){
            $usr->no_of_child = "";
        }
        $vvcnt = $pc_user->profile_view_count;
        $vvcnt = $vvcnt ? $vvcnt : 0;
        $usrtt= strval($vvcnt + 2 + $viewed_contacts);
        $full_name = $usr->full_name;
        $vcontact = $vvcnt + 2 + $viewed_contacts;
        // return $vvcnt;
        $fname = explode(' ',$full_name);
        $count = count($fname);
        if($count > 1){
            if($lname = $fname[1]){
                $first = mb_substr($lname, 0, 1);
                $usr->full_name = $fname[0]. ' '.$first;
            }
        }else{
            $usr->full_name = $full_name;
        }
        $pr = $data['profile_id'];
        $this->db->select('*');
        $this->db->from('member_profile_range');
        $this->db->where('member_id',$pr);
        $qr =  $this->db->where("$vcontact BETWEEN range_from AND range_to");
        $result = $this->db->get()->row();
        $price = (array)$result;
        if(!empty($price)){
            $usr->profile_view_price = $price['price'];
        }else{
            $usr->profile_view_price = '0';
        }
        if(!empty($profile)){
            $usr->interest = '1';
        }else{
            $usr->interest = '0';
        }
        if(!empty($short)){
            $usr->shortlisted = "Yes";
        }else{
            $usr->shortlisted = "No";
        }
        $photo = $usr->photo;
        $photo_approved = $usr->photo_approved;
        if($photo != "" && $photo_approved == "Yes"){
            $usr->photo = $usr->photo;
        }elseif($usr->gender == "Male"){
            $usr->photo  = "https://gallpakki.com/img/boy.jpg";
        }elseif($usr->gender == "Female"){
            $usr->photo  = "https://gallpakki.com/img/girl.jpg";
        }
        if($usr->member_type == 'Verified'){
            $usr->member_type = "https://gallpakki.com/img/promoted.png";
            
        }else{
            $usr->member_type = 'normal';
        }
        
        if($usr->is_trusted == 'Trusted'){
            $usr->is_trusted = "https://gallpakki.com/img/trusted.png";
        }
        $id = $data['user_id'];
        $profile_count = $this->db->select('profile_view_count')->from('members')->where('id',$id)->get()->row();
        $cnt2 = $profile_count->profile_view_count ? $profile_count->profile_view_count : 0;
        $cnt = $viewed_contacts + $cnt2;
        $usr->viewed_profile = (string) $cnt;
        return $usr;   
    }else{
        return $usr;
    }       
}
public function add_profile_view($data){
    $view = array(
        'member_id' => $data['user_id'],
        'viewed_profile_id' => $data['profile_id'],
    );
    $usr = $this->db->get_where('profile_viewed', array('member_id =' => $data['user_id'],'viewed_profile_id ='=> $data['profile_id']))->row();
            // $usrcount = count($usr);
    if($usr == TRUE ){
    }else{
     return $this->db->insert('profile_viewed',$view);
 }
}
public function get_profile_created_for()
{
    $query = $this->db->get('profile_created_for')->result();
    return $query;
}
public function get_heights()
{
    $heights = $this->db->get('heights')->result();
    return $heights;
}
public function get_countries()
{
    $countries = $this->db->get('countries')->result();
    return $countries;
}
public function get_states($country_id)
{
    $states = $this->db->get_where('states', array('country_id =' => $country_id))->result();
    return $states;
}
public function get_cities($state_id)
{
    $states = $this->db->order_by('name', 'ASC')->get_where('cities', array('state_id =' => $state_id))->result();
    return $states;
}
public function add_city($data){
    $city = array(
        'name'=> $data['name'],
        'state_id' => $data['state_id'],
    );
    $cdata = $this->db->insert('cities',$city);
    return $cdata;
}
public function get_educations()
{
    $educations = $this->db->get('educations')->result();
    return $educations;
}
public function get_employer()
{
    $employed_in = $this->db->get('employers')->result();
    return $employed_in;
}
public function get_occupations()
{
    $occupations = $this->db->get_where('occupations',array('status'=> 1))->result();
    return $occupations;
}
public function add_occupation($name)
{
    $data['occupation'] = $name;
    
    $occupation = $this->db->insert('occupations',$data);
    return $occupation;
}
public function get_annual_incomes()
{
    $annual_incomes = $this->db->query('SELECT * FROM annual_incomes');
    
    //$annual_incomes = $this->db->get('annual_incomes')->result();
    return  $annual_incomes->result_array();
}
public function get_marital_status()
{
    $marital_status = $this->db->get('marital_status')->result();
    return $marital_status;
}
public function get_religions()
{
    $religions = $this->db->get('religions')->result();
    return $religions;
}
public function get_casts()
{
    $casts = $this->db->get('casts')->result();
    return $casts;
}
public function get_mother_tongues()
{
    $mother_tongues = $this->db->get('mother_tongues')->result();
    return $mother_tongues;
}
public function get_family_status()
{
    $mother_tongues = $this->db->get('family_status')->result();
    return $mother_tongues;
}
public function reset_password($data){
    $array = array(
        'password' => $data['password']
    );
    $this->db->where('id',$data['user_id']);
    return $this->db->update('members',$array);
}
public function logout($uid)
{
    $arr = array(
        'status' => 'offline',
    );
    $this->db->where('user_id',$uid);
    return $this->db->update('user_activity',$arr);
}
public function view_my_profile($data)
{
    $usr = array();
    $usr = $this->db->get_where('members', array('id =' => $data['user_id']))->row();
// echo '<pre>'; print_r($usr); die;
    if(!empty($usr)){
        $profile=$this->db->get_where('sent_interests', array('member_id =' => $data['user_id'],'profile_id' => $data['profile_id']))->row();
        $short =  $this->db->where(['member_id'=> $data['user_id'],'profile_id' => $data['profile_id']])->from("short_listed")->count_all_results();
        $viewed_profile = $this->db->where(['member_id'=> $data['user_id']])->from("profile_viewed")->count_all_results();
        $viewed_contacts = $this->db->where(['member_id'=> $data['user_id']])->from("viewed_contacts")->count_all_results();
        $pc_user = $this->db->get_where('members', array('id =' => $data['user_id']))->row();
        $viewed_contact = $this->db->get_where('viewed_contacts', array('member_id =' => $data['user_id'],'profile_id' => $data['profile_id']))->row();
        if(!empty($viewed_contact)){
            $usr->profile_viewed = 'Yes';
        }else{
            $usr->profile_viewed = 'No';
        }
        
        $datee = $usr->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date = new DateTime($datee);
                        $usr->birth_date_time = $date->format('Y-m-d H:i:s');
                    }elseif(strpos($datee,' PM')){
                        $date = new DateTime($datee);
                        $usr->birth_date_time = $date->format('Y-m-d H:i:s');
                    }else{
                        $usr->birth_date_time = $datee;
                    }
        
        $like_profile = $this->db->get_where('profile_like', array('user_id =' => $data['user_id'],'like_profile_id' => $data['profile_id'],'status'=>'1'))->row();
        if(!empty($like_profile)){
            $usr->like = 'Yes';
        }else{
            $usr->like = 'No';
        }
        
        $created_for = $usr->profile_created_for;
        if($created_for == "Self"){
            $usr->profile_created_for = 'Self';
        }elseif($created_for == 'Relative'){
            $usr->profile_created_for = 'Relative';
        }elseif($created_for == 'Son' || $created_for == 'Daughter'){
            $usr->profile_created_for = 'Parents';
        }elseif($created_for == 'Brother' || $created_for == 'Sister'){
            $usr->profile_created_for = 'Sibilings';
        }elseif($created_for == 'Client (Marriage bureau)'){
            $usr->profile_created_for = 'Marriage Bureau';
        }else{
            $usr->profile_created_for = 'Friend';
        }
        $vvcnt = $pc_user->profile_view_count;
        $vvcnt = $vvcnt ? $vvcnt : 0;
        $usrtt= strval($vvcnt + 2 + $viewed_contacts);
        $full_name = $usr->full_name;
        $vcontact = $vvcnt + 2 + $viewed_contacts;
        // return $vvcnt;
        $fname = explode(' ',$full_name);
        $count = count($fname);
        if($count > 1){
            if($lname = $fname[1]){
                $first = mb_substr($lname, 0, 1);
                $usr->full_name = $fname[0]. ' '.$first;
            }
        }else{
            $usr->full_name = $full_name;
        }
        $pr = $data['profile_id'];
        $this->db->select('*');
        $this->db->from('member_profile_range');
        $this->db->where('member_id',$pr);
        $qr =  $this->db->where("$vcontact BETWEEN range_from AND range_to");
        $result = $this->db->get()->row();
        $price = (array)$result;
        if(!empty($price)){
            $usr->profile_view_price = $price['price'];
        }else{
            $usr->profile_view_price = '0';
        }
        if(!empty($profile)){
            $usr->interest = '1';
        }else{
            $usr->interest = '0';
        }
        if(!empty($short)){
            $usr->shortlisted = "Yes";
        }else{
            $usr->shortlisted = "No";
        }
        $photo = $usr->photo;
        $photo_approved = $usr->photo_approved;
        if($photo != "" && $photo_approved == "Yes"){
            $usr->photo = $usr->photo;
        }elseif($usr->gender == "Male"){
            $usr->photo  = "https://gallpakki.com/img/boy.jpg";
        }elseif($usr->gender == "Female"){
            $usr->photo  = "https://gallpakki.com/img/girl.jpg";
        }
        if($usr->member_type == 'Verified'){
            $usr->member_type = "https://gallpakki.com/img/promoted.png";
            
        }else{
            $usr->member_type = 'normal';
        }
        
        if($usr->is_trusted == 'Trusted'){
            $usr->is_trusted = "https://gallpakki.com/img/trusted.png";
        }
        $id = $data['user_id'];
        $profile_count = $this->db->select('profile_view_count')->from('members')->where('id',$id)->get()->row();
        $cnt2 = $profile_count->profile_view_count ? $profile_count->profile_view_count : 0;
        $cnt = $viewed_contacts + $cnt2;
        $usr->viewed_profile = (string) $cnt;
        return $usr;   
    }else{
        return $usr;
    }       
}
}
?>