# Registering and setting up postmarked

Everything starts with a postmark account. https://postmarkapp.com/ follow the link and click on the sign up button to create an account.

Once you are logged in you will have to create a server. A server is the base to starts with and represents a domain which can send / receive emails to.

To read more follow this link. http://developer.postmarkapp.com/developer-process-configure.html

When you visit the server's settings tab it will look like this.

![Server](/docs/images/postmark-server.png)

### Settings up the the inbound addresses.

When postmark is installed, we have to build a connection between postmark and our SilverStripe installation. This is how SilverStripe gets
alerted about any incoming emails.

Behind the curtains postmark sends a JSON with email contains and attachments, and as soon as this hit the SilverStripe controllers it creates a database
record and assign to an email thread depending on the hash keys.

Below is a screen shot of the inbound email servers.

![Inbound servers](/docs/images/inbound-servers.png)

http://yoursite.com/postmark-notifier

### Credentials

After settings up the inbound addresses etc, you need to set up the credentials.

Follow the credentials tab for your server

![Credentials](/docs/images/credentials.png)

Now go to your site's CMS, Settings tab and you will there are fields to enter the postmark token and the inbound email address.

### Sender Signatures

Once thats being done, you have to add sender signatures to the postmark. As well as for your CMS.

When all of these items are done you are all set to use the module.