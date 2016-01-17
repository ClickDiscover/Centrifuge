# Centrifuge

PHP Application for building landing pages and tracking events.

## Request Path (Middlewares & Hooks)

1. [Session](src/Flagship/Middleware/Session.php)
  * Construct: Registers session storage handler. Configures Session.
  * Middleware: call() - Registers hook for slim.before

2. [UserTracker](src/Flagship/Middleware/UserTracker.php)
  * Middleware: call() - Registers hooks for slim.before and slim.after

3. [SlimBootstrap](src/Flagship/SlimBootstrap.php)
  * Hook: slim.before - Librato system metric recording (num_requests, request_time)
  * Hook: slim.before - Custom Routes (From routes table)

4. [Session](src/Flagship/Middleware/Session.php)
  * Hook: slim.before - session_start() and sets the session_id in session with key Session::SESSION_ID

5. [UserTracker](src/Flagship/Middleware/UserTracker.php)
  * Hook: slim.before.dispatch - Creates $tracking array and gets or creates the tracking cookie. Gets or creates User (aerospike)
  * Hook: slim.after  - Sets the tracking cookie on user

6. [RouteMiddleware](src/Flagship/Middleware/RouteMiddleware.php)
  * base() - Creates the Event for the current action
    * [View](src/Flagship/Event/View.php) for /content/:id
    * [Click](src/Flagship/Event/Click.php) for /click/:stepId

7. Route handlers (eg: app.get('/foo'))
  * Save tracking events to storage
  * Render landing page for /content/:id
  * Redirect Clicks for /click/:stepId

3. [SlimBootstrap](src/Flagship/SlimBootstrap.php)
  * Hook: slim.after - Flushes EventQueue to storage. _IMPORTANT_: Must run last

## Routes

### Production

* /content/:id - URL for viewing a particular landing page referenced by ID

* /landers/:id - Alias for content

* /click/:stepNumber - Click tracking for offers, stepNumber is the offer Step 1 or Step 2?

* /conversions - Pulls conversion data from Redis, system level, used in cron job


### Administration

* /admin/models - Root admin site, pulls just about everything from Postgres. Ad Exchange parameters can be added here

* /admin/models/products - Product/Offer Configuration page. New Products can be added here

* /admin/models/landers - Lander configuration page. Build new Landing pages

* /admin/tracking - View tracking data (sent to Segment, Google Analytics, etc..)


### Status

* /admin/ping & /admin/phpinfo - Used for monitoring


## TODO

### Big Projects

- [ ] User Profile Store - Aerospike

- [ ] Event Store - Also Aerospike (Maybe..?). Alternatives are Kafka/Kinesis

- [ ] Cloaker Integration - Integrate cloaker with Centrifuge. Perhaps interacting with the fp cookie

- [ ] Replace CPV Lab by allowing campaign creation/split percentages/etc..


### Small Projects

- [ ] Custom Routes hook should use query like SELECT * FROM routes WHERE url = "{$request->getPathInfo()}" and add index on routes table (url column)
- [x] Need to add postgres migration for vertical on products table
- [ ] Use source column of product table for affiliate company (eg: Oasis)
- [x] OfferServices now add vertical information to product models


