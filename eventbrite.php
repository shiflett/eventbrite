<?php

class Eventbrite
{

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
        $url = "{$this->baseURL}event_get?user_key={$this->userKey}&app_key={$this->appKey}&id={$id}";
        return simplexml_load_file($url);
    }

    public function eventListAttendees($id)
    {
        $url = "{$this->baseURL}event_list_attendees?user_key={$this->userKey}&app_key={$this->appKey}&id={$id}";
        return simplexml_load_file($url);
    }

}

?>
