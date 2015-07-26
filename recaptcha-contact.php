<?php namespace Grav\Plugin;

use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use Grav\Common\Language\Language;

class   ReCaptchaContactPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->enable([
            'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables'   => ['onTwigSiteVariables', 0],
            'onPageInitialized'     => ['onPageInitialized', 0]
        ]);
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onTwigSiteVariables()
    {
        if ($this->grav['config']->get('plugins.recaptcha-contact.enabled')) {
            $this->grav['assets']->addCss('plugin://recaptcha-contact/assets/css/style.css');
            $this->grav['assets']->addJs('plugin://recaptcha-contact/assets/js/script.js');
        }
    }
    
    public function onPageInitialized()
    {      
        $language = $this->grav['language'];         
        $message_success = $language->translate(['RECAPTCHA-CONTACT.MESSAGES.SUCCESS'], null, true);
        $message_error = $language->translate(['RECAPTCHA-CONTACT.MESSAGES.ERROR'], null, true);
        $message_fail = $language->translate(['RECAPTCHA-CONTACT.MESSAGES.FAIL'], null, true);  
        
        $this->mergePluginConfig($this->grav['page']);

        $config = $this->grav['config'];

        $options = $config->get('plugins.recaptcha-contact');

        if ($options['enabled']) {
            $page   = $this->grav['page'];
            $twig   = $this->grav['twig'];
            $uri    = $this->grav['uri'];

            if (false === $uri->param('send')) {
                if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    if (false === $this->validateFormData()) {
                        $this->grav->redirect($page->slug() . '/send:error');
                    } else {
                        if (false === $this->sendEmail()) {
                            $this->grav->redirect($page->slug() . '/send:fail');
                        } else {
                            $this->grav->redirect($page->slug() . '/send:success');
                        }
                    }
                } else {
                    $old_content = $page->content();

                    $template = 'form.html.twig';
                    $data = [
                        'recaptcha-contact' => $options,
                        'page' => $page
                    ];

                    $page->content($old_content . $twig->twig()->render($template, $data));
                }
            } else {
                switch ($uri->param('send')) {
                    case 'success':
                        $page->content($message_success);
                    break;

                    case 'error':
                        $page->content($message_error);
                    break;

                    case 'fail':
                        $page->content($message_fail);
                    break;

                    default:
                    break;
                }
            }
        }
    }

    protected function validateFormData()
    {
        $form_data = $this->filterFormData($_POST);

        $name     = $form_data['name'];
        $email    = $form_data['email'];
        $message  = $form_data['message'];

        $antispam = $form_data['antispam'];
        
        $grecaptcha = $form_data['g-recaptcha-response'];
        $secretkey = $this->grav['config']->get('plugins.recaptcha-contact.grecaptcha_secret');
        if (!empty($grecaptcha)) {
           $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."&response=".$grecaptcha), true);
        }


        return (empty($name) or empty($message) or empty($email) or $antispam or empty($grecaptcha) or $response['success']==false) ? false : true;
    }

    protected function filterFormData($form)
    {
        $defaults = [
            'name'      => '',
            'email'     => '',
            'message'   => '',
            'antispam'  => '',
            'g-recaptcha-response' => ''
        ];

        $data = array_merge($defaults, $form);

        return [
            'name'      => $data['name'],
            'email'     => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
            'message'   => $data['message'],
            'antispam'  => $data['antispam'],
            'g-recaptcha-response' => $data['g-recaptcha-response']
        ];
    }

    protected function sendEmail()
    {
        $form   = $this->filterFormData($_POST);
        $options = $this->grav['config']->get('plugins.recaptcha-contact');

        $language = $this->grav['language']; 
        $recipient  = $language->translate(['RECAPTCHA-CONTACT.RECIPIENT'], null, true); 
        $subject    = $language->translate(['RECAPTCHA-CONTACT.SUBJECT'], null, true);  

        $email_content = "Name: {$form['name']}\n";
        $email_content .= "Email: {$form['email']}\n\n";
        $email_content .= "Message:\n{$form['message']}\n";

        $email_headers = "From: {$form['name']} <{$form['email']}>";

        return (mail($recipient, $subject, $email_content, $email_headers)) ? true : false;
    }

    private function mergePluginConfig( Page $page )
    {
        $defaults = (array)$this->grav['config']->get('plugins.recaptcha-contact');

        if (isset( $page->header()->recaptcha-contact )) {
            if (is_array($page->header()->recaptcha-contact)) {
                $this->grav['config']->set('plugins.recaptcha-contact', array_replace_recursive($defaults, $page->header()->recaptcha-contact));
            } else {
                $this->grav['config']->set('plugins.recaptcha-contact.enabled', $page->header()->recaptcha-contact);
            }
        } else {
            $this->grav['config']->set('plugins.recaptcha-contact.enabled', false);
        }
    }
}