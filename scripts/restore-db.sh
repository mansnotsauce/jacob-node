#!/bin/bash
aws s3 cp s3://horizonpwr-db-backups/backup.sql _tmp.sql && mysql horizonpwr < _tmp.sql && rm _tmp.sql
