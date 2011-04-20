PHP library for using the Eventbrite API
========================================

Requirements
------------

- [PHP](http://php.net/)
- [Eventbrite user key](http://www.eventbrite.com/userkeyapi)
- [Eventbrite app key](http://www.eventbrite.com/api/key)

Usage
-----

This library is currently very limited and only supports what we need for the
Brooklyn Beta web site. Supporting the full API is pretty straightforward, so
I'm sure I'll get to it eventually.

To use it, declare your user and app keys:

    $eventbrite = Eventbrite($userKey, $appKey);

This library caches by default, and you can indicate your preferences for where
to cache and for how long with the `cache()` method. For example, to cache files
in `/tmp` for one day:

    $eventbrite->cache('/tmp', 86400);

Use the `$eventbrite` object to access the API endpoints you want:

    $attendees = $eventbrite->eventListAttendees('1514765705');

Many responses are more than you need, but you can reformat as desired:

    $original = $attendees;
    $attendees = array();
    foreach ($original->attendee as $attendee) {
        $twitter = (string)$attendee->answers->answer->answer_text;
        $attendees[$twitter] = array('name' => (string)$attendee->first_name . ' ' . (string)$attendee->last_name,
                                     'email' => (string)$attendee->email,
                                     'blog' => (string)$attendee->blog);
    }