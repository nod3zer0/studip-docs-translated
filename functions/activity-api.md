---
id: activity-api
title: Activity API
sidebar_label: Activity API
---

# Activity API

Stud.IP version 3.5 introduces a new API for creating, displaying and filtering context-relevant activities. This API can be used to give users a quick overview of the information/activities relevant to them.

## Activity streams

The activity stream is located at the top level of the API. If you want to have an activity stream:

```php
$stream = new \Studip\Activity\Stream($observer_id, $contexts, $filter);
```

Where the parameter `$observer_id` is the user ID of the viewer, `$contexts` is a context or an array of the activity contexts to be viewed (e.g. an event, an institute or a user) and `$filter` is a corresponding filter object.


If you want to output this:


```php
foreach($stream->asArray() as $key => $activity) {
    echo $activity;
}
```

#### Filter
In order to focus access to certain activities or time spaces, corresponding filter objects can be defined. Filter objects consist of a start point, end point and an activity type, which describes a corresponding activity provider.


## Activity contexts
The activity stream is provided with the activities via activity contexts. Contexts describe the areas in Stud.IP in which potential activities can be generated.

Examples of contexts:

Courses
Institutions
System-wide context
User-related context

Class description of the activity context:

```php
abstract class Context
{
    protected
        $provider;

    /**
     * return array, listing all active providers in this context
     *
     * @return array
     */
    abstract protected function getProvider();

    /**
     * get id denoting the context (user_id, course_id, institute_id, ...)
     *
     * @return string
     */
    abstract public function getRangeId();

    /**
     * get type of context (f.e. user, system, course, institute, ...)
     *
     * @return string
     */
    abstract protected function getContextType();

    /**
     * get list of activities as array for the current context
     *
     * @param string $observer_id
     * @param \Studip\Activity\Filter $filter
     *
     * @return array
     */
    public function getActivities($observer_id, Filter $filter)
    {
        $providers = $this->filterProvider($this->getProvider(), $filter);

        $activities = Activity::findBySQL('context = ? AND context_id = ?  AND mkdate >= ? AND mkdate <= ? ORDER BY mkdate DESC',
            array($this->getContextType(), $this->getRangeId(), $filter->getStartDate(), $filter->getEndDate()));

        foreach ($activities as $key => $activity) {
            if (isset($providers[$activity->provider])) { // provider is available
                $providers[$activity->provider]->getActivityDetails($activity);
            } else {
                unset($activities[$key]);
            }
        }

        return array_flatten($activities);
    }

    /**
     *
     * @param type $provider
     */
    protected function addProvider($provider)
    {
        $class_name = 'Studip\Activity\\' . ucfirst($provider) . 'Provider';

        $reflectionClass = new \ReflectionClass($class_name);
        $this->provider[$provider] = $reflectionClass->newInstanceArgs();
    }

    /**
     *
     * @param type $providers
     * @param \Studip\Activity\Filter $filter
     * @return type
     */
    protected function filterProvider($providers, Filter $filter)
    {
        $filtered_providers = array();

        if (is_null($filter->getType())) {
            $filtered_providers = $providers;
        } else {
            foreach($providers as $provider) {
                $filtered_class = 'Studip\Activity\\' . ucfirst($filter->getType()) . 'Provider';

                if ($provider instanceof $filtered_class) {
                    $filtered_providers[] = $provider;
                }
            }
        }

        return $filtered_providers;

    }
}
```

## Activity providers
Activity providers exist orthogonally to these contexts. These aggregate certain activities. Examples of activity providers:

*Forum
*Blubber
*File area
*Messages
*News
*Participants
*Wiki
*Schedule
*Literature management

#### The activity provider interface

If you want to implement a new activity provider, you have to consider the corresponding interface, which requires the methods `getActivityDetails()` and `getLexicalField()`.

```php
interface ActivityProvider
{
    /**
     * Fill in the url, route and any lengthy content for the passed activity
     *
     * @param Studip\Activity\Activity $activity
     */
    public function getActivityDetails(&$activity);

    /**
     * Human readable name for the current provider to be used in the activity-title
     */
    public static function getLexicalField();
}
```

To ensure persistent data storage of the activities, it is recommended to also implement the `postActivity()` method, which stores data in the activity table using corresponding observers from the NotificationCenter.

#### The ActivityObserver class
The ActivityObserver class ensures that relevant observers are loaded from the NotifactionCenter. Additional observers can be stored here as required, e.g. for new providers.


## Activities

## Activity feed widget

The ActivityFeed widget is implemented as a portal plugin. In addition to viewing the activity stream, the widget also offers the user the option of filtering the stream individually.

## REST route
The following new routes are added to the Stud.IP REST-API as an alternative access option:

```php
/user/:user_id/activitystream # get - output of the stream
```

The route can be filtered by specifying additional query parameters.
---
id: activity-api
title: Activity API
sidebar_label: Activity API
---

# Activity API

Stud.IP version 3.5 introduces a new API for creating, displaying and filtering context-relevant activities. This API can be used to give users a quick overview of the information/activities relevant to them.

## Activity streams

The activity stream is located at the top level of the API. If you want to have an activity stream:

```php
$stream = new \Studip\Activity\Stream($observer_id, $contexts, $filter);
```

Where the parameter `$observer_id` is the user ID of the viewer, `$contexts` is a context or an array of the activity contexts to be viewed (e.g. an event, an institute or a user) and `$filter` is a corresponding filter object.


If you want to output this:


```php
foreach($stream->asArray() as $key => $activity) {
    echo $activity;
}
```

#### Filter
In order to focus access to certain activities or time spaces, corresponding filter objects can be defined. Filter objects consist of a start point, end point and an activity type, which describes a corresponding activity provider.


## Activity contexts
The activity stream is provided with the activities via activity contexts. Contexts describe the areas in Stud.IP in which potential activities can be generated.

Examples of contexts:

Courses
Institutions
System-wide context
User-related context

Class description of the activity context:

```php
abstract class Context
{
    protected
        $provider;

    /**
     * return array, listing all active providers in this context
     *
     * @return array
     */
    abstract protected function getProvider();

    /**
     * get id denoting the context (user_id, course_id, institute_id, ...)
     *
     * @return string
     */
    abstract public function getRangeId();

    /**
     * get type of context (f.e. user, system, course, institute, ...)
     *
     * @return string
     */
    abstract protected function getContextType();

    /**
     * get list of activities as array for the current context
     *
     * @param string $observer_id
     * @param \Studip\Activity\Filter $filter
     *
     * @return array
     */
    public function getActivities($observer_id, Filter $filter)
    {
        $providers = $this->filterProvider($this->getProvider(), $filter);

        $activities = Activity::findBySQL('context = ? AND context_id = ?  AND mkdate >= ? AND mkdate <= ? ORDER BY mkdate DESC',
            array($this->getContextType(), $this->getRangeId(), $filter->getStartDate(), $filter->getEndDate()));

        foreach ($activities as $key => $activity) {
            if (isset($providers[$activity->provider])) { // provider is available
                $providers[$activity->provider]->getActivityDetails($activity);
            } else {
                unset($activities[$key]);
            }
        }

        return array_flatten($activities);
    }

    /**
     *
     * @param type $provider
     */
    protected function addProvider($provider)
    {
        $class_name = 'Studip\Activity\\' . ucfirst($provider) . 'Provider';

        $reflectionClass = new \ReflectionClass($class_name);
        $this->provider[$provider] = $reflectionClass->newInstanceArgs();
    }

    /**
     *
     * @param type $providers
     * @param \Studip\Activity\Filter $filter
     * @return type
     */
    protected function filterProvider($providers, Filter $filter)
    {
        $filtered_providers = array();

        if (is_null($filter->getType())) {
            $filtered_providers = $providers;
        } else {
            foreach($providers as $provider) {
                $filtered_class = 'Studip\Activity\\' . ucfirst($filter->getType()) . 'Provider';

                if ($provider instanceof $filtered_class) {
                    $filtered_providers[] = $provider;
                }
            }
        }

        return $filtered_providers;

    }
}
```

## Activity providers
Activity providers exist orthogonally to these contexts. These aggregate certain activities. Examples of activity providers:

*Forum
*Blubber
*File area
*Messages
*News
*Participants
*Wiki
*Schedule
*Literature management

#### The activity provider interface

If you want to implement a new activity provider, you have to consider the corresponding interface, which requires the methods `getActivityDetails()` and `getLexicalField()`.

```php
interface ActivityProvider
{
    /**
     * Fill in the url, route and any lengthy content for the passed activity
     *
     * @param Studip\Activity\Activity $activity
     */
    public function getActivityDetails(&$activity);

    /**
     * Human readable name for the current provider to be used in the activity-title
     */
    public static function getLexicalField();
}
```

To ensure persistent data storage of the activities, it is recommended to also implement the `postActivity()` method, which stores data in the activity table using corresponding observers from the NotificationCenter.

#### The ActivityObserver class
The ActivityObserver class ensures that relevant observers are loaded from the NotifactionCenter. Additional observers can be stored here as required, e.g. for new providers.


## Activities

## Activity feed widget

The ActivityFeed widget is implemented as a portal plugin. In addition to viewing the activity stream, the widget also offers the user the option of filtering the stream individually.

## REST route
The following new routes are added to the Stud.IP REST-API as an alternative access option:

```php
/user/:user_id/activitystream # get - output of the stream
```

The route can be filtered by specifying additional query parameters.
