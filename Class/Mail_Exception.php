<?php
/**
 * Created by PhpStorm.
 * User: Silvio
 * Date: 26/07/2019
 * Time: 15:49
 * --------------------
 * This file is part of PHP-Mail_lib.
 * Mail_lib is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * PHP-Mail_lib is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with PHP-Mail_lib.  If not, see <http://www.gnu.org/licenses/>.
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