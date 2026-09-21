<?php
Class Profile_model extends CI_model{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}

	public function update_step_one($pdata)
	{
		$userdata = array(

                // 'gender'                => $pdata['gender'],

                'height'                => $pdata['height'],

                'profile_created_for'	=> $pdata['profile_created_for'],

                'any_disability'		=> $pdata['any_disability'],

                'marital_status'		=> $pdata['marital_status'],

                'blood_group'			=> $pdata['blood_group'],

                'health_info'			=> $pdata['health_info'],

                'birth_date_time'		=> $pdata['birth_date_time'],

                'birth_place'			=> $pdata['birth_place'],

                'horoscope_needed'		=> $pdata['horoscope_needed'],

                'manglik'				=> $pdata['manglik'],

                'mobile_number'			=> $pdata['mobile_number'],	

                'alternate_number'		=> $pdata['alternate_number'],

                'email'					=> $pdata['email'],

                'whatsapp_number'		=> $pdata['whatsapp_number'],

                'religion'				=> $pdata['religion'],

                'mother_tongue'			=> $pdata['mother_tongue'],

                'gotra'					=> $pdata['gotra'],

                'birth_date_time'		=> $pdata['birth_date_time'],

                'about_my_education'		=> $pdata['about_my_education'],

                'education'				=> $pdata['education'],

                'any_other_qualifications'		=> $pdata['any_other_qualifications'],

                'about_my_career'		=> $pdata['about_my_career'],

                'employed_in'			=> $pdata['employed_in'],

                'occupation'		=> $pdata['occupation'],

                'organization_name'		=> $pdata['organization_name'],

                'job_location'		=> $pdata['job_location'],

                'annual_income'		=> $pdata['annual_income'],

                'about_family'		=> $pdata['about_family'],

                'father_name'		=> $pdata['father_name'],

                'father_occupation'		=> $pdata['father_occupation'],

                'mother_name'		=> $pdata['mother_name'],

                'mother_occupation'		=> $pdata['mother_occupation'],

                'no_of_brothers'		=> $pdata['no_of_brothers'],

                'no_of_sisters'		=> $pdata['no_of_sisters'],

                'married_brothers'		=> $pdata['married_brothers'],

                'married_sisters'		=> $pdata['married_sisters'],

                'family_income'		=> $pdata['family_income'],

                'family_status'		=> $pdata['family_status'],

                'diet'		=> $pdata['diet'],

                'is_drinking'		=> $pdata['is_drinking'],

                'is_smoking'		=> $pdata['is_smoking'],

                'about_me'		=> $pdata['about_me'],

                'no_of_child'		=> $pdata['no_of_child'],

                'native_place'      => $pdata['native_place'],

                'sub_cast'          => $pdata['sub_cast'],

                'cast'              => $pdata['cast']

            );

			$this->db->where('id',$pdata['user_id']);
            
           $this->db->update('members', $userdata);
           $completed = $this->getProfileCompleted($pdata['user_id']);
           $complete = array('profile_completed' => $completed);
           $this->db->where('id',$pdata['user_id']);
           return $this->db->update('members', $complete);



	}



    public function get_recent_profiles($data)
    {

        $limit = 10; 
        $offset = ($data['page'] - 1) * $limit;
        $recent = array();
        $today =date('Y-m-d');
        $gender = $data['member_gender'];
        $user_id = $data['user_id'];
        $user = $this->db->get_where('members',array('id'=>$user_id))->row();
        $userr = (array) $user;
        $gender = $user->gender;
        $user = array();
        $user2 = array();
        $sql1 = "SELECT members.*, DATE_FORMAT(members.birth_date_time, '%Y-%m-%d %I:%i %s %p') as birthdatetime 
         FROM members 
         WHERE gender != '$gender' 
           AND id != $user_id 
           AND profile_hide != 'yes' 
           AND active = 'Yes' 
         ORDER BY activation_number DESC 
         LIMIT $limit OFFSET $offset";
        $query1 = $this->db->query($sql1);
        $recents = $query1->result();
        // echo '<pre>'; print_r($recents); die;
        foreach($recents as $key => $recent){
            $datee = $recent->birth_date_time;
            if(strpos($datee,' AM')){
                $date11 = str_replace(" AM","",$datee);
            }elseif(strpos($datee,' PM')){
                $date11 = str_replace(" PM","",$datee);
            }else{
                $date11 = $datee;
            }
            $date1 = new DateTime($date11);
            $date2 = $date1->diff(new DateTime($today));
            $user[$key]['age_years'] = $date2->y;
            $user[$key]['age_months'] = $date2->m;
            $photo = $recent->photo;
            $photo_approved = $recent->photo_approved;
            if($photo != "" && $photo_approved == "Yes"){
                $user[$key]['photo'] = $recent->photo;
            }elseif($recent->gender == "Male"){
                $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";
            }elseif($recent->gender == "Female"){
                $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";
            }
            if($recent->member_type == 'Verified'){
               $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 
                $user[$key]['mem_type'] = "Yes";
            }else{
                $user[$key]['member_type'] = 'normal';
            }
            if($recent->is_trusted == 'Trusted'){
                $user[$key]['is_trusted'] = "https://gallpakki.com/img/trusted.png";
            }else{
                $user[$key]['is_trusted'] = 'No';
            }

                $user[$key] = array_merge((array) $recent, $user[$key]);
        }
        return $user;
    }



    public function verified_profiles($data)
    {
        $limit = 10; 
        $offset = ($data['page'] - 1) * $limit;
        $verify = array();
        $today =date('Y-m-d');
        $user_id = $data['user_id'];
        $user = $this->db->get_where('members',array('id'=>$user_id))->row();
        $userr = (array) $user;
        $gender = $user->gender;
        $sql1 = "SELECT members.*, DATE_FORMAT(members.birth_date_time, '%Y-%m-%d %I:%i %s %p') as birthdatetime 
         FROM members 
         WHERE gender != '$gender' 
           AND id != $user_id 
           AND profile_hide != 'yes' 
           AND member_type = 'Verified' 
           AND active = 'Yes' 
         ORDER BY activation_number DESC 
         LIMIT $limit OFFSET $offset";
        $query1 = $this->db->query($sql1);
        $verified = $query1->result();
        $user = array();
        foreach($verified as $key => $recent){
            $datee = $recent->birth_date_time;
            if(strpos($datee,' AM')){
                $date11 = str_replace(" AM","",$datee);
            }elseif(strpos($datee,' PM')){
                $date11 = str_replace(" PM","",$datee);
            }else{
                $date11 = $datee;
            }
            $date1 = new DateTime($date11);
            $date2 = $date1->diff(new DateTime($today));
            $user[$key]['age_years'] = $date2->y;
            $user[$key]['age_months'] = $date2->m;
            $photo = $recent->photo;
            $photo_approved = $recent->photo_approved;
            if($photo != "" && $photo_approved == "Yes"){
                $user[$key]['photo'] = $recent->photo;
            }elseif($recent->gender == "Male"){
                $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";
            }elseif($recent->gender == "Female"){
                $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";
            }
            if($recent->member_type == 'Verified'){
               $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 
                $user[$key]['mem_type'] = "Yes";
            }else{
                $user[$key]['member_type'] = 'normal';
            }
            if($recent->is_trusted == 'Trusted'){
                $user[$key]['is_trusted'] = "https://gallpakki.com/img/trusted.png";
            }else{
                $user[$key]['is_trusted'] = 'No';
            }

                $user[$key] = array_merge((array) $recent, $user[$key]);
        }
        return $user;

    }



    public function shortlisted_profiles($user_id,$page){
        $limit = 10; 
        $offset = ($page - 1) * $limit;
        $shorlist = array();
        $today =date('Y-m-d');
        $sql1 = "SELECT members.*, DATE_FORMAT(members.birth_date_time, '%Y-%m-%d %I:%i %s %p') as birthdatetime 
         FROM members 
         WHERE id IN (SELECT profile_id FROM short_listed WHERE member_id = $user_id) 
           AND active = 'Yes' AND profile_hide != 'yes'
         ORDER BY activation_number DESC 
         LIMIT $limit OFFSET $offset";
        $query1 = $this->db->query($sql1);
        $results = $query1->result();
        $user = array();


        foreach($results as $key => $recent){

            $datee = $recent->birth_date_time;
            if(strpos($datee,' AM')){
                $date11 = str_replace(" AM","",$datee);
            }elseif(strpos($datee,' PM')){
                $date11 = str_replace(" PM","",$datee);
            }else{
                $date11 = $datee;
            }
            $date1 = new DateTime($date11);
            $date2 = $date1->diff(new DateTime($today));
            $user[$key]['age_years'] = $date2->y;
            $user[$key]['age_months'] = $date2->m;
            $photo = $recent->photo;
            $photo_approved = $recent->photo_approved;
            if($photo != "" && $photo_approved == "Yes"){
                $user[$key]['photo'] = $recent->photo;
            }elseif($recent->gender == "Male"){
                $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";
            }elseif($recent->gender == "Female"){
                $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";
            }
            if($recent->member_type == 'Verified'){
               $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 
                $user[$key]['mem_type'] = "Yes";
            }else{
                $user[$key]['member_type'] = 'normal';
            }
            if($recent->is_trusted == 'Trusted'){
                $user[$key]['is_trusted'] = "https://gallpakki.com/img/trusted.png";
            }else{
                $user[$key]['is_trusted'] = 'No';
            }

                $user[$key] = array_merge((array) $recent, $user[$key]);

        }
         return $user;

    }



    public function quick_search_post($data)
    {
        $today =date('Y-m-d');
        $member_gender = $data['member_gender'];
        $member_id = $data['user_id'];
        $age_to = $data['age_to'];
        $age_from = $data['age_from'];
        if(!empty($data['religion'])){
            $p_religion = explode(",",$data['religion']);
        }else{
            $p_religion = array();
        }
        if(!empty($data['cast'])){
            $p_cast  = explode(",",$data['cast']);
        }else{
            $p_cast = array();
        } 
        $m_status = $data['marital_status'];
        $sql = "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where gender != '$member_gender' and id != $member_id and active='Yes' and profile_hide !='yes' and marital_status='$m_status' ORDER BY activation_number DESC ";
        $query = $this->db->query($sql);
        $result = $query->result();
        $user=array();
        foreach($result as $re => $res){
            $date1 = new DateTime($res->birth_date_time);
            $date2 = $date1->diff(new DateTime($today));
            $age_years = $date2->y;
            $age_months = $date2->m;
            $religion = $res->religion;
            $cast = $res->cast;
            if($age_years >= $age_from && $age_years <= $age_to )
                {
                    if(in_array($religion, $p_religion))
                        {
                            if(in_array($cast,$p_cast)){
                                $user[$re]['id'] = $res->id;
                                $user[$re]['profile_id'] = $res->profile_id;
                                $user[$re]['gender'] = $res->gender;
                                $user[$re]['birth_date_time'] = $res->birthdatetime;
                                $user[$re]['height'] = $res->height;
                                $user[$re]['religion'] = $res->religion;
                                $user[$re]['mother_tongue'] = $res->mother_tongue;
                                $user[$re]['cast'] = $res->cast;
                                $user[$re]['education'] = $res->education;
                                $user[$re]['occupation'] = $res->occupation;
                                $country_living_in = $res->country_living_in;
                                $state_living_in = $res->state_living_in;
                                $city_living_in = $res->city_living_in;
                                $user[$re]['address_living_in'] = $res->address_living_in;
                                $user[$re]['location'] = $city_living_in.", ".$state_living_in.",".$country_living_in;
                                $photo = $res->photo;
                                $photo_approved = $res->photo_approved;
                                if($photo != "" && $photo_approved = "Yes"){
                                    $user[$re]['photo'] = $res->photo;
                                }elseif($res->gender == "Male"){
                                    $user[$re]['photo'] = "https://gallpakki.com/img/boy.jpg";
                                }elseif($res->gender == "Female"){
                                    $user[$re]['photo'] = "https://gallpakki.com/img/girl.jpg";
                                }
                                $user[$re] = array_merge((array) $res, $user[$re]);
                            }
                        
                        }

                }

        }



        $result=array();

        foreach($user as $key => $value)

        {

            if(!empty($value["id"]))

            {

                $result[]=$value;

            }

        }

        return $result;

    }



    



    public function matching_profiles($user_id)
    {
        $today =date('Y-m-d');
        $user =  $this->db->get_where('members', array('id'=>$user_id))->row();
        $gender = $user->gender;
        $looking_for = $user->looking_for;
        $looking_for = explode(',', $looking_for);
        $partner_age_from = $user->partner_age_from;
        $partner_age_to = $user->partner_age_to;
        $partner_country = $user->partner_country;
        $partner_country = explode(',', $partner_country);
        $partner_religion = $user->partner_religion;
        $partner_religion = explode(',', $partner_religion);
        $partner_cast = $user->partner_cast;
        if($partner_cast == 'Any')
        {
            $pcresult = "SELECT distinct(cast) as cast FROM members where cast != ''";
            $query = $this->db->query($pcresult);
            $result = $query->result();   
             foreach($result as $k => $res){
                 $partnercast[$k] = $res->cast;
            }
            $partner_cast = $partnercast;
        }else{
            $partner_cast = explode(',', $partner_cast);
        }
        $partner_height_from = $user->partner_height_from;
        $partner_height_to = $user->partner_height_to;
        $partner_education = $user->partner_education;
        $partner_education = explode(',', $partner_education);
        $partner_mothertongue = $user->partner_mothertongue;
        $partner_mothertongue = explode(',', $partner_mothertongue);
        $partner_annual_income_from = $user->partner_annual_income_from;
        $partner_annual_income_to = $user->partner_annual_income_to;
        
        // $partner_looking_for = $user->looking_for;

      $user = array();

        $sql1 = "Select members.*, DATE_FORMAT(members.birth_date_time,'%Y-%m-%d %I:%i %s %p') as birthdatetime from members where gender != '$gender' and id != $user_id and profile_hide != 'yes' and active = 'Yes' order by RAND()";

        $query1 = $this->db->query($sql1);

        $result = $query1->result();

        foreach($result as $key=>$rest){

            $datee = $rest->birth_date_time;
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
            $height = $rest->height;

            $religion = $rest->religion;

            $country_living_in = $rest->country_living_in;

            $cast = $rest->cast;

            $education = $rest->education;

            $mother_tongue = $rest->mother_tongue;

            $profile_completed = $rest->profile_completed;
            
            $l_for = $rest->marital_status;

            $user[$key]['id'] = '';
                if($age_years >= $partner_age_from && $age_years <= $partner_age_to )
                {
                    if($height >= $partner_height_from && $height <= $partner_height_to){
                    if(in_array($religion, $partner_religion)  && in_array($cast, $partner_cast) && in_array($l_for, $looking_for))
                    {
                        unset($user[$key]);
                        $user[$key]['id'] = $rest->id;
                        $user[$key]['profile_id'] = $rest->profile_id;
                        $user[$key]['full_name'] = $rest->full_name;
                        $datee = $rest->birth_date_time;
                        if(strpos($datee,' AM')){
                            $date11 = str_replace(" AM","",$datee);
                        }elseif(strpos($datee,' PM')){
                            $date11 = str_replace(" PM","",$datee);
                        }else{
                            $date11 = $datee;
                        }
                        $date1 = new DateTime($date11);
                        $date2 = $date1->diff(new DateTime($today));
                        $user[$key]['age_years'] = $date2->y;
                        $user[$key]['age_months'] = $date2->m;
                        $photo = $rest->photo;
                        $photo_approved = $rest->photo_approved;
                        if($photo != "" && $photo_approved = "Yes"){
                            $user[$key]['photo'] = $rest->photo;
                        }elseif($rest->gender == "Male"){
                            $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";
                        }elseif($rest->gender == "Female"){
                            $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";
                        }
                        $user[$key]['membr_type'] = $rest->member_type;
                        if($rest->member_type == 'Verified'){
                            $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 
                            $user[$key]['mem_type'] = "Yes";
                        }else{
                            $user[$key]['member_type'] = 'normal';
                        }
                        if($rest->is_trusted == 'Trusted'){
                            $user[$key]['is_trusted'] = "https://gallpakki.com/img/trusted.png";
                        }else{
                            $user[$key]['is_trusted'] = 'No';
                        }
                        $user[$key] = array_merge((array) $rest, $user[$key]);
                        
                    }
                }
            }

            }

            $result=array();
            foreach($user as $key => $value)
            {
                if(!empty($value["id"]))
                {
                    $result[]=$value;
                }
            }
        return $result;
    }

//     public function matching_profiles($user_id,$gender, $page = 1)
//     {
//     $limit  = 10; 
//     $offset = ($page - 1) * $limit;
//     $today  = date('Y-m-d');

//     $user =  $this->db->get_where('members', array('id'=>$user_id))->row();
//     $gender = $user->gender;
//     $looking_for = $user->looking_for;
//     $looking_for = explode(',', $looking_for);
//     $partner_age_from = $user->partner_age_from;
//     $partner_age_to = $user->partner_age_to;
//     $partner_country = $user->partner_country;
//     $partner_country = explode(',', $partner_country);
//     $partner_religion = $user->partner_religion;
//     $partner_religion = explode(',', $partner_religion);
//     $partner_cast = $user->partner_cast;
//     if($partner_cast == 'Any')
//     {
//         $pcresult = "SELECT distinct(cast) as cast FROM members where cast != ''";
//         $query = $this->db->query($pcresult);
//         $result = $query->result();   
//         foreach($result as $k => $res){
//             $partnercast[$k] = $res->cast;
//         }
//         $partner_cast = $partnercast;
//     }else{
//         $partner_cast = explode(',', $partner_cast);
//     }
//     $partner_height_from = $user->partner_height_from;
//     $partner_height_to = $user->partner_height_to;
//     $partner_education = $user->partner_education;
//     $partner_education = explode(',', $partner_education);
//     $partner_mothertongue = $user->partner_mothertongue;
//     $partner_mothertongue = explode(',', $partner_mothertongue);
//     $partner_annual_income_from = $user->partner_annual_income_from;
//     $partner_annual_income_to = $user->partner_annual_income_to;

//     $user = array();
//     $sql1 = "SELECT members.*, DATE_FORMAT(members.birth_date_time,'%Y-%m-%d %I:%i %s %p') as birthdatetime 
//              FROM members 
//              WHERE gender != '$gender' 
//               AND id != $user_id 
//               AND profile_hide != 'yes' 
//               AND active = 'Yes' 
//              ORDER BY activation_number DESC 
//              LIMIT $limit OFFSET $offset";

//     $query1 = $this->db->query($sql1);
//     $result = $query1->result();

//     foreach($result as $key=>$rest){
//         // 🔹 your existing matching logic stays exactly as-is
//         $datee = $rest->birth_date_time;
//         if(strpos($datee,' AM')){
//             $date11 = str_replace(" AM","",$datee);
//         }elseif(strpos($datee,' PM')){
//             $date11 = str_replace(" PM","",$datee);
//         }else{
//             $date11 = $datee;
//         }
//         $date1 = new DateTime($date11);
//         $date2 = $date1->diff(new DateTime($today));
//         $age_years = $date2->y;
//         $age_months = $date2->m;
//         $height = $rest->height;
//         $religion = $rest->religion;
//         $country_living_in = $rest->country_living_in;
//         $cast = $rest->cast;
//         $education = $rest->education;
//         $mother_tongue = $rest->mother_tongue;
//         $profile_completed = $rest->profile_completed;
//         $l_for = $rest->marital_status;

//         $user[$key]['id'] = '';
//         if($age_years >= $partner_age_from && $age_years <= $partner_age_to )
//         {
//             if($height >= $partner_height_from && $height <= $partner_height_to){
//                 if(in_array($religion, $partner_religion)  && in_array($cast, $partner_cast) && in_array($l_for, $looking_for))
//                 {
//                     unset($user[$key]);
//                     $user[$key]['id'] = $rest->id;
//                     $user[$key]['profile_id'] = $rest->profile_id;
//                     $user[$key]['full_name'] = $rest->full_name;
//                     $datee = $rest->birth_date_time;
//                     if(strpos($datee,' AM')){
//                         $date11 = str_replace(" AM","",$datee);
//                     }elseif(strpos($datee,' PM')){
//                         $date11 = str_replace(" PM","",$datee);
//                     }else{
//                         $date11 = $datee;
//                     }
//                     $date1 = new DateTime($date11);
//                     $date2 = $date1->diff(new DateTime($today));
//                     $user[$key]['age_years'] = $date2->y;
//                     $user[$key]['age_months'] = $date2->m;
//                     $photo = $rest->photo;
//                     $photo_approved = $rest->photo_approved;
//                     if($photo != "" && $photo_approved = "Yes"){
//                         $user[$key]['photo'] = $rest->photo;
//                     }elseif($rest->gender == "Male"){
//                         $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";
//                     }elseif($rest->gender == "Female"){
//                         $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";
//                     }
//                     $user[$key]['membr_type'] = $rest->member_type;
//                     if($rest->member_type == 'Verified'){
//                         $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 
//                         $user[$key]['mem_type'] = "Yes";
//                     }else{
//                         $user[$key]['member_type'] = 'normal';
//                     }
//                     if($rest->is_trusted == 'Trusted'){
//                         $user[$key]['is_trusted'] = "https://gallpakki.com/img/trusted.png";
//                     }else{
//                         $user[$key]['is_trusted'] = 'No';
//                     }
//                     $user[$key] = array_merge((array) $rest, $user[$key]);
//                 }
//             }
//         }
//     }

//     $result=array();
//     foreach($user as $key => $value)
//     {
//         if(!empty($value["id"]))
//         {
//             $result[]=$value;
//         }
//     }
//     return $result;
// }


    public function send_interest($data){

        $interest = array(

            'member_id'=>$data['user_id'],

            'profile_id'=>$data['profile_id']

        );

        $member_id = $data['user_id'];

        $profile_id = $data['profile_id'];

        $this->db->select('*');

        $this->db->from('sent_interests');

        $this->db->where("member_id = '$member_id' AND profile_id = '$profile_id'");

        $query=$this->db->get();

        $auser=$query->num_rows();

        if($auser == 0)

        {

            return $this->db->insert('sent_interests',$interest);

        }else{

            return "interest already sent";

        }



    }



    public function sent_interest_list($user_id)

    {
 $today =date('Y-m-d');

    //   $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where id in (select profile_id from sent_interests where member_id = $user_id and status = 0) and profile_hide != 'yes' and active='Yes'";
        //  $sql1 =  "SELECT members.*, sent_interests.member_id, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime FROM members INNER JOIN sent_interests ON members.id = sent_interests.member_id WHERE sent_interests.member_id = ".$user_id." and members.profile_hide != 'yes' and members.active='Yes' ORDER BY sent_interests.id ORDER BY sent_interests.id DESC";
 $sql1 = "SELECT members.*, sent_interests.member_id, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime FROM members INNER JOIN sent_interests ON members.id = sent_interests.profile_id WHERE sent_interests.member_id = ".$user_id." and members.profile_hide != 'yes' and members.active='Yes' and sent_interests.status = 0 ORDER BY sent_interests.id ASC";
       
// echo $sql1; die;
        $query1 = $this->db->query($sql1);

        $results = $query1->result();

        foreach($results as $key => $result){
            $photo = $result->photo;

            $photo_approved = $result->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $results[$key]->photo = $result->photo;

            }elseif($result->gender == "Male"){

                $results[$key]->photo = "boy.jpg";

            }elseif($result->gender == "Female"){

                $results[$key]->photo = "girl.jpg";

            }
            $datee = $result->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date11 = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date11 = str_replace(" PM","",$datee);
                    }else{
                        $date11 = $datee;
                    }
                    $date1 = new DateTime($date11);

            $date2 = $date1->diff(new DateTime($today));

            $results[$key]->age_years = $date2->y;

            $results[$key]->age_months = $date2->m;

            if($result->member_type == 'Verified'){
                $results[$key]->member_type = "https://gallpakki.com/img/verified.png"; 
                $results[$key]->mem_type = "Yes";
            }else{
                $results[$key]->member_type = 'normal';
            }
            if($result->is_trusted == 'Trusted'){
                $results[$key]->is_trusted = "https://gallpakki.com/img/trusted.png";
            }
        }
        return $results;
    }



    public function rejected_sent_interest_list($user_id)

    {

    //   $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where id in (select profile_id from sent_interests where member_id = $user_id and status = 2) and profile_hide != 'yes' and active='Yes'";
        $sql1 = "SELECT members.*, sent_interests.member_id, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime FROM members INNER JOIN sent_interests ON members.id = sent_interests.profile_id WHERE sent_interests.member_id = ".$user_id." and members.profile_hide != 'yes' and members.active='Yes' and sent_interests.status = 2 ORDER BY sent_interests.id ASC";

        $query1 = $this->db->query($sql1);

        $results = $query1->result();

        foreach($results as $key => $result){
            $photo = $result->photo;

            $photo_approved = $result->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $results[$key]->photo = $result->photo;

            }elseif($result->gender == "Male"){

                $results[$key]->photo = "boy.jpg";

            }elseif($result->gender == "Female"){

                $results[$key]->photo = "girl.jpg";

            }
            $datee = $result->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date11 = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date11 = str_replace(" PM","",$datee);
                    }else{
                        $date11 = $datee;
                    }
                    $date1 = new DateTime($date11);

            $date2 = $date1->diff(new DateTime($today));

            $results[$key]->age_years = $date2->y;

            $results[$key]->age_months = $date2->m;
            // $date1 = new DateTime($result->birth_date_time);

            // $date2 = $date1->diff(new DateTime($today));

            // $results[$key]['age_years'] = $date2->y;

            // $results[$key]['age_months'] = $date2->m;

            // $results[$key]['birth_date_time'] = $recent->birthdatetime;

            if($result->member_type == 'Verified'){
                $results[$key]->member_type = "https://gallpakki.com/img/verified.png"; 
                $results[$key]->mem_type = "Yes";
            }else{
                $results[$key]->member_type = 'normal';
            }
            if($result->is_trusted == 'Trusted'){
                $results[$key]->is_trusted = "https://gallpakki.com/img/trusted.png";
            }
        }

        return $results;

    }



    public function accepted_sent_interest_list($user_id)

    {

    //   $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where id in (select profile_id from sent_interests where member_id = $user_id and status = 1) and active='yes' and profile_hide != 'yes' and active='Yes'";

        $sql1 = "SELECT members.*, sent_interests.member_id, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime FROM members INNER JOIN sent_interests ON members.id = sent_interests.profile_id WHERE sent_interests.member_id = ".$user_id." and members.profile_hide != 'yes' and members.active='Yes' and sent_interests.status = 1 ORDER BY sent_interests.id ASC";
        $query1 = $this->db->query($sql1);

       $results = $query1->result();

        foreach($results as $key => $result){
            $photo = $result->photo;

            $photo_approved = $result->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $results[$key]->photo = $result->photo;

            }elseif($result->gender == "Male"){

                $results[$key]->photo = "boy.jpg";

            }elseif($result->gender == "Female"){

                $results[$key]->photo = "girl.jpg";

            }
            $datee = $result->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date11 = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date11 = str_replace(" PM","",$datee);
                    }else{
                        $date11 = $datee;
                    }
                    $date1 = new DateTime($date11);

            $date2 = $date1->diff(new DateTime($today));

            $results[$key]->age_years = $date2->y;

            $results[$key]->age_months = $date2->m;
            // $date1 = new DateTime($result->birth_date_time);

            // $date2 = $date1->diff(new DateTime($today));

            // $results[$key]['age_years'] = $date2->y;

            // $results[$key]['age_months'] = $date2->m;

            // $results[$key]['birth_date_time'] = $recent->birthdatetime;

            if($result->member_type == 'Verified'){
                $results[$key]->member_type = "https://gallpakki.com/img/verified.png"; 
                $results[$key]->mem_type = "Yes";
            }else{
                $results[$key]->member_type = 'normal';
            }
            if($result->is_trusted == 'Trusted'){
                $results[$key]->is_trusted = "https://gallpakki.com/img/trusted.png";
            }
        }

        return $results;

    }



    public function received_interest($user_id)

    {

        $today =date('Y-m-d');
    //   $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where id in (select member_id from sent_interests where profile_id = $user_id and status= 0) and profile_hide != 'yes' and active='Yes'";
$sql1 = "SELECT members.*, sent_interests.member_id, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime FROM members INNER JOIN sent_interests ON members.id = sent_interests.member_id WHERE sent_interests.profile_id = ".$user_id." and members.profile_hide != 'yes' and members.active='Yes' and sent_interests.status = 0 ORDER BY sent_interests.id ASC";
        $query1 = $this->db->query($sql1);

        $results = $query1->result();

        foreach($results as $key => $result){
            $photo = $result->photo;

            $photo_approved = $result->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $results[$key]->photo = $result->photo;

            }elseif($result->gender == "Male"){

                $results[$key]->photo = "boy.jpg";

            }elseif($result->gender == "Female"){

                $results[$key]->photo = "girl.jpg";

            }
            $datee = $result->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date11 = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date11 = str_replace(" PM","",$datee);
                    }else{
                        $date11 = $datee;
                    }
                    $date1 = new DateTime($date11);

            $date2 = $date1->diff(new DateTime($today));

            $results[$key]->age_years = $date2->y;

            $results[$key]->age_months = $date2->m;
//             $date1 = new DateTime($result->birth_date_time);
// echo $date1;die;
//             $date2 = $date1->diff(new DateTime($today));
//             echo $date2; die;

//             $results[$key]['age_years'] = $date2->y;

//             $results[$key]['age_months'] = $date2->m;

//             $results[$key]['birth_date_time'] = $result->birthdatetime;

            if($result->member_type == 'Verified'){
                $results[$key]->member_type = "https://gallpakki.com/img/verified.png"; 
                $results[$key]->mem_type = "Yes";
            }else{
                $results[$key]->member_type = 'normal';
            }
            if($result->is_trusted == 'Trusted'){
                $results[$key]->is_trusted = "https://gallpakki.com/img/trusted.png";
            }
        }

        return $results;

    }



    public function action_interest($data){

        $user_id = $data['user_id'];

        $profile_id = $data['profile_id'];

        $status = array('status' => $data['status']);

        $this->db->where("member_id = '$profile_id' AND profile_id = '$user_id'");

        return $this->db->update('sent_interests',$status);

    }



    public function accepted_interests($user_id)

    {

    //   $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where id in (select member_id from sent_interests where profile_id = $user_id and status= 1) and profile_hide != 'yes' and active='Yes'";
 
        $sql1 = "SELECT members.*, sent_interests.member_id, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime FROM members INNER JOIN sent_interests ON members.id = sent_interests.member_id WHERE sent_interests.profile_id = ".$user_id." and members.profile_hide != 'yes' and members.active='Yes' and sent_interests.status = 1 ORDER BY sent_interests.id ASC";
        $query1 = $this->db->query($sql1);

        $results = $query1->result();

        foreach($results as $key => $result){
            $photo = $result->photo;

            $photo_approved = $result->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $results[$key]->photo = $result->photo;

            }elseif($result->gender == "Male"){

                $results[$key]->photo = "boy.jpg";

            }elseif($result->gender == "Female"){

                $results[$key]->photo = "girl.jpg";

            }
$datee = $result->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date11 = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date11 = str_replace(" PM","",$datee);
                    }else{
                        $date11 = $datee;
                    }
                    $date1 = new DateTime($date11);

            $date2 = $date1->diff(new DateTime($today));

            $results[$key]->age_years = $date2->y;

            $results[$key]->age_months = $date2->m;
            if($result->member_type == 'Verified'){
                $results[$key]->member_type = "https://gallpakki.com/img/verified.png"; 
                $results[$key]->mem_type = "Yes";
            }else{
                $results[$key]->member_type = 'normal';
            }
            if($result->is_trusted == 'Trusted'){
                $results[$key]->is_trusted = "https://gallpakki.com/img/trusted.png";
            }
        }

        return $results;

    }



    public function rejected_interests($user_id)

    {

    //   $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where id in (select member_id from sent_interests where profile_id = $user_id and status= 2) and profile_hide != 'yes' and active='Yes'";
$sql1 = "SELECT members.*, sent_interests.member_id, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime FROM members INNER JOIN sent_interests ON members.id = sent_interests.member_id WHERE sent_interests.profile_id = ".$user_id." and members.profile_hide != 'yes' and members.active='Yes' and sent_interests.status = 2 ORDER BY sent_interests.id ASC";
        $query1 = $this->db->query($sql1);

        $results = $query1->result();

        foreach($results as $key => $result){
            $photo = $result->photo;

            $photo_approved = $result->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $results[$key]->photo = $result->photo;

            }elseif($result->gender == "Male"){

                $results[$key]->photo = "boy.jpg";

            }elseif($result->gender == "Female"){

                $results[$key]->photo = "girl.jpg";

            }
            $datee = $result->birth_date_time;
                    if(strpos($datee,' AM')){
                        $date11 = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date11 = str_replace(" PM","",$datee);
                    }else{
                        $date11 = $datee;
                    }
                    $date1 = new DateTime($date11);

            $date2 = $date1->diff(new DateTime($today));

            $results[$key]->age_years = $date2->y;

            $results[$key]->age_months = $date2->m;

            if($result->member_type == 'Verified'){
                $results[$key]->member_type = "https://gallpakki.com/img/verified.png"; 
                $results[$key]->mem_type = "Yes";
            }else{
                $results[$key]->member_type = 'normal';
            }
            if($result->is_trusted == 'Trusted'){
                $results[$key]->is_trusted = "https://gallpakki.com/img/trusted.png";
            }
        }

        return $results;

    }



    



    public function shortlist_profile($data){

        $interest = array(

            'member_id'=>$data['user_id'],

            'profile_id'=>$data['profile_id']

        );

        $member_id = $data['user_id'];

        $profile_id = $data['profile_id'];

        $this->db->select('*');

        $this->db->from('short_listed');

        $this->db->where("member_id = '$member_id' AND profile_id = '$profile_id'");

        $query=$this->db->get();

        $auser=$query->num_rows();

        if($auser == 0)

        {

            return $this->db->insert('short_listed',$interest);

        }else{

            return "Profile Already Shortlisted";

        }

    }



    public function delete_profile($data){

        $del = array(

            'user_id' => $data['user_id'],

            'reason' => $data['reason'],

            'date'  => date('d-m-Y')

        );
        $arr = array(
            'profile_hide' => 'yes'
        );
        $this->db->where('id',$data['user_id']);
        $this->db->update('members',$arr);
        return $this->db->insert('delete_profile_request',$del);

    }



    public function report_profile($data)

    {

        $user = array(

            'user_id'   => $data['user_id'],

            'profile_id'=> $data['profile_id'],

            'reason'    => $data['reason'],

            'date'      => date('d-m-Y')

        );



        return $this->db->insert('report_profile',$user);

    }



    public function search_profile_by_id($profile_id,$user_id)

    {
        $user =  array();
        $today =date('Y-m-d');
        $req_user = $this->db->get_where('members',array('id' => $user_id))->row();
        
        $r_user = (array) $req_user;
        
        $gender = $r_user['gender'];

        $user = $this->db->get_where('members',array('profile_id' => $profile_id,'id !=' => $user_id,'gender !=' => $gender,'active' => 'Yes'))->row();
        $date1 = new DateTime($user->birth_date_time);
        $date2 = $date1->diff(new DateTime($today));
        $user->age_years = $date2->y;
        $user->age_months = $date2->m;

        return $user ;

    }

    



    public function update_payment_status($data)

    {

        $sdata = array(

            'payment_date' => $data['payment_date'],

            'member_id' => $data['user_id'],

            'plan_id'  => $data['plan_id'],

            'payment_id' => $data['payment_id'],

            'amount'  => $data['amount'],

            'remarks'  => $data['remark'],

        );

        $user = $this->db->get_where('payments',array('member_id' => $data['user_id']))->row();

        if(!empty($user)){

            $this->db->where('member_id',$data['user_id']);

            $update = $this->db->update('payments',$sdata);

            if($update){

                $array = array('plan_id' => $data['plan_id'],'plan_activation_date'=>$data['payment_date']);
                $this->db->where('id',$data['user_id']);
                $return = $this->db->update('members',$array);

            }

        }else{
            if($this->db->insert('payments',$sdata)){
                $datas = array('member_id '=> $data['user_id'], 'wallet_balance'=>10);
                $this->db->insert('member_wallet',$datas);
                $array = array('plan_id' => $data['plan_id'],'plan_activation_date'=>$data['payment_date']);
                $this->db->where('id',$data['user_id']);
                $return = $this->db->update('members',$array);
            }

        }

        return $return;

    }



    public function update_payment_table($data)

    {

        $data = array(

            'payment_date' => $data['payment_date'],

            'member_id' => $data['user_id'],

            'plan_id'  => $data['plan_id'],

            'payment_id' => $data['txn_id'],

            'amount'  => $data['amount'],

            'remark'  => $data['remarke'],

        );

        return $this->db->insert('payments',$data);

    }



    public function set_partner_preferences($data)

    {

         $query = array(

                    'looking_for' => $data['looking_for'],

                    'partner_age_from' => $data['partner_age_from'], 

                    'partner_age_to' => $data['partner_age_to'], 

                    'partner_country' => $data['partner_country'], 

                    'partner_religion' => $data['partner_religion'], 

                    'partner_cast' => $data['partner_cast'], 

                    'partner_height_from' => $data['partner_height_from'], 

                    'partner_height_to' => $data['partner_height_to'], 

                    'partner_education' => $data['partner_education'], 

                    'partner_mothertongue' => $data['partner_mothertongue'], 

                    'partner_annual_income_from' => $data['partner_annual_income_from'] ,

                    'partner_annual_income_to' => $data['partner_annual_income_to'] ,

                    'is_partner_manglik'    => $data['is_partner_manglik'],

                    'partner_occupation'    => $data['partner_occupation'],

                    'partner_state'         => $data['partner_state'],

                    'partner_city'          => $data['partner_city'],

                    'partner_diet'          => $data['partner_diet'],

                    'is_partner_smoking'    => $data['is_partner_smoking'],

                    'is_partner_drinking'   => $data['is_partner_drinking'],

                    'about_my_partner'      => $data['about_my_partner']

                );

            $this->db->where('id',$data['member_id']); 

            return $this->db->update('members',$query);

    }

    

    public function gallery_images($user_id,$image_name)

	{

		$data['member_id'] = $user_id;

		$data['photo'] =  $image_name;	

		$data['photo_approved'] = 'No';

		return $this->db->insert('member_photos',$data);

	}



    public function get_offers()

    {

        $query = $this->db->get_where('offers', array('status'=>'Yes'))->result();

        return $query;

    }



    public function rating_us($data)

    {

        $rating = array(

            'name' => $data['name'],

            'email' => $data['email'],

            'rating' => $data['rating'],

            'profile_id' => $data['profile_id'],

            'description' => $data['description'],

            'submitted_on' => $data['submited_at'],

        );

         return $this->db->insert('user_rating',$rating);

    }



    public function photo_privacy($data)

    {

        $this->db->where('member_id',$data['user_id']);

        return $this->db->update('member_photos',array('photo_privacy' => $data['status']));

    }



    public function get_memberships()

    {

       $plans = $this->db->get('membership_type')->result();

       foreach($plans as $key => $plan){

            $guids = explode('_',$plan->plan_guide);

            $planss[$key]['id'] = $plan->id;

            $planss[$key]['membership_name'] = $plan->plan_name;

            // $planss[$key]['duration_days'] = $plan->duration_days;

            // $planss[$key]['view_profileview_contact'] = $plan->view_contact;

            // $planss[$key]['view_profile'] = $plan->view_profile;

            // $planss[$key]['plan_cost'] = $plan->plan_cost;

            // $planss[$key]['discount_percentage'] = $plan->discount_percentage;

            $planss[$key]['plan_description'] = $plan->plan_description;

            $planss[$key]['terms_and_conditions'] = $plan->terms_and_conditions;

            $planss[$key]['tag_line1'] = $guids[0];

            $planss[$key]['tag_line2'] = $guids[1];

            $planss[$key]['tag_line3'] = $guids[2];

            $planss[$key]['tag_line4'] = $guids[3];

            $planss[$key]['tag_line5'] = $guids[4];

            $planss[$key]['tag_line6'] = $guids[5];

       }

       return $planss;

    }



    public function get_plans($id)

    {

        $plans = $this->db->get_where('membership_plans',array('membership_type' => $id))->result();

        $myPlan = array();

        foreach($plans as $key => $plan){

            $myPlan[$key]['id'] = $plan->id;

            $myPlan[$key]['membership_type'] = $plan->membership_type;

            $myPlan[$key]['plan_name'] = $plan->plan_name;

            $myPlan[$key]['duration_days'] = $plan->duration_days;

            $myPlan[$key]['view_contact'] = $plan->view_contact;

            $myPlan[$key]['view_profile'] = $plan->view_profile;
    
            $myPlan[$key]['plan_cost'] = $plan->plan_cost;

            $myPlan[$key]['discount_percentage'] = $plan->discount_percentage;   

            $myPlan[$key]['final_cost']=  $plan->final_cost;

        }

        return $myPlan;

    }



    public function add_to_shortlist($data)

    {

        $short = array(

            'member_id' => $data['user_id'],

            'profile_id' => $data['profile_id'],

        );

         return $this->db->insert('short_listed',$short);

    }



    public function remove_from_shortlist($data){

        $del = array(

            'member_id' => $data['user_id'],

            'profile_id' => $data['profile_id']

        );



        $this->db->where($del);

        return $this->db->delete('short_listed');

    }



    public function view_contact($data)

    {

        $wallet_amount = $this->db->get_where('member_wallet',array('member_id' => $data['user_id']))->row();

        if(!empty($wallet_amount)){

            $balance = $wallet_amount->wallet_balance;

        }else{
            $balance = 0;

        }



        if($balance < $data['profile_amount']){

            return 'insufficient wallet balance';

        }else{

            $new_balance = $balance - $data['profile_amount'];

            $this->db->where('member_id',$data['member_id']);

            return $this->db->update('member_walllet',array('wallet_amount',$new_balance));

        }

    }



    public function hide_profile($data)

    {

        $array = array(

            'profile_hide' => 'yes',

            'hide_for_days' => $data['days'],

            'hidden_date' => date('d-m-Y'),

        );



        $this->db->where('id',$data['user_id']);

        return $this->db->update('members',$array);

    }



    public function viewed_contact($user_id,$profile_id)

    {

        $data = array(

            'member_id' => $user_id,

            'profile_id' => $profile_id,

            'viewed_date' => date('Y-m-d')

        );



        return $this->db->insert('viewed_contacts',$data);

    }



    public function update_wallet($data)

    {

        $array = array(

            'member_id' => $data['member_id'],

            'amount'    => $data['amount'],

            'payment_id' => $data['payment_id'],

            'remarks'    => $data['remarks']

        );

        $this->db->select_max('id');
    $this->db->where('member_id', $data['member_id']);
    $user = $this->db->get('member_wallet')->row();
        if(!empty($user)){
            $wid = $user->id;
        }else{
            $wid = 0;
        }
        
        $user_wallet_balance = $this->db->get_where('member_wallet', array('id'=>$wid))->row();
        if(!empty($user_wallet_balance)){
            $balance = $user_wallet_balance->wallet_balance;
        }else{
            $balance = 0;
        }
        $array2 = array(

            'member_id' => $data['member_id'],

            'wallet_balance'    => $data['amount'] + $balance,
            
            'amount_added'      => $data['amount'],
            
            'amount_deducted'   => 0,

        );

        $user = $this->db->where(['member_id'=> $data['member_id']])->from("member_wallet")->count_all_results();

        if($user != 0){

            $this->db->insert('member_wallet_payments',$array);

            // $this->db->where('member_id',$data['member_id']);

            return $this->db->insert('member_wallet',$array2);

        }else{

            $this->db->insert('member_wallet_payments',$array);

            return $this->db->insert('member_wallet',$array2);

        }
    }



    public function viewed_contacts($user_id){

        $shorlist = array();

        $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where id in (select profile_id from viewed_contacts where member_id = $user_id)  and active = 'Yes'";

        $query1 = $this->db->query($sql1);

        $results = $query1->result();

        $user = array();

        $pr = array();

        $shrt = array();

        $users =  $this->db->get_where('sent_interests', array('member_id'=>$user_id))->result();

        $shrtlists =  $this->db->get_where('short_listed', array('member_id'=>$user_id))->result();

        foreach($users as $skey=> $use){

           $pr[] = $use->profile_id;

        } 

        foreach($shrtlists as $mkey=> $srt){

           $shrt[] = $srt->profile_id;

        }
        $today = date('Y-m-d');

        foreach($results as $key => $shortlist){

            $user[$key]['id'] = $shortlist->id;
            
            $user[$key]['full_name'] = $shortlist->full_name;
            
            $user[$key]['birth_date_time'] = $shortlist->birth_date_time;

            $user[$key]['profile_id'] = $shortlist->profile_id;

            // $user[$key]['age'] = $shortlist->birth_date_time;
            $date1 = new DateTime($shortlist->birth_date_time);

            $date2 = $date1->diff(new DateTime($today));

            $user[$key]['age_years'] = $date2->y;

            $user[$key]['age_months'] = $date2->m;

            $user[$key]['religion'] = $shortlist->religion;

            $user[$key]['cast'] = $shortlist->cast;

            $user[$key]['height'] = $shortlist->height;

            $user[$key]['mother_tongue'] = $shortlist->mother_tongue;

            $user[$key]['annual_income'] = $shortlist->annual_income;

            $photo = $shortlist->photo;

            $photo_approved = $shortlist->photo_approved;

            if($photo != "" && $photo_approved = "Yes"){

                $user[$key]['photo'] = $shortlist->photo;

            }elseif($shortlist->gender == "Male"){

                $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";

            }elseif($shortlist->gender == "Female"){

                $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";

            }

            $user[$key]['membr_type'] = $shortlist->member_type;

            $member_type = $shortlist->member_type;

            if($member_type == 'Trusted'){

               $user[$key]['member_type'] = "https://gallpakki.com/img/trusted.png"; 

               $user[$key]['mem_type'] = "Yes";

            }elseif($member_type=='Verified'){

                 $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 

                 $user[$key]['mem_type'] = "Yes";

            }else{

                $user[$key]['mem_type'] = "No";

            }

            if(in_array($shortlist->id, $pr)){

                $user[$key]['interest'] = "Yes";

            }else{

                $user[$key]['interest'] = "No";

            }



            if(in_array($shortlist->id, $shrt)){

                $user[$key]['shortlisted'] = "Yes";

            }else{

                $user[$key]['shortlisted'] = "No";

            }
            $user[$key] = array_merge((array) $shortlist, $user[$key]);

        }





        return $user;

    }



    public function get_callbacknumber()

    {

        $user = $this->db->get_where('users',array('user_type'=> 5))->row();

        // $usr = (array) $user;

        $phone = $user->phone;

        return $phone;

    }



    public function get_wallet_offer()

    {

        $wallet = array();

        $user = $this->db->get('wallet_offers')->result();

        // $wallet = $user;

        // $amount = $wallet->amount;

        // $add_on_percentage = $wallet['add_on_percentage'];

        // $add = $add_on_percentage/100;

        // $final = $add * 100;

        // $wallet['final_amount'] = $amount + $final;

        return $user;

    }

    

     public function get_wallet_transactions($user_id)

    {

        $user = $this->db->get_where('member_wallet',array('member_id'=> $user_id))->result();

        return $user;

    }

     function getProfileCompleted($member_id){
        $percentage = 0;
        $sql ="SELECT full_name, email, mobile_number, birth_date_time, height, gender, birth_place, religion, mother_tongue, cast, manglik, marital_status, education, employed_in, occupation, designation, annual_income, country_living_in, state_living_in, city_living_in, address_living_in, family_type, family_status, father_name, father_occupation, mother_name, mother_occupation, no_of_brothers, no_of_sisters, married_brothers, married_sisters, about_family, about_me, looking_for, partner_age_from, partner_age_to, partner_country, partner_religion, partner_cast, partner_height_from, partner_height_to, partner_education, partner_mothertongue, partner_annual_income_from,partner_annual_income_to, id_proof, photo, plan_id, plan_activation_date from members WHERE id=$member_id";
       $query = $this->db->query($sql);
// $test = $query->result();
// print_r($test);
      if ($query->num_rows() > 0)
       
          { 
            $notEmpty =   0;
            $totalField = 49;
            foreach ($query->result() as $row)
              {
                $notEmpty +=  ($row->full_name != '') ? 1 : 0;
                $notEmpty +=  ($row->email != '') ? 1 : 0;
                $notEmpty +=  ($row->mobile_number != '') ? 1 : 0;
                $notEmpty +=  ($row->birth_date_time != '') ? 1 : 0;
                $notEmpty +=  ($row->height != '') ? 1 : 0;
                $notEmpty +=  ($row->gender != '') ? 1 : 0;
                $notEmpty +=  ($row->birth_place != '') ? 1 : 0;
                $notEmpty +=  ($row->religion != '') ? 1 : 0;
                $notEmpty +=  ($row->mother_tongue != '') ? 1 : 0;
                $notEmpty +=  ($row->cast != '') ? 1 : 0;
                $notEmpty +=  ($row->manglik != '') ? 1 : 0;
                $notEmpty +=  ($row->marital_status != '') ? 1 : 0;
                $notEmpty +=  ($row->education != '') ? 1 : 0;
                $notEmpty +=  ($row->employed_in != '') ? 1 : 0;
                $notEmpty +=  ($row->occupation != '') ? 1 : 0;
                $notEmpty +=  ($row->designation != '') ? 1 : 0;
                $notEmpty +=  ($row->annual_income != '') ? 1 : 0;
                $notEmpty +=  ($row->country_living_in != '') ? 1 : 0;
                $notEmpty +=  ($row->state_living_in != '') ? 1 : 0;
                $notEmpty +=  ($row->city_living_in != '') ? 1 : 0;
                $notEmpty +=  ($row->address_living_in != '') ? 1 : 0;
                $notEmpty +=  ($row->family_type != '') ? 1 : 0;
                $notEmpty +=  ($row->father_name != '') ? 1 : 0;
                $notEmpty +=  ($row->father_occupation != '') ? 1 : 0;
                $notEmpty +=  ($row->mother_name != '') ? 1 : 0;
                $notEmpty +=  ($row->mother_occupation != '') ? 1 : 0;
                $notEmpty +=  ($row->no_of_brothers != '') ? 1 : 0;
                $notEmpty +=  ($row->no_of_sisters != '') ? 1 : 0;
                $notEmpty +=  ($row->married_brothers != '') ? 1 : 0;
                $notEmpty +=  ($row->married_sisters != '') ? 1 : 0;
                $notEmpty +=  ($row->about_family != '') ? 1 : 0;
                $notEmpty +=  ($row->looking_for != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_age_from != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_age_to != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_country != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_religion != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_cast != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_height_from != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_height_to != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_education != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_mothertongue != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_annual_income_from != '') ? 1 : 0;
                $notEmpty +=  ($row->partner_annual_income_to != '') ? 1 : 0;
                $notEmpty +=  ($row->photo != '') ? 1 : 0;
                $notEmpty +=  ($row->plan_id != '') ? 1 : 0;
                $notEmpty +=  ($row->plan_activation_date != '') ? 1 : 0;
              }
          $percentage = round(($notEmpty / $totalField) * 100, 0);
          }
        return $percentage;
    }
    
    public function delete_gallery_image($data)
    {
        $where = array('id' => $data['id'], 'member_id' => $data['user_id']);
        // return $where;
        $this->db->where($where);
        $image =  $this->db->delete('member_photos', $where);
        return $image;
    }
    
    public function get_wallet_balance($user_id){
        $this->db->select_max('id');
        $this->db->where('member_id', $user_id);
        $res1 = $this->db->get('member_wallet');
        if ($res1->num_rows() > 0)
        {
         $id = $res1->row();
         $blnc = $this->db->get_where('member_wallet',array('id'=>$id->id))->row();
         return $blnc->wallet_balance;
        }else{
            return 0;
        }
    }
    
    public function like_profile($data)
    {
        $like = array(
            'user_id' => $data['user_id'],
            'like_profile_id' => $data['profile_id']
        );
        $ll = $this->db->get_where('profile_like',array('user_id'=> $data['user_id'],'like_profile_id'=>$data['profile_id']))->row();
        if($ll){
            $this->db->where($like);
            return $this->db->update('profile_like',array('status'=>1));
        }else{
            return $this->db->insert('profile_like',$like);
        }
    }
    
    public function unlike_profile($data)
    {
        $like = array(
            'user_id' => $data['user_id'],
            'like_profile_id' => $data['profile_id']
        );

            $this->db->where($like);
            return $this->db->update('profile_like',array('status'=>0));
       
    }
    
    public function success_story($data,$image)
    {
        $ss = array(
            'bride_name' => $data['bride_name'],
            'groom_name' => $data['groom_name'],
            'photo' => $image,
            'detail' => $data['description'],
            'user_id' => $data['user_id']
        );
        return $this->db->insert('success_stories',$ss);
    }
    
    public function get_success_stories()
    {
        return $this->db->get_where('success_stories',array('status'=>1))->result();
    }
    public function who_viewed($user_id,$page)
    {
        $recent = array();
        $limit = 10; // Number of results per page
        $offset = ($page - 1) * $limit; // Calculate starting point based on the page number
        $sql1 = "SELECT members.*, profile_viewed.viewed_profile_id, DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime FROM members INNER JOIN profile_viewed ON members.id = profile_viewed.member_id WHERE profile_viewed.viewed_profile_id = ".$user_id." AND members.active = 'Yes' AND members.profile_hide != 'yes' ORDER BY profile_viewed.id DESC LIMIT $limit OFFSET $offset";
        $query1 = $this->db->query($sql1);
        $recents = $query1->result();
        $user = array();
        foreach($recents as $key => $recent){

            $user[$key]['id'] = $recent->id;

            $user[$key]['profile_id'] = $recent->profile_id;
            $user[$key]['full_name'] = $recent->full_name;

            $date1 = new DateTime($recent->birth_date_time);

            $date2 = $date1->diff(new DateTime($today));

            $user[$key]['age_years'] = $date2->y;

            $user[$key]['age_months'] = $date2->m;

            $user[$key]['birth_date_time'] = $recent->birthdatetime;

            $user[$key]['religion'] = $recent->religion;

            $user[$key]['cast'] = $recent->cast;

            $user[$key]['height'] = $recent->height;

            $user[$key]['mother_tongue'] = $recent->mother_tongue;

            $user[$key]['annual_income'] = $recent->annual_income;

            $user[$key]['gender'] = $recent->gender;
            
            $user[$key]['activation_number'] = $recent->activation_number;

            $photo = $recent->photo;

            $photo_approved = $recent->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $user[$key]['photo'] = $recent->photo;

            }elseif($recent->gender == "Male"){

                $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";

            }elseif($recent->gender == "Female"){

                $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";

            }
             if($recent->member_type == 'Verified'){
                $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 
                $user[$key]['mem_type'] = "Yes";
            }else{
                $user[$key]['member_type'] = 'normal';
            }
            if($recent->is_trusted == 'Trusted'){
                $user[$key]['is_trusted'] = "https://gallpakki.com/img/trusted.png";
            }else{
                $user[$key]['is_trusted'] = 'No';
            }
            $user[$key] = array_merge((array) $recent, $user[$key]);

        }
        return $user;
    }
    
    public function who_likes($user_id)
    {
        $recent = array();

        $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%d-%m-%Y %I:%i %p') as birthdatetime from members where id in (select user_id from profile_like where like_profile_id = $user_id) and members.active='Yes' and profile_hide != 'yes'";

         $query1 = $this->db->query($sql1);

        $recents = $query1->result();

        $user = array();

        $pr = array();

        $users =  $this->db->get_where('sent_interests', array('member_id'=>$data['user_id']))->result();

        foreach($users as $skey=> $use){

           $pr[] = $use->profile_id;

        } 
        $today =date('Y-m-d');
        foreach($recents as $key => $recent){

            $user[$key]['id'] = $recent->id;

            $user[$key]['profile_id'] = $recent->profile_id;
            $user[$key]['full_name'] = $recent->full_name;

            $date1 = new DateTime($recent->birth_date_time);

            $date2 = $date1->diff(new DateTime($today));
            $user[$key]['age_years'] = $date2->y;

            $user[$key]['age_months'] = $date2->m;

            $user[$key]['birth_date_time'] = $recent->birthdatetime;

            $user[$key]['religion'] = $recent->religion;

            $user[$key]['cast'] = $recent->cast;

            $user[$key]['height'] = $recent->height;

            $user[$key]['mother_tongue'] = $recent->mother_tongue;

            $user[$key]['annual_income'] = $recent->annual_income;

            $user[$key]['gender'] = $recent->gender;
            
            $user[$key]['activation_number'] = $recent->activation_number;

            $photo = $recent->photo;

            $photo_approved = $recent->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $user[$key]['photo'] = $recent->photo;

            }elseif($recent->gender == "Male"){

                $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";

            }elseif($recent->gender == "Female"){

                $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";

            }



             if($recent->member_type == 'Verified'){

                              $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 

                                $user[$key]['mem_type'] = "Yes";

                            }else{

                                $user[$key]['member_type'] = 'normal';

                            }

                            if($recent->is_trusted == 'Trusted'){

                                $user[$key]['is_trusted'] = "https://gallpakki.com/img/trusted.png";

                            }else{
                                $user[$key]['is_trusted'] = 'No';
                            }



            if(in_array($recent->id, $pr)){

                $user[$key]['interest'] = "Yes";

            }else{

                $user[$key]['interest'] = "No";

            }
            
            // if(in_array($recent->id, $like)){

            //     $user[$key]['like'] = "Yes";

            // }else{

            //     $user[$key]['like'] = "No";

            // }
            
            $user[$key]['interest_status'] = " Send Interest";
            $status = $this->db->select('*')->from('sent_interests')->where(array('member_id'=> $user_id,'profile_id' => $recent->id))->get()->result();
            foreach($status as $stat){
                if($stat->status == 0){
                    $user[$key]['interest_status'] = "Pending";
                }elseif($stat->status == 1){
                    $user[$key]['interest_status'] = "Accepted";
                }elseif($stat->status == 2){
                    $user[$key]['interest_status'] = "Rejected";
                }else{
                    $user[$key]['interest_status'] = "Send Interest";
                }
            }          

        }
       return $user;
    }
    
    public function viewed_by_me($user_id,$page)
    {
        $recent = array();

       $limit = 10; // Number of results per page
        $offset = ($page - 1) * $limit; // Calculate the starting point based on the page number

        // $sql1 = "SELECT members.*, 
        //         DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime 
        //  FROM members 
        //  WHERE id IN (SELECT viewed_profile_id FROM profile_viewed WHERE member_id = $user_id) 
        //  ORDER BY id DESC 
        //  LIMIT $limit OFFSET $offset";
        $sql1 = "SELECT members.*, 
       DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime 
FROM members 
JOIN profile_viewed 
     ON members.id = profile_viewed.viewed_profile_id 
WHERE profile_viewed.member_id = $user_id 
ORDER BY profile_viewed.id DESC 
LIMIT $limit OFFSET $offset";
         $query1 = $this->db->query($sql1);

        $recents = $query1->result();

        $user = array();


        foreach($recents as $key => $recent){

            $user[$key]['id'] = $recent->id;

            $user[$key]['profile_id'] = $recent->profile_id;
            $user[$key]['full_name'] = $recent->full_name;

            // $date1 = new DateTime($recent->birth_date_time);

            // $date2 = $date1->diff(new DateTime($today));
            $datee = $recent->birthdatetime;
                    if(strpos($datee,' AM')){
                        $date11 = str_replace(" AM","",$datee);
                    }elseif(strpos($datee,' PM')){
                        $date11 = str_replace(" PM","",$datee);
                    }else{
                        $date11 = $datee;
                    }
                    $date1 = new DateTime($date11);

            $date2 = $date1->diff(new DateTime($today));

            $user[$key]['age_years'] = $date2->y;

            $user[$key]['age_months'] = $date2->m;

            $user[$key]['birth_date_time'] = $recent->birthdatetime;

            $user[$key]['religion'] = $recent->religion;

            $user[$key]['cast'] = $recent->cast;

            $user[$key]['height'] = $recent->height;

            $user[$key]['mother_tongue'] = $recent->mother_tongue;

            $user[$key]['annual_income'] = $recent->annual_income;

            $user[$key]['gender'] = $recent->gender;
            
            $user[$key]['activation_number'] = $recent->activation_number;

            $photo = $recent->photo;

            $photo_approved = $recent->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $user[$key]['photo'] = $recent->photo;

            }elseif($recent->gender == "Male"){

                $user[$key]['photo'] = "https://gallpakki.com/img/boy.jpg";

            }elseif($recent->gender == "Female"){

                $user[$key]['photo'] = "https://gallpakki.com/img/girl.jpg";

            }



             if($recent->member_type == 'Verified'){

                               $user[$key]['member_type'] = "https://gallpakki.com/img/verified.png"; 

                                $user[$key]['mem_type'] = "Yes";

                            }else{

                                $user[$key]['member_type'] = 'normal';

                            }

                            if($recent->is_trusted == 'Trusted'){

                                $user[$key]['is_trusted'] = "https://gallpakki.com/img/trusted.png";

                            }else{
                                $user[$key]['is_trusted'] = 'No';
                            }
$user[$key] = array_merge((array) $recent, $user[$key]);
        }
        return $user;
    }
    
    public function save_plan_id($user_id,$plan_id)
    {
        $array = array(
            'plan_id'=> $plan_id,
        );   
        $this->db->where('id',$user_id);
        return $this->db->update('members',$array);
    }
    
    public function delete_interest($data)
    {
        $this->db->where(array('member_id'=>$data['user_id'],'profile_id'=>$data['profile_id']));
        return $this->db->delete('sent_interests');
    }
}

?>