<!doctype html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="fr"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>CaterFund</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="CaterFund is a decentralized community fund management platform applying Blockchain technology and Smart contracts in community fundraising and management to improve the transparency, safety and efficiency of community financial activitities."/>
    <meta property="og:title" content="CaterFund"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="https://caterfund.com/client_resource/assets/img/facebook.jpg"/>
    <meta property="og:url" content="https://caterfund.com/"/>
    <meta property="og:description" content="CaterFund is a decentralized community fund management platform applying Blockchain technology and Smart contracts in community fundraising and management to improve the transparency, safety and efficiency of community financial activitities."/>

    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:description" content="CaterFund is a decentralized community fund management platform applying Blockchain technology and Smart contracts in community fundraising and management to improve the transparency, safety and efficiency of community financial activitities."/>
    <meta name="twitter:title" content="CaterFund"/>
    <meta name="twitter:image" content="https://caterfund.com/client_resource/assets/img/facebook.jpg"/>


    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="/client_resource/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/client_resource/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/client_resource/assets/css/owl.carousel.css">
    <link rel="stylesheet" href="/client_resource/assets/css/style.css?v={{ time }}">
    <link rel="stylesheet" href="/client_resource/assets/css/faq.css">
    <link rel="stylesheet" href="/client_resource/assets/css/responsive.css?v={{ time }}">

    <link rel="shortcut icon" href="/client_resource/assets/img/favico.png?v={{ time }}" type="image/x-icon">
    <!--[if lt IE 9]>
    <script src="/client_resource/assets/js/html5-3.6-respond-1.4.2.min.js"></script>
    <![endif]-->
    <script src="/client_resource/assets/js/jquery-2.2.4.js?v={{ time }}"></script>
    <script src="/client_resource/assets/js/web3.min.js"></script>
    <script src="/client_resource/assets/js/truffle-contract.js?v={{ time }}"></script>
    <script src="/client_resource/assets/js/contract_frontend.js?v={{ time }}"></script>
</head>
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.1&appId=962998917125858&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

{% include "layouts/header.volt" %}

{{ content() }}

{% include "layouts/footer.volt" %}

<script src="/client_resource/assets/js/bootstrap.min.js?v={{ time }}"></script>
<script src="/client_resource/assets/js/owl.carousel.js?v={{ time }}"></script>
<script src="/client_resource/assets/js/custom.js?v={{ time }}"></script>
<script src="/client_resource/custom/js/custom.js?v={{ time }}"></script>

<link rel="stylesheet" href="/admin_resource/custom/plugin/jquery-toast-plugin/jquery.toast.min.css">
<script src="/admin_resource/custom/plugin/jquery-toast-plugin/jquery.toast.min.js"></script>

</body>
</html>




