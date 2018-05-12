let fs = require('fs');

let options = {
    key: fs.readFileSync(process.env.SSL_KEY),
    cert: fs.readFileSync(process.env.SSL_CERT),
    ca: fs.readFileSync(process.env.SSL_CA)
};

let app = require('https').createServer(options),
    io = require('socket.io').list(app);
let redis = require('redis');

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


app.listen(8890);