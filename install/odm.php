<?php
/*
odm.php - main file for creating a fresh installation
Copyright (C) 2002-2014  Stephen Lawrence

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 3
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

// For ODM fresh install
// Admin table

// Added for automated script installers
$dbprefix = isset($GLOBALS['CONFIG']['db_prefix']) ? $GLOBALS['CONFIG']['db_prefix'] : $_SESSION['db_prefix'];
if (!isset($_SESSION['adminpass'])) {
    echo 'No Admin Pass!';
    exit;
}
$adminpass = $_SESSION['adminpass'];

global $pdo;

// Access Log Table
$query = "DROP TABLE IF EXISTS {$dbprefix}access_log";
$stmt = $pdo->prepare($query);
$stmt->execute();
        
$query = "
CREATE TABLE IF NOT EXISTS `{$dbprefix}access_log` (
  `file_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `action` enum('A','B','C','V','D','M','X','I','O','Y','R') NOT NULL,
  PRIMARY KEY ( `file_id`, `user_id`, `timestamp`, `action` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Admin table    
$query = "DROP TABLE IF EXISTS `{$dbprefix}admin`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "
CREATE TABLE IF NOT EXISTS `{$dbprefix}admin` (
  `id` INT(11) UNSIGNED DEFAULT NULL,
  `admin` TINYINT(1) DEFAULT NULL,
  PRIMARY KEY ( `id` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Admin user
$query = "INSERT INTO `{$dbprefix}admin` VALUES (1,1)";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Category table
$query = "DROP TABLE IF EXISTS `{$dbprefix}category`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "
CREATE TABLE IF NOT EXISTS `{$dbprefix}category` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY  ( `id` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}category` VALUES (NULL,'SOP')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}category` VALUES (NULL,'Training Manual')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}category` VALUES (NULL,'Letter')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}category` VALUES (NULL,'Presentation')";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Data table
$query = "DROP TABLE IF EXISTS `{$dbprefix}data`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}data` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `owner` INT(11) UNSIGNED DEFAULT NULL,
  `realname` VARCHAR(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` VARCHAR(255) DEFAULT NULL,
  `comment` VARCHAR(255) DEFAULT '',
  `status` SMALLINT(6) DEFAULT NULL,
  `department` SMALLINT(6) UNSIGNED DEFAULT NULL,
  `default_rights` TINYINT(1) DEFAULT NULL,
  `publishable` TINYINT(1) DEFAULT NULL,
  `reviewer` INT(11) UNSIGNED DEFAULT NULL,
  `reviewer_comments` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY  ( `id` ),
  KEY data_idx ( `id`, `owner` ),
  KEY publishable ( `publishable` ),
  KEY description ( `description` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Department Table
$query = "DROP TABLE IF EXISTS `{$dbprefix}department`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}department` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY  ( `id` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}department` VALUES (NULL,'Information Systems')";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Department Permissions table
$query = "DROP TABLE IF EXISTS `{$dbprefix}dept_perms`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}dept_perms` (
  `fid` INT(11) UNSIGNED DEFAULT NULL,
  `dept_id` INT(11) UNSIGNED DEFAULT NULL,
  `rights` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY fid ( `fid`, `dept_id` ),
  KEY dept_id ( `dept_id` ),
  KEY rights ( `rights` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Department Reviewer table
$query = "DROP TABLE IF EXISTS `{$dbprefix}dept_reviewer`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}dept_reviewer` (
  `dept_id` INT(11) UNSIGNED DEFAULT NULL,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY ( `dept_id`, `user_id` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// data for table 'dept_reviewer'
$query = "INSERT INTO `{$dbprefix}dept_reviewer` VALUES (1,1)";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Log table
$query = "DROP TABLE IF EXISTS `{$dbprefix}log`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}log` (
  `id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` VARCHAR(25) DEFAULT NULL,
  `note` TEXT,
  `revision` VARCHAR(255) NOT NULL,
  PRIMARY KEY ( `id`, `revision` ),
  KEY modified_on (`modified_on`)
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Rights table
$query = "DROP TABLE IF EXISTS `{$dbprefix}rights`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}rights` (
  `RightId` tinyint(1) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY ( `RightId` ),
  UNIQUE KEY `UnqRight` ( `Description` )
) ENGINE=MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Rights values
$query = "INSERT INTO `{$dbprefix}rights` VALUES (0,'none')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}rights` VALUES (1,'view')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}rights` VALUES (-1,'forbidden')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}rights` VALUES (2,'read')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}rights` VALUES (3,'write')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "INSERT INTO `{$dbprefix}rights` VALUES (4,'admin')";
$stmt = $pdo->prepare($query);
$stmt->execute();

// User table
$query = "DROP TABLE IF EXISTS `{$dbprefix}user`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}user` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(25) NOT NULL DEFAULT '',
  `password` VARCHAR(50) NOT NULL DEFAULT '',
  `department` INT(11) UNSIGNED DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `Email` VARCHAR(50) DEFAULT NULL,
  `last_name` VARCHAR(255) DEFAULT NULL,
  `first_name` VARCHAR(255) DEFAULT NULL,
  `pw_reset_code` char(32) DEFAULT NULL,
  `can_add` TINYINT(1) NULL DEFAULT 1,
  `can_checkin` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY  ( `id` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Create admin user
$query = "INSERT INTO `{$dbprefix}user` VALUES (NULL,'admin',md5('{$adminpass}'),'1','5555551212','admin@example.com','User','Admin','',1,1)";
$stmt = $pdo->prepare($query);
$stmt->execute();

// User permissions table
$query = "DROP TABLE IF EXISTS `{$dbprefix}user_perms`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}user_perms` (
  `fid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `rights` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY ( `fid`, `uid` ),
  KEY `uid` ( `uid` ),
  KEY `rights` ( `rights` )
) ENGINE=MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "DROP TABLE IF EXISTS `{$dbprefix}udf`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}udf` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT ,
  `table_name` VARCHAR(50),
  `display_name` VARCHAR(16),
  `field_type` TINYINT(1),
  PRIMARY KEY  ( `id` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "DROP TABLE IF EXISTS `{$dbprefix}odmsys`";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}odmsys` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT ,
  `sys_name`  VARCHAR(16),
  `sys_value` VARCHAR(255),
  PRIMARY KEY  ( `id` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Create version number in db
$query = "INSERT INTO `{$dbprefix}odmsys` VALUES (NULL,'version','1.3.5')";
$stmt = $pdo->prepare($query);
$stmt->execute();

$query = "DROP TABLE IF EXISTS `{$dbprefix}settings`";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Create the settings table
$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}settings` (
  `id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR( 255 ) NOT NULL ,
  `value` VARCHAR( 255 ) NOT NULL ,
  `description` VARCHAR( 255 ) NOT NULL ,
  `validation` VARCHAR( 255 ) NOT NULL ,
  PRIMARY KEY  ( `id` ) ,
  UNIQUE KEY  ( `name` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Populate the setttings table with default values
$sql_operations = array(
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'debug', 'False', '(True/False) - Default=False - Debug the installation (not working)', 'bool');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'demo', 'False', '(True/False) This setting is for a demo installation, where random people will be all loggging in as the same username/password like \"demo/demo\". This will keep users from removing files, users, etc.', 'bool');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'authen', 'mysql', '(Default = mysql) Currently only MySQL authentication is supported', '');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'title', 'Document Repository', 'This is the browser window title', 'maxsize=255');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'site_mail', 'root@localhost', 'The email address of the administrator of this site', 'email|maxsize=255|req');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'root_id', '1', 'This variable sets the root user id.  The root user will be able to access all files and have authority for everything.', 'num|req');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'dataDir', '{$_SESSION['datadir']}', 'location of file repository. This should ideally be outside the Web server root. Make sure the server has permissions to read/write files to this folder!. (Examples: Linux - /var/www/document_repository/ : Windows - c:/document_repository/', 'maxsize=255');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'max_filesize', '5000000', 'Set the maximum file upload size', 'num|maxsize=255');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'revision_expiration', '90', 'This var sets the amount of days until each file needs to be revised,  assuming that there are 30 days in a month for all months.', 'num|maxsize=255');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'file_expired_action', '1', 'Choose an action option when a file is found to be expired The first two options also result in sending email to reviewer  (1) Remove from file list until renewed (2) Show in file list but non-checkoutable (3) Send email to reviewer only (4) Do Nothing', 'num');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'authorization', 'True', 'True or False. If set True, every document must be reviewed by an admin before it can go public. To disable set to False. If False, all newly added/checked-in documents will immediately be listed', 'bool');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'allow_signup', 'False', 'Should we display the sign-up link?', 'bool');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'allow_password_reset', 'False', 'Should we allow users to reset their forgotten password?', 'bool');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'try_nis', 'False', 'Attempt NIS password lookups from YP server?', 'bool');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'theme', 'tweeter', 'Which theme to use?', '');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'language', 'english', 'Set the default language (english, spanish, turkish, etc.). Local users may override this setting. Check include/language folder for languages available', 'alpha|req');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'base_url', '{$_SESSION['baseurl']}', 'Set this to the url of the site. No need for trailing \"/\" here', 'url');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'max_query', '500', 'Set this to the maximum number of rows you want to be returned in a file listing. If your file list is slow decrease this value.', 'num');",
"INSERT INTO `{$dbprefix}settings` VALUES(NULL, 'show_footer', 'True', 'Set this to True to display the footer.', 'bool');"
);

foreach ($sql_operations as $query) {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}

$query = "DROP TABLE IF EXISTS `{$dbprefix}filetypes`";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Create the filetypes table
$query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}filetypes` (
  `id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(255) NOT NULL ,
  `active` TINYINT(1) NOT NULL ,
  PRIMARY KEY  ( `id` )
) ENGINE = MyISAM";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Create the filetypes data
$sql_operations=array(
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/gif', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'text/html', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'text/plain', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/pdf', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/pdf', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/x-pdf', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/msword', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/jpeg', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/pjpeg', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/png', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/msexcel', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/msaccess', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'text/richtxt', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/mspowerpoint', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/octet-stream', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/x-zip-compressed', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/x-zip', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/zip', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/tiff', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NUll, 'image/tif', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.ms-powerpoint', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.ms-excel', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.chart', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.chart-template', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.formula', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.formula-template', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.graphics', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.graphics-template', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.image', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.image-template', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.presentation', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.presentation-template', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.spreadsheet', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.spreadsheet-template', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.text', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.text-master', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.text-template', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'application/vnd.oasis.opendocument.text-web', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'text/csv', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'audio/mpeg', 0);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/x-dwg', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/x-dfx', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'drawing/x-dwf', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'image/svg', 1);",
"INSERT INTO `{$dbprefix}filetypes` VALUES(NULL, 'video/3gpp', 1);"
        );
foreach ($sql_operations as $query) {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}
