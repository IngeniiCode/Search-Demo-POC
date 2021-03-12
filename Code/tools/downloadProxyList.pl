#!/usr/bin/perl 
use strict;
use Mail::IMAPClient;
use Data::Dumper;
use MIME::Parser;
 
my $host    = 'mail.ingeniigroup.com';
my $user    = 'proxix@ingeniigroup.com';
my $pswd    = 'vLL2b42W';
my $inbox   = 'INBOX';
my $msgct   = 0;  # message count
my $lastID  = 0;
my $msg     = '';
my $IMAP  = Mail::IMAPClient->new(  
	Server   => $host, 
	User     => $user,
	Password => $pswd,
	Clear    => 5,  # Unnecessary since '5' is the default
	#               ...             # Other key=>value pairs go here
) or die "Cannot connect to $host as $user: $@";

# Test for connection
#test_connection($IMAP);

# Check Folders
#check_folders($IMAP);

# set the first folder
$IMAP->select($inbox);

#message_metrics($IMAP);
$lastID = pop($IMAP->messages());

# getch content of last message
$msg = $IMAP->message_string($lastID) or die "Could not message_string: $@\n";

# parse message
$proxies = parse_message($msg);

#printf("Last Message\n%s\n",$msg);

exit;   # -- END -- #

#
#   S U B   R O U T I N E S
#

# - - - - - - - - - - - - - - - - - - - - - - 
#   get conection information
#
sub test_connection($IMAP){
	printf("Authenticated %s\n",$IMAP->Authenticated());
	printf("Connected: %s\n",$IMAP->Connected());
}

# - - - - - - - - - - - - - - - - - - - - - - 
#  get list of the folders and return last 
#
sub check_folders($IMAP){
	# Folder Handling
	my @folders = $IMAP->folders;
	my $first   = shift @folders;
	printf("Folders %s\n",Dumper(@folders));
	return $first;
}

# - - - - - - - - - - - - - - - - - - - - - - 
#  show message metrics 
#
sub message_metrics($IMAP){
	my $count = $IMAP->message_count();
	printf("Message Count: %d\n",$IMAP->message_count($inbox));
	printf("messages: %s\n",$IMAP->messages);
}

# - - - - - - - - - - - - - - - - - - - - - - 
#  show message metrics 
#
sub parse_message($msg){
	my $P = MIME::Parser->new();
	$P->output_to_core(1);      # don't write attachments to disk
	my $M = $P->parse_data($msg); 
	my $num_parts = $M->parts;
	for (my $i=0; $i < $num_parts; $i++) {
		my $part         = $M->parts($i);
		my $content_type = $part->mime_type;
		my $body         = $part->as_string;
printf("%s BODY: %s\n",$content_type,$body);

	return 'nothing:yet'
	}
}

1;
