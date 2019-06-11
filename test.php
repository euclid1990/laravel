<?php

// create a new persistent client
$mc = new Memcached("memcached");
$mc->setOption(Memcached::OPT_BINARY_PROTOCOL, TRUE);
// some nicer default options
// - nicer TCP options
$mc->setOption(Memcached::OPT_TCP_NODELAY, TRUE);
$mc->setOption(Memcached::OPT_NO_BLOCK, FALSE);
// - timeouts
$mc->setOption(Memcached::OPT_CONNECT_TIMEOUT, 2000);    // ms
$mc->setOption(Memcached::OPT_POLL_TIMEOUT, 2000);       // ms
$mc->setOption(Memcached::OPT_RECV_TIMEOUT, 750 * 1000); // us
$mc->setOption(Memcached::OPT_SEND_TIMEOUT, 750 * 1000); // us
// - better failover
$mc->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
$mc->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, TRUE);
$mc->setOption(Memcached::OPT_RETRY_TIMEOUT, 2);
$mc->setOption(Memcached::OPT_SERVER_FAILURE_LIMIT, 1);
$mc->setOption(Memcached::OPT_AUTO_EJECT_HOSTS, TRUE);

$mc->addServer('memcached', 11211);
var_dump($mc->getServerList());
// $mc->setSaslAuthData('ms_user', 'ms_password');
var_dump($mc->set('foo', 'bar'));
$value = $mc->get('foo');
var_dump($value);