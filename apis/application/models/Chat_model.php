<?php

class Chat_model extends CI_model{
    
    public function get_user_chat_history($user_id)
    {
        $sql1 =  "Select members.*, DATE_FORMAT(members.birth_date_time,'%Y-%m-%d %I:%i %p') as birthdatetime 
        from members where id in (select profile_id from chat_detail where member_id = $user_id)";
        $query1 = $this->db->query($sql1);

        $results = $query1->result();
        
        $array = array();
        
        foreach($results as $key => $value)
        {
            $user = array('member_id' => $user_id,'profile_id'=>$value->id);
            $this->db->select_max('id');
            $this->db->where($user);
            $usr = $this->db->get('chat_detail')->row();
            $last_chat = $this->db->get_where('chat_detail',array('id' => $usr->id))->row();
            $array[$key]['profile_id'] = $value->profile_id;
            $array[$key]['full_name'] = $value->full_name;
            $array[$key]['message'] = $last_chat->message;
            $array[$key]['date_time'] = $last_chat->chat_date_time;
            $photo = $value->photo;

            $photo_approved = $value->photo_approved;

            if($photo != "" && $photo_approved == "Yes"){

                $array[$key]['photo'] = 'https://himrishtey.com/photos/photo/'.$value->photo;

            }elseif($value->gender == "Male"){

                $array[$key]['photo'] = "https://himrishtey.com/img/boy.jpg";

            }elseif($value->gender == "Female"){

                $array[$key]['photo'] = "https://himrishtey.com/img/girl.jpg";

            }
        }
        return $array;
    }
    
    public function single_user_chat($data)
    {
        $from_user_id = $data['user_id'];
        $to_user_id = $data['member_id'];
        $sqlQuery = "
			SELECT * FROM chat_detail 
			WHERE (member_id = '".$from_user_id."' 
			AND profile_id = '".$to_user_id."') 
			OR (member_id = '".$to_user_id."' 
			AND profile_id = '".$from_user_id."') 
			ORDER BY id ASC";
			
		$query1 = $this->db->query($sqlQuery);
        return $results = $query1->result();
        
    }
    
    public function chat_with_user($data)
    {
        $s = $data['profile_id'];
        $ss = filter_var($s, FILTER_SANITIZE_NUMBER_INT);
        $pid = abs(10000-$ss);
        $profile_id = strval($pid);
        $array = array(
            'member_id' => $data['user_id'],
            'profile_id' => $profile_id,
            'message'   => $data['message']
        );
        
        $chat = $this->db->insert('chat_detail', $array);
        return $chat;
    }
}