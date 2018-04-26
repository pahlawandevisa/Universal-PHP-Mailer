Universal PHP Mailer

[![If this project has business value for you then don't hesitate to support me with a small donation.](https://img.shields.io/badge/Donations-via%20Paypal-blue.svg)](https://www.paypal.me/PeterK93)

Easy to use yet powerful PHP mailer capable of sending any type of content including plain text, html, inline images, and any kind and any number of attachments, hence `Universal`.

## Self-configuring
If you have ever tried composing multipart MIME mail, you will know how cumbersome it can be. With **Universal PHP Mailer** you will no longer struggle figuring out where you put "this and that" content and within which boundary and in which order. **Universal PHP Mailer** is self-configuring. Just give it whatever content you have and fire it off. It will compose the correct MIME-compliant mail string with whatever parts, multiparts and boundaries are necessary.

## Efficiency for High Volume Mailing (Bulk)
When using the `SMTP` method, the mailer reuses the same socket connection for sending multiple messages, thus achieving better efficiency than the `mail()` function method.

## Handles Any Kind of Content

### Non-multipart mail:
- only one `attachment`
- only one `text/plain`
- only one `text/html`

### Multipart mail:
- two or more `attachment`
- `text/plain` + `text/html` + zero or more `attachment`
- `text/html`  + `inline images` + zero or more `attachment`
- `text/plain` + `text/html` + `inline images` + zero or more `attachment`

## Security

This package applies some measures in order to mitigate malicious abuse attempts. Despite this, it is advised that you always validate and/or sanitise all user input.

You should validate and santise all email addresses.

You should filter out (sanitise) line breaks (`\n`) from header strings.

## Email Address Format

This package requires that email addresses be compliant with RFC5322, i.e. contain only printable ASCII characters. If you intend to use IDN and Unicode character email addresses, you must convert them to ASCII before applying them to this package.

## Email Address Validation

This package does not validate email addresses. Therefore, you should validate all email addresses before applying them to this package.

## Usage

How to send `text/plain` (single recipient):
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->RecipientTo = array(
                            'john.smith@udwiwobg' => 'John Smith'
                            );

$mailor->Subject    = 'Lorem Ipsum';
$mailor->SenderFrom = array('james.jones@udwiwobg' => 'James Jones');
$mailor->hostName   = 'zarscwfo';

$mailor->textPlain = 'Hi John,

I am testing this mailer from GitHub. Please let me know if you\'ve received this message.

Best regards,
J.J.';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

How to send `text/plain` (multiple recipients):
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->RecipientTo = array(
                            'john.smith@udwiwobg' => 'John Smith',
                            'paul.smith@udwiwobg' => 'Paul',
                            'jane@udwiwobg'       => '',
                            );

$mailor->Subject    = 'Lorem Ipsum';
$mailor->SenderFrom = array('james.jones@udwiwobg' => 'James Jones');
$mailor->hostName   = 'zarscwfo';

$mailor->textPlain = 'Hello troops,

I am testing this mailer from GitHub. Please let me know if you\'ve received this message.

Best regards,
J.J.';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

How to send `text/html`:
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->RecipientTo = array(
                            'john.smith@udwiwobg' => 'John Smith'
                            );

$mailor->Subject    = 'Lorem Ipsum';
$mailor->SenderFrom = array('james.jones@udwiwobg' => 'James Jones');
$mailor->hostName   = 'zarscwfo';

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

How to send `text/html` + `inline images`:
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->RecipientTo = array(
                            'john.smith@udwiwobg' => 'John Smith'
                            );

$mailor->Subject    = 'Lorem Ipsum';
$mailor->SenderFrom = array('james.jones@udwiwobg' => 'James Jones');
$mailor->hostName   = 'zarscwfo';

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

How to send `text/plain` + `text/html` + `inline images`:
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->RecipientTo = array(
                            'john.smith@udwiwobg' => 'John Smith'
                            );

$mailor->Subject    = 'Lorem Ipsum';
$mailor->SenderFrom = array('james.jones@udwiwobg' => 'James Jones');
$mailor->hostName   = 'zarscwfo';

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

How to send `text/plain` + `text/html` + `inline images` + `attachment`:
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->RecipientTo = array(
                            'john.smith@udwiwobg' => 'John Smith'
                            );

$mailor->Subject    = 'Lorem Ipsum';
$mailor->SenderFrom = array('james.jones@udwiwobg' => 'James Jones');
$mailor->hostName   = 'zarscwfo';

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

How to send `text/html` + `inline images` in a loop (multiple recipients, high volume) while reusing the socket connection:
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

# Instatiate outside of (before) the loop
$mailor = new universalPHPmailer;

$mailor->Subject    = 'Weekly best deal newsletter';
$mailor->SenderFrom = array('james.jones@udwiwobg' => '"Mail Robot (don\'t reply)"'); # Display name per RFC5322
$mailor->hostName   = 'zarscwfo';

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

# These 2 images are same for all recipients (as are the content ID strings)
$cidA = $mailor->addInlineImage('/some/path/imageA.jpg'); # This gives us the content ID string
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

If you want to be sure that display name in headers is per RFC5322, use the method `formatDisplayName`. This will avoid some undesired behaviour.

(Here I am using an example of `text/plain` mail):
```php
use peterkahl\universalPHPmailer\universalPHPmailer;

$mailor = new universalPHPmailer;

$mailor->RecipientTo = array(
                            'mao@backintime.sample' => $mailor->formatDisplayName('Mao "Chairman" 毛泽东')
                            );

$mailor->Subject    = '請問';
$mailor->SenderFrom = array('james.jones@udwiwobg' => $mailor->formatDisplayName('James J. Jones'));
$mailor->hostName   = 'host.name.sample';

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

## Acknowledgements

Peter Kahl had written much of the SMTP-related methods of this package as a result of inspiration from the following class and extends his thanks to the authors thereof:

> PHPMailer RFC821 SMTP email transport class.
> Implements RFC 821 SMTP commands and provides some utility methods for sending mail to an SMTP server.
> @package PHPMailer
> @author Chris Ryan
> @author Marcus Bointon <phpmailer@synchromedia.co.uk>
> <https://github.com/PHPMailer/PHPMailer/blob/master/class.smtp.php>
