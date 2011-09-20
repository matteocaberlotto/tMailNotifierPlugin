<?php

class SampleTemplate implements TemplateInterface
{
  public function getMessage()
  {
    $subject = <<<END
Registration successfull for %user%
END;
    $body = <<<END
Dear %user%,
Thank you for your registration,
your account will be activated after you visit the following url
%activation_url%.
Remember you can contact us at %admin_mail%
Best regards,

The administrator
END;
    return array('subject' => $subject, 'body' => $body);
  }
}