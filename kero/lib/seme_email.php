<?php
/**
 * Class helper for sending email
 */

/**
* Cara penggunaan
* ====================================
* $this->load("seme_email","lib");
*
* Menggunakan Template HTML
* ====================================
* Pertama-tama buat dulu file html di folder kero/lib/seme_email/template
* dengan extension .php
* file tersebut berisikan HTML biasa
* Sementara untuk isinya supaya dinamis dengan diapit
* double kurung kurawal. Contoh: {{contoh_dinamis}}
*
*
* Identitas pengirim
* $this->seme_email->from("nama","email");
*
* Menambah judul email.
* $this->seme_email->subject("test kirim email");
*
* Menambah penerima, atau banyak penerima cukup diulang saja.
* $this->seme_email->to("email@domain.com",'nama');
*
* Menambahkan isi email
* 1. HTML
*   $this->seme_email->html("<h1>Test</h1>");
* ATAU
* 2. Teks Biasa
*   $this->seme_email->text("ini teks biasa");
*
* Mengirimkan email.
* $this->seme_email->send();
*
*
*/
class Seme_Email
{
    public $log = "";
    public $to = array();
    public $toname = array();
    public $cc = array();
    public $bcc = array();
    public $attachment = array();
    public $replacer = array();
    public $boundary = "";
    public $header = "";
    public $subject = "";
    public $body = "";
    public $eol = PHP_EOL;
    public $template;

    /**
     * Clear data
     * @return object this object
     */
    public function flush()
    {
        $this->log = '';
        $this->header = "";
        $this->to = array();
        $this->toname = array();
        $this->cc = array();
        $this->bcc = array();
        $this->attachment = array();
        $this->replacer = array();
        $this->boundary = '';
        $this->subject = '';
        $this->body = '';
        $this->template = '';
        $this->log = "Seme_Email: Flush Successfully".$this->eol;
        return $this;
    }
    public function from($mail, $name="")
    {
        $this->boundary = md5(uniqid(time()));
        if (!empty($mail) && !empty($name)) {
            $this->header .= "From: $name <$mail>".$this->eol;
        } elseif (!empty($mail) && empty($name)) {
            $this->header .= "From: $mail".$this->eol;
        } else {
            trigger_error('from email cant empty');
        }
        $this->log .= "set from $name $mail".$this->eol;
        return $this;
    }

    public function replyto($name, $mail)
    {
        $this->header .= "Reply-To: $name <$mail>".$this->eol;
        $this->log .= "adding Reply-To $name <$mail>".$this->eol;
        return $this;
    }

    public function to($mail, $name="")
    {
        if (!empty($name)) {
            $this->to[] = $mail;
            $this->toname[] = $name;
            $this->log .= "Send to $name $mail".$this->eol;
        } else {
            $this->to[] = $mail;
            $this->toname[] = "";
            $this->log .= "Send to $name $mail".$this->eol;
        }
        return $this;
    }

    public function cc($mail)
    {
        $this->cc[] = $mail;
        $this->log .= "adding cc $mail".$this->eol;
        return $this;
    }

    public function bcc($mail)
    {
        $this->bcc[] = $mail;
        $this->log .= "adding bcc $mail".$this->eol;
        return $this;
    }


    public function subject($subject)
    {
        $this->subject = $subject;
        $this->log .= "adding subject $subject".$this->eol;
        return $this;
    }

    public function text($text)
    {
        $this->body = "Content-Type: text/plain; charset=ISO-8859-1".$this->eol;
        $this->body .= "Content-Transfer-Encoding: 8bit".$this->eol;
        $this->body .= $text."".$this->eol;
        $this->log .= "adding text content".$this->eol;
        return $this;
    }

    public function html($html, $type="windows")
    {
        if ($type=="windows") {
            $this->body = "Content-Type: text/html; charset=\"ISO-8859-1\"".$this->eol;
        } else {
            $this->body = "Content-Type: text/html; charset=\"utf-8\"".$this->eol;
        }

        $this->body .= "Content-Transfer-Encoding: quoted-printable".$this->eol;
        $this->body .= "<html><body>".$this->eol."".$html."".$this->eol."</body></html>".$this->eol;
        $this->log .= "adding html content \n";
        return $this;
    }
    /**
     * Mengatur lokasi email template
     * @param  string $template relative path terhadap directory template email
     * @return object           this object
     */
    public function template($template)
    {
        $this->log .= "Loading template: ".$template." \n";
        $basetemp = SENELIB.'/seme_email/';
        if (!is_dir($basetemp)) {
            mkdir($basetemp);
        }
        $basetemp = SENELIB.'/seme_email/template/';
        if (!is_dir($basetemp)) {
            mkdir($basetemp);
        }
        $ftemp = $basetemp.$template.".php";
        if (!file_exists($ftemp)) {
            trigger_error("SEME_EMAIL: Template file not found in ".$ftemp);
            die();
        }
        $this->template = $ftemp;
        return $this;
    }
    /**
     * Memproses variabel dari template
     * @param  array $replacer array of name value pair yang akan menggantikan isi dari variabel di template email
     * @return void
     */
    public function replacer($replacer, $val="")
    {
        $this->log .= "Replacer added. \n";
        if (is_array($replacer)) {
            foreach ($replacer as $key=>$val) {
                if (!is_int($key)) {
                    $this->replacer[$key] = $val;
                }
            }
        } elseif (!empty($val)) {
            $this->replacer[$replacer] = $val;
        }
        return $this;
    }
    public function attachment_clear()
    {
        $this->attachment = array();
        return $this;
    }
    public function attachment_add($filepath, $filename="")
    {
        if (is_file($filepath)) {
            $a = new stdClass();
            $a->pathinfo = pathinfo($filepath);
            $a->basename = $a->pathinfo['basename'];
            if (strlen($filename)>3) {
                $a->basename = $filename;
            }

            //read file
            $a->filesize = filesize($filepath);
            $fh = fopen($filepath, "r");
            $a->content = fread($fh, $a->filesize);
            $a->chunked = chunk_split(base64_encode($a->content));
            $a->mime = mime_content_type($filepath);
            fclose($fh);
            $this->attachment[] = $a;
            unset($a);
        }
        return $this;
    }

    public function send()
    {
        if (empty($this->subject)) {
            trigger_error("subject can't empty");
        }
        $this->log .= "Send triggered \n";
        $max = count($this->cc);
        if ($max>0) {
            $this->header .= "Cc: ".$this->cc[0];
            for ($i=1;$i<$max;$i++) {
                $this->header .= ", ".$this->cc[$i];
            }
            $this->header .= "".$this->eol;
        }
        $max = count($this->bcc);
        if ($max>0) {
            $this->header .= "Bcc: ".$this->bcc[0];
            for ($i=1;$i<$max;$i++) {
                $this->header .= ", ".$this->bcc[$i];
            }
            $this->header .= "".$this->eol;
        }
        $this->header .= "MIME-Version: 1.0".$this->eol;
        $uid = '';
        $atc = count($this->attachment);
        if ($atc) {
            $this->body = '';
            $uid = md5(uniqid(time()));
            $this->header .= "Content-Type: multipart/mixed; boundary=\"$uid\"".$this->eol;
            $this->body .= "--$uid".$this->eol;
            $this->body .= "Content-Type: text/html; charset=\"UTF-8\"".$this->eol;
            $this->body .= "Content-Transfer-Encoding: 8bit".$this->eol.$this->eol;
        } else {
            $this->header .= "Content-Type: text/html; charset=\"UTF-8\"".$this->eol;
        }

        //if($atc) $this->header .= "Content-Transfer-Encoding: 8bit".$this->eol;
        //$this->header .= "Content-Transfer-Encoding: quoted-printable".$this->eol;
        if (!empty($this->template)) {
            $this->log .= "Template loaded: $this->template  \n";
            $f = fopen($this->template, 'r');
            $message = fread($f, filesize($this->template));
            fclose($f);
            if (strlen($message)<2) {
                trigger_error("SEME_EMAIL: Message too short, please provide more");
                die();
            }
            if (count($this->replacer)>0) {
                $this->log .= "Template processed and replaced \n";
                foreach ($this->replacer as $key=>$val) {
                    $message = str_replace("{{".$key."}}", $val, $message);
                }
            }
            $this->log .= "inserting template to email OK \n";
            $message = trim(preg_replace("/\s+/", " ", $message));
            $message = wordwrap($message, 75, "\n");
            $message = trim($message);

            if ($atc) {
                $this->body .= $message.$this->eol;
            } else {
                $this->header .= $message;
            }
            foreach ($this->attachment as $a) {
                $this->body .= "--$uid".$this->eol;
                $this->body .= "Content-Type: \"$a->mime\"; name=\"$a->basename\"".$this->eol;
                $this->body .= "Content-Transfer-Encoding: base64".$this->eol;
                $this->body .= "Content-Disposition: attachment; filename=\"$a->basename\"".$this->eol;
                $this->body .= $this->eol.$a->chunked.$this->eol;
                $this->log .= "attached: $a->basename -> $a->filesize bytes \n";
            }
            if ($atc) {
                $this->body .= "--$uid--";
            }
        } else {
            $this->log .= "inserting plain body to email OK \n";
            $message = $this->body;
            $message = trim(preg_replace("/\s+/", " ", $message));
            $message = wordwrap($message, 75, "\n");
            $message = trim($message);

            $atc = count($this->attachment);
            if ($atc) {
                $this->body = '';
                $uid = md5(uniqid(time()));
                $this->header .= "Content-Type: multipart/mixed; boundary=\"$uid\"".$this->eol;
                $this->body .= "--$uid".$this->eol;
                $this->body .= "Content-Type: text/plain; charset=\"iso-8859-1\"".$this->eol;
                $this->body .= "Content-Transfer-Encoding: 7bit".$this->eol;
                $this->body .= $message.$this->eol;
                foreach ($this->attachment as $a) {
                    $this->body .= "--$uid".$this->eol;
                    $this->body .= "Content-Type: \"$a->mime\"; name=\"$a->basename\"".$this->eol;
                    $this->body .= "Content-Transfer-Encoding: base64".$this->eol;
                    $this->body .= "Content-Disposition: attachment; filename=\"$a->basename\"".$this->eol;
                    $this->body .= $this->eol.$a->chunked.$this->eol;
                    $this->log .= "attached: $a->basename -> $a->filesize bytes \n";
                }
                if ($atc) {
                    $this->body .= "--$uid--";
                }
            } else {
                $this->header .= "Content-Type: text/plain; charset=\"iso-8859-1\"".$this->eol;
                $this->header .= $message;
            }
        }

        foreach ($this->to as $mail) {
            if ($atc) {
                $res = mail($mail, $this->subject, $this->body, $this->header);
            } else {
                $res = mail($mail, $this->subject, "", $this->header);
            }
            if ($res) {
                $this->log .= "sending to $mail success".$this->eol;
            } else {
                $this->log .= "sending to $mail failed".$this->eol;
            }
            $this->log .= "\n";
        }

        return $this;
    }
    public function getLog()
    {
        return $this->log;
    }
}
