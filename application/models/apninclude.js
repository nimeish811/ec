// Locate your certificate
/*var join = require('path').join
  , pfx = join(__dirname, 'certificates/Certificates_chat_new_push.p12');*/

//distribution
/*var join = require('path').join
  , pfx = join(__dirname, 'certificates/Certificates_distribution_02-11-2015.p12');*/

var join = require('path').join
  , pfx = join(__dirname, 'certificates/Certificates_distribution_adhoc_23_02_2016.p12');

// Create a new agent
var apnagent = require('apnagent')
  , agent = module.exports = new apnagent.Agent();
  
  // set our credentials
agent.set('pfx file', pfx);

// our credentials were for development
//agent.enable('sandbox');
agent.enable('production');

agent.connect(function (err) {
  console.log(err);
  // gracefully handle auth problems
  //if (err & err.name === 'GatewayAuthorizationError') 
  if (err) 
  {
    console.log('Authentication Error: %s', err.message);
    process.exit(1);
  }

  // handle any other err (not likely)
  else if (err) {
    throw err;
  }

  // it worked!
  var env = agent.enabled('sandbox')
    ? 'sandbox'
    : 'production';
});