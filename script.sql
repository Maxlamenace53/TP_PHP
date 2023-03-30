create table user
(
    id       int auto_increment
        primary key,
    prenom   varchar(255) not null,
    nom      varchar(255) not null,
    email    varchar(255) not null,
    password varchar(255) not null
)
    engine = InnoDB;

create table article
(
    id       int auto_increment
        primary key,
    title    varchar(255) not null,
    image    varchar(255) not null,
    category varchar(255) not null,
    content  text         not null,
    userId   int          not null,
    constraint article_ibfk_1
        foreign key (userId) references user (id)
)
    engine = InnoDB;

create index userId
    on article (userId);

create table commentaire
(
    id          int auto_increment
        primary key,
    commentaire text null,
    userId      int  not null,
    articleId   int  not null,
    constraint commentaire_ibfk_1
        foreign key (userId) references user (id),
    constraint commentaire_ibfk_2
        foreign key (articleId) references article (id)
)
    engine = InnoDB;

create index articleId
    on commentaire (articleId);

create index userId
    on commentaire (userId);

