#!/usr/bin/perl
use strict;
use warnings;
use autodie;

# Ensures that strings to be printed will be correctly treated as utf8.
binmode STDOUT, ":utf8";
# Required if the script itself is utf8 encoded.
use utf8;

# Locates the full path to the script bin directory to allow the use of paths relative to the bin directory.
# The $bin variable exported by the FindBin module will contain the path to the directory of the current script.
use FindBin qw($Bin);
use File::Basename qw(dirname);
use File::Spec::Functions qw(catdir);
use File::Spec::Functions qw(rel2abs);

use lib catdir($Bin, 'lib');

use AzureSDK::KeyVault::Client;

my $configPath = File::Spec->catdir(dirname(__FILE__), '../akv.config.json');
my $ac = new AzureSDK::KeyVault::Client($configPath);

my $secret_name = 'truesecretname';
print sprintf("Secret name: %s\n", $secret_name);

my $secretResponse = $ac->get_secret($secret_name);
print sprintf("Secret value: %s\n", $secretResponse->{'value'});