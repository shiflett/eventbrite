PHP library for using the Eventbrite API
========================================

Requirements
------------

- [PHP](http://php.net/)
- [Eventbrite user key](http://www.eventbrite.com/userkeyapi)
- [Eventbrite app key](http://www.eventbrite.com/api/key)

Usage
-----

Instantiate the `$eventbrite` object and declare the user and app keys:

    $eventbrite = Eventbrite(array('user_key' => $userKey, 'app_key' => $appKey));

Although it's not recommended, you can use the user's email address and password
instead of the user's API key:

    $eventbrite = Eventbrite(array('user' => 'chris@shiflett.org',
                                   'password' => 'mypass',
                                   'app_key' => $appKey));

This library caches by default, and you can indicate your preferences for where
to cache and for how long with the `cache()` method. For example, to cache files
in `/tmp` for one day (the default behavior):

    $eventbrite->cache(array('dir' => '/tmp', 'timeout' => 86400));

If you want to disable caching for some reason, you can do something like this:

    $eventbrite->cache(array('dir' => '/dev/null', 'timeout' => 0));

Use the `$eventbrite` object to access any of the API endpoints you want,
passing all required and any optional arguments:

    $attendees = $eventbrite->eventListAttendees(array('id' => '1514765705'));

Many responses are more than you need, but you can reformat them as desired. For
example, here's what we do to create a simpler and more manageable array of
attendees for the [Brooklyn Beta](http://brooklynbeta.org/) web site:

    $original = $eventbrite->eventListAttendees(array('id' => '1514765705'));
    $attendees = array();

    foreach ($original['attendees'] as $attendee) {
        $attendee = $attendee['attendee'];
        $twitter = strtolower(trim($attendee['answers'][0]['answer']['answer_text'], ' @'));
        $attendees[$twitter] = array('name' => "{$attendee['first_name']} {$attendee['last_name']}",
                                     'email' => $attendee['email'],
                                     'blog' => $attendee['blog']);
    }