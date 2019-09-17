#!/bin/bash
mysqldump horizonpwr > _tmp.sql && aws s3 cp _tmp.sql s3://horizonpwr-db-backups/backup.sql && rm _tmp.sql
