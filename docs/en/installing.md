# Installing

Installing postmarked module is simple as adding it to your composer.json and running a `composer install`

Or you can directly run 

	composer require "silverstripers/silverstripe-postmarked:*"

## Still want to install manually.

Well, you can just download a zip archive from the git repo, extract on to the root of your SilverStripe installation. 

Once you've installed you will have to run a dev/build?flush=all and it all will be ready for you to use. 

## Things to note

In order for postmark to work, it needs to update the type of your database to a custom connector class. And for the moment the module only supports mysql databases only. 
In case if you were going to use a different database type please be alerted, and make sure you extend the `PostmarkMySQLDatabase` and `PostmarkMySQLSchemaManager` classes only. 

