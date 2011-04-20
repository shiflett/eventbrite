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

Then use the `$eventbrite` object to access the API endpoints you want:

    $attendees = $eventbrite->eventListAttendees('1514765705');

Most responses are more than you need, but you can reformat as desired:

    $attendees = (array)$attendees;
    foreach ($attendees['attendee'] as $key => $attendee) {
        $attendees[$key] = array('name' => (string)$attendee->first_name . ' ' . (string)$attendee->last_name,
                                 'email' => (string)$attendee->email,
                                 'blog' => (string)$attendee->blog,
                                 'twitter' => $attendee->answers->answer[0]['answer_text']);
    }
    unset($attendees['attendee']);