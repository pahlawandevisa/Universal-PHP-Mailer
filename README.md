# Universal-PHP-Mailer
Simple but powerful wrapper for the PHP mail() function, capable of sending anything. You can use it to send very simple mail and even some very complex. Just give it whatever content and fire it off. It configures itself.

##Non-multipart Mail:
- only one `attachment`
- only one `text/plain`
- only one `text/html`

##Multipart Mail:
- two or more `attachment`
- `text/plain` + `text/html`
- `text/html`  + `inline images`
- `text/plain` + `text/html` + `inline images`
- `text/plain` + `text/html` + `inline images` + one or more `attachment`



### Send `text/plain`:
```php
require 'universalphpmailer.class.php';

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

### Send `text/html`:
```php
require 'universalphpmailer.class.php';

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

### Send `text/html` + `inline images`:
```php
require 'universalphpmailer.class.php';

$mailor = new universalPHPmailer;

$mailor->toName    = 'John Smith';
$mailor->toEmail   = 'john.smith@udwiwobg';
$mailor->subject   = 'Lorem Ipsum';
$mailor->fromName  = 'James Jones';
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$cidA = $mailor->addInlineImage('/some/path/imageA.jpg'); # This gives us the CID
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

### Send `text/plain` + `text/html` + `inline images`:
```php
require 'universalphpmailer.class.php';

$mailor = new universalPHPmailer;

$mailor->toName    = 'John Smith';
$mailor->toEmail   = 'john.smith@udwiwobg';
$mailor->subject   = 'Lorem Ipsum';
$mailor->fromName  = 'James Jones';
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$cidA = $mailor->addInlineImage('/some/path/imageA.jpg'); # This gives us the CID
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

### Send `text/plain` + `text/html` + `inline images` + `attachment`:
```php
require 'universalphpmailer.class.php';

$mailor = new universalPHPmailer;

$mailor->toName    = 'John Smith';
$mailor->toEmail   = 'john.smith@udwiwobg';
$mailor->subject   = 'Lorem Ipsum';
$mailor->fromName  = 'James Jones';
$mailor->fromEmail = 'james.jones@udwiwobg';
$mailor->hostName  = 'zarscwfo';

$cidA = $mailor->addInlineImage('/some/path/imageA.jpg'); # This gives us the CID
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
