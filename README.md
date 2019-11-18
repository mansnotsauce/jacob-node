## Setup

- install MySQL and nodejs
- make a file in this repo: config-dev.js and copy the contents of config-dev.example.js into it
- do one of the following:
  - use scripts/restore-db.sh to set up your database (will require you to install the aws cli tool, set up credentials in ~/.aws, and run the command from a linux environment)
  - log in to console.aws.com using the "ec2" credentials in the "horizon pwr hours + info" spreadsheet, download backup.sql in the horizonpwr-db-backups s3 bucket, and use that to get the database set up (if you are running MySQL on windows you'll have the MySQL workbench installed, and you can google how to restore a db using that. You may have to create a database called "horizonpwr" to restore the data into, I can't remember)
- Run `npm start` in this directory from a command line.
  - You can view the app at `http://localhost:1337` (or whatever port you set up in config-dev.js).
  - Any changes you make in `src` will be reflected in the application.

## Development

- all of the source files live in `src`.
  - The React lives in `client` and the server code lives in `server`
  - If you want to make a change to the db, add a change line in `src/server/schema.js`
  - API endpoints are defined in `koaRouter.js`. If you don't want to set up API endpoints for calls to the back-end, you can listen for front-end events on the back-end and emit events back to the front-end in `socketRouter.js`
    - grep or ctrl+f through source for `ClickedDeleteUsers` for a good example of this. I think it's easier than setting up new API endpoints for every call
  - The front-end follows a basic store/view pattern (like the one used at RSIS) -- check out stores and views for examples of how to use this

## Other

I'm sure I'm forgetting a lot here, so message me with questions and I'll update this document.

