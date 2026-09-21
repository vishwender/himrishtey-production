<?php



defined('BASEPATH') OR exit('No direct script access allowed');



// This can be removed if you use __autoload() in config.php OR use Modular Extensions

require APPPATH . '/libraries/REST_Controller.php';



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

class Chat extends REST_Controller {



    function __construct()

    {

        // Construct the parent class

        parent::__construct();



        $this->load->model('profile_model','pro');

        $this->load->model('user_model','usr');
        
        $this->load->model('chat_model','chat');

    }
    
    public function user_chat_get($user_id)
    {
        $query2 = $this->chat->get_user_chat_history($user_id);
        if(!$query2){
            $this->response( [
                'success'=>false,
                'message'=>'No Chat History Found'
            ], REST_Controller::HTTP_OK );
        }else{
            $this->response( [
                'success'=>true,
                'data'=> $query2,
                'message' => 'Chat history Found',
            ], REST_Controller::HTTP_OK );
        }
    }
    
    public function single_user_chat_post()
    {
        $data['user_id'] = $this->input->post('user_id');
        $s = $this->input->post('profile_id');
        $ss = filter_var($s, FILTER_SANITIZE_NUMBER_INT);
        $pid = abs(10000-$ss);
        // $ppid = strval($pid);
        $data['member_id'] = strval($pid);
        $query2 = $this->chat->single_user_chat($data);
        if(!$query2){
            $this->response( [
                'success'=>false,
                'data' => $pid,
                'message'=>'No Chat History Found'
            ], REST_Controller::HTTP_OK );
        }else{
            $this->response( [
                'success'=>true,
                'data'=> $query2,
                'message' => 'Chat history Found',
            ], REST_Controller::HTTP_OK );
        }
    }
    
    public function chat_with_user_post()
    {
        $data['user_id'] = $this->input->post('user_id');
        $data['profile_id'] = $this->input->post('profile_id');
        $data['message'] = $this->input->post('message');
        $query2 = $this->chat->chat_with_user($data);
        if(!$query2){
            $this->response( [
                'success'=>false,
                'message'=>'No Chat History Found'
            ], REST_Controller::HTTP_OK );
        }else{
            $this->response( [
                'success'=>true,
                'data'=> $query2,
                'message' => 'Chat history Found',
            ], REST_Controller::HTTP_OK );
        }
    }
}
?>