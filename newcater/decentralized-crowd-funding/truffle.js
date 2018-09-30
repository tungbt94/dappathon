const HDWalletProvider = require('truffle-hdwallet-provider');
const HDWalletPrikeyProvider = require('truffle-hdwallet-provider-privkey');
const infuraApiKey = 'Disclosed';
const privKey = '88d3338827e4f4a60ae5413a83be8c1250979b9594d136a2e21f877c92d56430'; // metamask account private key
const mnemonic = '';

module.exports = {
    networks: {
        development: {
            host: "127.0.0.1",
            port: 7545,
            network_id: "*",
            from: '0x5a1b2F824bF1877c8eA8E2366C0A1D1A246D2463'
        },
        ropsten: {
            provider: function () {
                return new HDWalletPrikeyProvider(privKey, 'https://ropsten.infura.io/ec2ddc284d6b40d09de1a494649b954b');
            },
            network_id: 3,
            gas: 4000000,
            gas_price: 100,
            from: '0x598f107f60b19dd1467b1bea332a2a3ab7dfe37b'
        },
        main: {
            provider: function () {
                return new HDWalletPrikeyProvider('', 'https://mainnet.infura.io/x');
            },
            network_id: 1,
            gas: 4000000,
            gas_price: 100,
            from: 'Disclosed'
        }
    }
};
