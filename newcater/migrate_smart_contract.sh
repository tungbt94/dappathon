workdir=${PWD}

cd $workdir/decentralized-crowd-funding && truffle migrate --reset
cd $workdir
cp -r $workdir/decentralized-crowd-funding/build/contracts/ $workdir/public/client_resource/assets/contracts
