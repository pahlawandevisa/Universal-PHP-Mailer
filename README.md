# Universal PHP Mailer

[![Downloads](https://img.shields.io/packagist/dt/peterkahl/universal-php-mailer.svg)](https://packagist.org/packages/peterkahl/universal-php-mailer)
[![License](http://img.shields.io/:license-apache-blue.svg)](http://www.apache.org/licenses/LICENSE-2.0.html)
[![If this project has business value for you then don't hesitate to support me with a small donation.](https://img.shields.io/badge/Donations-via%20Paypal-blue.svg)](https://www.paypal.me/PeterK93)

Easy to use yet powerful PHP mailer capable of sending any type of content including plain text, HTML, inline images, and any kind and any number of attachments, hence *Universal*.

### Self-configuring
If you have ever tried composing multipart MIME mail, you will know how cumbersome it can be. With *Universal PHP Mailer* you will no longer struggle figuring out where you put "this and that" content and within which boundary and in which order. *Universal PHP Mailer* is self-configuring. Just give it whatever content you have and fire it off. It will compose the correct MIME-compliant mail string with whatever parts, multiparts and boundaries are necessary.

### Efficiency for High Volume (Bulk) Mailing
When using the `SMTP` method, the mailer reuses the same socket connection for sending multiple messages, thus achieving better efficiency than the `mail()` function method.

### Handles Any Kind of Content

If we want to send a mail that consists of only 1 (category of) content, it is a *Non-multipart* mail. Non-multipart mail use cases--

| Case | text/plain | text/html | inline image | attachment |
| :---:|:----------:| :--------:| :-----------:| :---------:|
| 1    |      -     |     -     |      -       |     1      |
| 2    |      1     |     -     |      -       |     -      |
| 3    |      -     |     1     |      -       |     -      |

Once we need to send a mail with 2 or more contents (regardless of category), we're talking about *Multipart* mail. Multipart mail use cases--

| Case | text/plain | text/html | inline image | attachment |
| :---:|:----------:| :--------:| :-----------:| :---------:|
| 4    |      -     |     -     |      -       |     ≥ 2    |
| 5    |      -     |     1     |      ≥ 0     |     ≥ 1    |
| 6    |      -     |     1     |      ≥ 1     |     ≥ 0    |
| 7    |      1     |     1     |      ≥ 0     |     ≥ 0    |


## Considerations

This package applies some measures in order to mitigate malicious abuse attempts. Despite this, it is advised that you always validate and/or sanitise all user input.

- You should validate and santise all email addresses.
- You should filter out (sanitise) line breaks `\n` from header strings.

### Email Address Format

This package requires that email addresses be compliant with RFC5322 (i.e. contain only printable ASCII characters). If you intend to use IDN and Unicode character email addresses, you must convert them to ASCII before applying them to this package.

### Email Address Validation

This package uses simple email address validation. It is advised that you validate all email addresses before applying them to this package.

## Usage Examples

#### The Basics
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->SMTPserver      = 'smtp.gmail.com';
$mailor->SMTPport        = 587;
$mailor->SMTPusername    = 'example@gmail.com';
$mailor->SMTPpassword    = '************************';
$mailor->forceSMTPsecure = true;
$mailor->CAfile          = '/path/to/cacert.pem';
$mailor->SMTPhelo        = 'www.myamazingwebsite.com';
$mailor->CacheDir        = '/path/to/cache_dir';
$mailor->hostName        = 'myamazingwebsite.com';

// Subject. What's this about.
$mailor->Subject = 'Vibrant growth on epic scale';

// Who the sender is.
$mailor->SenderFrom = array('james.jones@hotmai1.con' => 'James Jones');

// Only 1 To: recipient.
$mailor->RecipientTo = array(
                            'john.smith@hotmai1.con' => 'John Smith'
                            );

// Or multiple To: recipients.
$mailor->RecipientTo = array(
                            'john.smith@hotmai1.con' => 'John Smith',
                            'paul.smith@hotmai1.con' => 'Paul',
                            'jane@hotmai1.con'       => '',
                            );

// You may have some Cc: recipients.
$mailor->RecipientCc = array(
                            'paul.smith@hotmai1.con' => 'Paul',
                            'jane@hotmai1.con'       => '',
                            );

// You may have 1 or more Bcc: recipients.
$mailor->RecipientBcc = array(
                            'root@hotmai1.con'       => 'Sean Connolly',
                            );

```

#### Sending `text/plain` mail:

```php
$mailor->textPlain = 'Hi John,

I am testing this mailer from GitHub. Please let me know if you\'ve received this message.

Best regards,
J.J.';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```


#### Sending `text/html` mail:
```php
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

#### Sending `text/html` + `inline images` mail:
```php
// First add the images. This gives us content ID strings.
$cidA = $mailor->addInlineImage('/some/path/imageA.jpg');
$cidB = $mailor->addInlineImage('/some/path/imageB.png');

// The below string has the <IMG> tag with respective content IDs.
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

#### Sending `text/plain` + `text/html` + `inline images` mail:
```php
// First add the images. This gives us content ID strings.
$cidA = $mailor->addInlineImage('/some/path/imageA.jpg');
$cidB = $mailor->addInlineImage('/some/path/imageB.png');

$mailor->textPlain = 'Hi John,

I am testing this mailer from GitHub. Please let me know if you\'ve received this message.

Best regards,
J.J.';

// The below string has the <IMG> tag with respective content IDs.
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

#### Sending `text/plain` + `text/html` + `inline images` + `attachment` mail:

```php
// First add the images. This gives us content ID strings.
$cidA = $mailor->addInlineImage('/some/path/imageA.jpg');
$cidB = $mailor->addInlineImage('/some/path/imageB.png');

// This is how we add attachments.
$mailor->addAttachment('/some/path/contract.pdf');

$mailor->textPlain = 'Hi John,

I have attached the contract PDF document.

Best regards,
J.J.';

// The below string has the <IMG> tag with respective content IDs.
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

#### Sending `text/html` + `inline images` mail in a loop (high volume) while reusing the socket connection:

```php
// This array has our recipients.
$recipientArr = array(
  0 => array(
            'name'  => 'John Doe', # Display name per RFC5322
            'email' => 'j.doe@hotmai1.con',
  ),
  1 => array(
            'name'  => '"Jane V. Wise" (smart cookie)', # Display name and comment per RFC5322
            'email' => 'j.wise@hotmai1.con',
  ),
  2 => array(
            'name'  => '"Robert W. Simth"', # Display name per RFC5322
            'email' => 'robert.smith@hotmai1.con',
  ),
);

# These 2 images are same for all recipients (as are the content ID strings)
$cidA = $mailor->addInlineImage('/some/path/imageA.jpg');
$cidB = $mailor->addInlineImage('/some/path/imageB.png');

# The loop
foreach ($recipientArr as $recipient) {

  $mailor->RecipientTo = array(
                              $recipient['email'] => $recipient['name']
                              );

  # If you want image CID's to be unique message to message, you should
  # unset these properties and add the attachments inside the loop!
  //$mailor->unsetInlineImages();
  //$mailor->unsetAttachments();
  //$cidA = $mailor->addInlineImage('/some/path/imageA.jpg');
  //$cidB = $mailor->addInlineImage('/some/path/imageB.png');

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

#### Formatting of Display Name

If you want to be sure that display name in headers is per RFC5322, use the method `formatDisplayName`. This will avoid some undesired behaviour.

```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->SMTPserver      = 'smtp.gmail.com';
$mailor->SMTPport        = 587;
$mailor->SMTPusername    = 'example@gmail.com';
$mailor->SMTPpassword    = '************************';
$mailor->forceSMTPsecure = true;
$mailor->CAfile          = '/path/to/cacert.pem';
$mailor->SMTPhelo        = 'www.myamazingwebsite.com';
$mailor->CacheDir        = '/path/to/cache_dir';
$mailor->hostName        = 'myamazingwebsite.com';

$mailor->RecipientTo = array(
                            'mao@backintime.sample' => $mailor->formatDisplayName('Mao "Chairman" 毛泽东')
                            );

$mailor->Subject    = '請問';
$mailor->SenderFrom = array('james.jones@hotmai1.con' => $mailor->formatDisplayName('James J. Jones'));

$mailor->textPlain = 'Hi 泽东,

Is there life in the afterworld?

Best regards,
J.J.J.';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```
~
## Acknowledgements

Peter Kahl had written much of the SMTP-related methods of this package as a result of inspiration from the following class and extends his thanks to the authors thereof:

> PHPMailer RFC821 SMTP email transport class.
> Implements RFC 821 SMTP commands and provides some utility methods for sending mail to an SMTP server.
> @package PHPMailer
> @author Chris Ryan
> @author Marcus Bointon <phpmailer@synchromedia.co.uk>
> <https://github.com/PHPMailer/PHPMailer/blob/master/class.smtp.php>
