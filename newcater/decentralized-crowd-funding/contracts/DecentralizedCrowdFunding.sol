pragma solidity ^0.4.23;

import "zeppelin-solidity/contracts/ownership/Ownable.sol";
import "zeppelin-solidity/contracts/math/SafeMath.sol";
import "zeppelin-solidity/contracts/lifecycle/Pausable.sol";


contract DecentralizedCrowdFunding is Pausable {
    using SafeMath for uint256;

    // campaign states
    enum State {
        Running,
        DisbursementPending,
        Closed
    }

    State public state = State.Running;

    address public creator;
    address public fundRecipient;
    uint256 public targetFunding = 0;
    string public campaignUrl;

    uint256 public totalRaised = 0;
    uint256 public currentBalance = 0;

    uint256 public fundingStartTime = 0;
    uint256 public fundingEndTime = 0;

    address[] public contributionAddresses;
    mapping(address => uint256) public contributionAmounts;

    uint256 public disbursementAmount = 0;
    uint256 public disbursementEndTime = 0;

    uint256 public approvalPercent = 0;
    mapping(address => bool) public approvalDisbursementStatuses;
    uint public consensusPercent = 51;

    uint256 public terminatePercent = 0;
    mapping(address => bool) public terminateStatuses;
    uint public objectionPercent = 51;

    event LogFundingReceived(address addr, uint256 amount, uint256 currentTotal);
    event LogWinnerPaid(address winnerAddress);
    event LogFunderInitialized(address creator, address fundRecipient, uint256 targetFunding, string campaignUrl, uint256 fundingStartTime, uint256 fundingEndTime);

    modifier inState(State _state) {
        require(state == _state);
        _;
    }

    modifier isCreator() {
        require(msg.sender == creator);
        _;
    }

    // Wait 1 day after final contract state before allowing contract destruction
    modifier atEndOfLifecycle() {
        require(state == State.Closed || fundingEndTime + 1 days >= now);
        _;
    }

    constructor(address _fundRecipient, uint256 _targetFunding, string _campaignUrl, uint256 _fundingStartTime, uint256 _fundingEndTime) public {
        creator = msg.sender;
        fundRecipient = _fundRecipient;
        targetFunding = _targetFunding;
        campaignUrl = _campaignUrl;
        fundingStartTime = _fundingStartTime;
        fundingEndTime = _fundingEndTime;
        currentBalance = 0;
        emit LogFunderInitialized(creator, fundRecipient, targetFunding, campaignUrl, fundingStartTime, fundingEndTime);
    }

    function() public payable {
        contribute(msg.sender, msg.value);
    }

    function contribute(address _contributor, uint256 _value) public returns (bool success) {
        require(state != State.Closed);
        require(totalRaised < targetFunding);
        require(_value > 0);
        require(fundingStartTime == 0 || now >= fundingStartTime);
        require(fundingEndTime == 0 || now <= fundingEndTime);

        uint256 prevAmt = contributionAmounts[_contributor];

        uint256 contributingAmt = _value;
        uint256 refundAmt = 0;

        uint256 checkedTotalRaised = totalRaised.add(contributingAmt);
        if (targetFunding < checkedTotalRaised) {
            contributingAmt = targetFunding.sub(totalRaised);
            refundAmt = _value.sub(contributingAmt);
        }

        contributionAmounts[_contributor] = contributingAmt.add(prevAmt);

        // refund in case user contribute over target funding
        if (refundAmt > 0) {
            _contributor.transfer(refundAmt);
        }

        totalRaised = totalRaised.add(contributingAmt);
        currentBalance = currentBalance.add(contributingAmt);
        if (prevAmt == 0) {
            contributionAddresses.push(_contributor);
        }

        return true;
    }

    function setConsensusPercent(uint _value) public isCreator() returns (bool success) {
        require(state == State.Running);
        // Only set before request disbursement
        require(_value <= 100);
        consensusPercent = _value;
        return true;
    }

    function setObjectionPercent(uint _value) public isCreator() returns (bool success) {
        require(state == State.Running);
        // Only set before request disbursement
        require(_value > 0 && _value <= 100);
        objectionPercent = _value;
        return true;
    }

    function requestDisbursement(uint256 _value) public returns (bool success) {
        require(state == State.Running);
        require(totalRaised > 0);
        require(_value > 0);
        require(currentBalance >= _value);
        require(msg.sender == creator || msg.sender == fundRecipient);
        disbursementAmount = _value;
        // state = State.DisbursementPending;
        approvalPercent = 0;
        terminatePercent = 0;
        disbursementEndTime = now + 7 days;
        for (uint i = 0; i < contributionAddresses.length; i++) {
            approvalDisbursementStatuses[contributionAddresses[i]] = false;
        }
        return true;
    }

    function setDisbursementEndTime(uint256 _value) public returns (bool success) {
        require(state != State.DisbursementPending);
        require(_value >= now + 1 hours);
        require(msg.sender == creator || msg.sender == fundRecipient);
        disbursementEndTime = _value;
        state = State.DisbursementPending;
        // change state after owner set end time
        return true;
    }

    function approveDisbursement() public returns (bool completed) {
        require(state == State.DisbursementPending);
        require(totalRaised > 0);
        require(currentBalance >= disbursementAmount);
        require(approvalDisbursementStatuses[msg.sender] != true);
        uint256 contributedAmt = contributionAmounts[msg.sender];
        require(contributedAmt > 0);
        approvalDisbursementStatuses[msg.sender] = true;
        uint256 totalApprovalAmt = 0;
        for (uint i = 0; i < contributionAddresses.length; i++) {
            if (approvalDisbursementStatuses[contributionAddresses[i]])
                totalApprovalAmt = totalApprovalAmt.add(contributionAmounts[contributionAddresses[i]]);
        }
        approvalPercent = totalApprovalAmt.mul(100).div(totalRaised);
        if (approvalPercent >= consensusPercent) {
            currentBalance = currentBalance.sub(disbursementAmount);
            state = State.Running;
            fundRecipient.transfer(disbursementAmount);
            return true;
        }
        return false;
    }

    function unapproveDisbursement() public returns (bool completed) {
        require(state == State.DisbursementPending);
        require(totalRaised > 0);
        require(approvalDisbursementStatuses[msg.sender]);
        uint256 contributedAmt = contributionAmounts[msg.sender];
        require(contributedAmt > 0);
        approvalDisbursementStatuses[msg.sender] = false;
        uint256 totalApprovalAmt = 0;
        for (uint i = 0; i < contributionAddresses.length; i++) {
            if (approvalDisbursementStatuses[contributionAddresses[i]])
                totalApprovalAmt = totalApprovalAmt.add(contributionAmounts[contributionAddresses[i]]);
        }
        approvalPercent = totalApprovalAmt.mul(100).div(totalRaised);
        return true;
    }

    function terminateFunding() public returns (bool completed) {
        require(state != State.Closed);
        require(totalRaised > 0);
        require(terminateStatuses[msg.sender] != true);
        uint256 contributedAmt = contributionAmounts[msg.sender];
        require(contributedAmt > 0);
        terminateStatuses[msg.sender] = true;
        uint256 totalTerminateAmt = 0;
        uint256 refundAmt = 0;
        uint i = 0;
        for (i = 0; i < contributionAddresses.length; i++) {
            if (terminateStatuses[contributionAddresses[i]])
                totalTerminateAmt = totalTerminateAmt.add(contributionAmounts[contributionAddresses[i]]);
        }
        terminatePercent = totalTerminateAmt.mul(100).div(totalRaised);
        if (terminatePercent >= objectionPercent) {
            uint256 bal = currentBalance;
            for (i = 0; i < contributionAddresses.length; i++) {
                refundAmt = contributionAmounts[contributionAddresses[i]].mul(currentBalance).div(totalRaised);
                bal = bal.sub(refundAmt);
                contributionAddresses[i].transfer(refundAmt);
            }
            currentBalance = bal;
            return true;
        }
        return false;
    }

    function unterminateFunding() public returns (bool completed) {
        require(state != State.Closed);
        require(totalRaised > 0);
        require(terminateStatuses[msg.sender]);
        uint256 contributedAmt = contributionAmounts[msg.sender];
        require(contributedAmt > 0);
        terminateStatuses[msg.sender] = false;
        uint256 totalTerminateAmt = 0;
        uint i = 0;
        for (i = 0; i < contributionAddresses.length; i++) {
            if (terminateStatuses[contributionAddresses[i]])
                totalTerminateAmt = totalTerminateAmt.add(contributionAmounts[contributionAddresses[i]]);
        }
        terminatePercent = totalTerminateAmt.mul(100).div(totalRaised);
        return true;
    }

    function removeContract() public isCreator() atEndOfLifecycle() {
        selfdestruct(msg.sender);
        // creator gets all money that hasn't be claimed
    }
}
