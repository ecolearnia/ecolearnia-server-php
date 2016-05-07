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
    .option('-v, --verbose', 'Verbose')
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
    var httpRequestOpts = {
        url: program.url,
        method: 'POST',
        body: jsonContent,
        headers: null,  //{'content-type': 'application/json'},
    };
    httpRequest(httpRequestOpts, program.verbose,
        function(err, httpResponse, body){
            if (err) {
                console.error('Error on ' + filePath + ': ' + JSON.stringify(err));
            } else if (httpResponse.statusCode < 400){
                console.log('Completed ' + filePath + ': ' + JSON.stringify(body, null, 2));
            } else {
                console.log('Error (status code: '+ httpResponse.statusCode+ ') on ' + filePath + ': ' + JSON.stringify(body, null, 2));
            }
        });
});

//setInterval(function(){}, 4000);

/**
 * Reads a file and converts it into JSON object
 * @param {string} filePath
 */
function readJson(filePath)
{
    var content = fs.readFileSync(filePath, 'utf8');
    var jsonContent;
    if (content) {
        jsonContent = JSON.parse(content);
    }
    return jsonContent;
}

/**
 * Makes an HTTP request call
 * @param {Object} can contain properties: url, method, body, headers
 */
function httpRequest(opts, verbose, callback)
{
    method = opts.method || 'GET';
    var options = {
        url: opts.url,
        method: method,
        body: opts.body,
        headers: opts.headers,
        json: true
    };
    if (verbose) {
        console.log('Making HTTP Request with ' + JSON.stringify(options, null, 2));
    }
    return request(options, callback);
}

/**
 * Populates the commander with default values if no values were set
 *
 * @param {Commander} program - the commander object that has the cli arguments
 * @param {Object} defaults - the object with the default properties
 */
function setCommandDefaults(program, defaults)
{
    for (var prop in defaults){
        if (program[prop] === null || program[prop] === undefined ) {
            program[prop] = defaults[prop];
        }
    }
}
