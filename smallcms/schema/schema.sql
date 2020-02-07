CREATE TABLE IF NOT EXISTS `article` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `login` varchar(20) NOT NULL,
  `password` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO  `user` (
`id` ,
`login` ,
`password` ,
`email`
)
VALUES (
NULL ,  'admin', 'password',  'admin@injection.loc'
);

INSERT INTO `article` (
`id` ,
`title` ,
`description` ,
`content`
)
VALUES (
NULL , 'article title', 'article description', 'article content'
)