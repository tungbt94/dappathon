App = {
    web3Provider: null,
    contracts: {},

    init: function () {
        // Is there an injected web3 instance?
        if (typeof web3 !== 'undefined') {
            App.web3Provider = web3.currentProvider;
        } else {

            // If no injected web3 instance is detected, fall back to Ganache
            App.web3Provider = new Web3.providers.HttpProvider('http://localhost:7545');
        }

        web3 = new Web3(App.web3Provider);

        //get metamask account to create
        App.getAccount();
        App.setAccount();

        web3.eth.getBalance('0x7d5d310f41f26a2a4f358730157aaaecc9ac1453', function (error, wei) {
            if (!error) {
                let balance = web3.fromWei(wei, 'ether');
                // console.log(web3.toDecimal(balance));
            }
        });

        return App.initContract();


    },

    initContract: function () {
        let current_time = new Date();
        $.getJSON('/client_resource/assets/contracts/DecentralizedCrowdFunding2.json?v=' + current_time, function (data) {
            //$.getJSON('//decentralized-crowd-funding/build/DecentralizedCrowdFunding.json', function(data) {
            // Get the necessary contract artifact file and instantiate it with truffle-contract
            let DCFundingArtifact = data;
            App.contracts.DCFunding = TruffleContract(DCFundingArtifact);

            // Set the provider for our contract
            App.contracts.DCFunding.setProvider(App.web3Provider);

            App.get_raised();

            // Use our contract to retrieve and mark the adopted pets
            // return App.markAdopted();
        });

        $.getJSON('/client_resource/assets/contracts/NewCaterAuction.json?v=' + current_time, function (data) {
            //$.getJSON('//decentralized-crowd-funding/build/DecentralizedCrowdFunding.json', function(data) {
            // Get the necessary contract artifact file and instantiate it with truffle-contract
            let DCBiddingArtifact = data;
            App.contracts.DCBidding = TruffleContract(DCBiddingArtifact);

            // Set the provider for our contract
            App.contracts.DCBidding.setProvider(App.web3Provider);

            App.get_current_warrior()

            // Use our contract to retrieve and mark the adopted pets
            // return App.markAdopted();
        });

        $.getJSON('/client_resource/assets/contracts/CollectibleToken.json?v=' + current_time, function (data) {
            //$.getJSON('//decentralized-crowd-funding/build/DecentralizedCrowdFunding.json', function(data) {
            // Get the necessary contract artifact file and instantiate it with truffle-contract
            let DCCollectibleArtifact = data;
            App.contracts.DCCollectible = TruffleContract(DCCollectibleArtifact);

            // Set the provider for our contract
            App.contracts.DCCollectible.setProvider(App.web3Provider);

            // Use our contract to retrieve and mark the adopted pets
            // return App.markAdopted();
        });

        return App.bindEvents();
    },

    bindEvents: function () {


        $(document).on('click', '#donate-fund', App.donateFund);

        $(document).on('click', '#btn_accept', App.accept_withdraw);

        $(document).on('click', '#btn_submit_withdraw', App.request_disburse);

        $(document).on('click', '#bid_now', App.bid_now);

        $(document).on('click', '#create_token', App.tokenize);
    },

    donateFund: function () {
        let project_info = $('.project_info');
        let target = Number_Lib.removeCommas(project_info.attr('data-target'));
        let total_raised = Number_Lib.removeCommas(project_info.attr('data-raised'));
        if (total_raised >= target) {
            return $.toast({
                heading: 'Warning',
                text: "Target Reached!",
                icon: 'warning',
                loader: true,
                position: 'top-right',
            });
        }

        let _toAddress = $('#contribute_address').val();
        let _value = Number_Lib.removeCommas($('#price-donate5').val());

        if (_value <= 0) {
            return $.toast({
                heading: 'Warning',
                text: "Invalid Amount",
                icon: 'warning',
                loader: true,
                position: 'top-right',
            });
        }

        let project_id = project_info.attr('data-id');

        web3.eth.getAccounts(function (error, accounts) {
            if (error) {
                console.log(error);
            }

            let send_account = accounts[0];

            web3.eth.sendTransaction({
                to: _toAddress,
                from: send_account,
                value: web3.toWei(_value, "ether")
            }, function (err, hash) {
                if (hash === undefined || !hash.length) {
                    return false
                }

                let fundContract = App.contracts.DCFunding.at($('#contribute_address').val());

                async function get_balance() {
                    let raised = await fundContract.totalRaised().then(function (raised) {
                        raised = web3.fromWei(raised, 'ether');
                        raised = web3.toDecimal(raised);
                        return raised;
                    });

                    let balance = await fundContract.currentBalance().then(function (balance) {
                        balance = web3.fromWei(balance, 'ether');
                        balance = web3.toDecimal(balance);
                        return balance;
                    });

                    $.ajax({
                        method: "POST",
                        url: "/api/create_project_contribute",
                        data: {project_id: project_id, value: _value, hash: hash, raised: raised, balance: balance}
                    }).done(function (msg) {
                        $.toast({
                            heading: 'Success',
                            text: "Success!",
                            icon: 'info',
                            loader: true,
                            position: 'top-right',
                        });
                    });
                    project_info.attr({'data-raised': raised, 'data-balance': balance});
                }

                get_balance();

            });

            /*	fundContract = App.contracts.DCFunding.at($('#contribute_address').val());
                var _state = fundContract.state().then(function(state) {
                    console.log(state);
                });

                var _currentBalance = fundContract.currentBalance().then(function(currentBalance) {
                    console.log(currentBalance);
                }); */

        });
    },

    getAccount: function () {

        web3.eth.getAccounts(function (error, accounts) {
            if (error) {
                console.log(error);
            }

            let account = accounts[0];

            $.ajax({
                method: "POST",
                url: "/api/create_user",
                data: {address: account}
            }).done(function (data) {
                $('.user_address').text(data.data.short_address);
            });
        });
    },

    setAccount: function () {
        let account = web3.eth.accounts[0];
        let accountInterval = setInterval(function () {
            if (web3.eth.accounts[0] !== account) {
                account = web3.eth.accounts[0];
                $.ajax({
                    method: "POST",
                    url: "/api/create_user",
                    data: {address: account}
                }).done(function (data) {
                    $('.user_address').text(data.data.short_address);
                    window.location.reload();
                });
            }
        }, 1000);
    },

    accept_withdraw: function () {
        let project_info = $('.project_info');
        let id = project_info.attr('data-id');
        let fundContract = App.contracts.DCFunding.at($('#contribute_address').val());

        let user_approve_id = $('#btn_accept').attr('data-user-approve');
        if (!user_approve_id.length) {
            console.log(1);

            async function approve() {

                let hash = await fundContract.approveDisbursement().then(function (res) {
                    return res.tx;
                });

                $.ajax({
                    method: "POST",
                    url: "/api/accept_withdraw",
                    data: {id: id, hash: hash}
                }).done(function (msg) {
                    $('#btn_accept').removeClass('bg-gray').addClass('bg-success');
                    $.toast({
                        heading: 'Success',
                        text: "Success!",
                        icon: 'info',
                        loader: true,
                        position: 'top-right',
                    });
                });
            }

            approve();
        } else {
            console.log(2);

            async function un_approve() {

                let hash = await fundContract.unapproveDisbursement().then(function (res) {
                    return res.tx;
                });

                $.ajax({
                    method: "POST",
                    url: "/api/un_accept_withdraw",
                    data: {id: id}
                }).done(function (msg) {
                    $('#btn_accept').attr('data-user-approve', '');
                    $('#btn_accept').removeClass('bg-success').addClass('bg-gray');
                    $.toast({
                        heading: 'Success',
                        text: "Success!",
                        icon: 'info',
                        loader: true,
                        position: 'top-right',
                    });
                });
            }

            un_approve();
        }


    },

    get_raised: function () {
        let contribute_addresss = $('#contribute_address').val();
        if (contribute_addresss) {

            let fundContract = App.contracts.DCFunding.at(contribute_addresss);

            let target = Number_Lib.removeCommas($('.project_target').attr('data-target'));
            let project_info = $('.project_info');
            let id = project_info.attr('data-id');

            async function get_project_info() {
                let raised = await fundContract.totalRaised().then(function (raised) {
                    raised = web3.fromWei(raised, 'ether');
                    raised = web3.toDecimal(raised);
                    if (raised >= target) {
                        $('.button_back').remove();
                    }
                    $('.project_total_raised').html(raised.toFixed(2));
                    return raised;

                });

                let balance = await fundContract.currentBalance().then(function (balance) {
                    balance = web3.fromWei(balance, 'ether');
                    balance = web3.toDecimal(balance);
                    $('.project_total_balance').html(balance.toFixed(2));
                    return balance;
                });

                $.ajax({
                    method: "POST",
                    url: "/api/update_project_info",
                    data: {id: id, raised: raised, balance: balance}
                }).done(function (data) {

                });
            }

            get_project_info();


        }

    },

    request_disburse: function () {
        let amount = Number_Lib.removeCommas($('#amount').val());
        let vote_start_time = $('#vote_start_time').val();
        if (!vote_start_time.length) {
            return $.toast({
                heading: 'Warning',
                text: "Please pick the voting start time",
                icon: 'warning',
                loader: true,
                position: 'top-right',
            });
        }

        let vote_end_time = $('#vote_end_time').val();
        if (!vote_end_time.length) {
            return $.toast({
                heading: 'Warning',
                text: "Please pick the voting end time",
                icon: 'warning',
                loader: true,
                position: 'top-right',
            });
        }

        let start_time = getTimestamp(vote_start_time);
        let end_time = getTimestamp(vote_end_time);

        let fundContract = App.contracts.DCFunding.at($('#project_address').val());

        async function request_withdraw() {


            let project_id = $('#project_id').val();
            let caption = $('#caption').val();

            let mul = Math.pow(10, 18);
            let request_amount = amount * mul;

            let result_request_hash = await fundContract.requestDisbursement(request_amount).then(function (res) {
                return res.tx;
            });

            let result_time_hash = await fundContract.setDisbursementEndTime(end_time).then(function (res) {
                return res.tx;
            });

            $.ajax({
                method: "POST",
                url: "/api/create_withdraw",
                data: {project_id: project_id, amount: amount, vote_start_time: start_time, vote_end_time: end_time, hash: result_request_hash, caption: caption}
            }).done(function (data) {
                if (data.status === 2) {
                    return $.toast({
                        heading: 'Success',
                        text: "Success!",
                        icon: 'info',
                        loader: true,
                        position: 'top-right',
                    });
                }

                return $.toast({
                    heading: 'Warning',
                    text: data.message,
                    icon: 'warning',
                    loader: true,
                    position: 'top-right',
                });

            });

        }

        request_withdraw();
    },

    get_current_warrior: function () {
        let contribute_addresss = $('#contribute_address').val();
        if (contribute_addresss) {

            let bidContract = App.contracts.DCBidding.at(contribute_addresss);

            let project_info = $('.project_info');
            let id = project_info.attr('data-id');

            async function get_project_info() {
                let current_warrior = await bidContract.bidCount().then(function (total_warrior) {
                    total_warrior = web3.toDecimal(total_warrior);
                    $('#bid_currently').html(total_warrior);
                    return total_warrior;
                });


                let highest_value = await bidContract.highest().then(function (highest) {
                    highest = web3.fromWei(highest, 'ether');
                    highest = web3.toDecimal(highest);
                    return highest;
                });

                $('#price_bid').text(highest_value);
                $('#bid_currently').text(current_warrior);

                $.ajax({
                    method: "POST",
                    url: "/api/update_project_info",
                    data: {id: id, current_warrior: current_warrior, highest_value: highest_value}
                }).done(function (data) {

                });
            }

            get_project_info();


        }

    },

    bid_now: function () {
        let project_info = $('.project_info');

        let _toAddress = $('#contribute_address').val();
        let _value = Number_Lib.removeCommas($('#price_bid_user').val());

        if (_value <= 0) {
            return $.toast({
                heading: 'Warning',
                text: "Invalid Amount",
                icon: 'warning',
                loader: true,
                position: 'top-right',
            });
        }

        let project_id = project_info.attr('data-id');

        web3.eth.getAccounts(function (error, accounts) {
            if (error) {
                console.log(error);
            }

            let send_account = accounts[0];

            web3.eth.sendTransaction({
                to: _toAddress,
                from: send_account,
                value: web3.toWei(_value, "ether")
            }, function (err, hash) {
                $.toast({
                    heading: 'Success',
                    text: "Success!",
                    icon: 'info',
                    loader: true,
                    position: 'top-right',
                });

                if (hash === undefined || !hash.length) {
                    return false
                }
            });

        });
    },

    tokenize: function () {

        let data_serialize = $('#token_form').serializeArray();
        $.ajax({
            method: "POST",
            url: "/api/create_project",
            data: data_serialize
        }).done(function (msg) {

        });

        let token_name = $('#token_name').val();
        let symbol = $('#symbol').val();
        let total_supply = Number_Lib.removeCommas($('#total_supply').val());
        total_supply = total_supply * 1e18;
        let decimal = 18;
        let link = $('.file_url').val();

        App.contracts.DCCollectible.new(token_name, symbol, decimal, total_supply, link).then(function (instance) {
            let address = instance.address;
            let hash = instance.transactionHash;

            let data_serialize = $('#token_form').serializeArray();
            $.toast({
                heading: 'Success',
                text: "Success!",
                icon: 'info',
                loader: true,
                position: 'top-right',
            });

        }).catch(function (err) {
            //	debugger;
            // There was an error! Handle it.
        });
    }


};

$(function () {
    $(window).load(function () {
        App.init();

    });
});