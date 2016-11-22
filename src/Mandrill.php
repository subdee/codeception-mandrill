<?php
namespace Codeception\Module;

use Codeception\Module;
use Codeception\Util\Debug;

/**
 * This module allows you to test emails sent to Mandrill
 *
 * ## Config
 *
 * * api_key: `string`, default `` - Your Mandrill test API key.
 */
class Mandrill extends Module
{
    /**
     * @var \Mandrill
     */
    protected $client;

    /**
     * @var array
     */
    protected $config = ['api_key' => null];

    /**
     * @var array
     */
    protected $requiredFields = ['api_key'];

    /**
     * @return void
     */
    public function _initialize()
    {
        $this->client = new \Mandrill($this->config['api_key']);
    }

    /**
     * @return string
     */
    protected function getLatestEmailId()
    {
        $messages = $this->client->messages->search();
        return $messages[0]['_id'];
    }

    /**
     * @param null $subject
     * @param int $minutes
     * @return string|null
     */
    protected function searchForEmail($subject = null, $minutes = 1)
    {
        $query = '';
        $now = time();
        if ($subject) {
            $query .= 'subject:"*' . $subject . '*" ';
        }
        $query .= 'ts:[' . $now - ($minutes * 60) . ' TO ' . $now . ']';
        Debug::debug('Searching mandrill with query string: ' . $query);
        $messages = $this->client->messages->search($query);
        if (isset($messages[0])) {
            return $messages[0]['_id'];
        }

        return null;
    }

    /**
     * @param string $id
     * @return string
     */
    protected function getEmailContent($id)
    {
        Debug::debug('Getting email content for email id: ' . $id);
        $content = $this->client->messages->content($id);
        return $content['html'];
    }

    /**
     * @param string $expected
     */
    public function seeInLatestEmailBody($expected)
    {
        $content = $this->getEmailContent($this->getLatestEmailId());
        $this->assertContains($expected, $content);
    }

    /**
     * @param string $expected
     * @param string $subject
     * @param int $lastMinutes
     */
    public function seeInEmailBody($expected, $subject, $lastMinutes = 1)
    {
        $content = $this->getEmailContent($this->searchForEmail($subject, $lastMinutes));
        $this->assertContains($expected, $content);
    }
}