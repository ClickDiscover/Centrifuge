# Centrifuge

PHP Application for building landing pages and tracking events.

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
