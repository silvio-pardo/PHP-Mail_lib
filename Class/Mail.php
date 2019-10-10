<?php
/**
 * Created by PhpStorm.
 * User: Silvio
 * Date: 26/07/2019
 * Time: 14:17
 */

require 'Mail_Exception.php';
require 'Mail_variable.php';
class Mail extends Mail_variable
{
    public function __construct($obj,$text,$header)
    {
        $this->subject = $obj;
        $this->message = $text;
        $this->headers = $this->create_header($header);
        $this->boundary_hash = md5(date('r', time()));
    }
    private function create_header($array_header){
        try {
            if(is_array($array_header)) {
                $result = array(
                    'X-Mailer: PHP/' . phpversion()
                );
                return array_merge($result, $array_header);
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_header();
        }
    }
    private function get_file_mimetype($file){
        try{
            if(is_string($file)){
                $type = strrchr( $file , '.');
                //.pgp as application/octet-stream
                foreach ( $this->mime_ftype as $key => $value )
                {
                    if($type == $key){
                        $ftype = $value;
                    }

                }
                //control for return
                if(empty($ftype)){
                    $ftype = "application/octet-stream";
                    return $ftype;
                }else{
                    return $ftype;
                }
            }else{
                throw new Mail_Exception();
            }
        }catch(Mail_Exception $error){
            return $error->error_fmimetype();
        }
    }
    private function get_chunk_fsplit($file){
        try{
            if(is_string($file)){
                $read_file = fopen($file, "rb");
                $data = fread($read_file,  filesize( $file ) );
                fclose($read_file);
                return chunk_split(base64_encode($data));
            }else{
                throw new Mail_Exception();
            }
        }catch(Mail_Exception $error){
            return $error->error_chunk_fsplit();
        }

    }
    private function set_multitype_message(){
        $new_message = "--".$this->boundary_hash.PHP_EOL."Content-type:text/html; charset=iso-8859-1\r\n"."Content-Transfer-Encoding: base64".PHP_EOL.PHP_EOL.chunk_split(base64_encode($this->message)).PHP_EOL.PHP_EOL."--".$this->boundary_hash.PHP_EOL;
        $this->message = $new_message;
    }
    private function fetch_attachment(){
        for($i=0;$i<=sizeof($this->attachment)-1;$i++){
            $this->message .= "Content-Type: ".$this->attachment[$i][1]."; name=\"".basename($this->attachment[$i][0])."\"".PHP_EOL;
            $this->message .= "Content-Disposition: attachment; filename=\"".basename($this->attachment[$i][0])."\"".PHP_EOL;
            $this->message .= "Content-Transfer-Encoding: base64".PHP_EOL;
            $this->message .= "X-Attachment-Id: ".rand(1000, 99999).PHP_EOL.PHP_EOL;
            $this->message .= $this->attachment[$i][2].PHP_EOL;
            $this->message .= "--".$this->boundary_hash."--";
        }
    }
    private function fetch_multi_header($mode){
        switch ($mode){
            case "bcc" :
                array_push($this->headers, 'Bcc: ' . $this->bcc);
                return true;
                break;
            case "cc" :
                array_push($this->headers, 'Cc: ' . $this->cc);
                return true;
                break;
            case "replyto" :
                array_push($this->headers, 'Reply-To: ' . $this->reply_to);
                return true;
                break;
            default :
                return false;
                break;
        }
    }
    private function match_header(){
       $this->headers = array_merge($this->headers,$this->header_c_Type);
    }
    public function set_sender($sender){
        try{
            if(is_string($sender)){
                if(filter_var($sender, FILTER_VALIDATE_EMAIL)){
                    array_push($this->headers,'From: <'.$sender.'>');
                    return $sender;
                }else{
                    throw new Mail_Exception();
                }
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_mail();
        }
    }
    public function set_dest($mail_dest){
        try{
            if(is_string($mail_dest)){
                if(filter_var($mail_dest, FILTER_VALIDATE_EMAIL)){
                    if(empty($this->sendTo)){
                        $this->sendTo .= $mail_dest;
                        return $this->sendTo;
                    }else {
                        $this->sendTo .= ', '.$mail_dest;
                        return $this->sendTo;
                    }
                }else{
                    throw new Mail_Exception();
                }
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_mail();
        }
    }
    public function set_bcc($bcc){
        try{
            if(is_string($bcc)){
                if(filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
                    if(empty($this->bcc)){
                        $this->bcc .= $bcc;
                        return $this->bcc;
                    }else {
                        $this->bcc .= ', '.$bcc;
                        return $this->bcc;
                    }
                }else{
                    throw new Mail_Exception();
                }
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_bcc();
        }
    }
    public function set_replyTo($replyto){
        try{
            if(is_string($replyto)) {
                if(filter_var($replyto, FILTER_VALIDATE_EMAIL)) {
                    if(empty($this->reply_to)){
                        $this->reply_to .= $replyto;
                        return $this->reply_to;
                    }else {
                        $this->reply_to .= ', '.$replyto;
                        return $this->reply_to;
                    }
                }else{
                    throw new Mail_Exception();
                }
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_replyTo();
        }
    }
    public function set_cc($cc){
        try{
            if(is_string($cc)) {
                if(filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                    if(empty($this->cc)){
                        $this->cc .= $cc;
                        return $this->cc;
                    }else {
                        $this->cc .= ', '.$cc;
                        return $this->cc;
                    }
                }else{
                    throw new Mail_Exception();
                }
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_cc();
        }
    }
    public function set_Content_type($Ctype){
        try{
            if(is_string($Ctype)) {
                array_push($this->header_c_Type, 'Content-type: ' . $Ctype);
                return $Ctype;
            }elseif(is_array($Ctype) && $Ctype['attach_mode'] < 2){
                array_push($this->header_c_Type, 'Content-type: ' . $Ctype["type"].'; boundary='.$this->boundary_hash,'Content-Transfer-Encoding: 7bit');
                return $Ctype;
            }else{
                if(is_array($Ctype) && $Ctype['attach_mode'] > 1){
                    // multi_boundary
                    $Mmime_boundary = "==Multipart_Boundary_x{$this->boundary_hash}x";
                    $this->boundary_hash = $Mmime_boundary;
                    array_push($this->header_c_Type, 'Content-type: ' . $Ctype["type"].'; boundary=\''.$this->boundary_hash.'\'','Content-Transfer-Encoding: 7bit');
                    return $Ctype;
                }else {
                    throw new Mail_Exception();
                }
            }
        }catch (Mail_Exception $error){
            return $error->error_Ctype();
        }
    }
    public function set_Mime_type($Mtype){
        try{
            if(is_int($Mtype)) {
                array_push($this->headers, 'MIME-Version: ' . $Mtype);
                return $Mtype;
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_Mtype();
        }
    }
    public function add_attachment($file){
        try {
            if (is_string($file) && file_exists($file)) {
                $arr_temp = array();
                array_push($arr_temp,$file);
                array_push($arr_temp,$this->get_file_mimetype($file));
                array_push($arr_temp,$this->get_chunk_fsplit($file));
                $this->attachment = array_merge($this->attachment,array($arr_temp));
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_attachment();
        }
    }

    public function send_mail(){
        try {
            if(!empty($this->reply_to)){
                $this->fetch_multi_header("replyto");
            }
            if(!empty($this->bcc)){
                $this->fetch_multi_header("bcc");
            }
            if(!empty($this->cc)){
                $this->fetch_multi_header("cc");
            }
            if(!empty($this->attachment)){
                $this->match_header();
                $this->set_multitype_message(); //salvo il messaggio prima dell'allegati
                $this->fetch_attachment(); //fetch allegati
            }
            if($mail = mail($this->sendTo, $this->subject, $this->message, implode(PHP_EOL, $this->headers))) {
                return $mail;
            }else{
                throw new Mail_Exception();
            }
        }catch (Mail_Exception $error){
            return $error->error_send();
        }
    }
}
?>