<?php

class MailNotify
{
  protected $mailer;

  public function __construct($mailer)
  {
    $this->mailer = $mailer;
  }

  /**
   * Get proper message and replace placeholders with actual values
   * @param string $type
   * @param array $config
   * @throws Exception in case type is not defined
   */
  public function getMessage($type, $config)
  {
    $types = sfConfig::get('app_teo_mail_notify_types');

    if(!isset($types[$type]))
    {
      throw new Exception("Notify {$type} is not defined");
    }

    $class = $types[$type];
    $messageTemplate = new $class();

    $message = $messageTemplate->getMessage();

    foreach($config as $key => $value)
    {
      if(!in_array($key, array('from', 'to')))
      {
        $message['subject'] = str_replace('%'.$key.'%', $value, $message['subject']);
        $message['body'] = str_replace('%'.$key.'%', $value, $message['body']);
      }
    }

    // clean placeholders
    // $message['subject'] = preg_replace('/%[a-z\_]+%/', '', $message['subject']);
    // $message['body'] = preg_replace('/%[a-z\_]+%/', '', $message['body']);

    return $message;
  }

  public function notify($type, $config)
  {
    $message = $this->getMessage($type, $config);

    return $this->mailer->composeAndSend(
      $config['from'],
      $config['to'],
      $message['subject'],
      $message['body']
    );
  }
}
