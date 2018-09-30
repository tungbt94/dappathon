const express = require('express');
const app = express();
const port = 3000 || process.env.PORT;
const Web3 = require('web3');
const truffleConnect = require('./connection/truffle_connect.js');
const bodyParser = require('body-parser');

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: false }));

// parse application/json
app.use(bodyParser.json());

app.get('/getAccounts', (req, res) => {
  console.log("**** GET /getAccounts ****");
  truffleConnect.start(function (answer) {
    res.send(answer);
  })
});

app.post('/contribute', (req, res) => {
  console.log('**** POST /contribute ****');
  console.log(req.body);

  const amount = req.body.amount;
  const sender = req.body.sender;

  truffleConnect.contribute(sender, amount, (instance) => {
    res.send(instance);
  });
})

app.listen(port, () => {
  if (typeof web3 !== 'undefined') {
    console.warn("Using web3 detected from external source. If you find that your accounts don't appear or you have 0 MetaCoin, ensure you've configured that source properly. If using MetaMask, see the following link. Feel free to delete this warning. :) http://truffleframework.com/tutorials/truffle-and-metamask")
    // Use Mist/MetaMask's provider
    truffleConnect.web3 = new Web3(web3.currentProvider);
  } else {
    console.warn("No web3 detected. Falling back to http://127.0.0.1:7545. You should remove this fallback when you deploy live, as it's inherently insecure. Consider switching to Metamask for development. More info here: http://truffleframework.com/tutorials/truffle-and-metamask");
    truffleConnect.web3 = new Web3(new Web3.providers.HttpProvider("http://127.0.0.1:7545"));
    // console.log("web3: ", truffleConnect.web3);
  }
  console.log("Express Listening at http://127.0.0.1:" + port);

});
