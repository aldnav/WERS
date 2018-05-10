# WERS

### Contributing Guide

File structure
```sh
# (dev)
resources/
|-- assets/
    |-- js/    # new js files goes here
    |-- sass/  # new sass files goes here
```
```sh
# (build)
public/
|-- css/       # compiled css goes here
|-- fonts/
|-- js/        # compiled js goes here
```

To compile:

```sh
npm install    # make sure node reqs are in
npm run dev    # build one time
npm run watch  # build when there is a change
```

Setup notifications:
Pubsub  is handled by redis. Follow installation instructions here https://redis.io/download#installation
Communications are handled via websockets.
After that, cd into `node_server` and run `node server.js`


Documentations:

* [Laravel mix (github)](https://github.com/JeffreyWay/laravel-mix/blob/master/docs/mixjs.md)
* [Laravel mix](https://laravel.com/docs/5.6/mix)