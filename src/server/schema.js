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
    'alter table user add column roleId varchar(255)',
    'alter table user drop column role',
    `
    create table team (
        teamId int unsigned not null primary key auto_increment,
        teamName varchar(255)
    )
    `,
    'alter table user add column profileImageFile varchar(255)',
    `create table role (
        roleId int unsigned not null primary key auto_increment,
        roleName varchar(255),
        isAdmin tinyint(1) NOT NULL DEFAULT 0,
        isOnboarder tinyint(1) NOT NULL DEFAULT 0
    )`,
    `alter table user add column deleted tinyint(1) NOT NULL DEFAULT 0`,
    `alter table user 
        add column approved tinyint(1) NOT NULL DEFAULT 0,
        add column percentComplete int
    `,
]

async function initialize(dbService) {
    for (const query of schemaQueries) {
        try {
            await dbService.query(query, [], { skipInitCheck: true })
        }
        catch (error) {
            console.log(error.message)
        }
    }
}

module.exports = { initialize }
