const schemaQueries = [
    `create table user (
        id int unsigned not null primary key auto_increment,
        email varchar(255),
        passwordHash varchar(255),
        passwordSalt varchar(255)
    )`,
    `create table session (
        sessionKey varchar(255) not null primary key,
        userId int unsigned not null
    )`,
    'ALTER TABLE `user` CHANGE COLUMN `id` `userId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
    'alter table user add column firstName varchar(255), add column lastName varchar(255)',
    'alter table user add column role varchar(255)',
    'alter table user add column phoneNumber varchar(255)',
    'alter table user add column teamId varchar(255)',
]

async function initialize(dbService) {
    for (const query of schemaQueries) {
        try {
            await dbService.query(query, [], { skipInitCheck: true })
        }
        catch (error) {
            const e = error
        }
    }
}

module.exports = { initialize }
