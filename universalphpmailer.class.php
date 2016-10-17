<?php

/**
 * Universal PHP Mailer
 *
 * @version    0.4.1 (2016-10-17 10:13:00 GMT)
 * @author     Peter Kahl <peter.kahl@colossalmind.com>
 * @copyright  2016 Peter Kahl
 * @license    Apache License, Version 2.0
 *
 * Copyright 2016 Peter Kahl
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class universalPHPmailer {

  /**
   * Version
   *
   * @var string
   */
  private $version = '0.4.1';

  public $sendto_name;

  public $sendto_mail;

  public $from_name;

  public $from_mail;

  public $return_path;

  public $message_subject;

  public $message_txt_plain;

  public $message_txt_html;

  /**
   * @var string
   * Valid values are ... 'base64' OR 'quoted-printable'
   */
  public $message_encoding;

  /**
   * If set, will create header 'Content-Language: en-gb'
   *
   * @var string (e.g. 'en-gb')
   */
  public $headerContentLang;

  /**
   * Specify custom headers, if any
   *
   * @var array
   * Required structure:
   *           array(
   *                'In-Reply-To' => '<MESSAGE.ID@domain.tld>',
   *                'References'  => '<MESSAGE.ID@domain.tld>',
   *                'Bcc'         => 'recipient@somewhere',
   *                )
   */
  public $custom_headers;

  /**
   * Inline images
   *
   * @var array
   * Required structure:
   *
   * array(
   *    0 => array(
   *              'file-extension'    => '',
   *              'original-filename' => '',
   *              'content-id'        => '',
   *              'base64-data'       => ''
   *              ),
   *    1 => array(
   *              'file-extension'    => '',
   *              'original-filename' => '',
   *              'content-id'        => '',
   *              'base64-data'       => ''
   *              ),
   *    2 => array(
   *              'file-extension'    => '',
   *              'original-filename' => '',
   *              'content-id'        => '',
   *              'base64-data'       => ''
   *              ),
   *    3 => array(
   *              'file-extension'    => '',
   *              'original-filename' => '',
   *              'content-id'        => '',
   *              'base64-data'       => ''
   *              ),
   *      );
   *
   */
  public $inline_images;

  /**
   * Hostname for use in message ID and for Content-ID
   *
   * @var string
   */
  public $hostName;

  private $message_id;

  /**
   * Randomly generated string for boundaries
   *
   * @var string
   */
  private $rbstr;

  /**
   * Multipart message boundaries
   *
   * @var array
   */
  private $boundary;

  /**
   * Maximum line length as per RFC2822
   * https://tools.ietf.org/html/rfc2822#section-2.2.3
   *
   * @var integer
   */
  private $max_line_length = 76;

  //==================================================================

  public function sendMessage() {

    $this->rbstr = false;
    $this->set_encoding();

    $to        = $this->endexplode('o: ', $this->encode_header('To', $this->sanitize_name($this->sendto_name), '<'.$this->sendto_mail.'>'));
    $subject   = $this->endexplode('ubject: ', $this->encode_header('Subject', $this->message_subject));

    $message   = '';

    $headers   = array();
    $headers[] = $this->header_date();
    $headers[] = $this->header_msg_id();
    $headers[] = $this->header_from();
    if (!empty($this->custom_headers) && is_array($this->custom_headers)) {
      foreach ($this->custom_headers as $key => $val) {
        $headers[] = $this->encode_header($key, $val);
      }
    }
    $headers[] = 'User-Agent: universalPHPmailer/'$this->version.' (https://github.com/peterkahl/Universal-PHP-Mailer)';
    $headers[] = 'MIME-Version: 1.0';

    // Determine message type
    if (!empty($this->message_txt_plain)) {
      if (!empty($this->message_txt_html)) {
        #################################################
        // text/plain, text/html = multipart/alternative + multipart/related
        $headers[] = 'Content-Type: multipart/alternative;';
        $headers[] = "\t boundary=\"".$this->getBoundary('multipart/alternative').'"';
        //=================================
        // PLAIN TEXT PART
        $message .= '--'.$this->getBoundary('multipart/alternative').PHP_EOL;
        $message .= 'Content-Type: text/plain; charset=utf-8'.PHP_EOL;
        $message .= 'Content-Transfer-Encoding: '.$this->message_encoding.PHP_EOL;
        if (!empty($this->headerContentLang)) {
          $message .= 'Content-Language: '.$this->headerContentLang.PHP_EOL;
        }
        $message .= 'Content-Disposition: inline'.PHP_EOL;
        $message .= PHP_EOL;
        $message .= $this->encode_body(trim($this->message_txt_plain)).PHP_EOL;
        //=================================
        // BOUNDARY
        $message .= '--'.$this->getBoundary('multipart/alternative').PHP_EOL;
        $message .= 'Content-Type: multipart/related;'.PHP_EOL;
        $message .= "\t boundary=\"".$this->getBoundary('multipart/related').'"'.PHP_EOL;
        $message .= PHP_EOL;
        //=================================
        // HTML PART
        $message .= '--'.$this->getBoundary('multipart/related').PHP_EOL;
        $message .= 'Content-Type: text/html; charset=utf-8'.PHP_EOL;
        $message .= 'Content-Transfer-Encoding: '.$this->message_encoding.PHP_EOL;
        if (!empty($this->headerContentLang)) {
          $message .= 'Content-Language: '.$this->headerContentLang.PHP_EOL;
        }
        $message .= 'Content-Disposition: inline'.PHP_EOL;
        $message .= PHP_EOL;
        $message .= $this->encode_body($this->message_txt_html).PHP_EOL;
        //=================================
        // ATTACHED IMAGES ?
        if ($this->has_inline_images()) {
          $message .= $this->generate_inline_images();
        }
        //=================================
        // BOUNDARY
        $message .= '--'.$this->getBoundary('multipart/related')    .'--'.PHP_EOL.PHP_EOL; // End
        $message .= '--'.$this->getBoundary('multipart/alternative').'--'.PHP_EOL;         // End
        #################################################
      }
      else {
        #################################################
        // only text/plain
        if (!empty($this->headerContentLang)) {
          $headers[] = 'Content-Language: '.$this->headerContentLang;
        }
        $headers[] = 'Content-type: text/plain; charset=utf-8';
        $headers[] = 'Content-Transfer-Encoding: '.$this->message_encoding;
        $message   = $this->encode_body(trim($this->message_txt_plain));
        #################################################
      }
    }
    elseif (!empty($this->message_txt_html)) {
      if ($this->has_inline_images()) {
        #################################################
        // text/html + multipart/related (images)
        $headers[] = 'Content-Type: multipart/related;';
        $headers[] = "\t boundary=\"".$this->getBoundary('multipart/related').'"';
        //=================================
        // HTML PART
        $message .= '--'.$this->getBoundary('multipart/related').PHP_EOL;
        $message .= 'Content-Type: text/html; charset=utf-8'.PHP_EOL;
        $message .= 'Content-Transfer-Encoding: '.$this->message_encoding.PHP_EOL;
        if (!empty($this->headerContentLang)) {
          $message .= 'Content-Language: '.$this->headerContentLang.PHP_EOL;
        }
        $message .= 'Content-Disposition: inline'.PHP_EOL;
        $message .= PHP_EOL;
        $message .= $this->encode_body($this->message_txt_html).PHP_EOL;
        //=================================
        // ATTACH IMAGES
        $message .= $this->generate_inline_images();
        //=================================
        // BOUNDARY
        $message .= '--'.$this->getBoundary('multipart/related').'--'.PHP_EOL; // End
        #################################################
      }
      else {
        #################################################
        // only text/html
        if (!empty($this->headerContentLang)) {
          $headers[] = 'Content-Language: '.$this->headerContentLang;
        }
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'Content-Transfer-Encoding: '.$this->message_encoding;
        $message   = $this->encode_body(trim($this->message_txt_html));
        #################################################
      }
    }
    else {
      throw new Exception('You need to give me some text');
    }

    $headers = implode(PHP_EOL, $headers).PHP_EOL;

    if (empty($this->return_path)) {
      $this->return_path = $this->from_mail;
    }

    $res = mail($to, $subject, $message, $headers, '-f'.$this->return_path);
    if ($res !== false) {
      return $this->message_id; // On success returns message ID.
    }
    return false;
  }

  //------------------------------------------------------------------

  private function generate_inline_images() {
    $str = '';
    foreach ($this->inline_images as $key => $val) {
      $str .= '--'.$this->getBoundary('multipart/related').PHP_EOL;
      $str .= 'Content-ID: <'.$val['content-id'].'>'.PHP_EOL;
      $str .= 'Content-Type: image/'.$val['file-extension'].'; name="'.$val['original-filename'].'"'.PHP_EOL;
      $str .= 'Content-Length: '.$val['file-size'].PHP_EOL;
      $str .= 'Content-Transfer-Encoding: base64'.PHP_EOL;
      $str .= 'Content-Disposition: inline; filename="'.$val['original-filename'].'";'.PHP_EOL;
      $str .= PHP_EOL;
      $str .= chunk_split($val['base64-data']).PHP_EOL;
    }
    return $str;
  }

  //------------------------------------------------------------------

  public function processImg($filename, $key) {
    if (empty($this->hostName)) {
      throw new Exception('Property hostName must be defined prior to calling this method');
    }
    if (!file_exists($filename)) {
      throw new Exception('Could not read/find image "'.$filename.'"');
    }
    $hash = substr(strtoupper(sha1($filename . microtime(true))), 0, 10);
    $extension = $this->file_extension($filename);
    $cid = $hash.'@'.$this->hostName;
    //---
    $this->inline_images[$key]['original-filename'] = $hash.'.'.$extension;
    $this->inline_images[$key]['file-extension']    = $extension;
    $this->inline_images[$key]['file-size']         = filesize($filename);
    $this->inline_images[$key]['base64-data']       = base64_encode(file_get_contents($filename));
    $this->inline_images[$key]['content-id']        = $cid;
    //---
    return $cid;
  }

  //------------------------------------------------------------------

  private function has_inline_images() {
    if (empty($this->inline_images) || !is_array($this->inline_images)) {
      return false;
    }
    foreach ($this->inline_images as $key => $val) {
      if (!empty($val['base64-data'])) {
        return true;
      }
    }
    return false;
  }

  //------------------------------------------------------------------

  private function getBoundary($key) {
    if (empty($this->rbstr)) {
      $this->rbstr = strtoupper(sha1(microtime(true)));
    }
    if (empty($this->boundary[$key])) {
      $this->boundary[$key] = '__'.strtoupper(substr(sha1($key . microtime(true)), 0, 8)).':'.$this->rbstr.'__';
    }
    return $this->boundary[$key]; // __C58AEBC1:CB3550AA1181777D6309216788001816598D0F25__
  }

  //------------------------------------------------------------------

  private function header_msg_id() {
    if (empty($this->hostName)) {
      throw new Exception('Undefined property hostName');
    }
    $a = str_replace('.', '', microtime(true));
    $a = strtoupper(base_convert(dechex($a), 16, 36));
    $b = strtoupper(base_convert(bin2hex(random_bytes(8)), 16, 36));
    $this->message_id = $a.'.'.$b.'@'.$this->hostName;
    return 'Message-Id: <'.$this->message_id.'>';
  }

  //------------------------------------------------------------------

  private function header_date() {
    return 'Date: '.date('D, j M Y H:i:s O (T)');
  }

  //------------------------------------------------------------------

  private function header_from() {
    $this->from_name = $this->sanitize_name($this->from_name);
    return $this->encode_header('From', $this->from_name, '<'.$this->from_mail.'>');
  }

  //------------------------------------------------------------------
  /**
   * Some characters in the name string would cause problems.
   */
  private function sanitize_name($str) {
    $str = preg_replace('/:+/', ' ', $str);
    $str = preg_replace('/\s+/', ' ', $str);
    return trim($str);
  }

  //------------------------------------------------------------------

  private function encode_header($name, $str, $append = '') {
    if ($this->is_multibyte_string($str)) {
      $str = $this->encode_mimestr($str);
    }
    return trim($name.': '.$str.' '.$append, ' :');
  }

  //------------------------------------------------------------------

  private function encode_mimestr($str) {
    $preferences = array(
      "input-charset"      => 'utf-8',
      "output-charset"     => 'utf-8',
      "line-length"        => 999,
      "line-break-chars"   => '',
      "scheme"             => "B"
    );
    $enc = iconv_mime_encode('XXX', $str, $preferences);
    return $this->endexplode(': ', $enc);
  }

  //------------------------------------------------------------------

  private function is_multibyte_string($str) {
    return iconv_strlen($str, 'utf-8') < strlen($str);
  }

  //------------------------------------------------------------------

  private function set_encoding() {
    if (!empty($this->message_encoding) && in_array($this->message_encoding, array('quoted-printable', 'base64'))) {
      return;
    }
    $this->message_encoding = 'base64';
  }

  //------------------------------------------------------------------

  private function encode_body($str) {
    if ($this->message_encoding == 'quoted-printable') {
      return quoted_printable_encode($str).PHP_EOL;
    }
    return chunk_split(base64_encode($str));
  }

  //------------------------------------------------------------------

  private function endexplode($glue, $str) {
    if (strpos($str, $glue) === false) {
      return $str;
    }
    $str = explode($glue, $str);
    $str = end($str);
    return $str;
  }

  //------------------------------------------------------------------

  private function file_extension($str) {
    if (strpos($str, '.') === false) {
      throw new Exception('File name has no extension');
    }
    $str = strrchr($str, '.');
    $str = substr($str, 1);
    $str = strtolower($str);
    return $str;
  }

  //------------------------------------------------------------------
}


