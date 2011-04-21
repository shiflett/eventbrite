<?php

class Eventbrite
{

    protected $baseUrl = 'https://www.eventbrite.com/xml/';
    protected $userKey;
    protected $appKey;

    // Set default caching properties.
    protected $cacheDir = '/tmp';
    protected $cacheTimeout = 86400;

    public function __construct($userKey, $appKey)
    {
        $this->userKey = $userKey;
        $this->appKey = $appKey;
    }

    public function cache($dir, $timeout)
    {
        $this->cacheDir = $dir;
        $this->cacheTimeout = $timeout;
    }

    protected function request($url, $file)
    {
        $xml = '';

        if (file_exists($file) && is_readable($file) && (time() - filemtime($file) < $this->cacheTimeout)) {
            $xml = file_get_contents($file);
        } else {
            $xml = file_get_contents($url);
            file_put_contents($file, $xml);
        }

        return $xml;
    }

    public function eventGet($id)
    {
        // http://developer.eventbrite.com/doc/events/event_get/

        $url = "{$this->baseUrl}event_get?user_key={$this->userKey}&app_key={$this->appKey}&id={$id}";
        $file = "{$this->cacheDir}/eventbrite-event-get-{$id}";

        return simplexml_load_string($this->request($url, $file));

    }

    public function eventListAttendees($id)
    {
        // http://developer.eventbrite.com/doc/events/event_list_attendees/

        // FIXME: Support optional arguments.

        $url = "{$this->baseUrl}event_list_attendees?user_key={$this->userKey}&app_key={$this->appKey}&id={$id}";
        $file = "{$this->cacheDir}/eventbrite-event-list-attendees-{$id}";

        return simplexml_load_string($this->request($url, $file));
    }

    public function eventListDiscounts($id)
    {
        // http://developer.eventbrite.com/doc/events/event_list_discounts/

        $url = "{$this->baseUrl}event_list_discounts?user_key={$this->userKey}&app_key={$this->appKey}&id={$id}";
        $file = "{$this->cacheDir}/eventbrite-event-list-discounts-{$id}";

        return simplexml_load_string($this->request($url, $file));
    }

    public function organizerListEvents($id)
    {
        // http://developer.eventbrite.com/doc/organizers/organizer_list_events/

        $url = "{$this->baseUrl}event_list_discounts?user_key={$this->userKey}&app_key={$this->appKey}&id={$id}";
        $file = "{$this->cacheDir}/eventbrite-organizer-list-events-{$id}";

        return simplexml_load_string($this->request($url, $file));
    }

    public function userGet($id)
    {
        // http://developer.eventbrite.com/doc/users/user_get/

        // Allow $id to be a user identifier or an email address.
        if (!ctype_digit($id)) {
            $url = "{$this->baseUrl}user_get?user_key={$this->userKey}&app_key={$this->appKey}&user_id={$id}";
        } else {
            $url = "{$this->baseUrl}user_get?user_key={$this->userKey}&app_key={$this->appKey}&email={$id}";
        }

        $file = "{$this->cacheDir}/eventbrite-user-get-{$id}";

        return simplexml_load_string($this->request($url, $file));
    }

}

?>