#!/usr/bin/perl
use strict;
use warnings;

# Loads all important LWP classes, and makes sure your version is reasonably recent.
use LWP 6.43;
# Load JSON decoding/encoding classes.
use JSON;

package AzureSDK::KeyVault::Client;

sub new {
    my $class = shift;
    my $self = {
        _config => undef
    };
    bless $self, $class;
    $self->_init(@_);
    return $self;
}

sub _init {
    if (@_) {
        my $self = shift;
        if (defined $self) {
            my $file = shift;
            if (defined $file) {

                # JSON input file is read into a variable.
                my $json;
                {
                    # Enable 'slurp' mode (reading a file in one step).
                    local $/;
                    open my $fh, "<", $file or die "Could not open $file, $!";
                    $json = <$fh>;
                    close $fh;
                }
                # Convert the json string into a perl data structure.
                $self->{_config} = JSON::decode_json($json);
            }
        }
    }
}

sub get_bearer_token {
    if (@_) {
        my $self = shift;
        if (defined $self) {
            my $ua = LWP::UserAgent->new();
            if (defined $ua) {
                my $response = $ua->post(sprintf('https://login.microsoftonline.com/%s/oauth2/token', $self->{_config}->{'tenantId'}),
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => $self->{_config}->{'clientId'},
                        'client_secret' => $self->{_config}->{'clientSecret'},
                        'resource' => 'https://vault.azure.net'
                    ]
                );

                if ($response->is_success) {
                    return JSON::decode_json($response->decoded_content);
                }
                return $response;
            }
        }
    }
}

sub get_secret {
    if (@_) {
        my $self = shift;
        if (defined $self) {
            # secret_name parameter.
            my $secret_name = shift;
            if (defined $secret_name) {
                my $bearer_response = $self->get_bearer_token();
                if (defined $bearer_response) {
                    my $ua = LWP::UserAgent->new();
                    if (defined $ua) {
                        my %parameters = ('api-version' => '2016-10-01');
                        my $url = URI->new(sprintf('%s/secrets/%s/', $self->{_config}->{'vaultUri'}, $secret_name));
                        $url->query_form(%parameters);
                        my $response = $ua->get($url,
                            (
                                'Authorization' => sprintf("Bearer %s", $bearer_response->{'access_token'}),
                                'Content-Type' => 'application/json'
                            )
                        );

                        if ($response->is_success) {
                            return JSON::decode_json($response->decoded_content);
                        }
                        return $response;
                    }
                }
            }
        }
    }
}

1;