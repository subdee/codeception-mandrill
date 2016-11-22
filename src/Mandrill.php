<?php
namespace Codeception\Module;

use Codeception\Module;

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
     * @param $id string
     * @return string
     */
    protected function getEmailContent($id)
    {
        $content = $this->client->messages->content($id);
        return $content['html'];
    }

    /**
     * @param $expected string
     */
    public function seeInEmailBody($expected)
    {
        $content = $this->getEmailContent($this->getLatestEmailId());
        $this->assertContains($expected, $content);
    }
}