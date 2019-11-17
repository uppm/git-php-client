<?php
require "Git.php";

use modules\git\Git;

$git = new Git();
$git->setNoOutput(true);
$git->changeDirectory(".");
$git->initIfNot();

// $git->addRemote("origin", "git@github.com:interaapps/javahttprequest.git");

$git->setRemote("origin");

$git->add(".");

$git->commit("Hello");

$git->push("develop");