#!/usr/bin/perl
use warnings;
use strict;

use Net::OpenSSH;
use Getopt::Long;
use Data::Dumper;
use DBI ;

my $storageLogFile = "/tmp/storageSVCVersionBuild.log";
my ($active,$tasklist,$help);


sub usage(){
    print "usage: $0 [--active ] [--tasklist] [--help|-?]\n";
    exit;
}


usage() if ( @ARGV < 1  );

GetOptions( 'active'   => \$active,
            'tasklist' => \$tasklist,
            'help|?'   => \$help
            );
open ( STGLOG, ">>$storageLogFile" ) or die "Uable to open Storage Build check log file [ ".$storageLogFile." ]\n"; 
sub logger{

    my $data     = shift;
    chomp($data);
    my $infoType = shift;
    my $time = `date +"[ %m/%d/%y %T:%2N ] "`;
    chomp($time);
    my %logger = (
            "0" => "emerg", #0 - Emergency (emerg)
            "1" => "alert", #1 - Alerts (alert)
            "2" => "crit",  #2 - Critical (crit)
            "3" => "err",   #3 - Errors (err)
            "4" => "warn",  #4 - Warnings (warn)
            "5" => "notice",#5 - Notification (notice)
            "6" => "info",  #6 - Information (info)
            "7" => "debug", #7 - Debug (debug)
                );
    if ( $logger{$infoType} =~ "emerg" || $logger{$infoType} =~ "debug" || $logger{$infoType} =~ "crit"){
        print STGLOG $time."[".$logger{$infoType}."] ".$data."\n"; 
        print $time."[".$logger{$infoType}."] ".$data."\n"; 
    }else{
        print STGLOG $time."[".$logger{$infoType}."] ".$data."\n"; 
    } 
}
sub dbConnect {
    my $dbname = "automation";
    my $dbuser = "root";
    my $dbpass = "a1b2c3";
    my $dbhost = "localhost";
    my $DBC = "DBI:mysql:" . $dbname. ":" . $dbhost ;

    my $dbh = DBI->connect($DBC, $dbuser, $dbpass) 
            || die "Could not connect to database: $DBI::errstr";
                my $threadId = $dbh->{ q{mysql_thread_id} };
           return $dbh 

} 
sub selSVCVersion {

    my $svcV = shift;
    my $dbh = dbConnect();
    my ($ID,$rows);
    my $sth = $dbh->prepare("SELECT DISTINCT id FROM svcVersion WHERE version = \"".$svcV."\"");
    $sth->execute();
    $rows = $sth->rows;
    $ID = $sth->fetchrow();
    if  ( $rows eq "0" ) {
        logger("SQL Statment [ INSERT IGNORE INTO svcVersion ( version ) VALUES (".$svcV.") ]",7);

        $sth = $dbh->prepare("INSERT IGNORE INTO svcVersion ( version ) VALUES (\"".$svcV."\")");
        $sth->execute();
        if ( $DBI::errstr ) {
            logger("ERROR Inserting Data to database",2); 
            return 255;
        }
        $ID = $sth->last_insert_id;
    }
    logger("selSVCVersion [ ".$ID." ]",7);
    $sth->finish() ;
    $dbh->disconnect();

    return $ID;
}
sub selSVCBuild {
    my $svcB    = shift;
    my $dbh = dbConnect();
    my ($ID,$rows);
    logger("SELECT DISTINCT id FROM svcBuildVersion WHERE  build = \"".$svcB."\"",7);
    #logger("SELECT DISTINCT id FROM svcBuilds WHERE ( svcVersionID = \"".$svcVid."\" ) AND ( svcBuild = \"".$svcB."\")",7);
    my $sth = $dbh->prepare("SELECT DISTINCT id FROM svcBuildVersion WHERE build = \"".$svcB."\"");
    #my $sth = $dbh->prepare("SELECT DISTINCT id FROM svcBuilds WHERE ( svcVersionID = \"".$svcVid."\" ) AND ( svcBuild = \"".$svcB."\" )");
    $sth->execute();
    $rows = $sth->rows;
    $ID = $sth->fetchrow();

    logger("Total rows are ".$rows, 7) ;
    if ( $rows eq "0" ) {
        logger("INSERT SVC Build to database ",7);
        logger("INSERT IGNORE INTO svcBuildVersion ( build ) VALUES ( \"".$svcB."\" )",7);
        my $q = "INSERT IGNORE INTO svcBuildVersion ( build ) VALUES ( \"".$svcB."\" )";
        my $sth = $dbh->prepare($q);
        $sth->execute();
        #$ID = $sth->last_insert_id;
        if ( $DBI::errstr) {
            logger("ERROR Insert into svcBuild tables ",2); 
            return 255;
        }
        $sth = $dbh->prepare("SELECT DISTINCT id FROM svcBuildVersion WHERE build = \"".$svcB."\"");
        $sth->execute();
        $ID = $sth->fetchrow();
    }
    logger("selSVCBuild ID [ ".$ID." ]",7);
    $sth->finish() ;
    $dbh->disconnect();
    return $ID;
}
sub updateStorageVerBuild {
    my $storageInfo = shift;
    my $dbh = dbConnect();
    my ($sth,@svcVBid);

    print Dumper($storageInfo);

    foreach my $storage ( keys %{$storageInfo}) { 
        my $buildID = $storageInfo->{$storage}->{buildID};
        my $verID   = $storageInfo->{$storage}->{verID};
        print "Update storage ".$storage." SVC Version [ ".$verID. " ] SVC Build [ " .$buildID." ]\n";
        my $selStg = "SELECT DISTINCT id FROM svcVersionBuildID WHERE ( svcVersionID = \"".$verID."\" ) AND ( svcBuildID = \"".$buildID."\" )";

        $sth = $dbh->prepare($selStg);
        $sth->execute();
        my $rv = $sth->rows;
        my @svcVBid = $sth->fetchrow_array;

        if ( $rv eq "0" ) {
            logger("INSERT INTO svcVersionBuildID table column svcVersionBuildID,svcBuildID",7);
            my $iSql = "INSERT IGNORE INTO svcVersionBuildID ( svcVersionID ,svcBuildID ) VALUES ( '".$verID."','".$buildID."')";
            $sth = $dbh->prepare($iSql);
            $sth->execute();
            $sth = $dbh->prepare($selStg);
            $sth->execute();
            @svcVBid = $sth->fetchrow_array;

            if ( $DBI::errstr ) {
                logger("unable to Insert into svcVersionBuildID data",2);
                exit 999;
            }
        }

        logger("Update Storage table in column svcVersionBuildID",2);
       print Dumper(@svcVBid); 

        my $sqlStorageUpdate = "UPDATE Storage SET svcVersionBuildID = '".$svcVBid[0]."' WHERE id = '".$storageInfo->{$storage}->{storageNodeID}."'";
        $sth = $dbh->prepare($sqlStorageUpdate);
        $sth->execute();

        if ( $DBI::errstr ) {
            logger("unable to update storage table ",2);
            exit 998;
        }
        logger("Udate successfully [ ". $storage." ]",7);
    }
#    my $sth = $dbh->prepare("SELECT DISTINCT id FROM svcVersionBuildID WHERE ( svcVersionID = \"".$verID."\" ) AND ( svcBuildID = \"".$buildID."\" )");


}

sub GetSVCVerBuild($) { 
    my $standList  = shift;
    my $dbh = dbConnect();

    for my $standName ( keys %{$standList} ) {
    logger("Connecting to " . $standName,7);
    my $passwd = "l0destone"; 

    my $ssh = Net::OpenSSH->new($standList->{$standName}->{clusterIP}, port => 26 ,password => $passwd);
        if ( $ssh->error() ) { 
            logger("Failed Connecting to " . $standName . " via SSH with IP ".$standList->{$standName}->{clusterIP} ,2);
            logger("SSH ERROR " .$ssh->error(),2);
            for ( my $n = 1 ; $n <= 2 ; $n++) {
                logger("Trying to connect to " . $standName . " via SSH to node ".$standList->{$standName}->{"node".$n},7);
#                my $ssh = Net::OpenSSH->new($standList->{$standName}->{"node".$n}, port => 26 ,password => $passwd);
            } 
            
        }else {
            logger("Successfully Connected to " . $standName . " via SSH with IP ".$standList->{$standName}->{clusterIP} ,7);
            my ( $stdout, $stderr ) = $ssh->capture2("cat /compass/vrmf ; cat /compass/version");
            my ($ver,$build) = (split("\n",$stdout))[0,1];

            logger("Stand Name " .$standName." SVC Version ".$ver." Build ".$build,7);
            $standList->{$standName}->{verID} = selSVCVersion($ver); 
            $standList->{$standName}->{buildID} = selSVCBuild($build); 
            logger("Stand Version ID [ ".$standList->{$standName}->{verID}." ] ",7);
        }
    }
    logger(Dumper($standList),6);
    updateStorageVerBuild($standList);

}

if ( $active ) {
    my $selActiveStorage = "
        SELECT DISTINCT Storage.name, 
                        Storage.ip AS clusterIP, 
                        Storage.id AS storageNodeID
                   FROM automation.Storage Storage, 
                        automation.StorageNodes StorageNodes
                  WHERE (Storage.active = '0')";
    my $dbh = dbConnect();
    my $sth = $dbh->prepare($selActiveStorage);
    $sth->execute();
    my $activeStorageIP = $sth->fetchall_hashref([qw( name )]);
    foreach my $storageName ( keys %{ $activeStorageIP }  ) {
        logger("Storage Name : ".$storageName . " stotageNodesID " .$activeStorageIP->{$storageName}->{storageNodeID},7);

        my $selStorageNodes = "SELECT StorageNodes.name, StorageNodes.ip
                                 FROM automation.StorageNodes StorageNodes
                                WHERE (StorageNodes.clusterID = '".$activeStorageIP->{$storageName}->{storageNodeID} ."')";
        my $sth = $dbh->prepare($selStorageNodes);
        $sth->execute();
        my $storageNodesIP = $sth->fetchall_arrayref({});

        foreach my $name ( @{ $storageNodesIP } ) {
            logger("Node Name " . $name->{name} . " Node IP " .$name->{ip},6);
            $activeStorageIP->{$storageName}->{"$name->{name}"} = $name->{ip} ;
        }
    }
    $sth->finish() ;
    $dbh->disconnect();
    GetSVCVerBuild($activeStorageIP);

}
