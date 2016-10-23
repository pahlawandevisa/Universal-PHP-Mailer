# Universal-PHP-Mailer
Generates MIME compliant mail, be it just text/plain, text/html, multipart/...anything and pretty much any combination that makes sense, including attachment, or only attachments.

## Send text/plain:
```php
<?php

require 'universalphpmailer.class.php';

$mailor = new universalPHPmailer;

$mailor->sendto_name       = 'John Smith';
$mailor->sendto_mail       = 'john.smith@somedomain';
$mailor->message_subject   = 'Lorem Ipsum';
$mailor->from_name         = 'James Jones';
$mailor->from_mail         = 'james.jones@anotherdomain';
$mailor->hostName          = 'myserver.tld';

$mailor->message_txt_plain = 'Hi John,

I am testing this mailer from GitHub. Please let me know if you\'ve received this message.

Best regards,
J.J.';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

## Send text/html:
```php
<?php

require 'universalphpmailer.class.php';

$mailor = new universalPHPmailer;

$mailor->sendto_name       = 'John Smith';
$mailor->sendto_mail       = 'john.smith@somedomain';
$mailor->message_subject   = 'Lorem Ipsum';
$mailor->from_name         = 'James Jones';
$mailor->from_mail         = 'james.jones@anotherdomain';
$mailor->hostName          = 'myserver.tld';

$mailor->message_txt_html  = '<body>
  <p>Hi John,</p>
  <p>I am testing this mailer from GitHub. Please let me know if you\'ve received this message.</p>
  <p>Best regards,<br>J.J.</p>
</body>';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

## Send text/html + inline images:
```php
<?php

require 'universalphpmailer.class.php';

$mailor = new universalPHPmailer;

$mailor->sendto_name       = 'John Smith';
$mailor->sendto_mail       = 'john.smith@somedomain';
$mailor->message_subject   = 'Lorem Ipsum';
$mailor->from_name         = 'James Jones';
$mailor->from_mail         = 'james.jones@anotherdomain';
$mailor->hostName          = 'myserver.tld';

$imageKey = 0;
$imagePath = '/some/path/imageA.jpg';
$tag[$imageKey] = $mailor->processImg($imagePath, $imageKey); // This gives us the CID

$imageKey = 1;
$imagePath = '/some/path/imageB.png';
$tag[$imageKey] = $mailor->processImg($imagePath, $imageKey); // This gives us the CID

$mailor->message_txt_html  = '<body>
  <p>Hi John,</p>
  <p>I am testing this mailer from GitHub. Please let me know if you\'ve received this message.</p>
  <p>Best regards,<br>J.J.</p>
  <div><img src="cid:'.$tag[0].'" width="200" height="100"></div>
  <div><img src="cid:'.$tag[1].'" width="200" height="50"></div>
</body>';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```

## Send text/plain + text/html + inline images:
```php
<?php

require 'universalphpmailer.class.php';

$mailor = new universalPHPmailer;

$mailor->sendto_name       = 'John Smith';
$mailor->sendto_mail       = 'john.smith@somedomain';
$mailor->message_subject   = 'Lorem Ipsum';
$mailor->from_name         = 'James Jones';
$mailor->from_mail         = 'james.jones@anotherdomain';
$mailor->hostName          = 'myserver.tld';

$imageKey = 0;
$imagePath = '/some/path/imageA.jpg';
$tag[$imageKey] = $mailor->processImg($imagePath, $imageKey); // This gives us the CID

$imageKey = 1;
$imagePath = '/some/path/imageB.png';
$tag[$imageKey] = $mailor->processImg($imagePath, $imageKey); // This gives us the CID

$mailor->message_txt_plain = 'Hi John,

I am testing this mailer from GitHub. Please let me know if you\'ve received this message.

Best regards,
J.J.';

$mailor->message_txt_html  = '<body>
  <p>Hi John,</p>
  <p>I am testing this mailer from GitHub. Please let me know if you\'ve received this message.</p>
  <p>Best regards,<br>J.J.</p>
  <div><img src="cid:'.$tag[0].'" width="200" height="100"></div>
  <div><img src="cid:'.$tag[1].'" width="200" height="50"></div>
</body>';

$msgID = $mailor->sendMessage();

if (!empty($msgID)) {
  echo 'Successfully sent message ID ..... '. $msgID;
}

```
