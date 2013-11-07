# Doucheswag

Swag for douchebags.

Doucheswag is a sample application that showcases an implementation of the
Entity-Boundary-Interactor pattern (aka Hexagonal Architecture or Ports and
Adapters).

The core is an auction application. The main delivery mechanism that plugs
into it is based on [silex](http://silex.sensiolabs.org) and maps web requests
onto the application-specific request objects.

It was made for a talk given at Symfony Live Portland 2013 titled "[Silex: An
implementation detail](https://www.youtube.com/watch?v=bTawx0TGIj8)".

## Setup

In order to set up the project, first install the dependencies via composer:

    $ composer install --dev

Then populate the production database (it's an sqlite db in the `store`
directory):

    $ make init

Now you should be able to start the webserver and hit the page at
`localhost:8080`:

    $ make web

## Slides

Check out the [slides](https://speakerdeck.com/igorw/silex-an-implementation-detail)
from the presentation.

## Authors

* Dave Marshall (@davedevelopment)
* Igor Wiedler (@igorw)
