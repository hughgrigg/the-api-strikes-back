The API Strikes Back
====================

Sample project around API consumption and data processing.

Alternative names:

 - A New SOAP
 - Return of the Function
 - Attack of the Git Clones
 - The Git Push Force Awakens

## Set up

To install dependencies via Composer:

```bash
make vendor
```

## Running tests

To run the tests and static analysers:

```bash
make test
```

To get code coverage:

```bash
make coverage
```

## Usage

### Client SSL certificate

You'll need to generate an SSL cert for the client to use when accessing the
Death Star API. We have used the Force and middleware-chlorians to ensure that
the self-signed SSL cert you generate on your machine will be accepted by the
Death Star's CA regardless of the details.

E.g. using `openssl`:

```bash
openssl req -x509 -newkey rsa:1024 -keyout client.key -out client.crt
openssl dhparam -out client.pem 1024
```

(The second command will take a long time, in a galaxy far away.)

Now the `client.pem` certificate file can be used to make signed requests to the
Death Star API.

### Local test server

The real Death Star API is at `https://death.star.api/`, but for local testing
purposes we can use the test server:

```bash
php -S localhost:8888 test-server.php
```

That runs a forgiving test server that will respond to our requests. Once you've
got that running somewhere, you can continue.

### Environment

The console commands use environment variables to get the required client id,
client secret and cert file location. Export these environment variables first
for the commands to use:

```bash
export DEATH_STAR_URI='http://localhost:8888/'
export DEATH_STAR_ID='Alderan'
export DEATH_STAR_SECRET='R2D2'
export DEATH_STAR_CERT_FILE='client.pem'
```

You can see available commands with:

```bash
php console.php help
```

You can make a token request using the client like this:

```bash
php console.php authorise
```

That will return an Oauth2 token if you want to use that.

Note that the other commands will automatically fetch the Oauth2 token first and
use it to make their request.

To blow up a reactor exhaust, use this command. Specify which reactor exhaust to
blow up with the first argument. This is sure to cause some pager alerts for
Death Star engineers.

```bash
php console.php delete:reactor-exhaust 1
```

To get information about the location of a prisoner, use this command. Specify
the name of the prisoner as the first argument.

```bash
php console.php get:prisoner 'leia'
```

That will fetch the raw Droidspeak response. To get it converted to Galactic
Basic, pipe it to the translator command like this:

```bash
php console.php get:prisoner 'leia' | php console.php translate:droid-speak
```

You will then get the output in Galactic Basic.
