const contract = require('truffle-contract');
const web3 = require('web3');

const DecentralizedCrowdFundingContract = require('../build/contracts/DecentralizedCrowdFunding.json');
var DecentralizedCrowdFunding = contract(DecentralizedCrowdFundingContract);

module.exports = {
  start: function(callback) {
    let self = this;
    // Bootstrap the DecentralizedCrowdFunding abstraction for Use.
    DecentralizedCrowdFunding.setProvider(self.web3.currentProvider);
    // Get the initial account balance so it can be displayed.
    console.log(self.web3.currentProvider);
    self.web3.eth.getAccounts(function(err, accs) {
      if (err != null) {
        console.log("There was an error fetching your accounts.");
        return;
      }
      if (accs.length == 0) {
        console.log("Couldn't get any accounts! Make sure your Ethereum client is configured correctly.");
        return;
      }
      self.accounts = accs;
      self.account = self.accounts[2];
      callback(self.accounts);
    });
  },

  contribute: function(sender, amount, callback) {
    let self = this;
    // DecentralizedCrowdFunding.setProvider(self.web3.currentProvider);
    let dcf;
    console.log(self.web3.currentProvider);
    console.log("sender: ", sender);
    console.log("amount:", amount);

    DecentralizedCrowdFunding.deployed().then(function(instance) {
      dcf = instance;
      // return meta.sendCoin(receiver, amount, {from: sender});
      console.log(instance);
      return dcf.contribute('0xb6e4b00b2af0f324c87fbe8de1d64d29719bb566', amount, {from: sender});
    }).then(function() {
      console.log('then');
      // self.refreshBalance(sender, function (answer) {
      //   callback(answer);
      // });
      callback("SUCCESS");
    }).catch(function(e) {
      console.log(e);
      callback("ERROR 404");
    });
  },
}
