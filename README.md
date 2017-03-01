#Universal PHP Mailer
Easy to use yet powerful PHP mailer, capable of sending any type of content including plain text, html, inline images, and any kind and any number of attachments, hence `Universal`.

##Intelligent
If you have ever tried composing multipart MIME mail, you will know how cumbersome it can be. With **Universal PHP Mailer** you will no longer struggle figuring out where you put "this and that" content and within which boundary and in which order. **Universal PHP Mailer** is intelligent. Just give it whatever content you have and fire it off. It will **automatically configure itself** to compose the correct MIME mail string with whatever parts, multiparts and boundaries are appropriate.

##Efficiency for High Volume Mailing (Bulk)
When using the `SMTP` method, the mailer reuses the same socket connection for sending multiple messages, thus achieving better efficiency than the `mail()` function method.

##These are the possible combinations of content this mailer can handle (that's virtually anything!):

###Non-multipart mail:
- only one `attachment`
- only one `text/plain`
- only one `text/html`

###Multipart mail:
- two or more `attachment`
- `text/plain` + `text/html` + zero or more `attachment`
- `text/html`  + `inline images` + zero or more `attachment`
- `text/plain` + `text/html` + `inline images` + zero or more `attachment`

---

##Security

This package applies some measures in order to mitigate malicious abuse attempts. Despite this, it is advised that you always validate and/or sanitise all user input.

You should validate and santise all email addresses.

You should filter out (sanitise) line breaks (`\n`) from header strings.

---

##Email Address Format

This package requires that email addresses be compliant with RFC5322, i.e. contain only printable ASCII characters. If you intend to use IDN and Unicode character email addresses, you must convert them to ASCII before applying them to this package.

---

##Email Address Validation

This package does not validate email addresses. Therefore, you should validate all email addresses before applying them to this package.

---

##Usage

How to send `text/plain`:
```php
use universalphpmailer\universalphpmailer;

$mailor = new universalPHPmailer;

$mailor->toName    = 'John Smith';
$mailor->toEmail   = 'john.smith@udwiwobg';
$mailor->subject   = 'Lorem Ipsum';
$mailor->fromName  = 'James Jones';
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$mailor->textPlain = 'Hi John,

I am testing this mailer from GitHub. Please let me know if you\'ve received this message.

Best regards,
J.J.';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

---

How to send `text/html`:
```php
use universalphpmailer\universalphpmailer;

$mailor = new universalPHPmailer;

$mailor->toName    = 'John Smith';
$mailor->toEmail   = 'john.smith@udwiwobg';
$mailor->subject   = 'Lorem Ipsum';
$mailor->fromName  = 'James Jones';
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$mailor->textHtml  = '<body>
  <p>Hi John,</p>
  <p>I am testing this mailer from GitHub. Please let me know if you\'ve received this message.</p>
  <p>Best regards,<br>J.J.</p>
</body>';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

---

How to send `text/html` + `inline images`:
```php
use universalphpmailer\universalphpmailer;

$mailor = new universalPHPmailer;

$mailor->toName    = 'John Smith';
$mailor->toEmail   = 'john.smith@udwiwobg';
$mailor->subject   = 'Lorem Ipsum';
$mailor->fromName  = 'James Jones';
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$cidA = $mailor->addInlineImage('/some/path/imageA.jpg'); # This gives us the content ID string
$cidB = $mailor->addInlineImage('/some/path/imageB.png');

$mailor->textHtml  = '<body>
  <p>Hi John,</p>
  <p>I am testing this mailer from GitHub. Please let me know if you\'ve received this message.</p>
  <p>Best regards,<br>J.J.</p>
  <div><img src="cid:'.$cidA.'" width="200" height="100" alt="Image A"></div>
  <div><img src="cid:'.$cidB.'" width="200" height="100" alt="Image B"></div>
</body>';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

---

How to send `text/plain` + `text/html` + `inline images`:
```php
use universalphpmailer\universalphpmailer;

$mailor = new universalPHPmailer;

$mailor->toName    = 'John Smith';
$mailor->toEmail   = 'john.smith@udwiwobg';
$mailor->subject   = 'Lorem Ipsum';
$mailor->fromName  = 'James Jones';
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$cidA = $mailor->addInlineImage('/some/path/imageA.jpg'); # This gives us the content ID string
$cidB = $mailor->addInlineImage('/some/path/imageB.png');

$mailor->textPlain = 'Hi John,

I am testing this mailer from GitHub. Please let me know if you\'ve received this message.

Best regards,
J.J.';

$mailor->textHtml  = '<body>
  <p>Hi John,</p>
  <p>I am testing this mailer from GitHub. Please let me know if you\'ve received this message.</p>
  <p>Best regards,<br>J.J.</p>
  <div><img src="cid:'.$cidA.'" width="200" height="100" alt="Image A"></div>
  <div><img src="cid:'.$cidB.'" width="200" height="100" alt="Image B"></div>
</body>';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

---

How to send `text/plain` + `text/html` + `inline images` + `attachment`:
```php
use universalphpmailer\universalphpmailer;

$mailor = new universalPHPmailer;

$mailor->toName    = 'John Smith';
$mailor->toEmail   = 'john.smith@udwiwobg';
$mailor->subject   = 'Lorem Ipsum';
$mailor->fromName  = 'James Jones';
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$cidA = $mailor->addInlineImage('/some/path/imageA.jpg'); # This gives us the content ID string
$cidB = $mailor->addInlineImage('/some/path/imageB.png');

$mailor->addAttachment('/some/path/contract.pdf');

$mailor->textPlain = 'Hi John,

I have attached the contract PDF document.

Best regards,
J.J.';

$mailor->textHtml  = '<body>
  <p>Hi John,</p>
  <p>I have attached the contract PDF document.</p>
  <p>Best regards,<br>J.J.</p>
  <div><img src="cid:'.$cidA.'" width="200" height="100" alt="Image A"></div>
  <div><img src="cid:'.$cidB.'" width="200" height="100" alt="Image B"></div>
</body>';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

---

How to send `text/html` + `inline images` in a loop (multiple recipients, high volume) while reusing the socket connection:
```php
use universalphpmailer\universalphpmailer;

# Instatiate outside of the loop
$mailor = new universalPHPmailer;

$mailor->subject   = 'Weekly best deal newsletter';
$mailor->fromName  = '"Mail Robot (don\'t reply)"'; # Display name per RFC5322
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$recipientArr = array(
  0 => array(
            'name'  => 'John Doe', # Display name per RFC5322
            'email' => 'j.doe@udwiwobg',
  ),
  1 => array(
            'name'  => '"Jane V. Wise" (smart cookie)', # Display name and comment per RFC5322
            'email' => 'j.wise@udwiwobg',
  ),
  2 => array(
            'name'  => '"Robert W. Simth"', # Display name per RFC5322
            'email' => 'robert.smith@udwiwobg',
  ),
);

# These 2 images are same for all recipients
$cidA = $mailor->addInlineImage('/some/path/imageA.jpg'); # This gives us the content ID string
$cidB = $mailor->addInlineImage('/some/path/imageB.png');

# The loop
foreach ($recipientArr as $recipient) {

  $mailor->toName    = $recipient['name'];
  $mailor->toEmail   = $recipient['email'];

  $mailor->textHtml  = '<body>
    <p>Hi '.$recipient['name'].',</p>
    <p>You are receiving this newsletter because you have subscribed.</p>
    <p>Best regards,<br>'.$mailor->fromName.'</p>
    <div><img src="cid:'.$cidA.'" width="200" height="100" alt="Image A"></div>
    <div><img src="cid:'.$cidB.'" width="200" height="100" alt="Image B"></div>
  </body>';

  $msgID = $mailor->sendMessage();

  if (!empty($msgID)) {
    echo 'Successfully sent message ID ..... '. $msgID .' .... To: '. $recipient['email'] . PHP_EOL;
  }
}

```

---

If you want to be sure that display name in headers is per RFC5322, use the method `formatDisplayName`. This will avoid some undesired behaviour.

(Here I am using an example of `text/plain` mail):
```php
use universalphpmailer\universalphpmailer;

$mailor = new universalPHPmailer;

$mailor->toName    = $mailor->formatDisplayName('Mao "Chairman" 毛泽东');
$mailor->toEmail   = 'mao@backintime.sample';
$mailor->subject   = '請問';
$mailor->fromName  = $mailor->formatDisplayName('James J. Jones');
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'host.name.sample';

$mailor->textPlain = 'Hi 泽东,

Is there life in the afterworld?

Best regards,
J.J.J.';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

---

#Acknowledgements

Peter Kahl had written much of the SMTP-related methods of this package as a result of inspiration from the following class and extends his thanks to the authors thereof:

> PHPMailer RFC821 SMTP email transport class.
>
> Implements RFC 821 SMTP commands and provides some utility methods for sending mail to an SMTP server.
>
> @package PHPMailer
>
> @author Chris Ryan
>
> @author Marcus Bointon <phpmailer@synchromedia.co.uk>
>
> <https://github.com/PHPMailer/PHPMailer/blob/master/class.smtp.php>
