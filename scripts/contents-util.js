/**
 *
 *IMPORTANT:
 * Make sure to run the php artisan as:
 * php artisan serve --host=127.0.0.1
 *
 * Sample:
 * node contents-util.js --path '../artifacts/samples/*.container.json'
 */
var program = require('commander');
var fs = require('fs');
var glob = require("glob")
var request = require('request');

program
    .version('0.0.1')
    .usage('[options] <config-file>')
    .option('-p, --post', 'Post files')
    .option('-d, --path <path>', 'Directory where the files to put/post are')
    .option('-u, --url <url>', 'URL')
    .parse(process.argv);

/*
try {
    fs.accessSync(program.dir, fs.R_OK);
} catch (error) {
    console.log('Directory :' + program.dir
        + ' does not exist or is not readable');
    process.exit(-1);
}*/

if (program.args) {
    var configPath = program.args[0];
    try {
        fs.accessSync(configPath, fs.R_OK);
    } catch (error) {
        console.log('Directory :' + configPath
            + ' does not exist or is not readable');
        process.exit(-1);
    }
    var programConfig = readJson(configPath);
    setCommandDefaults(program, programConfig);
}

if (!program.path) {
    console.log('path not provied');
    process.exit(-1)
}
var files = glob.sync(program.path);

console.log('Executing: ' + JSON.stringify(program.args));
console.log('path: ' + program.path);


files.forEach(function(filePath, index){
    console.log('processing ['+ filePath + '] (POST ' + program.url +')');
    var jsonContent = readJson(filePath);

    //console.log(JSON.stringify(jsonContent, null, 2));
    httpRequest(program.url, 'POST',
        jsonContent,
        null, //{'content-type': 'application/json'},
        function(err, httpResponse, body){
            if (err) {
                console.error('Error on ' + filePath + ': ' + JSON.stringify(err));
            } else {
                console.log('Completed ' + filePath + ': ' + body);
            }
        });
});

//setInterval(function(){}, 4000);


function readJson(filePath)
{
    var content = fs.readFileSync(filePath, 'utf8');
    var jsonContent;
    if (content) {
        jsonContent = JSON.parse(content);
    }
    return jsonContent;
}

function httpRequest(url, method, body, headers, callback)
{
    method = method || 'GET';
    var options = {
        url: url,
        method: method,
        body: body,
        headers: headers,
        json: true
    };
    console.log(JSON.stringify(options, null, 2));
    return request(options, callback);
}

function setCommandDefaults(program, defaults)
{
    for (var prop in defaults){
        if (program[prop] === null || program[prop] === undefined ) {
            program[prop] = defaults[prop];
        }
    }
}
