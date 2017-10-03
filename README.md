## Requirements

- ```node``` >= ```7.0.0```
- ```php``` >= ```7.1```

To install php7.2:
```sh
$ sudo add-apt-repository ppa:ondrej/php
$ sudo apt-get update
(optional) $ sudo apt-get remove php7.0
$ sudo apt-get install php7.2
```

PPA [link](https://launchpad.net/~ondrej/+archive/ubuntu/php/).

## What guides we are following?
- [rscss](http://rscss.io/other-resources.html) - for ```CSS``` structure
- [vue components style guide](https://pablohpsilva.github.io/vuejs-component-style-guide/) - for ```Vue``` components structure

## Vue components should be used

###### General
- ```vue-authenticate``` - for OAuth 2.0 authentication
- ```vuex-persistedstate``` - for session or local storage
- ```vuex-rest-api``` - to prepare store for REST api fetching

###### For Google analytics:
- ```vue-ua``` - Google Universal Analytics support in Vue.js.
- ```vue-analytics``` - Vue plugin for Google Analytics.
- ```vue-gtm``` - Vue plugin for Google Tag Manager

## TODO:

#### BO:

##### Modules:
- Session
- OAuth
- Slugs generation
- Cli
- Event manager
- Query and schema builders
- Logging
- Caching

##### Improvements:
- Request / Response
- Invoking in container
- Making in router

#### FO:

##### Components:
- Delegation
- Popups and tooltips
- Timeago component
- Add plugin storage-state for development

##### Improvements:
- Rating
- Motion
- Validation
