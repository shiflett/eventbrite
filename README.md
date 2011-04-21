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

Many responses are more than you need, but you can reformat as desired. For
example, here's what we do to create a more manageable array of attendees for
the Brooklyn Beta web site:

    $original = $attendees;
    $attendees = array();
    foreach ($original->attendee as $attendee) {
        // The first answer is the Twitter username.
        $twitter = (string)$attendee->answers->answer->answer_text;

        $attendees[$twitter] = array('name' => (string)$attendee->first_name . ' ' . (string)$attendee->last_name,
                                     'email' => (string)$attendee->email,
                                     'blog' => (string)$attendee->blog);
    }

Methods
-------

Only a few of the [API methods](http://developer.eventbrite.com/doc/) are supported. I'll update this list as more are added:

- [`eventGet()`](http://developer.eventbrite.com/doc/events/event_get/)
- [`eventListAttendees()`](http://developer.eventbrite.com/doc/events/event_list_attendees/)
- [`eventListDiscounts()`] (http://developer.eventbrite.com/doc/events/event_list_discounts/)
- [`organizerListEvents()`](http://developer.eventbrite.com/doc/organizers/organizer_list_events/)
- [`userGet()`](http://developer.eventbrite.com/doc/users/user_get/)