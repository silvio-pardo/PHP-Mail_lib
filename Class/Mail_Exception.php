<?php
/**
 * Created by PhpStorm.
 * User: Silvio
 * Date: 26/07/2019
 * Time: 15:49
 */

class Mail_Exception extends Exception {
    public function error_mail(){
        return false;
    }
    public function error_send(){
        return false;
    }
    public function error_header(){
        return false;
    }
    public function error_bcc(){
        return false;
    }
    public function error_replyTo(){
        return false;
    }
    public function error_cc(){
        return false;
    }
    public function error_Ctype(){
        return false;
    }
    public function error_Mtype(){
        return false;
    }
    public function error_attachment(){
        return false;
    }
    public function error_fmimetype(){
        return false;
    }
    public function error_chunk_fsplit(){
        return false;
    }
}
?>