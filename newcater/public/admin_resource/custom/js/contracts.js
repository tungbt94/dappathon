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
            App.get_state();

            // Use our contract to retrieve and mark the adopted pets
            // return App.markAdopted();
        });

        return App.bindEvents();
    },

    bindEvents: function () {
        $(document).on('click', '#save-project', App.acceptProject);
        $(document).on('click', '#save-withdraw', App.request_withdraw);
        $(document).on('click', '#save-project-auction', App.acceptProjectBid);
        $(document).on('click', '.end_project', App.endProject);
    },

    acceptProject: function (event) {
        let project_status = $('#project_status').attr('data-status') || '0';
        let status_diplay = $('#status_display').val() || '1';
        if (status_diplay === '2' && project_status === '0') {
            event.preventDefault();
            let funding_receipt = $('#funding_receipt');
            let _fundRecipient = funding_receipt.val() ? funding_receipt.val() : '0xF50B2d9eF8b7e8e4ad72b3d5460b91cE59903Cd5';
            let target = Number_Lib.removeCommas($('#target').val());
            let _targetFunding = target * 1e18;
            let _campaignUrl = $('#project_link').val();
            let _fundingStartTime = Number_Lib.removeCommas($('#funding_start_time').val());
            let _fundingEndTime = Number_Lib.removeCommas($('#funding_end_time').val());
            let _projectId = $('#project_id').val();

            App.contracts.DCFunding.new(_fundRecipient, _targetFunding, _campaignUrl, _fundingStartTime, _fundingEndTime).then(function (instance) {
                let address = instance.address;
                let hash = instance.transactionHash;
                $.ajax({
                    method: "POST",
                    url: "/api/update_address_contract",
                    data: {id: _projectId, address: instance.address, hash: hash}
                }).done(function (msg) {
                    $.toast({
                        heading: 'Success',
                        text: "Success!",
                        icon: 'info',
                        loader: true,
                        position: 'top-right',
                    });

                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                });
            }).catch(function (err) {
                //	debugger;
                // There was an error! Handle it.
            });

            //    var DCFundingInstance;

            /*    web3.eth.getAccounts(function(error, accounts) {
                  if (error) {
                    console.log(error);
                  }

                  var account = accounts[0];

                  App.contracts.DCFunding.deployed().then(function(instance) {
                    DCFundingInstance = instance;
                    //alert(DCFundingInstance.address);
                  }).then(function(result) {
                    //  alert(DCFundingInstance.address);
                     // alert(JSON.stringify(result));
                  }).catch(function(err) {
                    console.log(err.message);
                  });
                }); */
        }

    },

    request_withdraw: function (event) {
        let vote_start_time = $('#vote_start_time').val();
        if (!vote_start_time.length) {
            event.preventDefault();
            return $.toast({
                heading: 'Warning',
                text: "Please pick the voting start time!",
                icon: 'warning',
                loader: true,
                position: 'top-right',
            });
        }

        let vote_end_time = $('#vote_end_time').val();
        if (!vote_end_time.length) {
            event.preventDefault();
            return $.toast({
                heading: 'Warning',
                text: "Please pick the voting end time!",
                icon: 'warning',
                loader: true,
                position: 'top-right',
            });
        }


        let start_time = Number_Lib.removeCommas($('#vote_start_time').attr('data-value'));
        let end_time = Number_Lib.removeCommas($('#vote_end_time').attr('data-value'));

        let amount = Number_Lib.removeCommas($('#amount').attr('data-amount'));

        let hash = $('#hash').val();
        let status = $('#id_status').val();

        if (!hash.length && status === '1') {
            event.preventDefault();

            let id = $('.withdraw_id').val();

            let fundContract = App.contracts.DCFunding.at($('#contribute_address').val());

            async function request_withdraw() {

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
                    url: "/api/update_withdraw",
                    data: {id: id, hash: result_request_hash}
                }).done(function (msg) {
                    $.toast({
                        heading: 'Success',
                        text: "Success!",
                        icon: 'info',
                        loader: true,
                        position: 'top-right',
                    });
                });
            }

            request_withdraw();
        }


    },

    acceptProjectBid: function (event) {
        let project_status = $('#project_status').attr('data-status') || '0';
        let status_diplay = $('#status_display').val() || '1';
        if (status_diplay === '2' && project_status === '0') {
            event.preventDefault();
            let funding_receipt = $('#funding_receipt');
            let _fundRecipient = funding_receipt.val() ? funding_receipt.val() : '0xF50B2d9eF8b7e8e4ad72b3d5460b91cE59903Cd5';
            let target = Number_Lib.removeCommas($('#target').val());
            let _targetFunding = target * 1e18;
            let _campaignUrl = $('#project_link').val();
            let _fundingStartTime = Number_Lib.removeCommas($('#funding_start_time').val());
            let _fundingEndTime = Number_Lib.removeCommas($('#funding_end_time').val());
            let _projectId = $('#project_id').val();

            let min_value = Number_Lib.removeCommas($('#min_value').val());
            let step_value = Number_Lib.removeCommas($('#step_value').val());

            App.contracts.DCBidding.new(_fundRecipient, min_value, _campaignUrl, _fundingStartTime, _fundingEndTime, step_value).then(function (instance) {
                let address = instance.address;
                let hash = instance.transactionHash;
                $.ajax({
                    method: "POST",
                    url: "/api/update_address_contract",
                    data: {id: _projectId, address: instance.address, hash: hash}
                }).done(function (msg) {
                    $.toast({
                        heading: 'Success',
                        text: "Success!",
                        icon: 'info',
                        loader: true,
                        position: 'top-right',
                    });

                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                });
            }).catch(function (err) {
                //	debugger;
                // There was an error! Handle it.
            });

        }

    },

    get_state: function () {
        $.each($('.state_bc'), function (i, v) {
            let contribute_addresss = $(v).find('.contribute_address').val();
            if (contribute_addresss) {
                let bidContract = App.contracts.DCBidding.at(contribute_addresss);
                let state = bidContract.state().then(function (state) {
                    state = (state.toNumber());
                    return state;
                });

                let txt = 'Running';
                if (state === 0) txt = 'Ended';
                else if (state === 1) txt = 'Running';

                $(v).find('.state').text(txt);
            }

        });

    },
    
    endProject : function () {
        let tr = $(this).closest('tr');
        let contribute_address = tr.find('.contribute_address').val();
        let bidContract = App.contracts.DCBidding.at(contribute_address);
        let state = bidContract.closeAuction().then(function (res) {
            console.log(res);
        });
    }

};

$(function () {

    $(window).load(function () {
        App.init();


    });

});
