<?php

class Eventbrite
{
    // FIXME: Implement caching.

    protected $baseUrl = 'https://www.eventbrite.com/xml/';
    protected $userKey;
    protected $appKey;

    public function __construct($userKey, $appKey)
    {
        $this->userKey = $userKey;
        $this->appKey = $appKey;
    }

    public function eventGet($id)
    {
        // http://developer.eventbrite.com/doc/events/event_get/

        $url = "{$this->baseUrl}event_get?user_key={$this->userKey}&app_key={$this->appKey}&id={$id}";
        return simplexml_load_file($url);
    }

    public function eventListAttendees($id)
    {
        // http://developer.eventbrite.com/doc/events/event_list_attendees/

        $url = "{$this->baseUrl}event_list_attendees?user_key={$this->userKey}&app_key={$this->appKey}&id={$id}";
        return simplexml_load_file($url);
    }

}

?>
