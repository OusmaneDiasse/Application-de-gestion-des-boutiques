
/*==============================================================*/
/* Table: CLIENT                                                */
/*==============================================================*/
create table CLIENT
(
   ID_CLIENT            int not null,
   NOM_CLIENT           varchar(255),
   E_MAIL_CLIENT        varchar(255),
   MOT_DE_PASSE         varchar(255),
   TELEPHONE            int,
   primary key (ID_CLIENT)
);

/*==============================================================*/
/* Table: CREANCE                                               */
/*==============================================================*/
create table CREANCE
(
   ID_CREANCE           int not null,
   FAC_ID_FACTURE       int not null,
   MONTANT_DU           int,
   DATE_ECHEANCE        date,
   STATUT               bool,
   primary key (ID_CREANCE)
);

/*==============================================================*/
/* Table: FACTURE                                               */
/*==============================================================*/
create table FACTURE
(
   ID_FACTURE           int not null,
   ID_CLIENT            int not null,
   ID_UTILISATEUR       int not null,
   TYPE_DE_PAYMENT      varchar(255),
   MONTANT_TOTAL        int,
   primary key (ID_FACTURE)
);

/*==============================================================*/
/* Table: PAIEMENT                                              */
/*==============================================================*/
create table PAIEMENT
(
   ID_PAIEMENT          int not null,
   ID_CREANCE           int not null,
   DATE_PAIEMENT        date,
   MONTANT_PAYE         int,
   MODE_PAIEMENT        varchar(255),
   primary key (ID_PAIEMENT)
);

/*==============================================================*/
/* Table: PRODUIT                                               */
/*==============================================================*/
create table PRODUIT
(
   ID_PRODUIT           int not null,
   NOM_PRODUIT          varchar(255),
   PRIX                 int,
   STOCK                int,
   DATE_DE_FABRICATION  date,
   DATE_DE_PEREMPTION   date,
   primary key (ID_PRODUIT)
);

/*==============================================================*/
/* Table: ROLE                                                  */
/*==============================================================*/
create table ROLE
(
   ID_ROLE              int not null,
   NOM_DU_ROLE          varchar(255),
   primary key (ID_ROLE)
);

/*==============================================================*/
/* Table: UTILISATEUR                                           */
/*==============================================================*/
create table UTILISATEUR
(
   ID_UTILISATEUR       int not null,
   ID_ROLE              int not null,
   NOM_UTILISATEUR      varchar(255),
   E_MAIL_UTILISATEUR   varchar(255),
   MOT_DE_PASSE_UTILISATEUR varchar(255),
   TELEPHONE_UTILISATEUR int,
   ADRESS_UTILISATEUR   varchar(255),
   primary key (ID_UTILISATEUR)
);

/*==============================================================*/
/* Table: VENDRE                                                */
/*==============================================================*/
create table VENDRE
(
   ID_PRODUIT           int not null,
   ID_FACTURE           int not null,
   DATE_VENTE           date,
   QUANTITE_VENDUE      int,
   primary key (ID_PRODUIT, ID_FACTURE)
);

alter table CREANCE add constraint FK_GENERER foreign key (FAC_ID_FACTURE)
      references FACTURE (ID_FACTURE) on delete restrict on update restrict;

alter table FACTURE add constraint FK_EFFECTUER foreign key (ID_CLIENT)
      references CLIENT (ID_CLIENT) on delete restrict on update restrict;

alter table FACTURE add constraint FK_ENREGISTRER foreign key (ID_UTILISATEUR)
      references UTILISATEUR (ID_UTILISATEUR) on delete restrict on update restrict;

alter table PAIEMENT add constraint FK_REGLER foreign key (ID_CREANCE)
      references CREANCE (ID_CREANCE) on delete restrict on update restrict;

alter table UTILISATEUR add constraint FK_OCCUPER foreign key (ID_ROLE)
      references ROLE (ID_ROLE) on delete restrict on update restrict;

alter table VENDRE add constraint FK_VENDRE foreign key (ID_FACTURE)
      references FACTURE (ID_FACTURE) on delete restrict on update restrict;

alter table VENDRE add constraint FK_VENDRE2 foreign key (ID_PRODUIT)
      references PRODUIT (ID_PRODUIT) on delete restrict on update restrict;

