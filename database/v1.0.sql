--
-- Formats
--
CREATE TABLE IF NOT EXISTS `cb_ads_formats` (
  `id`     int(5)       unsigned NOT NULL AUTO_INCREMENT,
  `name`   varchar(100)          DEFAULT NULL,
  `width`  int(4)       unsigned NOT NULL,
  `height` int(4)       unsigned NOT NULL,
  `status` tinyint(1)   unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='version:1.0';

--
-- Banners
--
CREATE TABLE IF NOT EXISTS `cb_ads_banners` (
  `id`               int(5)       unsigned NOT NULL AUTO_INCREMENT,
  `file_id`          int(10)      unsigned NOT NULL,
  `format_id`        int(5)       unsigned NOT NULL,
  `link_href`        varchar(300)          DEFAULT NULL,
  `link_target`      varchar(10)           DEFAULT NULL,
  `views`            int(8)       unsigned DEFAULT 0,
  `date_start`       datetime              DEFAULT NULL,
  `date_end`         datetime              DEFAULT NULL,
  `status`           tinyint(1)   unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='version:1.0';

--
-- Advertisement
--
CREATE TABLE IF NOT EXISTS `cb_ads` (
  `id`         int(5)       unsigned NOT NULL AUTO_INCREMENT,
  `name`       varchar(100)          DEFAULT NULL,
  `date_start` datetime              DEFAULT NULL,
  `date_end`   datetime              DEFAULT NULL,
  `status`     tinyint(1)   unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='version:1.0';

--
-- Advertisement Association Banners
--
CREATE TABLE IF NOT EXISTS `cb_ads_association-banners` (
  `id`        int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ad_id`     int(5) unsigned NOT NULL,
  `banner_id` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='version:1.0';

--
-- Association Tags
--
CREATE TABLE IF NOT EXISTS `cb_ads_marcadores` (
  `id`      int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ad_id`   int(5) unsigned NOT NULL,
  `tag_id`  int(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='version:1.0';
