ALTER TABLE `profile` MODIFY `added` datetime NOT NULL DEFAULT '0000-00-00';
ALTER TABLE `profile` MODIFY `updated` timestamp NOT NULL DEFAULT on update CURRENT_TIMESTAMP;


