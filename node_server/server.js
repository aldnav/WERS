let app = require('express')();
let server = require('http').Server(app);
let io = require('socket.io')(server);
let redis = require('redis');

server.listen(8890);
io.on('connection', (socket) => {
    console.log('client connected');
    let redisClient = redis.createClient(6379);

    redisClient.on('message', function(channel, message) {
        console.log('message:', message);
        message = JSON.parse(message);
        socket.emit(message.pchannel, message);
    });

    redisClient.subscribe('message');
});
